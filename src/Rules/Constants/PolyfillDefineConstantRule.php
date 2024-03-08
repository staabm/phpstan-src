<?php declare(strict_types = 1);

namespace PHPStan\Rules\Constants;

use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\Type\VerbosityLevel;
use function count;
use function sprintf;

/**
 * @implements Rule<FuncCall>
 */
class PolyfillDefineConstantRule implements Rule
{

	public function __construct(
		private ReflectionProvider $reflectionProvider,
	)
	{
	}

	public function getNodeType(): string
	{
		return FuncCall::class;
	}

	public function processNode(Node $node, Scope $scope): array
	{
		if (!$node->name instanceof Node\Name || $node->name->toString() !== 'define') {
			return [];
		}

		$args = $node->getArgs();
		if (count($args) < 2) {
			return [];
		}

		$constValue = $scope->getType($args[1]->value);
		if (!$constValue->isConstantValue()->yes()) {
			return [];
		}

		foreach ($scope->getType($args[0]->value)->getConstantStrings() as $constantString) {
			$constName = new Node\Name($constantString->getValue());
			if (!$this->reflectionProvider->hasConstant($constName, $scope)) {
				continue;
			}

			$constantReflection = $this->reflectionProvider->getConstant($constName, $scope);
			if (!$constantReflection->isNative()->yes()) {
				continue;
			}

			$nativeValueType = $constantReflection->getValueType();
			if ($nativeValueType->isSuperTypeOf($constValue)->yes()) {
				continue;
			}

			return [
				RuleErrorBuilder::message(sprintf(
					'Polyfill of native constant %s with value %s needs to be compatible with native constant value %s.',
					$constantString->getValue(),
					$constValue->describe(VerbosityLevel::precise()),
					$nativeValueType->describe(VerbosityLevel::precise()),
				))
				->build(),
			];
		}

		return [];
	}

}
