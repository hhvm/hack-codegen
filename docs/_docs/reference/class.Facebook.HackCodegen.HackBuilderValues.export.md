---
layout: docs
title: export
id: class.Facebook.HackCodegen.HackBuilderValues.export
docid: class.Facebook.HackCodegen.HackBuilderValues.export
permalink: /docs/reference/class.Facebook.HackCodegen.HackBuilderValues.export/
---
# Facebook\\HackCodegen\\HackBuilderValues::export()




Render the value as Hack code that produces the same value




``` Hack
public static function export(): IHackBuilderValueRenderer<mixed>;
```




For example, an ` int ` will be rendered without changes but a `` string ``
will be rendered with quotes.




## Returns




+ ` IHackBuilderValueRenderer<mixed> `