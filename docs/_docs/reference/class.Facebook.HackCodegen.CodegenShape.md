---
layout: docs
title: Facebook\HackCodegen\CodegenShape
id: class.Facebook.HackCodegen.CodegenShape
docid: class.Facebook.HackCodegen.CodegenShape
permalink: /docs/reference/class.Facebook.HackCodegen.CodegenShape/
---
# Facebook\\HackCodegen\\CodegenShape




Generate code for a shape




Please don't use this class directly; instead use
the function codegenShape.  E.g.:




```
codegenShape(
  new CodegenShapeMember('x', 'int'),
  (new CodegenShapeMember('y', 'int'))->setIsOptional(),
)
```




## Interface Synopsis




``` Hack
namespace Facebook\HackCodegen;

final class CodegenShape implements ICodeBuilderRenderer {...}
```




### Public Methods




- [` ->__construct(IHackCodegenConfig $config, vec<CodegenShapeMember> $members) `](<class.Facebook.HackCodegen.CodegenShape.__construct.md>)
- [` ->allowsSubtyping(): bool `](<class.Facebook.HackCodegen.CodegenShape.allowsSubtyping.md>)
- [` ->appendToBuilder(HackBuilder $builder): HackBuilder `](<class.Facebook.HackCodegen.CodegenShape.appendToBuilder.md>)
- [` ->setAllowsSubtyping(bool $value): \this `](<class.Facebook.HackCodegen.CodegenShape.setAllowsSubtyping.md>)
- [` ->setManualAttrsID(?string $id = NULL): \this `](<class.Facebook.HackCodegen.CodegenShape.setManualAttrsID.md>)