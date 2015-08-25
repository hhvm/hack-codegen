<?hh
/**
 * Copyright (c) 2015-present, Facebook, Inc.
 * All rights reserved.
 *
 * This source code is licensed under the BSD-style license found in the
 * LICENSE file in the root directory of this source tree. An additional grant
 * of patent rights can be found in the PATENTS file in the same directory.
 */

namespace Facebook\HackCodegen;

require_once('DormUserMutator.php');
require_once('DormUser.php');

/**
 * This is to demo how the Dorm classes for User can be used.
 * Notice that before being able to run this, you should set
 * up your DB dsn in DormUserSchema and create the table in
 * your db using, for example:
 *
 * create table user
 * (
 *   user_id integer primary key,
 *   first_name varchar(50) not null,
 *   last_name varchar(50) not null,
 *   birthday date,
 *   country_id integer,
 *   is_active boolean not null
 *  );
 *
 * Before trying this example, you'll need to generate the code by running
 * php codegen.php demo/DormUserSchema.php.
 */
$id = DormUserMutator::create()
 ->setFirstName('John')
 ->setLastName('Smith')
 ->setBirthday(new \DateTime('1978-03-26'))
 ->setCountryId(54)
 ->setIsActive(true)
 ->save();

echo "Created user with id $id\n";

$user = DormUser::load($id);
echo "Loaded: ".$user->getFirstName()." ".$user->getLastName()."\n";

DormUserMutator::update($id)
  ->setFirstName('Peter')
  ->save();

echo "Updated the user first name.\n";

$user = DormUser::load($id);
echo "Loaded: ".$user->getFirstName()." ".$user->getLastName()."\n";
