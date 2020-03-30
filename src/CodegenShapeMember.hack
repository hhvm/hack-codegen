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

   private bool $isOptional = false;

   public function __construct(
     private string $name,
     private mixed $type,
   ) {
     invariant(
       $type is string || $type is CodegenShape,
       'You must provide either a string or shape',
     );
   }

   public function setIsOptional(bool $value = true): this {
     $this->isOptional = $value;
     return $this;
   }

   public function getIsOptional(): bool {
     return $this->isOptional;
   }

   public function getName(): string {
     return $this->name;
   }

   public function getType(): string {
     if ($this->type is ICodeBuilderRenderer) {
       return $this->type->render();
     }

     return $this->type as string;
   }
 }
