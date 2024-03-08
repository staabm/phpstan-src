<?php declare(strict_types = 1);

namespace PHPStan\Rules\Constants;

use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<PolyfillDefineConstantRule>
 */
class PolyfillDefineConstantRuleTest extends RuleTestCase
{

	protected function getRule(): Rule
	{
		return new PolyfillDefineConstantRule(
			$this->getContainer()->getByType(ReflectionProvider::class),
		);
	}

	public function testConstants(): void
	{
		$this->analyse([__DIR__ . '/data/polyfill-constants.php'], [
			[
				'Polyfill of native constant FILTER_SANITIZE_ADD_SLASHES with value false needs to be compatible with native constant value 523.',
				7,
			],
			[
				'Polyfill of native constant FILTER_SANITIZE_ADD_SLASHES with value 123 needs to be compatible with native constant value 523.',
				12,
			],
		]);
	}

}
