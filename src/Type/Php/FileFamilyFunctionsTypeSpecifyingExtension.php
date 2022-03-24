<?php declare(strict_types = 1);

namespace PHPStan\Type\Php;

use PhpParser\Node\Expr\FuncCall;
use PHPStan\Analyser\Scope;
use PHPStan\Analyser\SpecifiedTypes;
use PHPStan\Analyser\TypeSpecifier;
use PHPStan\Analyser\TypeSpecifierAwareExtension;
use PHPStan\Analyser\TypeSpecifierContext;
use PHPStan\Reflection\FunctionReflection;
use PHPStan\Type\Accessory\AccessoryNonEmptyStringType;
use PHPStan\Type\FunctionTypeSpecifyingExtension;
use PHPStan\Type\TypeCombinator;
use function array_key_exists;

class FileFamilyFunctionsTypeSpecifyingExtension implements FunctionTypeSpecifyingExtension, TypeSpecifierAwareExtension
{

	private TypeSpecifier $typeSpecifier;

	/** @var array<string, int> */
	private array $fileFnArgPositions = [
		'is_readable' => 0,
		'is_writable' => 0,
		'is_writeable' => 0,
		'is_executable' => 0,
		'is_link' => 0,
		'is_file' => 0,
		'is_dir' => 0,
		'file_exists' => 0,
		'fopen' => 0,
		'opendir' => 0,
		'fileatime' => 0,
		'filemtime' => 0,
		'filectime' => 0,
		'unlink' => 0,
	];

	public function isFunctionSupported(
		FunctionReflection $functionReflection,
		FuncCall $node,
		TypeSpecifierContext $context,
	): bool
	{
		return array_key_exists($functionReflection->getName(), $this->fileFnArgPositions)
			&& $context->true();
	}

	public function specifyTypes(
		FunctionReflection $functionReflection,
		FuncCall $node,
		Scope $scope,
		TypeSpecifierContext $context,
	): SpecifiedTypes
	{
		$args = $node->getArgs();
		$argPosition = $this->fileFnArgPositions[$functionReflection->getName()];

		$fileNameArg = $args[$argPosition]->value ?? null;
		if ($fileNameArg === null) {
			return new SpecifiedTypes();
		}

		$fileNameType = $scope->getType($fileNameArg);
		if (!$fileNameType->isString()->yes()) {
			return new SpecifiedTypes();
		}

		return $this->typeSpecifier->create(
			$fileNameArg,
			TypeCombinator::intersect($fileNameType, new AccessoryNonEmptyStringType()),
			$context,
			false,
			$scope,
		);
	}

	public function setTypeSpecifier(TypeSpecifier $typeSpecifier): void
	{
		$this->typeSpecifier = $typeSpecifier;
	}

}
