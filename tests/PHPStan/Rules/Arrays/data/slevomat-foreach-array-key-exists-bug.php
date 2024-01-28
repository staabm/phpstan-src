<?php

namespace SlevomatForeachArrayKeyExistsBug;

use function PHPStan\Testing\assertType;

class Foo
{

	public function doFoo(array $percentageIntervals, array $changes): void
	{
		$intervalResults = [];
		foreach ($percentageIntervals as $percentageInterval) {
			foreach ($changes as $changeInPercents => $itemsCount) {
				if ($percentageInterval->isInInterval((float) $changeInPercents)) {
					$key = $percentageInterval->getFormatted();
					if (array_key_exists($key, $intervalResults)) {
						assertType('array<int|non-int-string, array{itemsCount: mixed, interval: mixed}>', $intervalResults);
						assertType('array{itemsCount: mixed, interval: mixed}', $intervalResults[$key]);
						$intervalResults[$key]['itemsCount'] += $itemsCount;
						assertType('non-empty-array<int|non-int-string, array{itemsCount: (array|float|int), interval: mixed}>', $intervalResults);
						assertType('array{itemsCount: (array|float|int), interval: mixed}', $intervalResults[$key]);
					} else {
						assertType('array<int|string, array{itemsCount: mixed, interval: mixed}>', $intervalResults);
						assertType('array{itemsCount: mixed, interval: mixed}', $intervalResults[$key]);
						$intervalResults[$key] = [
							'itemsCount' => $itemsCount,
							'interval' => $percentageInterval,
						];
						assertType('non-empty-array<int|string, array{itemsCount: mixed, interval: mixed}>', $intervalResults);
						assertType('array{itemsCount: mixed, interval: mixed}', $intervalResults[$key]);
					}
				}
			}
		}

		assertType('array<int|string, array{itemsCount: mixed, interval: mixed}>', $intervalResults);
		foreach ($intervalResults as $data) {
			echo $data['interval'];
		}
	}

}
