<?php declare(strict_types = 1);

namespace ArrayWalfRecursive;

use function PHPStan\Testing\assertType;

function doFoo(array $a) {
	array_walk_recursive($a, function($item, $key)
	{
		assertType('mixed', $item);
		assertType('(int|string)', $key);
	});
}
