---
layout: docs
title: Facebook\HackCodegen\SignedSourceBase
id: class.Facebook.HackCodegen.SignedSourceBase
docid: class.Facebook.HackCodegen.SignedSourceBase
permalink: /docs/reference/class.Facebook.HackCodegen.SignedSourceBase/
---
# Facebook\\HackCodegen\\SignedSourceBase




## Interface Synopsis




``` Hack
namespace Facebook\HackCodegen;

abstract class SignedSourceBase {...}
```




### Public Methods




- [` ::getPattern(): string `](<class.Facebook.HackCodegen.SignedSourceBase.getPattern.md>)
- [` ::getSigningToken(): string `](<class.Facebook.HackCodegen.SignedSourceBase.getSigningToken.md>)\
  Get the signing token, which you must embed in the file you wish to sign
- [` ::hasValidSignature(string $file_data): bool `](<class.Facebook.HackCodegen.SignedSourceBase.hasValidSignature.md>)\
  Check if a file has a valid signature
- [` ::hasValidSignatureFromAnySigner(string $data): bool `](<class.Facebook.HackCodegen.SignedSourceBase.hasValidSignatureFromAnySigner.md>)
- [` ::isSigned(string $file_data): bool `](<class.Facebook.HackCodegen.SignedSourceBase.isSigned.md>)\
  Determine if a file is signed or not
- [` ::isSignedByAnySigner(string $data): bool `](<class.Facebook.HackCodegen.SignedSourceBase.isSignedByAnySigner.md>)
- [` ::signFile(string $file_data): string `](<class.Facebook.HackCodegen.SignedSourceBase.signFile.md>)\
  Sign a source file into which you have previously embedded a signing
  token
- [` ::verifySignature(string $file_data): bool `](<class.Facebook.HackCodegen.SignedSourceBase.verifySignature.md>)\
  Verify a file's signature







### Protected Methods




+ [` ::getTokenName(): string `](<class.Facebook.HackCodegen.SignedSourceBase.getTokenName.md>)
+ [` ::preprocess(string $file_data): string `](<class.Facebook.HackCodegen.SignedSourceBase.preprocess.md>)\
  Override this method to process the file data before signing a file
  or before checking the signature