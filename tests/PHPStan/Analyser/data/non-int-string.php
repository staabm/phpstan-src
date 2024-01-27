<?php

namespace NonIntString;

use function PHPStan\Testing\assertType;

function doFoo(string $s) {
	$a = [];
	$a[$s] = 1;

	assertType('array<int|non-int-string, int>', $a);
}

/** @param non-int-string $s */
function doBar(string $s) {
	assertType('string', $s . "12");
	assertType('non-int-string', $s . "a");
}

function doFoo() {
	assertType('', '001');
	assertType('', 'abc');
	assertType('', '42');
	assertType('', '42');
	assertType('', '-42');
	assertType('', '+42');
	assertType('', '02471');
	assertType('', '0x539');
	assertType('', '1337e0');
	assertType('', '');
}
