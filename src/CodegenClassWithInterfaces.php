<?hh // strict
/*
 *  Copyright (c) 2015-present, Facebook, Inc.
 *  All rights reserved.
 *
 *  This source code is licensed under the MIT license found in the
 *  LICENSE file in the root directory of this source tree.
 *
 */

namespace Facebook\HackCodegen;

use namespace HH\Lib\{C, Vec};

trait CodegenClassWithInterfaces {
  private vec<CodegenImplementsInterface> $interfaces = vec[];

  public function setInterfaces(
    Traversable<CodegenImplementsInterface> $value,
  ): this {
    invariant(
      C\is_empty($this->interfaces),
      'interfaces have already been set',
    );
    $this->interfaces = vec($value);
    return $this;
  }

  public function addInterface(CodegenImplementsInterface $value): this {
    $this->interfaces[] = $value;
    return $this;
  }

  public function addInterfaces(
    Traversable<CodegenImplementsInterface> $interfaces,
  ): this {
    $this->interfaces = Vec\concat($this->interfaces, $interfaces);
    return $this;
  }

  public function getImplements(): vec<string> {
    // Interface<T> becomes Interface
    return Vec\map(
      $this->interfaces,
      $interface ==> {
        $name = $interface->getName();
        return \strstr($name, '<', true) ?: $name;
      },
    );
  }

  public function renderInterfaceList(
    HackBuilder $builder,
    string $introducer,
  ): void {
    if (!C\is_empty($this->interfaces)) {
      $builder->newLine()->indent()->addLine($introducer);
      $i = 0;
      foreach ($this->interfaces as $interface) {
        $i++;
        $builder->addRenderer($interface);
        $builder->addLineIf($i !== C\count($this->interfaces), ',');
      }
      $builder->unindent();
    }

  }
}
