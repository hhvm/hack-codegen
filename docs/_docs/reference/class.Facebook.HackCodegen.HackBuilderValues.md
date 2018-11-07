---
layout: docs
title: Facebook\HackCodegen\HackBuilderValues
id: class.Facebook.HackCodegen.HackBuilderValues
docid: class.Facebook.HackCodegen.HackBuilderValues
permalink: /docs/reference/class.Facebook.HackCodegen.HackBuilderValues/
---
# Facebook\\HackCodegen\\HackBuilderValues




Factory class for creating ` IHackBuilderValueRenderer ` instances




## Interface Synopsis




``` Hack
namespace Facebook\HackCodegen;

abstract final class HackBuilderValues {...}
```




### Public Methods




- [` ::classname(): \IHackBuilderValueRenderer<string> `](<class.Facebook.HackCodegen.HackBuilderValues.classname.md>)\
  Assume the value is a classname, and render a `` ::class `` constant
- [` ::codegen(): \IHackBuilderValueRenderer<ICodeBuilderRenderer> `](<class.Facebook.HackCodegen.HackBuilderValues.codegen.md>)\
  Render a Codegen* value to code, and use it as the value
- [` ::dict<\Tk as arraykey, \Tv>(\ IHackBuilderKeyRenderer<\Tk> $kr, \ IHackBuilderValueRenderer<\Tv> $vr): \IHackBuilderValueRenderer<dict<\Tk, \Tv>> `](<class.Facebook.HackCodegen.HackBuilderValues.dict.md>)\
  Render a [` dict `](<class.Facebook.HackCodegen.HackBuilderValues.dict.md>) literal
- [` ::export(): \IHackBuilderValueRenderer<\mixed> `](<class.Facebook.HackCodegen.HackBuilderValues.export.md>)\
  Render the value as Hack code that produces the same value
- [` ::immMap<\Tk as arraykey, \Tv>(\ IHackBuilderKeyRenderer<\Tk> $kr, \ IHackBuilderValueRenderer<\Tv> $vr): \IHackBuilderValueRenderer<ImmMap<\Tk, \Tv>> `](<class.Facebook.HackCodegen.HackBuilderValues.immMap.md>)\
  Render an `` ImmMap `` literal
- [` ::immSet<\Tv>(\ IHackBuilderValueRenderer<\Tv> $vr): \IHackBuilderValueRenderer<ImmSet<\Tv>> `](<class.Facebook.HackCodegen.HackBuilderValues.immSet.md>)\
  Render an `` ImmSet `` literal
- [` ::immVector<\Tv>(\ IHackBuilderValueRenderer<\Tv> $vr): \IHackBuilderValueRenderer<ImmVector<\Tv>> `](<class.Facebook.HackCodegen.HackBuilderValues.immVector.md>)\
  Render an `` ImmVector `` literal
- [` ::keyValueArray<\Tk as arraykey, \Tv>(\ IHackBuilderKeyRenderer<\Tk> $kr, \ IHackBuilderValueRenderer<\Tv> $vr): \IHackBuilderValueRenderer<\array<\Tk, \Tv>> `](<class.Facebook.HackCodegen.HackBuilderValues.keyValueArray.md>)\
  Render a [` dict `](<class.Facebook.HackCodegen.HackBuilderValues.dict.md>)-like PHP array literal
- [` ::keyset<\Tv as arraykey>(\ IHackBuilderValueRenderer<\Tv> $vr): \IHackBuilderValueRenderer<keyset<\Tv>> `](<class.Facebook.HackCodegen.HackBuilderValues.keyset.md>)\
  Render a [` keyset `](<class.Facebook.HackCodegen.HackBuilderValues.keyset.md>) literal
- [` ::lambda<\T>(\callable $render): \IHackBuilderValueRenderer<\T> `](<class.Facebook.HackCodegen.HackBuilderValues.lambda.md>)\
  The value will be rendered with the specified lambda
- [` ::literal(): \IHackBuilderValueRenderer<string> `](<class.Facebook.HackCodegen.HackBuilderValues.literal.md>)\
  Render the value with no changes or escaping
- [` ::map<\Tk as arraykey, \Tv>(\ IHackBuilderKeyRenderer<\Tk> $kr, \ IHackBuilderValueRenderer<\Tv> $vr): \IHackBuilderValueRenderer<Map<\Tk, \Tv>> `](<class.Facebook.HackCodegen.HackBuilderValues.map.md>)\
  Render a `` Map `` literal
- [` ::regex<\T>(): \IHackBuilderValueRenderer<\\HH\Lib\Regex\Pattern<\T>> `](<class.Facebook.HackCodegen.HackBuilderValues.regex.md>)\
  Render a regex literal, e
- [` ::set<\Tv>(\ IHackBuilderValueRenderer<\Tv> $vr): \IHackBuilderValueRenderer<Set<\Tv>> `](<class.Facebook.HackCodegen.HackBuilderValues.set.md>)\
  Render a `` Set `` literal
- [` ::shapeWithPerKeyRendering(shape( ) $value_renderers): \IHackBuilderValueRenderer<shape( )> `](<class.Facebook.HackCodegen.HackBuilderValues.shapeWithPerKeyRendering.md>)\
  Render a `` shape() `` literal with a different renderer for each shape key
- [` ::shapeWithUniformRendering<\Tv>(\ IHackBuilderValueRenderer<\Tv> $vr): \IHackBuilderValueRenderer<shape( )> `](<class.Facebook.HackCodegen.HackBuilderValues.shapeWithUniformRendering.md>)\
  Render a `` shape() `` literal where all values are rendered the same way
- [` ::valueArray<\Tv>(\ IHackBuilderValueRenderer<\Tv> $vr): \IHackBuilderValueRenderer<\array<\Tv>> `](<class.Facebook.HackCodegen.HackBuilderValues.valueArray.md>)\
  Render a [` vec `](<class.Facebook.HackCodegen.HackBuilderValues.vec.md>)-like PHP array literal
- [` ::vec<\Tv>(\ IHackBuilderValueRenderer<\Tv> $vr): \IHackBuilderValueRenderer<vec<\Tv>> `](<class.Facebook.HackCodegen.HackBuilderValues.vec.md>)\
  Render a [` vec `](<class.Facebook.HackCodegen.HackBuilderValues.vec.md>) literal
- [` ::vector<\Tv>(\ IHackBuilderValueRenderer<\Tv> $vr): \IHackBuilderValueRenderer<Vector<\Tv>> `](<class.Facebook.HackCodegen.HackBuilderValues.vector.md>)\
  Render a `` Vector `` literal