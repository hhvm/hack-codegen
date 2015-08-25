<?hh // strict
/**
 * Copyright (c) 2015-present, Facebook, Inc.
 * All rights reserved.
 *
 * This source code is licensed under the BSD-style license found in the
 * LICENSE file in the root directory of this source tree. An additional grant
 * of patent rights can be found in the PATENTS file in the same directory.
 */

namespace Facebook\HackCodegen;

enum CodegenPHPMethodVisibility : string as string {
  V_PUBLIC = 'public';
  V_PRIVATE = 'private';
  V_PROTECTED = 'protected';
}

trait CodegenWithVisibility {
  private CodegenPHPMethodVisibility $visibility =
    CodegenPHPMethodVisibility::V_PUBLIC;

  public function setPrivate(): this {
    $this->visibility = CodegenPHPMethodVisibility::V_PRIVATE;
    return $this;
  }

  public function setPublic(): this {
    $this->visibility = CodegenPHPMethodVisibility::V_PUBLIC;
    return $this;
  }

  public function setProtected(): this {
    $this->visibility = CodegenPHPMethodVisibility::V_PROTECTED;
    return $this;
  }

  public function getVisibility(): string {
    return $this->visibility;
  }
}
