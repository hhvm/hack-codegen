---
docid: overview-config-and-factory
title: Config and Factory
layout: docs
permalink: /docs/overview/config-and-factory/
---

[`IHackCodegenConfig`](https://github.com/hhvm/hack-codegen/blob/master/src/IHackCodegenConfig.php)
defines several methods for project-specification, such as indentation preferences, and where
any paths generated should be relative to.

`HackCodegenConfig` is a concrete implementation, which takes the project root directory as a
parameter; [`HackCodegenFactory`](https://github.com/hhvm/hack-codegen/blob/master/src/HackCodegenFactory.php)
is a convenience class to instantiate the codegen builders without having to explicitly pass
configuration into each of them; for example:

``` php
<?hh

use Facebook\HackCodegen\{
  CodegenClass
  HackCodegenConfig,
  HackCodegenFactory
};

$config = new HackCodegenConfig(realpath(__DIR__.'/../'));

$class_a = new CodegenClass($config, 'ClassA');
$class_b = new CodegenClass($config, 'ClassB');

$cg = new HackCodegenFactory($config);

$class_c = $cg->codegenClass('ClassC');
$class_d = $cg->codegenClass('ClassD');
```

`HackCodegenFactory` is the recommended way to use Hack Codegen.
