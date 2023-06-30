/*
 *  Copyright (c) 2015-present, Facebook, Inc.
 *  All rights reserved.
 *
 *  This source code is licensed under the MIT license found in the
 *  LICENSE file in the root directory of this source tree.
 *
 */

namespace Facebook\HackCodegen;

use namespace HH\Lib\{Keyset, Str, Vec};

/**
 * Base class to generate a function or a method.
 */
abstract class CodegenFunctionish implements ICodeBuilderRenderer {

  use HackBuilderRenderer;
  use CodegenWithAttributes;

  protected string $name;
  protected ?string $body = null;
  protected ?string $docBlock = null;
  protected ?keyset<string> $contexts = null;
  protected ?string $returnType = null;
  private ?string $fixme = null;
  protected bool $isAsync = false;
  protected bool $isOverride = false;
  protected bool $isManualBody = false;
  protected bool $isMemoized = false;
  protected vec<string> $parameters = vec[];
  protected ?CodegenGeneratedFrom $generatedFrom;

  public function __construct(
    protected IHackCodegenConfig $config,
    string $name,
  ) {
    $this->name = $name;
  }

  public function setName(string $name): this {
    $this->name = $name;
    return $this;
  }

  public function setIsAsync(bool $value = true): this {
    $this->isAsync = $value;
    return $this;
  }

  public function setIsMemoized(bool $value = true): this {
    $this->isMemoized = $value;
    return $this;
  }

  public function addContext(
    string $context,
  ): this {
    if($this->contexts === null) {
      $this->contexts = keyset<string>[$context];
    } else {
      $this->contexts[] = $context;
    }

    return $this;
  }

  public function addContexts(
    Traversable<string> $contexts,
  ): this {
    $contexts = keyset($contexts);
    if (C\is_empty($contexts)) {
      // Don't accidentally convert `foo(): void` to `foo()[]: void`;
      // `addContexts()` should only make things *more* permissive
      return $this;
    }

    if ($this->contexts is null || C\is_empty($this->contexts)) {
      $this->contexts = $contexts;
    } else {
      $this->contexts = Keyset\union($this->contexts, $contexts);
    }

    return $this;
  }

  /** Set or remove the contexts.
   *
   * - if passed `null`, the function or method will not contain a contexts
   *   declaration, e.g. `function foo(): void {}`; this is equivalent to
   *   `function foo()[defaults]: void {}`
   * - if passed the empty set (e.g. `keyset[]`), the function will have an
   *   empty contexts declaration, e.g. `function foo()[]: void {}`. This is
   *   considered to be an approximation of declaration pure functions.
   */
  public function setContexts(
    ?Container<string> $contexts,
  ): this {
    $this->contexts = ($contexts is null) ? null : keyset($contexts);
    return $this;
  }

  public function setReturnType(string $type): this {
    return $this->setReturnTypef('%s', $type);
  }

  public function setReturnTypef(
    Str\SprintfFormatString $type,
    mixed ...$args
  ): this {
    $type = \vsprintf($type, $args);
    if ($type) {
      $this->returnType = $type;
    }
    return $this;
  }

  public function addParameter(string $param): this {
    return $this->addParameterf('%s', $param);
  }

  public function addParameterf(
    Str\SprintfFormatString $param,
    mixed ...$args
  ): this {
    $param = \vsprintf($param, $args);
    $this->parameters[] = $param;
    return $this;
  }

  public function addParameters(Traversable<string> $params): this {
    foreach ($params as $param) {
      $this->addParameter($param);
    }
    return $this;
  }

  public function setBody(string $body): this {
    return $this->setBodyf('%s', $body);
  }

  public function setBodyf(
    Str\SprintfFormatString $body,
    mixed ...$args
  ): this {
    $this->body = \vsprintf($body, $args);

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

  public function getParameters(): vec<string> {
    return $this->parameters;
  }

  public function getContexts(): ?keyset<string> {
    return $this->contexts;
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
    $builder = (new HackBuilder($this->config))
      ->add($keywords)
      ->addf('%s(%s)', $this->name, Str\join($this->parameters, ', '))
      ->addIf($this->contexts !== null, '[' . Str\join($this->contexts ?? keyset[], ', ') . ']')
      ->addIf($this->returnType !== null, ': '.($this->returnType ?? ''));

    $code = $builder->getCode();

    // If the total length is longer than max len, try to break it. Otherwise
    // return Total length = 2 (indent) + codelength + 2 or 1 (" {" or ";")
    // If the function/method is abstract, the ";" will be appended later
    // Therefore it has one char less than non-abstract functions, which has "{"
    if (
      Str\length($code) <=
        $this->config->getMaxLineLength() - 4 + (int)$is_abstract ||
      $this->fixme !== null
    ) {
      return (new HackBuilder($this->config))->add($code)->getCode();
    } else {
      $parameter_lines = Vec\map(
        $this->parameters,
        $line ==> {
          if (Str\search($line, '...$') !== null) {
            return $line;
          }
          return $line.',';
        },
      );

      $multi_line_builder = (new HackBuilder($this->config))
        ->add($keywords)
        ->addLine($this->name.'(')
        ->indent()
        ->addLines($parameter_lines)
        ->unindent()
        ->add(')')
        ->addIf($this->contexts !== null, '[' . Str\join($this->contexts ?? keyset[], ', ') . ']')
        ->addIf($this->returnType !== null, ': '.($this->returnType ?? ''));

      return $multi_line_builder->getCode();
    }
  }

  protected function getMaxCodeLength(): int {
    $max_length = $this->config->getMaxLineLength();
    if ($this is CodegenMethodish) {
      $max_length -= $this->config->getSpacesPerIndentation();
    }
    return $max_length;
  }

  public function addHHFixMe(int $code, string $why): this {
    $max_length = $this->getMaxCodeLength() - 6;
    $str = \sprintf('HH_FIXME[%d] %s', $code, $why);
    invariant(
      \strlen($str) <= $max_length,
      'ERROR: Your fixme has to fit on one line, with indentation '.
      'and comments. So you need to shorten your message by %d '.
      'characters.',
      \strlen($str) - $max_length,
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
          $this->docBlock."\n(".$this->generatedFrom->render().')',
        );
      } else {
        $builder->addDocBlock($this->docBlock);
      }
    } else {
      if ($this->generatedFrom) {
        $builder->addInlineComment($this->generatedFrom->render());
      }
    }
    if ($this->hasAttributes()) {
      $builder->ensureNewLine()->addLine($this->renderAttributes());
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
      $builder->startManualSection($containing_class_name.$this->name);
      $builder->add($this->body);
      $builder->endManualSection();
    } else {
      $builder->add($this->body);
    }
    $builder->closeBrace();
    return $builder;
  }

  protected function getExtraAttributes(): dict<string, vec<string>> {
    $attributes = dict[];
    if ($this->isOverride) {
      $attributes['__Override'] = vec[];
    }
    if ($this->isMemoized) {
      $attributes['__Memoize'] = vec[];
    }
    return $attributes;
  }

  private function getFunctionDeclaration(): string {
    // $keywords is shared by both single and multi line declaration
    $keywords = (new HackBuilder($this->config))
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
