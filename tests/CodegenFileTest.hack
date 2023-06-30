/*
 *  Copyright (c) 2015-present, Facebook, Inc.
 *  All rights reserved.
 *
 *  This source code is licensed under the MIT license found in the
 *  LICENSE file in the root directory of this source tree.
 *
 */

namespace Facebook\HackCodegen;

use type Facebook\HackCodegen\_Private\Filesystem;
use namespace HH\Lib\Str;
use type Facebook\HackTest\DataProvider;
use function Facebook\FBExpect\expect;

final class CodegenFileTest extends CodegenBaseTest {
  public function testAutogenerated(): void {
    $cgf = $this->getCodegenFactory();
    $code = $cgf
      ->codegenFile('no_file')
      ->setDocBlock('Completely autogenerated!')
      ->addClass(
        $cgf
          ->codegenClass('AllAutogenerated')
          ->addMethod(
            $cgf
              ->codegenMethod('getName')
              ->setReturnType('string')
              ->setBody('return $this->name;'),
          ),
      )
      ->render();

    expect_with_context(static::class, $code)->toBeUnchanged();
  }

  public function testGenerateTopLevelFunctions(): void {
    $cgf = $this->getCodegenFactory();
    $function =
      $cgf->codegenFunction('fun')->setReturnType('int')->setBody('return 0;');
    $code = $cgf->codegenFile('no_file')->addFunction($function)->render();

    expect_with_context(static::class, $code)->toBeUnchanged();
  }

  public function testGenerateEnums(): void {
    $cgf = $this->getCodegenFactory();
    $enum = $cgf->codegenEnum('TestEnum', 'int')
      ->addMember(
        $cgf->codegenEnumMember('FIRST')
          ->setValue(0, HackBuilderValues::export()),
      )
      ->addMember(
        $cgf->codegenEnumMember('SECOND')
          ->setValue(1, HackBuilderValues::export()),
      );
    $code = $cgf->codegenFile('no_file')->addEnum($enum)->render();

    expect_with_context(static::class, $code)->toBeUnchanged();
  }

  public function testPartiallyGenerated(): void {
    $cgf = $this->getCodegenFactory();
    $code = $cgf
      ->codegenFile('no_file')
      ->addClass(
        $cgf
          ->codegenClass('PartiallyGenerated')
          ->addMethod($cgf->codegenMethod('getSomething')->setManualBody()),
      )
      ->addClass(
        $cgf
          ->codegenClass('PartiallyGeneratedLoader')
          ->setDocBlock('We can put many clases in one file!'),
      )
      ->render();

    expect_with_context(static::class, $code)->toBeUnchanged();
  }

  private function saveAutogeneratedFile(?string $fname = null): string {
    $cgf = $this->getCodegenFactory();
    if ($fname === null) {
      $fname = Filesystem::createTemporaryFile('codegen', true);
    }

    $cgf
      ->codegenFile($fname)
      ->setDocBlock('Testing CodegenFile with autogenerated files')
      ->addClass(
        $cgf
          ->codegenClass('Demo')
          ->addMethod(
            $cgf
              ->codegenMethod('getName')
              ->setBody('return "Codegen";'),
          ),
      )
      ->save();

    return $fname;
  }

  private function saveManuallyWrittenFile(?string $fname = null): string {
    if ($fname === null) {
      $fname = Filesystem::createTemporaryFile('codegen', true);
    }

    Filesystem::writeFileIfChanged(
      $fname,
      "<?php\n".'// Some handwritten code',
    );
    return $fname;
  }

  private function savePartiallyGeneratedFile(
    ?string $fname = null,
    bool $extra_method = false,
  ): string {
    $cgf = $this->getCodegenFactory();

    if ($fname === null) {
      $fname = Filesystem::createTemporaryFile('codegen', true);
    }

    $class = $cgf
      ->codegenClass('Demo')
      ->addMethod(
        $cgf
          ->codegenMethod('getName')
          ->setBody('// manual_section_here')
          ->setManualBody(),
      );

    if ($extra_method) {
      $class->addMethod($cgf->codegenMethod('extraMethod')->setManualBody());
    }

    $cgf
      ->codegenFile($fname)
      ->setDocBlock('Testing CodegenFile with partially generated files')
      ->addClass($class)
      ->save();

    return $fname;
  }

  public function testSaveAutogenerated(): void {
    $fname = $this->saveAutogeneratedFile();
    expect_with_context(static::class, Filesystem::readFile($fname))
      ->toBeUnchanged();
  }

  public function testClobberManuallyWrittenCode(): void {
    expect(() ==> {
      $fname = $this->saveManuallyWrittenFile();
      $this->saveAutogeneratedFile($fname);
    })->toThrow(CodegenFileNoSignatureException::class);
  }

  public function testReSaveAutogenerated(): void {
    $fname = $this->saveAutogeneratedFile();
    $content0 = Filesystem::readFile($fname);
    $this->saveAutogeneratedFile($fname);
    $content1 = Filesystem::readFile($fname);
    expect($content0)->toBePHPEqual($content1);
  }

  public function testSaveModifiedAutogenerated(): void {
    expect(() ==> {
      $fname = $this->saveAutogeneratedFile();
      $content = Filesystem::readFile($fname);
      Filesystem::writeFile($fname, $content.'.');
      $this->saveAutogeneratedFile($fname);
    })->toThrow(CodegenFileBadSignatureException::class);
  }

  public function testSavePartiallyGenerated(): void {
    $fname = $this->savePartiallyGeneratedFile();
    $content = Filesystem::readFile($fname);
    expect_with_context(static::class, $content)->toBeUnchanged();
    expect(PartiallyGeneratedSignedSource::hasValidSignature($content))
      ->toBeTrue();
  }

  public function testReSavePartiallyGenerated(): void {
    $fname = $this->savePartiallyGeneratedFile();
    $content0 = Filesystem::readFile($fname);
    $this->savePartiallyGeneratedFile($fname);
    $content1 = Filesystem::readFile($fname);
    expect($content0)->toBePHPEqual($content1);
  }

  public function testSaveModifiedWrongPartiallyGenerated(): void {
    expect(() ==> {
      $fname = $this->savePartiallyGeneratedFile();
      $content = Filesystem::readFile($fname);
      Filesystem::writeFile($fname, $content.'.');
      $this->saveAutogeneratedFile($fname);
    })->toThrow(CodegenFileBadSignatureException::class);
  }

  private function createAndModifyPartiallyGeneratedFile(): string {
    $fname = $this->savePartiallyGeneratedFile();
    $content = Filesystem::readFile($fname);

    $new_content =
      \str_replace('// manual_section_here', 'return $this->name;', $content);
    expect($content === $new_content)->toBeFalse(
      "The manual content wasn't replaced. Please fix the test setup!",
    );
    Filesystem::writeFile($fname, $new_content);
    return $fname;
  }

  /**
   * Test modifying a manual section and saving.
   */
  public function testSaveModifiedManualSectionPartiallyGenerated(): void {
    $fname = $this->createAndModifyPartiallyGeneratedFile();
    $this->savePartiallyGeneratedFile($fname);
    $content = Filesystem::readFile($fname);
    expect(\strpos($content, 'this->name') !== false)->toBeTrue();
  }

  /**
   * Test modifying a manual section and changing the code generation so
   * that the generated part is different too.
   */
  public function testSaveModifyPartiallyGenerated(): void {
    $fname = $this->createAndModifyPartiallyGeneratedFile();
    $this->savePartiallyGeneratedFile($fname, true);
    $content = Filesystem::readFile($fname);
    expect(\strpos($content, 'return $this->name;') !== false)->toBeTrue();
    expect(\strpos($content, 'function extraMethod()') !== false)->toBeTrue();
  }

  public function testNoSignature(): void {
    $cgf = $this->getCodegenFactory();
    $code = $cgf
      ->codegenFile('no_file')
      ->setIsSignedFile(false)
      ->setDocBlock('Completely autogenerated!')
      ->addClass(
        $cgf
          ->codegenClass('NoSignature')
          ->addMethod(
            $cgf
              ->codegenMethod('getName')
              ->setReturnType('string')
              ->setBody('return $this->name;'),
          ),
      )
      ->render();

    expect_with_context(static::class, $code)->toBeUnchanged();
  }

  public function testNamespace(): void {
    $cgf = $this->getCodegenFactory();
    $code = $cgf
      ->codegenFile('no_file')
      ->setNamespace('MyNamespace')
      ->useNamespace('Another\Space')
      ->useType('My\Space\Bar', 'bar')
      ->useFunction('My\Space\my_function', 'f')
      ->useConst('My\Space\MAX_RETRIES')
      ->addClass($cgf->codegenClass('Foo'))
      ->render();

    expect_with_context(static::class, $code)->toBeUnchanged();
  }

  public function testStrictFile(): void {
    $cgf = $this->getCodegenFactory();
    $code = $cgf
      ->codegenFile('no_file')
      ->addClass($cgf->codegenClass('Foo'))
      ->render();

    expect_with_context(static::class, $code)->toBeUnchanged();
  }

  public function testPhpFile(): void {
    $cgf = $this->getCodegenFactory();
    $code = $cgf
      ->codegenFile('no_file')
      ->setFileType(CodegenFileType::PHP)
      ->addClass($cgf->codegenClass('Foo'))
      ->render();

    expect_with_context(static::class, $code)->toBeUnchanged();
  }

  public function testExecutable(): void {
    $cgf = $this->getCodegenFactory();
    $code = $cgf
      ->codegenFile('no_file')
      ->setFileType(CodegenFileType::HACK_PARTIAL)
      ->setShebangLine('#!/usr/bin/env hhvm')
      ->setPseudoMainHeader('require_once(\'vendor/autoload.php\');')
      ->addFunction(
        $cgf
          ->codegenFunction('main')
          ->setReturnType('void')
          ->setBody('print("Hello, world!\n");'),
      )
      ->setPseudoMainFooter('main();')
      ->render();
    expect_with_context(static::class, $code)->toBeUnchanged();
  }

  public function testDotHackExecutable(): void {
    $cgf = $this->getCodegenFactory();
    $code = $cgf
      ->codegenFile('no_file')
      ->setFileType(CodegenFileType::DOT_HACK)
      ->setShebangLine('#!/usr/bin/env hhvm')
      ->addFunction(
        $cgf->codegenFunction('main')
          ->setReturnType('noreturn')
          ->addEmptyUserAttribute('__EntryPoint')
          ->setBody('exit(0);'),
      )
      ->render();
    expect_with_context(static::class, $code)->toBeUnchanged();
  }

  public function testNoPseudoMainHeaderInStrict(): void {
    expect(
      () ==> $this->getCodegenFactory()
        ->codegenFile('no_file')
        ->setFileType(CodegenFileType::HACK_STRICT)
        ->setPseudoMainHeader('exit();')
        ->render(),
    )->toThrow(InvariantException::class);
  }

  public function testNoPseudoMainFooterInStrict(): void {
    expect(
      () ==> $this->getCodegenFactory()
        ->codegenFile('no_file')
        ->setFileType(CodegenFileType::HACK_STRICT)
        ->setPseudoMainFooter('exit();')
        ->render(),
    )->toThrow(InvariantException::class);
  }

  public function testFormattingFullyGeneratedFile(): void {
    $config = (new HackCodegenConfig())
      ->withRootDir(__DIR__);

    $cgf = new HackCodegenFactory(
      $config
        ->withFormatter(new HackfmtFormatter($config)),
    );

    $code = $cgf
      ->codegenFile('no_file')
      ->addFunction(
        $cgf->codegenFunction('my_func')
          ->addParameter('string $'.Str\repeat('a', 60))
          ->addParameter('string $'.Str\repeat('b', 60))
          ->setReturnType('(string, string)')
          ->setBody(
            $cgf->codegenHackBuilder()
              ->addReturnf(
                'tuple($%s, $%s)',
                Str\repeat('a', 60),
                Str\repeat('b', 60),
              )
              ->getCode(),
          ),
      )
      ->render();

    expect_with_context(static::class, $code)->toBeUnchanged();
    expect(SignedSourceBase::hasValidSignatureFromAnySigner($code))->toBeTrue(
      'bad signed source',
    );
    expect(Str\ends_with($code, "\n"))->toBeTrue('Should end with newline');
    expect(Str\ends_with($code, "\n\n"))->toBeFalse(
      'Should end with one newline, not multiple',
    );

    $lines = Str\split($code, "\n");
    expect(Str\starts_with($lines[8], ' '))->toBeTrue(
      'use spaces instead of tabs',
    );
  }

  public function testFormattingFullyGeneratedFileWithTabs(): void {
    $cgf = new HackCodegenFactory((new TestTabbedCodegenConfig()));

    $code = $cgf
      ->codegenFile('no_file')
      ->addFunction(
        $cgf->codegenFunction('my_func')
          ->addParameter('string $'.Str\repeat('a', 60))
          ->addParameter('string $'.Str\repeat('b', 60))
          ->setReturnType('(string, string)')
          ->setBody(
            $cgf->codegenHackBuilder()
              ->addReturnf(
                'tuple($%s, $%s)',
                Str\repeat('a', 60),
                Str\repeat('b', 60),
              )
              ->getCode(),
          ),
      )
      ->render();
    expect_with_context(static::class, $code)->toBeUnchanged();
    expect(SignedSourceBase::hasValidSignatureFromAnySigner($code))->toBeTrue(
      'bad signed source',
    );

    $lines = Str\split($code, "\n");
    expect(Str\starts_with($lines[8], "\t"))->toBeTrue(
      'use tabs instead of spaces',
    );
  }

  public function testFormattingFullyGeneratedFileWithOptions(): void {
    $cgf = new HackCodegenFactory((new TestHackfmtCodegenConfig()));

    $code = $cgf
      ->codegenFile('no_file')
      ->addFunction(
        $cgf->codegenFunction('my_func')
          ->addParameter('string $'.Str\repeat('a', 60))
          ->addParameter('string $'.Str\repeat('b', 60))
          ->setReturnType('(string, string)')
          ->setBody(
            $cgf->codegenHackBuilder()
              ->addReturnf(
                'tuple($%s, $%s)',
                Str\repeat('a', 60),
                Str\repeat('b', 60),
              )
              ->getCode(),
          ),
      )
      ->render();
    expect_with_context(static::class, $code)->toBeUnchanged();
    expect(SignedSourceBase::hasValidSignatureFromAnySigner($code))->toBeTrue(
      'bad signed source',
    );

    $lines = Str\split($code, "\n");
    expect(Str\starts_with($lines[8], "\t"))->toBeTrue(
      'use tabs instead of spaces',
    );
  }

  public function testFormattingUnsignedFile(): void {
    $config = (new HackCodegenConfig())
      ->withRootDir(__DIR__);

    $cgf = new HackCodegenFactory(
      $config
        ->withFormatter(new HackfmtFormatter($config)),
    );

    $code = $cgf
      ->codegenFile('no_file')
      ->setIsSignedFile(false)
      ->addFunction(
        $cgf->codegenFunction('my_func')
          ->addParameter('string $'.Str\repeat('a', 60))
          ->addParameter('string $'.Str\repeat('b', 60))
          ->setReturnType('(string, string)')
          ->setBody(
            $cgf->codegenHackBuilder()
              ->addReturnf(
                'tuple($%s, $%s)',
                Str\repeat('a', 60),
                Str\repeat('b', 60),
              )
              ->getCode(),
          ),
      )
      ->render();
    expect_with_context(static::class, $code)->toBeUnchanged();
    expect(SignedSourceBase::hasValidSignatureFromAnySigner($code))->toBeFalse(
      'file should be unsigned, but has valid signature',
    );
  }

  public function testFormattingPartiallyGeneratedFile(): void {
    $config = (new HackCodegenConfig())
      ->withRootDir(__DIR__);

    $cgf = new HackCodegenFactory(
      $config
        ->withFormatter(new HackfmtFormatter($config)),
    );

    $code = $cgf
      ->codegenFile('no_file')
      ->addFunction(
        $cgf->codegenFunction('my_func')
          ->addParameter('string $'.Str\repeat('a', 60))
          ->addParameter('string $'.Str\repeat('b', 60))
          ->setReturnType('(string, string)')
          ->setBody(
            $cgf->codegenHackBuilder()
              ->startManualSection('whut')
              ->endManualSection()
              ->addReturnf(
                'tuple($%s, $%s)',
                Str\repeat('a', 60),
                Str\repeat('b', 60),
              )
              ->getCode(),
          ),
      )
      ->render();
    expect_with_context(static::class, $code)->toBeUnchanged();
    expect(SignedSourceBase::hasValidSignatureFromAnySigner($code))->toBeTrue(
      'bad signed source',
    );
  }

  public function testConstants(): void {
    $cgf = $this->getCodegenFactory();
    $code = $cgf
      ->codegenFile('no_file')
      ->setNamespace('Foo\\Bar')
      ->useNamespace('Herp\\Derp')
      ->addConstant(
        $cgf->codegenConstant('FOO')
          ->setType('string')
          ->setValue('bar', HackBuilderValues::export()),
      )
      ->addConstant(
        $cgf->codegenConstant('HERP')
          ->setDocBlock('doc comment')
          ->setType('string')
          ->setValue('derp', HackBuilderValues::export()),
      )
      ->render();
    expect_with_context(static::class, $code)->toBeUnchanged();
  }

  public function provideFileNamesWithExpectedModes(
  )[]: dict<string, (string, CodegenFileType)> {
    return dict[
      'Legacy extensions, such as `.php` trigger // strict mode.' =>
        tuple('filename.php', CodegenFileType::HACK_STRICT),
      'Legacy extensions, such as .hh trigger // strict mode.' =>
        tuple('filename.hh', CodegenFileType::HACK_STRICT),
      'Just guessing, but .hhi files need the <?hh sigil???' =>
        tuple('filename.hhi', CodegenFileType::HACK_STRICT),
      'Dot hack implies there is no need for a <?hh sigil.' =>
        tuple('filename.hack', CodegenFileType::DOT_HACK),
      // https://hhvm.com/blog/2021/10/26/hhvm-4.133.html
      'As of hhvm 4.133 all files without a recognized extensions are treated as .hack' =>
        tuple('filename', CodegenFileType::DOT_HACK),
    ];
  }

  <<DataProvider('provideFileNamesWithExpectedModes')>>
  public function testFileModeInference(
    string $filename,
    CodegenFileType $mode,
  ): void {
    $cfg = $this->getCodegenFactory();
    $file = $cfg->codegenFile($filename);
    expect($file->getFileType())->toEqual($mode);
  }
}

final class TestTabbedCodegenConfig implements IHackCodegenConfig {
  public function getFileHeader(): ?Vector<string> {
    return null;
  }

  public function getSpacesPerIndentation(): int {
    return 4;
  }

  public function getMaxLineLength(): int {
    return 80;
  }

  public function shouldUseTabs(): bool {
    return true;
  }

  public function getRootDir(): string {
    return __DIR__;
  }

  public function getFormatter(): ?ICodegenFormatter {
    return null;
  }
}

final class TestHackfmtCodegenConfig implements IHackCodegenConfig {
  public function getFileHeader(): ?Vector<string> {
    return null;
  }

  public function getSpacesPerIndentation(): int {
    return 4;
  }

  public function getMaxLineLength(): int {
    return 80;
  }

  public function shouldUseTabs(): bool {
    return true;
  }

  public function getRootDir(): string {
    return __DIR__;
  }

  public function getFormatter(): ?ICodegenFormatter {
    return new HackfmtFormatter($this);
  }
}
