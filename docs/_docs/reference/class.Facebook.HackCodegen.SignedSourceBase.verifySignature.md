---
layout: docs
title: verifySignature
id: class.Facebook.HackCodegen.SignedSourceBase.verifySignature
docid: class.Facebook.HackCodegen.SignedSourceBase.verifySignature
permalink: /docs/reference/class.Facebook.HackCodegen.SignedSourceBase.verifySignature/
---
# Facebook\\HackCodegen\\SignedSourceBase::verifySignature()




Verify a file's signature




``` Hack
public static function verifySignature(
  string $file_data,
): bool;
```




You should first use isSigned() to determine
if a file is signed.




@param  string  File data.
@return bool    True if the file's signature is correct.




## Parameters




* ` string $file_data `




## Returns




- ` bool `