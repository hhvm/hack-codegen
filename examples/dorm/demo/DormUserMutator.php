<?hh // strict
/**
 * This file is partially generated. Only make modifications between BEGIN
 * MANUAL SECTION and END MANUAL SECTION designators.
 *
 * To re-generate this file run
 * /home/ubuntu/hack-codegen/examples/dorm/codegen.php
 *
 *
 * @partially-generated SignedSource<<f9c5059806c0ec22c4ba40846c277eb9>>
 */

final class DormUserMutator {

  private Map<string, mixed> $data = Map {
  };
  private static Map<string, int> $pdoType = Map {
    'birthday' => PDO::PARAM_STR,
    'country_id' => PDO::PARAM_INT,
    'first_name' => PDO::PARAM_STR,
    'is_active' => PDO::PARAM_BOOL,
    'last_name' => PDO::PARAM_STR,
  };

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
    $quoted = $this->data->mapWithKey(
      ($k, $v) ==> $conn->quote($v, self::$pdoType[$k]),
    );
    $id = $this->id;
    if ($id === null) {
      $this->checkRequiredFields();
      $names = "(".implode(",", $quoted->keys()).")";
      $values = "(".implode(",", $quoted->values()).")";
      $st = "insert into user $names values $values";
      $conn->exec($st);
      return (int) $conn->lastInsertId();
    } else {
      $pairs = $quoted->mapWithKey(($field, $value) ==>  "$field=$value");
      $st = "update user set ".implode(",", $pairs)." where user_id=".$this->id;
      $conn->exec($st);
      return $id;
    }
  }

  public function checkRequiredFields(): void {
    $required = Set {
      'first_name',
      'last_name',
      'is_active',
    };
    $missing = $required->removeAll($this->data->keys());;
    invariant(
      $missing->isEmpty(),
      "The following required fields are missing: ".implode(", ", $missing),
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
