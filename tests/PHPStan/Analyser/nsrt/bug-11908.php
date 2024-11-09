<?php // lint >= 7.4

namespace Bug11908;

use function PHPStan\Testing\assertType;

if (preg_match('/a/', '', $matches) != false) {
	assertType('array{string}', $matches);
}

if (preg_match('/a/', '', $matches) !== false) {
	assertType('array{0?: string}', $matches);
}

if (preg_match('/a/', '', $matches) > 0) {
	assertType('array{string}', $matches);
}

if (preg_match('/a/', '', $matches) >= 0) {
	assertType('array{0?: string}', $matches);
}
