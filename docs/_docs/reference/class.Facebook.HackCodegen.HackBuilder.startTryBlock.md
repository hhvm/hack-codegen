---
layout: docs
title: startTryBlock
id: class.Facebook.HackCodegen.HackBuilder.startTryBlock
docid: class.Facebook.HackCodegen.HackBuilder.startTryBlock
permalink: /docs/reference/class.Facebook.HackCodegen.HackBuilder.startTryBlock/
---
# Facebook\\HackCodegen\\HackBuilder::startTryBlock()




Start try-catch-finally blocks in the code




``` Hack
public function startTryBlock(): this;
```




Very similar to startIfBlock, this is mostly a sugar on openBrace
to make the code more meaningful.




Example:
hack_builder()
->startTryBlock()
->addLine('my_func();')
->addCatchBlock('SystemException', '$ex')
->addLine('return null;')
->addFinallyBlock()
->addLine('bump_ods();')
->endTryBlock()




## Returns




+ ` this `