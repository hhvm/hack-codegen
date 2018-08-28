<?hh // strict
/*
 *  Copyright (c) 2015-present, Facebook, Inc.
 *  All rights reserved.
 *
 *  This source code is licensed under the MIT license found in the
 *  LICENSE file in the root directory of this source tree.
 *
 */

namespace Facebook\HackCodegen;

use namespace HH\Lib\{C, Str, Vec};
use namespace Facebook\HackCodegen\_Private\C as CP;

/**
 * Abstract class to generate class-like definitions.
 *
 * @see CodegenClass
 * @see CodegenInterface
 * @see CodegenTrait
 * @see CodegenEnum
 */
abstract class CodegenClassish implements ICodeBuilderRenderer {

  use CodegenWithVisibility;
  use CodegenWithAttributes;
  use HackBuilderRenderer;

  protected vec<string> $genericsDecl = vec[];
  protected ?string $docBlock;
  protected ?CodegenGeneratedFrom $generatedFrom;
  protected ?CodegenFunction $wrapperFunc = null;
  protected vec<CodegenMethod> $methods = vec[];
  private vec<CodegenUsesTrait> $traits = vec[];
  protected vec<(string, bool, mixed, ?string)> $consts = vec[];
  protected vec<CodegenProperty> $vars = vec[];
  protected bool $hasManualFooter = false;
  protected bool $hasManualHeader = false;
  private bool $isConsistentConstruct = false;
  private ?string $headerName;
  private ?string $headerContents;
  private ?string $footerName;
  private ?string $footerContents;

  public function __construct(
    protected IHackCodegenConfig $config,
    protected string $name,
  ) {
  }

  /** @selfdocumenting */
  public function getName(): string {
    return $this->name;
  }

  /**
   * Add generic parameters.
   *
   * For example:
   *
   * ```
   * $class->addGenerics(vec['TRead', 'TWrite as T']);
   * ```
   *
   * Will generate:
   *
   * ```
   * class MyClass<TRead, TWrite as T> {
   * ```
   *
   * @see addGeneric
   * @see addGenericf
   */
  public function addGenerics(
    Traversable<string> $generics_decl,
  ): this {
    foreach ($generics_decl as $decl) {
      $this->addGeneric($decl);
    }
    return $this;
  }

  /** Add a generic parameter using a %-placeholder format string.
   *
   * @see addGenerics
   */
  public function addGenericf(
    SprintfFormatString $format,
    mixed ...$args
  ): this {
    $this->addGenerics(\vsprintf($format, $args));
    return $this;
  }

  /** Add a generic parameter.
   *
   * @see addGenerics
   * @see addGenericsf
   */
  public function addGeneric(
    string $decl,
  ): this {
    $this->genericsDecl[] = $decl;
    return $this;
  }

  /** Add a generic parameter with subtype constraint.
   *
   * Subtype constraint of two types ```T``` and ```U``` will be evaluated to
   * the following: ```T as U``` whereas this statement asserts
   * that ```T``` must be a subtype of ```U```.
   *
   * @see addGeneric
   * @see addGenericSupertypeConstraint
   */
  public function addGenericSubtypeConstraint(
    string $subtype,
    string $baseType
  ): this {
    $this->addGeneric($subtype.' as '.$baseType);
    return $this;
  }

  /** Add a generic parameter with supertype constraint.
   *
   * Supertype constraint of two types ```T``` and ```U``` will be evaluated to
   * the following: ```T super U``` whereas this statement asserts
   * that ```T``` must be a supertype of ```U```.
   *
   * @see addGeneric
   * @see addGenericSubtypeConstraint
   */
  public function addGenericSupertypeConstraint(
    string $superType,
    string $subtype
  ): this {
    $this->addGeneric($superType.' super '.$subtype);
    return $this;
  }

  /** @selfdocumenting */
  public function addMethods(Traversable<CodegenMethod> $methods): this {
    foreach ($methods as $method) {
      $this->addMethod($method);
    }
    return $this;
  }

  /** @selfdocumenting */
  public function addMethod(CodegenMethod $method): this {
    $method->setContainingClass($this);
    $this->methods[] = $method;
    return $this;
  }

  /** @selfdocumenting */
  public function setDocBlock(string $comment): this {
    $this->docBlock = $comment;
    return $this;
  }

  /** @selfdocumenting */
  public function setGeneratedFrom(CodegenGeneratedFrom $from): this {
    $this->generatedFrom = $from;
    return $this;
  }

  /** @selfdocumenting */
  protected function getTraits(): vec<string> {
    // Trait<T> becomes Trait
    return Vec\map(
      $this->traits,
      $trait ==> {
        $name = $trait->getName();
        return \strstr($name, '<', true) ?: $name;
      },
    );
  }

  /** @selfdocumenting */
  public function addTraits(Traversable<CodegenUsesTrait> $traits): this {
    foreach ($traits as $trait) {
      $this->addTrait($trait);
    }
    return $this;
  }

  /** @selfdocumenting */
  public function addTrait(CodegenUsesTrait $trait): this {
    $this->traits[] = $trait;
    return $this;
  }

  /** @selfdocumenting */
  public function addTypeConst(
    string $name,
    string $type,
    ?string $comment = null,
  ): this {
    return $this->addConst(
      'type '.$name,
      $type,
      $comment,
      HackBuilderValues::literal(),
    );
  }

  /** @selfdocumenting */
  public function addPartiallyAbstractTypeConst(
    string $name,
    string $type,
    string $constraint,
    ?string $comment = null,
  ): this {
    return $this->addConst(
      \sprintf('type %s as %s', $name, $constraint),
      $type,
      $comment,
      HackBuilderValues::literal(),
    );
  }

  /** @selfdocumenting */
  public function addAbstractTypeConst(
    string $name,
    string $type,
    ?string $comment = null,
  ): this {
    return $this->addAbstractConst(
      \sprintf('type %s as%s%s', $name, HackBuilder::DELIMITER, $type),
      $comment,
    );
  }

  /** @selfdocumenting */
  public function addClassNameConst(
    string $type,
    string $name,
    ?string $comment = null,
  ): this {
    return $this->addConst(
      \sprintf('classname<%s> %s', $type, $name),
      \sprintf('%s::class', $type),
      $comment,
      HackBuilderValues::literal(),
    );
  }

  /** @selfdocumenting */
  public function addAbstractClassNameConst(
    string $type,
    string $name,
    ?string $comment = null,
  ): this {
    return $this->addAbstractConst(
      \sprintf('classname<%s> %s', $type, $name),
      $comment,
    );
  }

  /** @selfdocumenting */
  public function addConst<T>(
    string $name,
    T $value,
    ?string $comment = null,
    IHackBuilderValueRenderer<T> $values_config = HackBuilderValues::export(),
  ): this {
    $rendered_value = $values_config->render($this->config, $value);
    $this->consts[] = tuple($name, false, $rendered_value, $comment);
    return $this;
  }

  /** @selfdocumenting */
  public function addAbstractConst(
    string $name,
    ?string $comment = null,
  ): this {
    $this->consts[] = tuple($name, true, null, $comment);
    return $this;
  }

  /** @selfdocumenting */
  public function addProperty(CodegenProperty $var): this {
    $this->vars[] = $var;
    return $this;
  }

  /** @selfdocumenting */
  public function addProperties(Traversable<CodegenProperty> $vars): this {
    $this->vars = Vec\concat(
      $this->vars,
      $vars,
    );
    return $this;
  }

  /**
   * Set whether or not the class has a section to contain manually written
   * or modified methods.
   *
   * Manual method sections appear at the bottom of the class.
   *
   * You may specify a name for the section, which will appear in
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
   * declarations, such as type constants.
   *
   * Manual declaration sections appear at the top of the class.
   *
   * You may specify a name for the section, which will appear in
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

  /** Requires subclasses to have compatible constructors. */
  public function setIsConsistentConstruct(bool $value = true): this {
    $this->isConsistentConstruct = $value;
    return $this;
  }

  abstract protected function buildDeclaration(HackBuilder $builder): void;

  protected function buildGenericsDeclaration(): string {
    $generics_count = C\count($this->genericsDecl);
    if ($generics_count === 0) {
      return '';
    }

    if ($generics_count === 1) {
      return '<'.C\onlyx($this->genericsDecl).'>';
    }

    return $this->genericsDecl
      |> Vec\map(
        $$,
        $decl ==> '    '.$decl.",\n",
      )
      |> \implode("", $$)
      |> Str\strip_suffix($$, ",\n")
      |> "\n  <\n".$$."\n  >";
  }

  protected function buildTraits(HackBuilder $builder): void {
    if (C\is_empty($this->traits)) {
      return;
    }
    $builder->ensureEmptyLine();
    foreach ($this->traits as $trait) {
      $builder->add($trait->render());
    }
  }

  protected function buildConsts(HackBuilder $builder): void {
    if (C\is_empty($this->consts)) {
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
        $builder->addWithSuggestedLineBreaksf('abstract const %s;', $name);
      } else {
        $builder->addWithSuggestedLineBreaksf(
          "const %s =\t%s;",
          $name,
          (string)$value,
        );
      }
      $builder->newLine();
    }
  }

  protected function buildVars(HackBuilder $builder): void {
    if (C\is_empty($this->vars)) {
      return;
    }
    $builder->ensureEmptyLine();
    foreach ($this->vars as $var) {
      $builder->addRenderer($var);
    }
  }

  protected function buildMethods(HackBuilder $builder): void {
    foreach ($this->methods as $method) {
      $builder->ensureEmptyLine()->addRenderer($method);
    }
    if ($this->hasManualFooter) {
      $footer_name = $this->footerName === null
        ? ($this->name.'_footer')
        : $this->footerName;
      $footer = $this->footerContents === null
        ? '// Insert additional methods here'
        : $this->footerContents;
      $builder
        ->ensureEmptyLine()
        ->startManualSection($footer_name)
        ->addLine($footer)
        ->endManualSection();
    }
  }

  protected function buildManualDeclarations(HackBuilder $builder): void {
    if ($this->hasManualHeader) {
      $manual_section = CP\coalescevax($this->headerName, $this->name.'_header');
      $content = CP\coalescevax(
        $this->headerContents,
        '// Insert additional consts and vars here',
      );
      $builder
        ->ensureEmptyLine()
        ->startManualSection($manual_section)
        ->addLine($content)
        ->endManualSection();
    }
  }

  /** Returns all the attributes defined on the class */
  protected function getExtraAttributes(): dict<string, vec<string>> {
    if ($this->isConsistentConstruct) {
      return dict['__ConsistentConstruct' => vec[]];
    }
    return dict[];
  }

  /** Append just the body of the class (between `{` and `}` to a
   * `HackBuilder` */
  abstract protected function appendBodyToBuilder(HackBuilder $builder): void;

  /** Append the entire declaration to a `HackBuilder`.
   *
   * This includes the keywords, interface declaraitons, etc - and the
   * body.
   */
  final public function appendToBuilder(HackBuilder $builder): HackBuilder {
    $generated_from =
      $this->generatedFrom ? $this->generatedFrom->render() : null;

    $doc_block_parts = \array_filter(array($this->docBlock, $generated_from));

    if ($doc_block_parts) {
      $builder->addDocBlock(\implode("\n\n", $doc_block_parts));
    }

    $wrapper_func = $this->wrapperFunc;
    if ($wrapper_func) {
      $builder->addRenderer($wrapper_func)->ensureEmptyLine();
    }

    if ($this->hasAttributes()) {
      $builder->ensureNewLine()->addLine($this->renderAttributes());
    }

    $this->buildDeclaration($builder);
    $builder->openBrace();

    $this->appendBodyToBuilder($builder);

    $builder->closeBrace();

    return $builder;
  }
}
