<?php declare(strict_types=1);

namespace Bug13270;

class HelloWorld
{
	public function test(array $data): void
	{
		foreach($data as $k => $v) {
			$data[$k]['a'] = true;
			foreach($data[$k] as $val) {
			}
		}
	}
}
