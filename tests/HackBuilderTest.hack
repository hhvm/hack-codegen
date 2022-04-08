/*
 *  Copyright (c) 2015-present, Facebook, Inc.
 *  All rights reserved.
 *
 *  This source code is licensed under the MIT license found in the
 *  LICENSE file in the root directory of this source tree.
 *
 */

namespace Facebook\HackCodegen;

use namespace HH\Lib\Regex;

final class HackBuilderTest extends CodegenBaseTest {

  private function getHackBuilder(): HackBuilder {
    return $this->getCodegenFactory()->codegenHackBuilder();
  }

  public function testIfBlock(): void {
    $body = $this
      ->getHackBuilder()
      ->startIfBlockf('$value <= %d', 0)
      ->addLine('return 0;')
      ->addElseIfBlockf('$value === %d', 1)
      ->addLine('return 1;')
      ->addElseBlock()
      ->addLine('return 2;')
      ->endIfBlock();
    expect_with_context(static::class, $body->getCode())->toBeUnchanged();
  }

  public function testForeachLoop(): void {
    $body = $this
      ->getHackBuilder()
      ->startForeachLoop('$values', null, '$value')
      ->addLine('something($value);')
      ->endForeachLoop()
      ->startForeachLoop('$values', '$idx', '$value')
      ->addLine('$values[$idx] = $value + 1;')
      ->endForeachLoop();
    expect_with_context(static::class, $body->getCode())->toBeUnchanged();
  }

  public function testTryBLock(): void {
    $body = $this
      ->getHackBuilder()
      ->startTryBlock()
      ->addLine('my_func();')
      ->addCatchBlock('SystemException', '$ex')
      ->addLine('return null;')
      ->addFinallyBlock()
      ->addLine('bump_ods();')
      ->endTryBlock();
    expect_with_context(static::class, $body->getCode())->toBeUnchanged();
  }

  public function testDocBlock(): void {
    $comment = 'Wow a really long comment that '.
      'will span multiple lines and probably go over '.
      'the limit so we gotta cut it up.';
    $body = $this->getHackBuilder()->addDocBlock($comment);
    expect_with_context(static::class, $body->getCode())->toBeUnchanged();

    $body = $this->getHackBuilder()->addDocBlock($comment, /* max len */ 50);
    expect_with_context(static::class, $body->getCode())->toBeUnchanged(
      'docblock2',
    );
  }

  public function testAsValue(): void {
    $dict = $this->getHackBuilder()
      ->addValue(
        dict[
          'foo' => 'bar',
        ],
        HackBuilderValues::dict(
          HackBuilderKeys::export(),
          HackBuilderValues::literal(),
        ),
      )
      ->getCode();
    expect_with_context(static::class, $dict)->toBeUnchanged();
  }

  public function testRegex(): void {
    $make_code = (Regex\Pattern<Regex\Match> $re) ==> $this->getHackBuilder()
      ->addValue($re, HackBuilderValues::regex())
      ->getCode();
    expect($make_code(re"/foo/"))->toBeSame('re"/foo/"');
    expect($make_code(re"/\$foo/"))->toBeSame('re"/\$foo/"');
    expect($make_code(re"/a\"b/"))->toBeSame('re"/a\"b/"');
    expect($make_code(re"/a?b/"))->toBeSame('re"/a?b/"');
  }

  public function testShapeWithUniformRendering(): void {
    $shape = $this
      ->getHackBuilder()
      ->addValue(
        shape('x' => 3, 'y' => 5, 'url' => 'www.facebook.com'),
        HackBuilderValues::shapeWithUniformRendering(
          HackBuilderValues::export(),
        ),
      );

    expect_with_context(static::class, $shape->getCode())->toBeUnchanged();
  }

  public function testShapeWithPerKeyRendering(): void {
    $shape = $this
      ->getHackBuilder()
      ->addValue(
        shape('herp' => 'derp', 'foo' => Vector {'foo', 'bar', 'baz'}),
        HackBuilderValues::shapeWithPerKeyRendering(
          shape(
            'herp' => HackBuilderValues::export(),
            'foo' => HackBuilderValues::vector(HackBuilderValues::export()),
          ),
        ),
      );

    expect_with_context(static::class, $shape->getCode())->toBeUnchanged();
  }

  public function testWrappedStringSingle(): void {
    expect_with_context(
      static::class,
      $this
        ->getHackBuilder()
        ->add('return ')
        ->addWrappedString('This is short')
        ->add(';')
        ->getCode(),
    )->toBeUnchanged();
  }

  public function testWrappedStringDouble(): void {
    expect_with_context(
      static::class,
      $this
        ->getHackBuilder()
        ->add('return ')
        ->addWrappedString(
          'This is a bit longer so we will hit our max '.
          'length cap and then go ahead and finish the line.',
        )
        ->add(';')
        ->getCode(),
    )->toBeUnchanged();
  }

  public function testWrappedStringMulti(): void {
    $lorem_ipsum = 'So here is a super long string that will wrap past the
two line breaks. Also note that we include a newline and also '.
      'do a concat operation to really mix it up. We need to
      respect newlines with this code and also senseless indentation.';
    expect_with_context(
      static::class,
      $this
        ->getHackBuilder()
        ->add('return ')
        ->addWrappedString($lorem_ipsum)
        ->add(';')
        ->getCode(),
    )->toBeUnchanged();
  }

  public function testVerbatimString(): void {
    expect_with_context(
      static::class,
      $this
        ->getHackBuilder()
        ->addLine('function foo(): void {')
        ->indent()
        ->add('$x = ')
        ->addVerbatim("<<<EOF\nfoo bar\nEOF;")
        ->ensureNewLine()
        ->addLine('return $x;')
        ->unindent()
        ->addLine('}')
        ->getCode(),
    )->toBeUnchanged();
  }

  public function testWrappedStringDoNotIndent(): void {
    expect_with_context(
      static::class,
      $this
        ->getHackBuilder()
        ->add('$this->callMethod(')
        ->newLine()
        ->indent()
        ->addWrappedStringNoIndent(
          'This string is quite long so will spread across three lines but '.
          'the user wants it to look like just in this piece of code (second '.
          'line indentation matches the first one)',
          null,
        )
        ->unindent()
        ->newLine()
        ->add(');')
        ->getCode(),
    )->toBeUnchanged();
  }

  public function testSet(): void {
    $set = $this
      ->getHackBuilder()
      ->addValue(
        Set {'apple', 'oreos', 'banana'},
        HackBuilderValues::set(HackBuilderValues::export()),
      );

    expect_with_context(static::class, $set->getCode())->toBeUnchanged();
  }

  public function testAddWithSuggestedLineBreaksNoBreakage(): void {
    $del = HackBuilder::DELIMITER;
    $body = $this
      ->getHackBuilder()
      ->addWithSuggestedLineBreaks(
        'final class'.
        $del.
        'ClassNameJustLongEnoughToAvoidEightyColumns'.
        $del.
        'extends SomeBaseClass',
      );
    expect_with_context(static::class, $body->getCode())->toBeUnchanged();
  }

  public function testAddWithSuggestedLineBreaksWithBreakage(): void {
    $del = HackBuilder::DELIMITER;
    $body = $this
      ->getHackBuilder()
      ->addWithSuggestedLineBreaks(
        'final abstract class'.
        $del.
        'ImpossibleClassLongEnoughToCrossEightyColumns'.
        $del.
        'extends SomeBaseClass',
      );
    expect_with_context(static::class, $body->getCode())->toBeUnchanged();
  }

  public function testAddfWithSuggestedLineBreaks(): void {
    $code = $this
      ->getHackBuilder()
      ->addWithSuggestedLineBreaksf("%s\n%s", 'foo', 'bar')
      ->getCode();
    expect($code)->toBeSame("foo\nbar");
  }

  public function testAddSmartMultilineCall(): void {
    $del = HackBuilder::DELIMITER;
    $body = $this
      ->getHackBuilder()
      ->addMultilineCall(
        "\$foobarbaz_alphabetagama =".
        $del.
        "\$this->callSomeThingReallyLongName".
        'ReallyReallyLongName',
        Vector {
          '$someSmallParameter',
          "\$foobarbaz_alphabetagama +".
          $del.
          "\$foobarbaz_alphabetagamaa +".
          $del.
          "\$foobarbaz_alphabetagamatheta_foobarbaz",
        },
      );
    expect_with_context(static::class, $body->getCode())->toBeUnchanged();
  }

  public function testLiteralMap(): void {
    $body = $this
      ->getHackBuilder()
      ->addValue(
        Map {
          'MY_ENUM::A' => 'ANOTHER_ENUM::A',
          'MY_ENUM::B' => 'ANOTHER_ENUM::B',
        },
        HackBuilderValues::map(
          HackBuilderKeys::literal(),
          HackBuilderValues::literal(),
        ),
      );
    expect_with_context(static::class, $body->getCode())->toBeUnchanged();
  }

  public function testAnotherConfig(): void {
    $body = (new HackBuilder(new TestAnotherCodegenConfig()))
      ->addInlineComment(
        'Here we wrap at 40 chars because we use a different configuration.',
      )
      ->startIfBlock('$do_that')
      ->add('return ')
      ->addValue(
        vec[1, 2, 3],
        HackBuilderValues::valueArray(HackBuilderValues::export()),
      )
      ->closeStatement()
      ->endIfBlock();

    expect_with_context(static::class, $body->getCode())->toBeUnchanged();
  }

  public function testSwitchBodyWithReturnsInCaseAndDefault(): void {
    // Gosh, I have no idea what names of football shots are!
    $players = Vector {
      dict['name' => 'Ronaldo', 'favorite_shot' => 'freeKick'],
      dict['name' => 'Messi', 'favorite_shot' => 'slideKick'],
      dict['name' => 'Maradona', 'favorite_shot' => 'handOfGod'],
    };

    $body = $this
      ->getHackBuilder()
      ->startSwitch('$soccer_player')
      ->addCaseBlocks(
        $players,
        ($player, $body) ==> {
          $body
            ->addCase($player['name'], HackBuilderValues::export())
            ->addLinef('$shot = new Shot(\'%s\');', $player['favorite_shot'])
            ->returnCasef('$shot->execute()');
        },
      )
      ->addDefault()
      ->addLine('invariant_violation(\'ball deflated!\');')
      ->endDefault()
      ->endSwitch();
    expect_with_context(static::class, $body->getCode())->toBeUnchanged();
  }

  public function testSwitchBodyWithBreaksInCaseAndDefault(): void {
    // Gosh, I have no idea what names of football shots are!
    $players = Vector {
      dict['name' => 'Ronaldo', 'favorite_shot' => 'freeKick'],
      dict['name' => 'Messi', 'favorite_shot' => 'slideKick'],
      dict['name' => 'Maradona', 'favorite_shot' => 'handOfGod'],
    };

    $body = $this
      ->getHackBuilder()
      ->startSwitch('$soccer_player')
      ->addCaseBlocks(
        $players,
        ($player, $body) ==> {
          $body
            ->addCase($player['name'], HackBuilderValues::export())
            ->addLinef('$shot = new Shot(\'%s\');', $player['favorite_shot'])
            ->breakCase();
        },
      )
      ->addDefault()
      ->addLine('invariant_violation(\'ball deflated!\');')
      ->endDefault()
      ->endSwitch();
    expect_with_context(static::class, $body->getCode())->toBeUnchanged();
  }

  public function testSwitchBodyWithMultipleCasesWithoutBreaks(): void {
    // Gosh, I have no idea what names of football shots are!
    $players = Vector {
      dict['name' => 'Ronaldo', 'favorite_shot' => 'freeKick'],
      dict['name' => 'Messi', 'favorite_shot' => 'slideKick'],
      dict['name' => 'Maradona', 'favorite_shot' => 'handOfGod'],
    };

    $body = $this
      ->getHackBuilder()
      ->startSwitch('$soccer_player')
      ->addCaseBlocks(
        $players,
        ($player, $body) ==> {
          $body
            ->addCase($player['name'], HackBuilderValues::export())
            ->addLinef('$shot = new Shot(\'%s\');', $player['favorite_shot'])
            ->unindent();
        },
      )
      ->addDefault()
      ->addLine('invariant_violation(\'ball deflated!\');')
      ->endDefault()
      ->endSwitch();
    expect_with_context(static::class, $body->getCode())->toBeUnchanged();
  }

  public function testExportedVectorDoesNotHaveHHPrefix(): void {
    $body = $this
      ->getHackBuilder()
      ->add('$foo = ')
      ->addValue(
        Vector {1, 2, 3},
        HackBuilderValues::vector(HackBuilderValues::export()),
      )
      ->getCode();
    expect($body)->toContainSubstring('Vector');
    expect($body)->toNotContainSubstring('HH');
    expect_with_context(static::class, $body)->toBeUnchanged();
  }

  public function testVectorOfExportedVectors(): void {
    $body = $this
      ->getHackBuilder()
      ->addAssignment(
        '$foo',
        Vector {Vector {'$foo', '$bar'}, Vector {'$herp', '$derp'}},
        HackBuilderValues::vector(
          HackBuilderValues::vector(HackBuilderValues::export()),
        ),
      );
    expect_with_context(static::class, $body->getCode())->toBeUnchanged();
  }

  public function testVectorOfLiteralVectors(): void {
    $body = $this
      ->getHackBuilder()
      ->addAssignment(
        '$foo',
        Vector {Vector {'$foo', '$bar'}, Vector {'$herp', '$derp'}},
        HackBuilderValues::vector(
          HackBuilderValues::vector(HackBuilderValues::literal()),
        ),
      );
    expect_with_context(static::class, $body->getCode())->toBeUnchanged();
  }

  public function testVectorOfMaps(): void {
    $body = $this
      ->getHackBuilder()
      ->addAssignment(
        '$foo',
        Vector {Map {'foo' => 'bar'}, Map {'herp' => 'derp'}},
        HackBuilderValues::vector(
          HackBuilderValues::map(
            HackBuilderKeys::export(),
            HackBuilderValues::export(),
          ),
        ),
      );
    expect_with_context(static::class, $body->getCode())->toBeUnchanged();
  }

  public function testImmVectorOfImmVectors(): void {
    $body = $this
      ->getHackBuilder()
      ->addAssignment(
        '$foo',
        ImmVector {ImmVector {'abc', 'def'}, ImmVector {'ghi', 'jkl'}},
        HackBuilderValues::immVector(
          HackBuilderValues::immVector(HackBuilderValues::export()),
        ),
      );
    expect_with_context(static::class, $body->getCode())->toBeUnchanged();
  }

  public function testImmMapOfImmMaps(): void {
    $body = $this
      ->getHackBuilder()
      ->addAssignment(
        '$foo',
        ImmMap {
          'foo' => ImmMap {'a' => 12, 'b' => 34},
          'bar' => ImmMap {'c' => 45},
        },
        HackBuilderValues::immMap(
          HackBuilderKeys::export(),
          HackBuilderValues::immMap(
            HackBuilderKeys::export(),
            HackBuilderValues::export(),
          ),
        ),
      );
    expect_with_context(static::class, $body->getCode())->toBeUnchanged();
  }

  public function testImmSet(): void {
    $body = $this
      ->getHackBuilder()
      ->addAssignment(
        '$foo',
        ImmSet {'abc', 'def'},
        HackBuilderValues::immSet(HackBuilderValues::export()),
      );
    expect_with_context(static::class, $body->getCode())->toBeUnchanged();
  }

  public function testVec(): void {
    $body = $this
      ->getHackBuilder()
      ->addAssignment(
        '$foo',
        vec['foo', 'bar'],
        HackBuilderValues::vec(HackBuilderValues::export()),
      );
    expect_with_context(static::class, $body->getCode())->toBeUnchanged();
  }

  public function testKeyset(): void {
    $body = $this
      ->getHackBuilder()
      ->addAssignment(
        '$foo',
        keyset['foo', 'bar'],
        HackBuilderValues::keyset(HackBuilderValues::export()),
      );
    expect_with_context(static::class, $body->getCode())->toBeUnchanged();
  }

  public function testDict(): void {
    $body = $this
      ->getHackBuilder()
      ->addAssignment(
        '$foo',
        dict['foo' => 1, 'bar' => 2],
        HackBuilderValues::dict(
          HackBuilderKeys::export(),
          HackBuilderValues::export(),
        ),
      );
    expect_with_context(static::class, $body->getCode())->toBeUnchanged();
  }

  public function testClassnameMap(): void {
    $body = $this
      ->getHackBuilder()
      ->addValue(
        Map {self::class => \stdClass::class},
        HackBuilderValues::map(
          HackBuilderKeys::classname(),
          HackBuilderValues::classname(),
        ),
      );
    expect_with_context(static::class, $body->getCode())->toBeUnchanged();
  }

  public function testLambdaMap(): void {
    $body = $this
      ->getHackBuilder()
      ->addValue(
        Map {'foo' => 'bar'},
        HackBuilderValues::map(
          HackBuilderKeys::lambda(($_config, $v) ==> "'key:".$v."'"),
          HackBuilderValues::lambda(($_config, $v) ==> "'value:".$v."'"),
        ),
      );
    expect_with_context(static::class, $body->getCode())->toBeUnchanged();
  }
}

final class TestAnotherCodegenConfig implements IHackCodegenConfig {
  public function getFileHeader(): ?Vector<string> {
    return Vector {'Codegen Tests'};
  }

  public function getSpacesPerIndentation(): int {
    return 4;
  }

  public function getMaxLineLength(): int {
    return 40;
  }

  public function shouldUseTabs(): bool {
    return false;
  }

  public function getRootDir(): string {
    return '/';
  }

  public function getFormatter(): ?ICodegenFormatter {
    return null;
  }
}
