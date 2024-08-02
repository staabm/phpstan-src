<?php declare(strict_types = 1);

namespace PHPStan\Type\Php;

use PhpParser\Node\Expr\FuncCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\FunctionReflection;
use PHPStan\Reflection\Native\NativeParameterReflection;
use PHPStan\Reflection\ParameterReflection;
use PHPStan\TrinaryLogic;
use PHPStan\Type\ClosureType;
use PHPStan\Type\Constant\ConstantArrayType;
use PHPStan\Type\Constant\ConstantArrayTypeBuilder;
use PHPStan\Type\FunctionParameterClosureTypeExtension;
use PHPStan\Type\StringType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeCombinator;

final class PregReplaceCallbackArrayClosureTypeExtension implements FunctionParameterClosureTypeExtension
{

	public function __construct(
		private RegexArrayShapeMatcher $regexShapeMatcher,
	)
	{
	}

	public function isFunctionSupported(FunctionReflection $functionReflection, ParameterReflection $parameter): bool
	{
		return $functionReflection->getName() === 'preg_replace_callback_array' && $parameter->getName() === 'pattern';
	}

	public function getTypeFromFunctionCall(FunctionReflection $functionReflection, FuncCall $functionCall, ParameterReflection $parameter, Scope $scope): ?Type
	{
		$args = $functionCall->getArgs();

		$patternArg = $args[0] ?? null;
		if ($patternArg === null) {
			return null;
		}
		$patternType = $scope->getType($patternArg->value);
		if (!$patternType instanceof ConstantArrayType) {
			return null;
		}

		$flagsArg = $args[4] ?? null;
		$flagsType = null;
		if ($flagsArg !== null) {
			$flagsType = $scope->getType($flagsArg->value);
		}

		$builder = ConstantArrayTypeBuilder::createEmpty();
		foreach ($patternType->getKeyTypes() as $keyType) {
			$strings = $keyType->getConstantStrings();
			if ($strings === []) {
				return null;
			}

			$matchesTypes = [];
			foreach ($strings as $string) {
				$matchesType = $this->regexShapeMatcher->matchType($string, $flagsType, TrinaryLogic::createYes());
				if ($matchesType === null) {
					return null;
				}
				$matchesTypes[] = $matchesType;
			}

			$builder->setOffsetValueType($keyType, new ClosureType(
				[
					new NativeParameterReflection(
						$parameter->getName(),
						$parameter->isOptional(),
						TypeCombinator::union(...$matchesTypes),
						$parameter->passedByReference(),
						$parameter->isVariadic(),
						$parameter->getDefaultValue(),
					),
				],
				new StringType(),
			));
		}

		return $builder->getArray();
	}

}
