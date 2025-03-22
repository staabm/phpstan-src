<?php declare(strict_types = 1);

namespace PHPStan\Type\Regex;

use function array_key_exists;

final class RegexAlternation
{

	public function __construct(
		private readonly int $alternationId,
		private readonly int $alternationsCount,
		private readonly array $groupCombinations = [],
	)
	{
	}

	public function getId(): int
	{
		return $this->alternationId;
	}

	public function pushGroup(int $combinationIndex, RegexCapturingGroup $group): self
	{
		$groupCombinations = $this->groupCombinations;
		if (!array_key_exists($combinationIndex, $groupCombinations)) {
			$groupCombinations[$combinationIndex] = [];
		}

		$groupCombinations[$combinationIndex][] = $group->getId();
		return new self(
			$this->alternationId,
			$this->alternationsCount,
			$groupCombinations,
		);
	}

	public function getAlternationsCount(): int
	{
		return $this->alternationsCount;
	}

	/**
	 * @return array<int, list<int>>
	 */
	public function getGroupCombinations(): array
	{
		return $this->groupCombinations;
	}

}
