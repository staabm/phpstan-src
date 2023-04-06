<?php

namespace ObjectShape;

use function PHPStan\Testing\assertType;

class Foo
{

	/**
	 * @param object{foo: self, bar: int, baz?: string} $o
	 */
	public function doFoo($o): void
	{
		assertType('object{foo: ObjectShape\Foo, bar: int, baz?: string}', $o);
	}

	/**
	 * @param object{foo: self, bar: int, baz?: string} $o
	 */
	public function doFoo2(object $o): void
	{
		assertType('object{foo: ObjectShape\Foo, bar: int, baz?: string}', $o);
	}

	public function doBaz(): void
	{
		assertType('object{}&stdClass', (object) []);

		$a = ['bar' => 2];
		if (rand(0, 1)) {
			$a['foo'] = 1;
		}

		assertType('object{bar: 2, foo?: 1}&stdClass', (object) $a);
	}

}
