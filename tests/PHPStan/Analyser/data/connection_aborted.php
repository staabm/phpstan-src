<?php

namespace ConnectionAborted;

use function PHPStan\Testing\assertType;

class HelloWorld
{
	public function impureConnectionAborted(): void
	{
		if (connection_aborted()) {
			assertType('0|1', connection_aborted());
		}
	}
}
