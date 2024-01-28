<?php

namespace NonIntString;

use function PHPStan\Testing\assertType;

function doFoo(string $s) {
	$a = [];

	$a[$s] = 1;
	assertType('non-empty-array<non-int-string, 1>', $a);

	$a[2] = 1;
	assertType('non-empty-array<2|non-int-string, 1>&hasOffsetValue(2, 1)', $a);
}

/** @param non-int-string $s */
function doConcat(string $s) {
	assertType('string', $s . "0");
	assertType('non-falsy-string', $s . "12");
	assertType('non-falsy-string&non-int-string', $s . "a");
	assertType('non-int-string', $s . "");
	assertType('non-falsy-string&non-int-string', $s . $s);
}
