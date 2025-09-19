<?php

namespace Bug13512;

final class HelloWorld
{
	/**
	 * @return array<array{0: non-empty-string, 1: bool}>
	 */
	public static function dataProvider(): array
	{
		return [
			[
				'One',
				false,
			],
			[
				'Two with data set #0',
				false,
			],
		];
	}
}
