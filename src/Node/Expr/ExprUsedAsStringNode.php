<?php declare(strict_types = 1);

namespace PHPStan\Node\Expr;

use Override;
use PhpParser\Node\Expr;
use PHPStan\Node\VirtualNode;
use PHPStan\Type\Type;

/**
 * @api
 */
final class ExprUsedAsStringNode extends Expr implements VirtualNode
{

	/** @api */
	public function __construct(private Expr $expr)
	{
		parent::__construct();
	}

	public function getExpr(): Expr
	{
		return $this->expr;
	}

	#[Override]
	public function getType(): string
	{
		return 'PHPStan_Node_ExprUsedAsString';
	}

	/**
	 * @return string[]
	 */
	#[Override]
	public function getSubNodeNames(): array
	{
		return [];
	}

}
