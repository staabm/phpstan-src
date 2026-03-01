<?php

enum DescriptionKeyword: string
{
	case CLOSE = 'close';
	case CLOSES = 'closes';
	case CLOSED = 'closed';
	case FIX = 'fix';
	case FIXES = 'fixes';
	case FIXED = 'fixed';
	case RESOLVE = 'resolve';
	case RESOLVES = 'resolves';
	case RESOLVED = 'resolved';
}

final class KeywordsParser
{
	/**
	 * @return list<IssueReference>
	 */
	static public function findReferencedIssues(string $prUrl, string $bodyText): array
	{
		$keywords = [];
		foreach(DescriptionKeyword::cases() as $case) {
			$keywords[] = $case->value;
		}

		// find "closes phpstan/phpstan#10169"
		$matches = [];
		preg_match_all('{('. implode('|', $keywords) .')\s+([a-z]+/[a-z]+#[0-9]+)}i', $bodyText, $matches);

		$issues = [];
		foreach($matches[0] as $i => $match) {
			$issues[] = new IssueReference(
				DescriptionKeyword::from(strtolower($matches[1][$i])),
				$matches[2][$i]
			);
		}

		// find urls "closes https://github.com/phpstan/phpstan/issues/8366"
		$matches = [];
		preg_match_all('{('. implode('|', $keywords) .')\s+https://github.com/([a-z]+/[a-z]+)/issues/([0-9]+)}i', $bodyText, $matches);

		foreach($matches[0] as $i => $match) {
			$issues[] = new IssueReference(
				DescriptionKeyword::from(strtolower($matches[1][$i])),
				$matches[2][$i].'#'.$matches[3][$i]
			);
		}

		// find relative references "closes #597"
		$matches = [];
		preg_match_all('{('. implode('|', $keywords) .')\s+(#[0-9]+)}i', $bodyText, $matches);

		$urlParser = new PullRequestUrlParser($prUrl);
		foreach($matches[0] as $i => $match) {
			$issues[] = new IssueReference(
				DescriptionKeyword::from(strtolower($matches[1][$i])),
				$urlParser->getRepoIdentifier().$matches[2][$i]
			);
		}

		return $issues;	}
}




readonly class IssueReference
{
	public function __construct(
		public DescriptionKeyword $keyword,
		public string $issueRef // e.g. "phpstan/phpstan#10169"
	)
	{
	}

	public function getUrl(): string
	{
		return 'https://github.com/' . $this->issueRef;
	}

	public function getRepoOwner(): string {
		$prefix = explode('#', $this->issueRef)[0];

		return explode('/', $prefix)[0];
	}

	public function getRepoName(): string {
		$prefix = explode('#', $this->issueRef)[0];

		return explode('/', $prefix)[1];
	}

	public function getRepoIdentifier(): string {
		$prefix = explode('#', $this->issueRef)[0];

		return $prefix;
	}

	public function getNumber(): int {
		return (int) explode('#', $this->issueRef)[1];
	}
}


class PullRequestUrlParser {
	public function __construct(
		private string $url
	) {
	}

	public function getRepoIdentifier(): string {
		$path = parse_url($this->url, PHP_URL_PATH);
		if (!is_string($path)) {
			throw new \RuntimeException('Could not parse url: ' . $this->url);
		}
		$parts = explode('/', $path);

		return $parts[1] . '/' . $parts[2];
	}

}


function formatAuthor(string $author): string {
	if ($author === 'Markus Staab') {
		return '**Markus Staab**';
	}
	if ($author === 'Vincent Langlet') {
		return '**Vincent Langlet**';
	}

	return $author;
}
