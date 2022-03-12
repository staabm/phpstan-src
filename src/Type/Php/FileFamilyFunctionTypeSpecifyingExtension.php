<?php declare(strict_types = 1);

namespace PHPStan\Type\Php;

use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\Variable;
use PHPStan\Analyser\Scope;
use PHPStan\Analyser\SpecifiedTypes;
use PHPStan\Analyser\TypeSpecifier;
use PHPStan\Analyser\TypeSpecifierAwareExtension;
use PHPStan\Analyser\TypeSpecifierContext;
use PHPStan\Reflection\FunctionReflection;
use PHPStan\Type\Accessory\AccessoryLiteralStringType;
use PHPStan\Type\Accessory\AccessoryNonEmptyStringType;
use PHPStan\Type\FunctionTypeSpecifyingExtension;
use PHPStan\Type\IntersectionType;
use PHPStan\Type\StringType;
use function count;

final class FileFamilyFunctionTypeSpecifyingExtension implements FunctionTypeSpecifyingExtension, TypeSpecifierAwareExtension
{
	/**
	 * @var string[]
	 */
	private $fileFamilfy = [
		'file_exists',
		'is_file',
		'is_dir',
		'is_link',
		'is_readable',
		'is_writable',
		'is_executable',
		'filectime',
		'fileatime',
		'filemtime',
	];

	private TypeSpecifier $typeSpecifier;

	public function setTypeSpecifier(TypeSpecifier $typeSpecifier): void
	{
		$this->typeSpecifier = $typeSpecifier;
	}

	public function isFunctionSupported(FunctionReflection $functionReflection, FuncCall $node, TypeSpecifierContext $context): bool
	{
		return in_array(strtolower($functionReflection->getName()), $this->fileFamilfy, true)
			&& $context->true();
	}

	public function specifyTypes(FunctionReflection $functionReflection, FuncCall $node, Scope $scope, TypeSpecifierContext $context): SpecifiedTypes
	{
		$args = $node->getArgs();
		$pathType = $scope->getType($args[0]->value);

		$stringType = new StringType();
		if (!$stringType->isSuperTypeOf($pathType)->yes()) {
			return new SpecifiedTypes();
		}

		$accessoryTypes = [];
		$accessoryTypes[] = new StringType();
		$accessoryTypes[] = new AccessoryNonEmptyStringType();

		if ($pathType->isLiteralString()->yes()) {
			$accessoryTypes[] = new AccessoryLiteralStringType();
		}

		return $this->typeSpecifier->create(
			$args[0]->value,
			new IntersectionType($accessoryTypes),
			$context,
			true,
			$scope,
		);
	}

}
