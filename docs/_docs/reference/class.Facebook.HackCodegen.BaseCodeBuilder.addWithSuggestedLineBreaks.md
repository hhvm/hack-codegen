---
layout: docs
title: addWithSuggestedLineBreaks
id: class.Facebook.HackCodegen.BaseCodeBuilder.addWithSuggestedLineBreaks
docid: class.Facebook.HackCodegen.BaseCodeBuilder.addWithSuggestedLineBreaks
permalink: /docs/reference/class.Facebook.HackCodegen.BaseCodeBuilder.addWithSuggestedLineBreaks/
---
# Facebook\\HackCodegen\\BaseCodeBuilder::addWithSuggestedLineBreaks()




Let's the user suggest linebreaks in the code string provided, marked by
the delimiter




``` Hack
final public function addWithSuggestedLineBreaks(
  ?string $code,
): this;
```




The max length is calculated based on the current
indentation level.




If the code string exceeds the max length
    - Preferentially uses the delimiter to break the line
    - If some part is too big to fit, it lets it be.
If the code string or a part doesn't exceed the max length
    - Replaces the delimiter with space.




The delimiter is ` BaseCodeBuilder::DELIMITER `.




## Parameters




* ` ?string $code `




## Returns




- ` this `