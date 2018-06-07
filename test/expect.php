<?hh

namespace Facebook\HackCodegen;

function expect<T>(T $obj, mixed ...$args): CodegenExpectObj<T> {
  return new CodegenExpectObj(new ImmVector(\func_get_args()));
}
