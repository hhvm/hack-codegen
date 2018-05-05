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

 final class CodegenShapeMember {

   public function __construct(
     public string $name,
     private mixed $type,
     public bool $optional = false,
   ) {
     invariant(
       is_string($type) || $type instanceof CodegenShapeFuture || $type instanceof CodegenShape,
       "You must provide either a string or shape",
     );
   }

   public function getType(): string {
     if ($this->type instanceof ICodeBuilderRenderer) {
       return $this->type->render();
     }

     invariant($this->type !== null, "Somehow type is null");
     invariant(is_string($this->type), "Somehow type is not a string");
     return $this->type;
   }
 }
