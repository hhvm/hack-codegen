---
docid: overview-partially-generated
title: Partially Generated Files
layout: docs
permalink: /docs/overview/partially-generated-files/
---

Partially-generated files are a major feature of Hack Codegen, allowing marked
manual sections to be edited, while retaining the ability to use Hack codegen
to rewrite the remainder of the file.

A common question is why this should be used instead of generating abstract
parent classes or traits; while that is still an option, partially-generated
files can have major benefits:

 - simplified class/trait hierarchy
 - "fill in the blanks" is simpler than "extend this class"
 - the generated code can be used immediately, and modified as needed; this is
   especially useful when the majority of implementations will not need any
   modifications, or when generating both a class and a unit test for that class
   at the same time
 - manual sections can be used outside of classes

There are some cases where it's not possible to use techniques like superclasses and
traits, such as when generating scripts:

``` php
#!/usr/bin/env hhvm
<?hh
/**
 * This file is partially generated. Only make modifications between BEGIN
 * MANUAL SECTION and END MANUAL SECTION designators.
 */

/* BEGIN MANUAL SECTION init */
// We can't use code from a class or function as we don't have an autoloader yet
require_once(__DIR__.'/../../../vendor/autoload.php');
/* END MANUAL SECTION */
```

There alternative approaches, however they generall require putting code into
a string, which is usually less preferable than having it as real code.

One of the reasons that partially-generated files are generally discouraged with
other frameworks is that major upgrades often require re-creating the files
completely, then manually re-doing edits; This isn't the case with Hack Codegen:
if a new version of a file contains a manual section with the same 'key' as an
old version, the old manual section is copiedi into the new manual section.

Creating Manual Sections
------------------------

The most common way to create a manual section is to mark an entire function or method
body as a manual section - this is done by calling `->setManualBody(true)` on the
function or method builder:

``` php
<?hh

$class = $factory
  ->codegenClass('SomeClass')
  ->addMethod(
    $factory
      ->codegenMethod('someMethod')
      ->setManualBody(true)
      ->setBody('myDefaultImplementation();');
  );
```

Manual sections created this way are automatically keyed with the class and method
name - in this case, `/* BEGIN MANUAL SECTION SomeClass::someMethod */`.

Manual sections can also be created in the middle of a function body or
in pseudo-main code when using `HackBuilder`:

``` php
<?hh

$file = $factory
  ->codegenFile('somefile.php')
  ->setPseudomainHeader(
    $factory
      ->codegenHackBuilder()
      ->startManualSection('mykey')
      ->addLine('require_once(\'vendor/autoload.php\');')
      ->endManualSection()
      ->getCode()
  );
```

Keys & Signatures
-----------------

Keys must be unique within a file; they are used by Hack Codegen to put the code
they contain in the appropriate place when a file is rewritten; this can include
re-ordering them, adding more, or removing existing ones that are no longer needed.

Files created by Hack Codegen are signed by default; if a file is modified, the
signature will not match, and Hack Codegen will refuse to modify the file - however,
manual sections are excluded from this calculation.
