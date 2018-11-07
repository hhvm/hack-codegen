---
layout: docs
title: Facebook\HackCodegen\_Private\Filesystem
id: class.Facebook.HackCodegen._Private.Filesystem
docid: class.Facebook.HackCodegen._Private.Filesystem
permalink: /docs/reference/class.Facebook.HackCodegen._Private.Filesystem/
---
# Facebook\\HackCodegen\\_Private\\Filesystem




## Interface Synopsis




``` Hack
namespace Facebook\HackCodegen\_Private;

final class Filesystem {...}
```




### Public Methods




+ [` ::createDirectory(string $path, int $umask = 511): \void `](<class.Facebook.HackCodegen._Private.Filesystem.createDirectory.md>)
+ [` ::createTemporaryFile(string $prefix = '', bool $cleanup = false): string `](<class.Facebook.HackCodegen._Private.Filesystem.createTemporaryFile.md>)
+ [` ::readFile(string $path): string `](<class.Facebook.HackCodegen._Private.Filesystem.readFile.md>)
+ [` ::remove(string $path): \void `](<class.Facebook.HackCodegen._Private.Filesystem.remove.md>)
+ [` ::writeFile(string $path, string $data): \void `](<class.Facebook.HackCodegen._Private.Filesystem.writeFile.md>)
+ [` ::writeFileIfChanged(string $path, string $data): bool `](<class.Facebook.HackCodegen._Private.Filesystem.writeFileIfChanged.md>)