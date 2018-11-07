---
layout: docs
title: export
id: class.Facebook.HackCodegen.HackBuilderKeys.export
docid: class.Facebook.HackCodegen.HackBuilderKeys.export
permalink: /docs/reference/class.Facebook.HackCodegen.HackBuilderKeys.export/
---
# Facebook\\HackCodegen\\HackBuilderKeys::export()




Render the key as Hack code that produces the same value




``` Hack
public static function export(): IHackBuilderKeyRenderer<arraykey>;
```




For example, an ` int ` will be rendered without changes but a `` string ``
will be rendered with quotes.




## Returns




- ` IHackBuilderKeyRenderer<arraykey> `