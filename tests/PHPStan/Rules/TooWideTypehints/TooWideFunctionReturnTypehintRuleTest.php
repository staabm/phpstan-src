<?php declare(strict_types = 1);

namespace PHPStan\Rules\TooWideTypehints;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends \PHPStan\Testing\RuleTestCase<TooWideFunctionReturnTypehintRule>
 */
class TooWideFunctionReturnTypehintRuleTest extends RuleTestCase
{

	protected function getRule(): Rule
	{
		return new TooWideFunctionReturnTypehintRule();
	}

	public function testRule(): void
	{
		require_once __DIR__ . '/data/tooWideFunctionReturnType.php';
		$this->analyse([__DIR__ . '/data/tooWideFunctionReturnType.php'], [
			[
				'Function TooWideFunctionReturnType\bar() never returns string so it can be removed from the return typehint.',
				11,
			],
			[
				'Function TooWideFunctionReturnType\baz() never returns null so it can be removed from the return typehint.',
				15,
			],
			[
				'Function TooWideFunctionReturnType\ipsum() never returns null so it can be removed from the return typehint.',
				27,
			],
			[
				'Function TooWideFunctionReturnType\dolor2() never returns null so it can be removed from the return typehint.',
				41,
			],
			[
				'Function TooWideFunctionReturnType\dolor4() never returns int so it can be removed from the return typehint.',
				59,
			],
			[
				'Function TooWideFunctionReturnType\dolor6() never returns null so it can be removed from the return typehint.',
				79,
			],
		]);
	}

}
