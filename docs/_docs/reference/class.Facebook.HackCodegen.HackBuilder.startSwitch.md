---
layout: docs
title: startSwitch
id: class.Facebook.HackCodegen.HackBuilder.startSwitch
docid: class.Facebook.HackCodegen.HackBuilder.startSwitch
permalink: /docs/reference/class.Facebook.HackCodegen.HackBuilder.startSwitch/
---
# Facebook\\HackCodegen\\HackBuilder::startSwitch()




Starts building a switch-statement that can loop over an Iterable
to build each case-statement




``` Hack
public function startSwitch(
  string $condition,
): this;
```




example:




hack_builder()
->startSwitch('$soccer_player')
->addCaseBlocks(
$players,
($player, $body) ==> {
$body->addCase($player['name'])
->addLine('$shot = new Shot(\''.$player['favorite_shot'].'\');')
->returnCase('$shot->execute()');
},
)
->addDefault()
->addLine('invariant_violation(\'ball deflated!\');')
->endDefault()
->endSwitch();




## Parameters




* ` string $condition `




## Returns




- ` this `