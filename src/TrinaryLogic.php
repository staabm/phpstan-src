<?php declare(strict_types = 1);

namespace PHPStan;

use PHPStan\Type\BooleanType;
use PHPStan\Type\Constant\ConstantBooleanType;
use function array_column;
use function max;
use function min;

/**
 * @api
 * @see https://phpstan.org/developing-extensions/trinary-logic
 */
enum TrinaryLogic: int
{
	case YES = 1;
	case MAYBE = 0;
	case NO = -1;


	public static function createYes(): self
	{
		return self::YES;
	}

	public static function createNo(): self
	{
		return self::NO;
	}

	public static function createMaybe(): self
	{
		return self::MAYBE;
	}

	public static function createFromBoolean(bool $value): self
	{
		return $value ? self::YES : self::NO;
	}

	private static function create(int $value): self
	{
		if ($value === 0) {
			return self::MAYBE;
		}
		if ($value === 1) {
			return self::YES;
		}
		if ($value === -1) {
			return self::NO;
		}
		throw new ShouldNotHappenException();
	}

	public function yes(): bool
	{
		return $this === self::YES;
	}

	public function maybe(): bool
	{
		return $this === self::MAYBE;
	}

	public function no(): bool
	{
		return $this === self::NO;
	}

	public function toBooleanType(): BooleanType
	{
		if ($this === self::MAYBE) {
			return new BooleanType();
		}

		return new ConstantBooleanType($this === self::YES);
	}

	public function and(self ...$operands): self
	{
		$min = $this->value;
		foreach ($operands as $operand) {
			if ($operand->value >= $min) {
				continue;
			}

			$min = $operand->value;
		}
		return self::create($min);
	}

	/**
	 * @template T
	 * @param T[] $objects
	 * @param callable(T): self $callback
	 */
	public function lazyAnd(
		array $objects,
		callable $callback,
	): self
	{
		if ($this->no()) {
			return $this;
		}

		$results = [];
		foreach ($objects as $object) {
			$result = $callback($object);
			if ($result->no()) {
				return $result;
			}

			$results[] = $result;
		}

		return $this->and(...$results);
	}

	public function or(self ...$operands): self
	{
		$operandValues = array_column($operands, 'value');
		$operandValues[] = $this->value;
		return self::create(max($operandValues));
	}

	/**
	 * @template T
	 * @param T[] $objects
	 * @param callable(T): self $callback
	 */
	public function lazyOr(
		array $objects,
		callable $callback,
	): self
	{
		if ($this->yes()) {
			return $this;
		}

		$results = [];
		foreach ($objects as $object) {
			$result = $callback($object);
			if ($result->yes()) {
				return $result;
			}

			$results[] = $result;
		}

		return $this->or(...$results);
	}

	public static function extremeIdentity(self ...$operands): self
	{
		if ($operands === []) {
			throw new ShouldNotHappenException();
		}
		$operandValues = array_column($operands, 'value');
		$min = min($operandValues);
		$max = max($operandValues);
		return self::create($min === $max ? $min : self::MAYBE->value);
	}

	/**
	 * @template T
	 * @param T[] $objects
	 * @param callable(T): self $callback
	 */
	public static function lazyExtremeIdentity(
		array $objects,
		callable $callback,
	): self
	{
		if ($objects === []) {
			throw new ShouldNotHappenException();
		}

		$lastResult = null;
		foreach ($objects as $object) {
			$result = $callback($object);
			if ($lastResult === null) {
				$lastResult = $result;
				continue;
			}
			if ($lastResult->equals($result)) {
				continue;
			}

			return self::createMaybe();
		}

		return $lastResult;
	}

	public static function maxMin(self ...$operands): self
	{
		if ($operands === []) {
			throw new ShouldNotHappenException();
		}
		$operandValues = array_column($operands, 'value');
		return self::create(max($operandValues) > 0 ? 1 : min($operandValues));
	}

	/**
	 * @template T
	 * @param T[] $objects
	 * @param callable(T): self $callback
	 */
	public static function lazyMaxMin(
		array $objects,
		callable $callback,
	): self
	{
		$results = [];
		foreach ($objects as $object) {
			$result = $callback($object);
			if ($result->yes()) {
				return $result;
			}

			$results[] = $result;
		}

		return self::maxMin(...$results);
	}

	public function negate(): self
	{
		return self::create(-$this->value);
	}

	public function equals(self $other): bool
	{
		return $this === $other;
	}

	public function compareTo(self $other): ?self
	{
		if ($this->value > $other->value) {
			return $this;
		} elseif ($other->value > $this->value) {
			return $other;
		}

		return null;
	}

	public function describe(): string
	{
		if ($this === self::MAYBE) {
			return 'Maybe';
		}
		if ($this === self::YES) {
			return 'Yes';
		}
		if ($this === self::NO) {
			return 'No';
		}
		throw new ShouldNotHappenException();
	}

}
