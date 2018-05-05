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

   private ?string $string_type;
   private mixed $shape_type;

   public function __construct(
     public string $name,
     mixed $type,
     public bool $optional = false,
   ) {
     invariant(
       is_string($type) || $type instanceof CodegenShapeFuture || $type instanceof CodegenShape,
       "You must provide either a string or shape",
     );

     if ($type instanceof CodegenShapeFuture || $type instanceof CodegenShape) {
       $this->shape_type = $type;
     } else {
       $this->string_type = (string) $type;
     }
   }

   public function getType(): string {
     if ($this->shape_type instanceof ICodeBuilderRenderer) {
       return $this->shape_type->render();
     }

     invariant($this->string_type !== null, "Somehow type is null");
     return $this->string_type;
   }
 }
