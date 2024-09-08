<?php declare(strict_types = 1);

namespace PHPStan\Type\Php;

use DivisionByZeroError;
use PhpParser\Node\Expr\FuncCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\FunctionReflection;
use PHPStan\Type\Constant\ConstantIntegerType;
use PHPStan\Type\DynamicFunctionThrowTypeExtension;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use function count;

final class BcdivThrowTypeExtension implements DynamicFunctionThrowTypeExtension
{

	public function isFunctionSupported(FunctionReflection $functionReflection): bool
	{
		return $functionReflection->getName() === 'bcdiv';
	}

	public function getThrowTypeFromFunctionCall(FunctionReflection $functionReflection, FuncCall $funcCall, Scope $scope): ?Type
	{
		if (count($funcCall->getArgs()) < 2) {
			return $functionReflection->getThrowType();
		}

		$divisorType = $scope->getType($funcCall->getArgs()[1]->value);
		if (!$divisorType->toNumber()->isSuperTypeOf(new ConstantIntegerType(0))->no()) {
			return new ObjectType(DivisionByZeroError::class);
		}

		return null;
	}

}
