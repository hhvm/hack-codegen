---
layout: docs
title: Facebook\HackCodegen\_Private\strip_hh_prefix
id: function.Facebook.HackCodegen._Private.strip_hh_prefix
docid: function.Facebook.HackCodegen._Private.strip_hh_prefix
permalink: /docs/reference/function.Facebook.HackCodegen._Private.strip_hh_prefix/
---
# Facebook\\HackCodegen\\_Private\\strip_hh_prefix()




Remove the 'HH\' prefix from typehint strings
and from strings produced by var_export()




``` Hack
namespace Facebook\HackCodegen\_Private;

function strip_hh_prefix(
  string $str,
  bool $nonobject_types_only = false,
): string;
```




## Parameters




* ` string $str `
* ` bool $nonobject_types_only = false `




## Returns




- ` string `