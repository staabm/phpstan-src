<?php

namespace NonEmptyStringFileSpecifying;

use function PHPStan\Testing\assertType;

class Foo {
	public function doFoo(string $s): void
	{
		if (file_exists($s)) {
			assertType('non-empty-string', $s);
		} else {
			assertType('string', $s);
		}
		assertType('string', $s);

		if (is_file($s)) {
			assertType('non-empty-string', $s);
		} else {
			assertType('string', $s);
		}
		assertType('string', $s);

		if (is_dir($s)) {
			assertType('non-empty-string', $s);
		} else {
			assertType('string', $s);
		}
		assertType('string', $s);

		if (is_readable($s)) {
			assertType('non-empty-string', $s);
		} else {
			assertType('string', $s);
		}
		assertType('string', $s);

		if (is_writable($s)) {
			assertType('non-empty-string', $s);
		} else {
			assertType('string', $s);
		}
		assertType('string', $s);

	}

}
