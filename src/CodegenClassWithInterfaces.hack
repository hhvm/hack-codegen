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

/** Functionality shared by all class-like definitions that are able to
 * implement interfaces.
 *
 * For example, classes and traits can implement interfaces, but enums
 * can't.
 */
trait CodegenClassWithInterfaces {
  require extends CodegenClassish;

  private vec<CodegenImplementsInterface> $interfaces = vec[];

  /** @selfdocumenting */
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

  /** @selfdocumenting */
  public function addInterface(CodegenImplementsInterface $value): this {
    $this->interfaces[] = $value;
    return $this;
  }

  /** @selfdocumenting */
  public function addInterfaces(
    Traversable<CodegenImplementsInterface> $interfaces,
  ): this {
    $this->interfaces = Vec\concat($this->interfaces, vec($interfaces));
    return $this;
  }

  /** Return the list of interfaces implemented by the generated class */
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

  /** @selfdocumenting */
  protected function renderInterfaceList(
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
