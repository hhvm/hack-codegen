---
layout: docs
title: signFile
id: class.Facebook.HackCodegen.SignedSourceBase.signFile
docid: class.Facebook.HackCodegen.SignedSourceBase.signFile
permalink: /docs/reference/class.Facebook.HackCodegen.SignedSourceBase.signFile/
---
# Facebook\\HackCodegen\\SignedSourceBase::signFile()




Sign a source file into which you have previously embedded a signing
token




``` Hack
public static function signFile(
  string $file_data,
): string;
```




Signing modifies only the signing token, so the semantics of
the file will not change if the token is put in a comment.




@param  string  Data with embedded token, to be signed.
@return string  Signed data.




## Parameters




- ` string $file_data `




## Returns




+ ` string `