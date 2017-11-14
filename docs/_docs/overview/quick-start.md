---
docid: overview-quick-start
title: Quick Start
layout: docs
permalink: /docs/overview/quick-start/
---

## Installation

Hack Codegen is installed with [with Composer](https://packagist.org/):

```
$ composer require facebook/hack-codegen
```

## Commit Your Codegen Output

In other languages, it's usually best practice not to commit generated
artifacts; this isn't the case for generated Hack code, as not commiting the
output creates a circular dependency:

 - If the Hack typechecker reports any errors, HHVM raises a fatal error when
   any Hack code is executed
 - References to undefined classes, functions, types, etc are a typechecker
   error
 - Any references to your generated code will be undefined references on a
   fresh checkout
 - As Hack Codegen itself is written in Hack, a fatal error will be raised by
   HHVM when you try to build your code.

Additionally, this is required to support partially-generated files.

## A Simple Example

``` php
<?hh
require_once('vendor/autoload.php');

use Facebook\HackCodegen\{
  CodegenFileType,
  HackCodegenConfig,
  HackCodegenFactory,
  HackBuilderValues
};

function make_script(): void {
  $cg = new HackCodegenFactory(
    new HackCodegenConfig(__DIR__),
  );

  $cg->codegenFile('script.php')
    ->setFileType(CodegenFileType::HACK_PARTIAL)
    ->setShebangLine('#!/usr/bin/env hhvm')
    ->setPseudoMainHeader(
      'require_once("vendor/autoload.php");',
    )
    ->addFunction(
      $cg->codegenFunction('main')
        ->setReturnType('void')
        ->setBody(
          $cg->codegenHackBuilder()
            ->startManualSection('greeting')
            ->addAssignment(
              '$greeting',
              "Hello, world!",
              HackBuilderValues::export(),
            )
            ->endManualSection()
            ->addLine(
              'printf("%s\n", $greeting);'
            )
            ->getCode(),
        ),
    )
    ->setPseudoMainFooter(
      'main();',
    )
    ->save();
}

make_script();
```

This generates:

``` php
#!/usr/bin/env hhvm
<?hh
/**
 * This file is partially generated. Only make modifications between BEGIN
 * MANUAL SECTION and END MANUAL SECTION designators.
 *
 * @partially-generated SignedSource<<484789e339a716fbb2ed2385d32b6b75>>
 */
require_once("vendor/autoload.php");

function main(): void {
  /* BEGIN MANUAL SECTION greeting */
  $greeting = 'Hello, world!';
  /* END MANUAL SECTION */
  printf("%s\n", $greeting);
}

main();
```

Changes can be made between the `MANUAL SECTION` markers, and will be preserved
regardless of any codegen changes to the rest of the file.
