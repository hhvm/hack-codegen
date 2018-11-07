---
layout: docs
title: Facebook\HackCodegen\_Private\normalized_var_export
id: function.Facebook.HackCodegen._Private.normalized_var_export
docid: function.Facebook.HackCodegen._Private.normalized_var_export
permalink: /docs/reference/function.Facebook.HackCodegen._Private.normalized_var_export/
---
# Facebook\\HackCodegen\\_Private\\normalized_var_export()




` var_export() `, normalized to produce valid Hack code, rather than PHP




``` Hack
namespace Facebook\HackCodegen\_Private;

function normalized_var_export(
  \mixed $value,
): string;
```




This includes changes such as:

* ` null ` instead of `` NULL ``
* ` vec ` instead of `` HH\vec ``




## Parameters




- ` \mixed $value `




## Returns




+ ` string `