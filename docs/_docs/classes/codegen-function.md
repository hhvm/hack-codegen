---
docid: classes-codegen-function
title: CodegenFunction
layout: docs
permalink: /docs/classes/CodegenFunction/
---

`CodegenFunctionBase` provides shared functionality for methods and functions;
`CodegenFunction` does not add to it. The recommended way to get an instance of
`CodegenFunction` is to call `->codegenFunction('function_name()')` on an instance of
`IHackCodegenFactory`.

Basics
------

 - `->setReturnType(string $type)`: set the return type of the function if
   generating Hack code
 - `->setIsAsync(bool)`: if the function should use the `async` keyword or not. This
   doesn't affect the return type - include `Awaitable<>` in `setReturnType()` if
   appropriate
 - `->addParameter(string $param)`: add a parameter to the signature. `$param` should
   include both the type and variable name - you can use
   `->addParameterf($format, ...)` for complicated cases
 - `->setBody(string $code)`: set the body (implementation) of the function. You
   should generally use `HackBuilder` to create the string
 - `->setManualBody(bool $manual = true)`: if true, make the entire function body a
   manual section. If called, `->setBody()` is still used for the default value when
   generating a new file.

Attributes
----------

To make a function memoized (adding the `<<__Memoize>>` attribute), call
`->setIsMemoized(bool $value = true)`; for other attributes, call
`->addUserAttribute(string $name, ?string $value)`.

FIXMEs
------

In some cases, you need to add an `HH_FIXME` or similar to the method declaration -
for example, variadics are not support in Hack before HHVM 3.15, so a fixme is
needed for all variadic functions.

Call `->addHHFixMe(int $code, string $why)` to add `/* HH_FIXME[$code] $why */` to
the function declaration.

Metadata
--------

`CodegenFunctionBase` supports `->setDocBlock(string)` and
`->setGeneratedFrom(CodegenGeneratedFrom)` in the same way as `CodegenFile`.
