<?hh // strict
/**
 * This file is partially generated. Only make modifications between BEGIN
 * MANUAL SECTION and END MANUAL SECTION designators.
 *
 * To re-generate this file run codegen.php DormUserSchema
 *
 *
 * @partially-generated SignedSource<<62acdce3703664c784088994a3e9951d>>
 */

final class DormUserMutator {

  private dict<string, mixed> $data = dict [
  ];
  private static dict<string, int> $pdoType = dict [
    'first_name' => PDO::PARAM_STR,
    'last_name' => PDO::PARAM_STR,
    'birthday' => PDO::PARAM_STR,
    'country_id' => PDO::PARAM_INT,
    'is_active' => PDO::PARAM_BOOL,
  ];

  private function __construct(private ?int $id = null) {
  }

  public function create(): this {
    return new DormUserMutator();
  }

  public function update(int $id): this {
    return new DormUserMutator($id);
  }

  public function save(): int {
    $conn = new PDO('sqlite:/path/to/database.db');
    $quoted = \HH\Lib\Dict\map_with_key(
      $this->data,
      ($k, $v) ==> $conn->quote($v, self::$pdoType[$k]),
    );
    $id = $this->id;
    if ($id === null) {
      $this->checkRequiredFields();
      $names = "(".\HH\Lib\Str\join(",", \HH\Lib\Vec\keys($quoted)).")";
      $values = "(".\HH\Lib\Str\join(",", vec($quoted)).")";
      $st = "insert into user $names values $values";
      $conn->exec($st);
      return (int) $conn->lastInsertId();
    } else {
      $pairs =
        \HH\Lib\Dict\map_with_key($quoted, ($field, $value) ==>  "$field=$value");
      $st = "update user set ".\HH\Lib\Str\join(",", $pairs)." where user_id=".$this->id;
      $conn->exec($st);
      return $id;
    }
  }

  public function checkRequiredFields(): void {
    $required = keyset [
      'first_name',
      'last_name',
      'is_active',
    ];
    $missing = \HH\Lib\Dict\diff_by_key($required, $this->data);;
    invariant(
      \HH\Lib\C\is_empty($missing),
      "The following required fields are missing: %s",
      \HH\Lib\Str\join(", ", $missing),
    );
  }

  public function setFirstName(string $value): this {
    $this->data["first_name"] = $value;
    return $this;
  }

  public function setLastName(string $value): this {
    $this->data["last_name"] = $value;
    return $this;
  }

  public function setBirthday(DateTime $value): this {
    $this->data["birthday"] = $value->format("Y-m-d");
    return $this;
  }

  public function setCountryId(int $value): this {
    /* BEGIN MANUAL SECTION CountryId */
    // You may manually change this section of code
    $this->data["country_id"] = $value;
    /* END MANUAL SECTION */
    return $this;
  }

  public function setIsActive(bool $value): this {
    $this->data["is_active"] = $value;
    return $this;
  }
}
