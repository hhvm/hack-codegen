---
docid: classes-codegen-file
title: CodegenFile
layout: docs
permalink: /docs/classes/CodegenFile/
---

A `CodegenFile` represents a generated file, and implements writing the signed
file to disk.

Basic Usage
-----------

The recommended way to get an instance of `CodegenFile` is to call
`->codegenFile('/path/to/foo.php')` on an instance of `IHackCodegenFactory`. After
this, definitions are added to the file with:

 - `->addClass(CodegenClassBase $class_or_interface)`
 - `->addTrait(CodegenTrait $trait)`
 - `->addFunction(CodegenFunction $function)`
 - `->addBeforeType(CodegenType $type)`: added before functions, classes, and traits
 - `->addAfterType(CodegenType $type)`: added at the end of the file

Finally, call `->save()` to write to file.

Namespaces
----------

You can set the namespace of the file by calling `->setNamespace($ns)`; you can
`use` declarations from other namespaces by calling:

 - `->useNamespace(string $ns, ?string $as = null)`
 - `->useType(string $ns, ?string $as = null)`
 - `->useFunction(string $ns, ?string $as = null)`
 - `->useConst(string $ns, ?string $as = null)`

`useType` is for any kind of type; this includes `type`s, `newtype`s,
enums, classes, and interfaces.

File Types
----------

By default, CodegenFile will create a Hack-partial file (`<?hh`); other formats
can be specified by calling `->setFileType($type)`; valid types are:

 - `CodegenFileType::PHP`
 - `CodegenFileType::HACK_DECL`
 - `CodegenFileType::HACK_PARTIAL`
 - `CodegenFileTYpe::HACK_STRICT`

Generating Scripts
------------------

Scripts can not be Hack strict, as scripts require top-level ('pseudo-main') code -
`->setFileType(CodegenFileType::HACK_PARTIAL)` is the recommended approach here.

 - `->setShebangLine(string $line)`: set the first line of the file, in Unix
   'shebang' format - eg `->setShebangLine('#!/usr/bin/env hhvm')`
 - `->setPseudoMainHeader(string $code)`: add raw code before all definitions. Using
   `HackBuilder` is recommended.
 - `->setPseudoMainFooter(string $code)`: add raw code at the bottom of the file.
   Using `HackBuilder` is recommended.

Metadata
--------

A docblock can added to the top of the file with `->setDocBlock(string)`; also,
`CodegenFile` has built-in support for adding documentation on how to re-generate
the file via `->setGeneratedFrom(CodegenGeneratedFrom)`. `IHackCodegenFactory`
includes several helpers for this:

 - `->codegenGeneratedFromScript(?string $script = null)`: adds a comment saying to
   re-run `$script` to re-generate the file. If `$script` is `null`, it is inferred
   from the environment and converted to a path relative to the root directory
   provided by `IHackCodegenConfig`
 - `->codegenGeneratedFromClass(string $class)`: similar, but reports the responsible
   class instead of script
 - `->codegenGeneratedFromMethod(string $class, string $method)`: as above, but also
   includes the method
 - `->codegenGeneratedFromMethodWithKey(string $class, string $method, string $key)`:
   also includes a custom key - usually a parameter to the method.

Signatures
----------

By default, CodegenFile will:

 - validate signature and error out if the signature is invalid or missing
 - create or modify the file
 - write a signature into the modified file

These can be overridden by:

 - `->doClobber(true)`: ignore signature errors
 - `->createOnly()`: only create files - don't modify existing files
 - `->setIsSignedFile(false)`: don't sign the output
