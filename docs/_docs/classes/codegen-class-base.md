---
docid: classes-codegen-class-base
title: CodegenClassBase
layout: docs
permalink: /docs/classes/CodegenClassBase/
---

`CodegenClassBase` is not used directly, however it defines the common
functionality for 'class-like' things, such as classes, interfaces, traits, and
enums.

Methods are added by calling `->addMethod($codegen_method)`.

Interfaces and Traits
---------------------

A `CodegenClass` can be marked as implementing an interface by passing
a `CodegenImplementsInterface` to `->addInterface()`.

`CodegenImplementsInterface` instances are created by calling
`$factory->codegenImplementsInterface('IFoo')`; optionally, a comment can be
added by calling `->setComment($comment)` or `->setCommentf($format, ...)`, and code can be marked as responsible
for them by calling `->setGeneratedFrom($codegen_generated_from)`.

`->addTrait()` works in combination with `CodegenUsesTrait` via
`$factory->codegenUsesTrait()`, and also provides `setComment()` and
`setGeneratedFrom()` methods.

Generics
--------

This API is likely to be changed in a future release of Hack Codegen.

A class can be marked as generic by calling `addGenerics()`, which takes a `Traversable`. Alternatively, you can separately call `addGeneric()` or `addGenericf()` for each generic.

Constants
---------

These APIS are likely to be changed in a future release of Hack Codegen.

 - `->addConst($type, $name, $comment = null)`: add a simple constant
 - `->addAbstractConst($name, $comment = null)`: add an abstract constant - types
   can be specified by prepending them to the name
 - `->addTypeConst($name, $type, ?$comment = null)`: add a type  constant
 - `->addAbstractTypeConst($name, $type, $comment = null)`: adds an abstract
   type constant, constrainted to `$type`

Manual Sections
---------------

 - `->setHasManualDeclarations($enabled = true, $key = null, $contents = null)`:
   creates a manual section at the top of the class for declarations; you can
   optionally specify a custom key for the section, or default contents
 - `->setHasManualMethod($enabled = true, $key = null, $contents = null)`: creates
   a manual section at the bottom of the class for any additional methods to be
   added by hand
