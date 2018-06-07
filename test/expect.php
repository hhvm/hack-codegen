<?hh

namespace Facebook\HackCodegen;

function expect<T>(T $obj, mixed ...$args): CodegenExpectObj<T> {
  return new CodegenExpectObj(new ImmVector(\func_get_args()));
}

function expect_with_context<T>(string $context, T $obj, mixed ...$args): CodegenExpectObj<T> {
  return new CodegenExpectObj(new ImmVector(\array_slice(\func_get_args(), 1)), $context);
}
