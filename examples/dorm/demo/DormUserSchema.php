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

final class DormUserSchema implements DormSchema {
  public function getFields(): Map<string, DormField> {
    return Map {
      'FirstName' => string_field('first_name'),
      'LastName'  => string_field('last_name'),
      'Birthday'  => date_field('birthday')->optional(),
      'CountryId' => int_field('country_id')->optional()->manual(),
      'IsActive' => bool_field('is_active'),
    };
  }

  public function getDsn(): string {
    return 'sqlite:/path/to/database.db';
  }

  public function getTableName(): string {
    return 'user';
  }

  public function getIdField(): string {
    return 'user_id';
  }
}
