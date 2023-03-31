<?php declare(strict_types=1);

namespace Bug2018;

class HelloWorld
{
	public function testNonBlockingEventIsExecuted(): void
	{
		$executed = false;

		$handle = static function () use (&$executed): void {
			$executed = true;
		};

		doStuff($handle);

		\PHPStan\Testing\assertType('bool', $executed);

		self::assertFalse($executed);

		doSomeOtherStuff($handle);

		\PHPStan\Testing\assertType('bool', $executed);

		self::assertTrue($executed);
	}

	/** @phpstan-assert true $executed */
	static public function assertTrue(mixed $executed): void
	{
	}

	/** @phpstan-assert false $executed */
	static public function assertFalse(mixed $executed): void
	{
	}
}

function doStuff(callable $f): void
{
}

function doSomeOtherStuff(callable $f): void
{
}
