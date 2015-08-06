# DORM Example

## Introduction
This example combines many features of the code generation to illustrate how it could be used in an ORM (Object-relational mapping) framework.
The structure of the database in described in schemas (e.g. DormUserSchema), from which code is generated to read and write data.
DORM stands for Demo ORM, and although it actually works, it is not indendeed for real usage.


## Usage
1. ** Optional ** If you want to try running the generated code (as opposed to just generating the code), you'll need to set up the database:
  1. Using your favorite database, create the "user" table with the schema shown in demo_usage.php
  2. In file DemoUserSchema.php, change the getDsn method to return the DSN pointing to your database.
2. To generate the code, run: php codegen.php demo/DormUserSchema.php.  This will generate the files DormUser.php and DormUserMutator.php. Take a look a the generated code!
3. If you followed step 1, run php demo/demo_usage.php to have it inserting a rown in the database.

Here are a few things that you can try out to better understand the code generation:
- Both generated files have a manual section, delimited by the comments "BEGIN MANUAL SECTION" and "END MANUAL SECTION".  Try changing the code inside the section and re-generating the code, to see that the code is kept.
- Change something in a generated file and try re-generating the code to see what happens.
- Play adding new fields in the schema, or generate your own schema.
