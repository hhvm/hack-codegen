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
 * Abstract class to generate code for a class or trait.
 *
 */
abstract class CodegenClassBase
  implements ICodeBuilderRenderer {

  use CodegenWithVisibility;
  use HackBuilderRenderer;

  protected string $name;
  protected Map<string, ?string> $genericsDecl = Map {};
  protected ?string $docBlock;
  protected ?CodegenGeneratedFrom $generatedFrom;
  protected ?CodegenFunction $wrapperFunc = null;
  protected Vector<CodegenMethod> $methods = Vector {};
  private Vector<CodegenUsesTrait> $traits = Vector {};
  protected Vector<(string, bool, mixed, ?string)> $consts = Vector {};
  protected Vector<CodegenMemberVar> $vars = Vector {};
  protected bool $hasManualFooter = false;
  protected bool $hasManualHeader = false;
  private bool $isConsistentConstruct = false;
  private ?string $headerName;
  private ?string $headerContents;
  private ?string $footerName;
  private ?string $footerContents;

  public function __construct(string $name) {
    $this->name = $name;
  }

  public function getName(): string {
    return $this->name;
  }

  /**
   * E.g.
   * $class->setGenericsDecl(Map {'TRead' => null, 'TWrite' => 'T'})
   *
   * Will generate:
   * class MyClass<TRead, TWrite as T> {
   */
  public function setGenericsDecl(Map<string, ?string> $generics_decl): this {
    $this->genericsDecl = $generics_decl;
    return $this;
  }

  public function addMethods(\ConstVector<CodegenMethod> $methods): this {
    foreach ($methods as $method) {
      $this->addMethod($method);
    }
    return $this;
  }

  public function addMethod(CodegenMethod $method): this {
    $method->setContainingClass($this);
    $this->methods->add($method);
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

  protected function getTraits(): Vector<string> {
    // Trait<T> becomes Trait
    return $this->traits
      ->map($trait ==> {
        $name = $trait->getName();
        return strstr($name, '<', true) ?: $name;
      });
  }

  public function addTraits(Vector<CodegenUsesTrait> $traits): this {
    foreach ($traits as $trait) {
      $this->addTrait($trait);
    }
    return $this;
  }

  public function addTrait(CodegenUsesTrait $trait): this {
    $this->traits->add($trait);
    return $this;
  }

  public function addTypeConst(
    string $name,
    string $type,
    ?string $comment = null,
  ): this {
    return $this->addConst(
      'type '.$name,
      $type,
      $comment,
      HackBuilderValues::LITERAL,
    );
  }

  public function addPartiallyAbstractTypeConst(
    string $name,
    string $type,
    string $constraint,
    ?string $comment = null,
  ): this {
    return $this->addConst(
      sprintf('type %s as %s', $name, $constraint),
      $type,
      $comment,
      HackBuilderValues::LITERAL,
    );
  }

  public function addAbstractTypeConst(
    string $name,
    string $type,
    ?string $comment = null,
  ): this {
    return $this->addAbstractConst(
      sprintf('type %s as%s%s', $name, HackBuilder::DELIMITER, $type),
      $comment,
    );
  }

  public function addClassNameConst(
    string $type,
    string $name,
    ?string $comment = null,
  ): this {
    return $this->addConst(
      sprintf('classname<%s> %s', $type, $name),
      sprintf('%s::class', $type),
      $comment,
      HackBuilderValues::LITERAL,
    );
  }

  public function addAbstractClassNameConst(
    string $type,
    string $name,
    ?string $comment = null,
  ): this {
    return $this->addAbstractConst(
      sprintf('classname<%s> %s', $type, $name),
      $comment,
    );
  }

  public function addConst(
    string $name,
    mixed $value,
    ?string $comment = null,
    HackBuilderValues $values_config = HackBuilderValues::EXPORT,
  ): this {
    $rendered_value = $values_config === HackBuilderValues::LITERAL
      ? $value
      : var_export($value, true);
    $this->consts[] = tuple($name, false, $rendered_value, $comment);
    return $this;
  }

  public function addAbstractConst(
    string $name,
    ?string $comment = null,
  ): this {
    $this->consts[] = tuple($name, true, null, $comment);
    return $this;
  }

  public function addVar(CodegenMemberVar $var): this {
    $this->vars[] = $var;
    return $this;
  }

  /**
   * If value is set to true, the class will have a section for manually adding
   * methods.  You may specify a name for the section, which will appear in
   * the comment and is used to merge the code when re-generating it.
   * You may also specify a default content for the manual section, e.g.
   * a comment indicating that additional methods should be placed there.
   */
  public function setHasManualMethodSection(
    bool $value = true,
    ?string $name = null,
    ?string $contents = null,
  ): this {
    $this->hasManualFooter = $value;
    $this->footerName = $name;
    $this->footerContents = $contents;
    return $this;
  }

  /**
   * If value is set to true, the class will have a section for manually adding
   * declarations.  You may specify a name for the section, which will appear in
   * the comment and is used to merge the code when re-generating it.
   * You may also specify a default content for the manual section, e.g.
   * a comment indicating that additional declarations should be placed there.
   */
  public function setHasManualDeclarations(
    bool $value = true,
    ?string $name = null,
    ?string $contents = null,
  ): this {
    $this->hasManualHeader = $value;
    $this->headerName = $name;
    $this->headerContents = $contents;
    return $this;
  }

  public function setIsConsistentConstruct(bool $value = true): this {
    $this->isConsistentConstruct = $value;
    return $this;
  }

  abstract protected function buildDeclaration(HackBuilder $builder): void;

  protected function buildGenericsDeclaration(): string {
    $generics_dec = "";
    $generics_count = count($this->genericsDecl);
    if ($generics_count == 1) {
      foreach ($this->genericsDecl as $key => $type) {
        $generics_dec = "<$key".((bool)$type ? " as $type>" : ">");
      }
    } else if ($generics_count > 1) {
      $generics_dec .= "\n  <\n";
      foreach ($this->genericsDecl as $key => $type) {
        $generics_dec .= "    $key".((bool)$type ? " as $type,\n" : ",\n");
      }
      $generics_dec = substr($generics_dec, 0, strlen($generics_dec) - 2);
      $generics_dec .= "\n  >";
    }
    return $generics_dec;
  }

  protected function buildTraits(HackBuilder $builder): void {
    if ($this->traits->isEmpty()) {
      return;
    }
    $builder->ensureEmptyLine();
    foreach ($this->traits as $trait) {
      $builder->add($trait->render());
    }
  }

  protected function buildConsts(HackBuilder $builder): void {
    if ($this->consts->isEmpty()) {
      return;
    }
    $builder->ensureEmptyLine();

    foreach ($this->consts as $const) {
      list($name, $is_abstract, $value, $comment) = $const;
      if ($comment !== null) {
        $builder->ensureEmptyLine();
        $builder->addDocBlock($comment);
      }
      if ($is_abstract) {
        $builder->addWithSuggestedLineBreaks('abstract const %s;', $name);
      } else {
        $builder->addWithSuggestedLineBreaks(
          'const %s ='.HackBuilder::DELIMITER.'%s;',
          $name,
          $value,
        );
      }
      $builder->newLine();
    }
  }

  protected function buildVars(HackBuilder $builder): void {
    if ($this->vars->isEmpty()) {
      return;
    }
    $builder->ensureEmptyLine();
    foreach ($this->vars as $var) {
      $builder->addRenderer($var);
    }
  }

  protected function buildMethods(HackBuilder $builder): void {
    foreach ($this->methods as $method) {
      $builder
        ->ensureEmptyLine()
        ->addRenderer($method);
    }
    if ($this->hasManualFooter) {
      $footer_name =
        $this->footerName === null
          ? ($this->name.'_footer')
          : $this->footerName;
      $footer =
        $this->footerContents === null
          ? '// Insert additional methods here'
          : $this->footerContents;
      $builder
        ->ensureEmptyLine()
        ->beginManualSection($footer_name)
        ->addLine($footer)
        ->endManualSection();
    }
  }

  protected function buildManualDeclarations(HackBuilder $builder): void {
    if ($this->hasManualHeader) {
      $manual_section = coalesce($this->headerName, $this->name . '_header');
      $content = coalesce(
        $this->headerContents,
        '// Insert additional consts and vars here',
      );
      $builder
        ->ensureEmptyLine()
        ->beginManualSection($manual_section)
        ->addLine($content)
        ->endManualSection();
    }
  }

  private function hasClassAnnotation(): bool {
    return $this->getClassAnnotation() !== null;
  }

  private function getClassAnnotation(): ?string {
    $annotations = Vector {};
    if ($this->isConsistentConstruct) {
      $annotations->add('__ConsistentConstruct');
    }
    return $annotations ?
      '<<'.implode(', ', $annotations).'>>' :
      null;
  }

  abstract protected function appendBodyToBuilder(HackBuilder $builder): void;

  public function appendToBuilder(HackBuilder $builder): HackBuilder {
    $generated_from = $this->generatedFrom
      ? $this->generatedFrom->render()
      : null;

    $doc_block_parts =
      array_filter(array($this->docBlock, $generated_from));

    if ($doc_block_parts) {
      $builder->addDocBlock(implode("\n\n", $doc_block_parts));
    }

    $wrapper_func = $this->wrapperFunc;
    if ($wrapper_func) {
      $builder
        ->addRenderer($wrapper_func)
        ->ensureEmptyLine();
    }

    if ($this->hasClassAnnotation()) {
      $builder->ensureNewLine()->addLine($this->getClassAnnotation());
    }

    $this->buildDeclaration($builder);
    $builder->openBrace();

    $this->appendBodyToBuilder($builder);

    $builder->closeBrace();

    return $builder;
  }
}

trait CodegenClassWithInterfaces {
  private Vector<CodegenImplementsInterface> $interfaces = Vector {};

  public function setInterfaces(
    Vector<CodegenImplementsInterface> $value
  ): this {
    invariant($this->interfaces->isEmpty(), 'interfaces have already been set');
    $this->interfaces = $value;
    return $this;
  }

  public function addInterface(CodegenImplementsInterface $value): this {
    $this->interfaces->add($value);
    return $this;
  }

  public function addInterfaces(
    Vector<CodegenImplementsInterface> $interfaces,
  ): this {
    $this->interfaces->addAll($interfaces);
    return $this;
  }

  public function getImplements(): Vector<string> {
    // Interface<T> becomes Interface
    return $this->interfaces
      ->map($interface ==> {
        $name = $interface->getName();
        return strstr($name, '<', true) ?: $name;
      });
  }

  public function renderInterfaceList(
    HackBuilder $builder,
    string $introducer,
  ): void {
    if (!$this->interfaces->isEmpty()) {
      $builder->newLine()->indent()->addLine($introducer);
      $i = 0;
      foreach ($this->interfaces as $interface) {
        $i++;
        $builder->addRenderer($interface);
        $builder->addLineIf($i !== $this->interfaces->count(), ',');
      }
      $builder->unindent();
    }

  }
}
