<?php

namespace Bug13512b;

final class HelloWorld
{
	/**
	 * @return array<array<int|null>>
	 */
	public static function dataProvider(): array
	{
		return [
			[
				1
			],
			[
				10
			],
		];
	}
}
