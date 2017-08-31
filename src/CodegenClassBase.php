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

use namespace HH\Lib\{C, Vec};

/**
 * Abstract class to generate code for a class or trait.
 *
 */
abstract class CodegenClassBase implements ICodeBuilderRenderer {

  use CodegenWithVisibility;
  use CodegenWithAttributes;
  use HackBuilderRenderer;

  protected dict<string, ?string> $genericsDecl = dict[];
  protected ?string $docBlock;
  protected ?CodegenGeneratedFrom $generatedFrom;
  protected ?CodegenFunction $wrapperFunc = null;
  protected vec<CodegenMethod> $methods = vec[];
  private vec<CodegenUsesTrait> $traits = vec[];
  protected vec<(string, bool, mixed, ?string)> $consts = vec[];
  protected vec<CodegenMemberVar> $vars = vec[];
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
  public function setGenericsDecl(
    KeyedTraversable<string, ?string> $generics_decl,
  ): this {
    $this->genericsDecl = dict($generics_decl);
    return $this;
  }

  public function addMethods(Traversable<CodegenMethod> $methods): this {
    foreach ($methods as $method) {
      $this->addMethod($method);
    }
    return $this;
  }

  public function addMethod(CodegenMethod $method): this {
    $method->setContainingClass($this);
    $this->methods[] = $method;
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

  protected function getTraits(): vec<string> {
    // Trait<T> becomes Trait
    return Vec\map(
      $this->traits,
      $trait ==> {
        $name = $trait->getName();
        return strstr($name, '<', true) ?: $name;
      },
    );
  }

  public function addTraits(Traversable<CodegenUsesTrait> $traits): this {
    foreach ($traits as $trait) {
      $this->addTrait($trait);
    }
    return $this;
  }

  public function addTrait(CodegenUsesTrait $trait): this {
    $this->traits[] = $trait;
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
      HackBuilderValues::literal(),
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
      HackBuilderValues::literal(),
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
      HackBuilderValues::literal(),
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
        ->beginManualSection($footer_name)
        ->addLine($footer)
        ->endManualSection();
    }
  }

  protected function buildManualDeclarations(HackBuilder $builder): void {
    if ($this->hasManualHeader) {
      $manual_section = coalescex($this->headerName, $this->name.'_header');
      $content = coalescex(
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

  protected function getExtraAttributes(): dict<string, vec<string>> {
    if ($this->isConsistentConstruct) {
      return dict['__ConsistentConstruct' => vec[]];
    }
    return dict[];
  }

  abstract protected function appendBodyToBuilder(HackBuilder $builder): void;

  public function appendToBuilder(HackBuilder $builder): HackBuilder {
    $generated_from =
      $this->generatedFrom ? $this->generatedFrom->render() : null;

    $doc_block_parts = array_filter(array($this->docBlock, $generated_from));

    if ($doc_block_parts) {
      $builder->addDocBlock(implode("\n\n", $doc_block_parts));
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

trait CodegenClassWithInterfaces {
  private vec<CodegenImplementsInterface> $interfaces = vec[];

  public function setInterfaces(
    Traversable<CodegenImplementsInterface> $value,
  ): this {
    invariant(
      C\is_empty($this->interfaces),
      'interfaces have already been set',
    );
    $this->interfaces = vec($value);
    return $this;
  }

  public function addInterface(CodegenImplementsInterface $value): this {
    $this->interfaces[] = $value;
    return $this;
  }

  public function addInterfaces(
    Traversable<CodegenImplementsInterface> $interfaces,
  ): this {
    $this->interfaces = Vec\concat($this->interfaces, $interfaces);
    return $this;
  }

  public function getImplements(): vec<string> {
    // Interface<T> becomes Interface
    return Vec\map(
      $this->interfaces,
      $interface ==> {
        $name = $interface->getName();
        return strstr($name, '<', true) ?: $name;
      },
    );
  }

  public function renderInterfaceList(
    HackBuilder $builder,
    string $introducer,
  ): void {
    if (!C\is_empty($this->interfaces)) {
      $builder->newLine()->indent()->addLine($introducer);
      $i = 0;
      foreach ($this->interfaces as $interface) {
        $i++;
        $builder->addRenderer($interface);
        $builder->addLineIf($i !== C\count($this->interfaces), ',');
      }
      $builder->unindent();
    }

  }
}
