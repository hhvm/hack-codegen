<?hh // strict
/**
 * Copyright (c) 2015-present, Facebook, Inc.
 * All rights reserved.
 *
 * This source code is licensed under the BSD-style license found in the
 * LICENSE file in the root directory of this source tree. An additional grant
 * of patent rights can be found in the PATENTS file in the same directory.
 */

namespace Facebook\HackCodegen;

/**
 * Base class to generate a function or a method.
 */
abstract class CodegenFunctionBase
  implements ICodeBuilderRenderer {

  use HackBuilderRenderer;

  protected string $name;
  protected ?string $body = null;
  protected ?string $docBlock = null;
  protected ?string $returnType = null;
  private ?string $fixme = null;
  protected bool $isAsync = false;
  protected bool $isOverride = false;
  protected bool $isManualBody = false;
  protected bool $isMemoized = false;
  protected Map<string, ImmVector<\Stringish>> $userAttributes = Map {};
  protected Vector<string> $parameters = Vector {};
  protected ?CodegenGeneratedFrom $generatedFrom;

  public function __construct(
    protected IHackCodegenConfig $config,
    string $name,
  ) {
    $this->name = $name;
  }

  public function setIsAsync(bool $value = true): this {
    $this->isAsync = $value;
    return $this;
  }

  public function setIsOverride(bool $value = true): this {
    $this->isOverride = $value;
    return $this;
  }

  public function setIsMemoized(bool $value = true): this {
    $this->isMemoized = $value;
    return $this;
  }

  /* HH_FIXME[4033] variadic params with type constraints are not supported */
  public function setUserAttribute(string $attribute_name, ...$args): this {
    $this->userAttributes[$attribute_name] = new ImmVector($args);
    return $this;
  }

  /* HH_FIXME[4033] variadic params with type constraints are not supported */
  public function setReturnType(string $type, ...$args): this {
    $type = vsprintf($type, $args);
    if ($type) {
      $this->returnType = $type;
    }
    return $this;
  }

  /* HH_FIXME[4033] variadic params with type constraints are not supported */
  public function addParameter(string $param, ...$args): this {
    $param = vsprintf($param, $args);
    $this->parameters->add($param);
    return $this;
  }

  public function setParameters(Vector<string> $params): this {
    $this->parameters = $params;
    return $this;
  }

  /* HH_FIXME[4033] variadic params with type constraints are not supported */
  public function setBody(string $body, ...$args): this {
    if (count($args)) {
      $this->body = vsprintf($body, $args);
    } else {
      $this->body = $body;
    }

    return $this;
  }

  public function setManualBody(bool $val = true): this {
    if ($val) {
      if ($this->body === null) {
        $this->body = "throw new ViolationException('Unimplemented');";
      }
    }
    $this->isManualBody = $val;
    return $this;
  }

  public function setDocBlock(string $comment): this {
    $this->docBlock = $comment;
    return $this;
  }

  public function setGeneratedFrom(CodegenGeneratedFrom $from): this {
    $this->generatedFrom = $from;
    return $this;
  }

  public function getName(): string {
    return $this->name;
  }

  public function getParameters(): Vector<string> {
    return $this->parameters;
  }

  public function getReturnType(): ?string {
    return $this->returnType;
  }

  public function isManualBody(): bool {
    return $this->isManualBody;
  }

  /**
   * Break lines for function declaration. First calculate the string length as
   * if there were no line break. If the string exceeds one line, try break
   * by having each parameter per line.
   *
   * $is_abstract - only valid for CodegenMethodX for code reuse purposes
   */
  protected function getFunctionDeclarationBase(
    string $keywords,
    bool $is_abstract = false,
  ): string {
    $builder = hack_builder()
      ->add($keywords)
      ->add(
        '%s(%s)',
        $this->name,
        implode(', ', $this->parameters->toArray())
      )
      ->addIf($this->returnType !== null, ': ' . $this->returnType);

    $code = $builder->getCode();

    // If the total length is longer than max len, try to break it. Otherwise
    // return Total length = 2 (indent) + codelength + 2 or 1 (" {" or ";")
    // If the function/method is abstract, the ";" will be appended later
    // Therefore it has one char less than non-abstract functions, which has "{"
    if (Str::len($code) <=
        $this->config->getMaxLineLength() - 4 + (int)$is_abstract ||
        $this->fixme !== null) {
      return hack_builder()
        ->add($code)
        ->getCode();
    } else {
      $parameter_lines = $this->parameters
        ->map(function(string $line) { return $line.","; });

      $multi_line_builder = hack_builder()
        ->add($keywords)
        ->addLine("$this->name(")
        ->indent()
        ->addLines($parameter_lines)
        ->unindent()
        ->add(')')
        ->addIf($this->returnType !== null, ': ' . $this->returnType);

      return $multi_line_builder->getCode();
    }
  }

  protected function getMaxCodeLength(): int {
    $max_length = $this->config->getMaxLineLength();
    if ($this instanceof CodegenMethodBase) {
      $max_length -= $this->config->getSpacesPerIndentation();
    }
    return $max_length;
  }

  public function addHHFixMe(int $code, string $why): this {
    $max_length = $this->getMaxCodeLength() - 6;
    $str = sprintf('HH_FIXME[%d] %s', $code, $why);
    invariant(
      strlen($str) <= $max_length,
      'ERROR: Your fixme has to fit on one line, with indentation '.
      'and comments. So you need to shorten your message by %d '.
      'characters.',
      strlen($str) - $max_length,
    );
    $this->fixme = $str;
    return $this;
  }

  /**
   * $is_abstract and $containing_class_name
   * only valid for CodegenMethodX for code reuse purposes
   */
  protected function appendToBuilderBase(
    HackBuilder $builder,
    string $func_declaration,
    bool $is_abstract = false,
    string $containing_class_name = '',
  ): HackBuilder {
    if ($this->docBlock !== null && $this->docBlock !== '') {
      if ($this->generatedFrom) {
        $builder->addDocBlock(
          $this->docBlock . "\n(" . $this->generatedFrom->render() . ")"
        );
      } else {
        $builder->addDocBlock($this->docBlock);
      }
    } else {
      if ($this->generatedFrom) {
        $builder->addInlineComment($this->generatedFrom->render());
      }
    }
    if ($this->hasFunctionAnnotation()) {
      $builder->ensureNewLine()->addLine($this->getFunctionAnnotation());
    }
    if ($this->fixme !== null) {
      $builder->addInlineCommentWithStars($this->fixme);
    }
    $builder->add($func_declaration);

    if ($is_abstract) {
      $builder->addLine(';');
      return $builder;
    }

    $builder->openBrace();
    if ($this->isManualBody) {
      $builder->beginManualSection($containing_class_name. $this->name);
      $builder->add($this->body);
      $builder->endManualSection();
    } else {
      $builder->add($this->body);
    }
    $builder->closeBrace();
    return $builder;
  }

  private function hasFunctionAnnotation(): bool {
    return $this->getFunctionAnnotation() !== null;
  }

  private function getFunctionAnnotation(): ?string {
    $annotations = Vector {};
    if ($this->isOverride) {
      $annotations->add('__Override');
    }
    if ($this->isMemoized) {
      $annotations->add('__Memoize');
    }
    foreach ($this->userAttributes as $attribute_name => $arguments) {
      $annotations->add($arguments
        ? $attribute_name.'('.implode(',', $arguments).')'
        : $attribute_name,
      );
    }
    return $annotations ?
      '<<'.implode(', ', $annotations).'>>' :
      null;
  }

  private function getFunctionDeclaration(): string {
    // $keywords is shared by both single and multi line declaration
    $keywords = hack_builder()
      ->addIf($this->isAsync, 'async ')
      ->add('function ')
      ->getCode();

    return $this->getFunctionDeclarationBase($keywords);
  }

  public function appendToBuilder(HackBuilder $builder): HackBuilder {
    $func_declaration = $this->getFunctionDeclaration();
    return $this->appendToBuilderBase($builder, $func_declaration);
  }
}
