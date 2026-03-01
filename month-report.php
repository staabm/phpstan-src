<?php

// see https://cli.github.com/manual/gh_pr_list

require_once 'report-classes.php';

$author = 'staabm';
$authorHandle = '@staabm';
$fullName = 'Markus Staab';
echo "Month: February\n";

// gh pr list --repo phpstan/phpstan-src --state merged --limit 50 --json number,title,author,createdAt,reviews,url,body --search 'sort:updated-desc is:pr author:staabm created:2026-02-01..2026-02-28' > staabm-prs-feb.json
$json = file_get_contents('staabm-prs-feb.json');
$result = json_decode($json, true);

printf("## %s Pull requests authored by %s\n", count($result), $fullName);
foreach($result as $pr) {
	printf("- [PR#%s](%s) %s\n", $pr['number'], $pr['url'], $pr['title']);

	$references = KeywordsParser::findReferencedIssues($pr['url'], $pr['body']);
	foreach($references as $reference) {
		printf ("  - Fixes [issue %s](%s) \n", $reference->issueRef, $reference->getUrl());
	}
}


// gh pr list --repo phpstan/phpstan-src --state merged --limit 500 --json number,title,author,createdAt,reviews,url,body --search 'sort:updated-desc is:pr reviewed-by:staabm -author:staabm merged:2026-02-01..2026-02-28' > staabm-reviewed-prs-feb.json
$json = file_get_contents('staabm-reviewed-prs-feb.json');
$result = json_decode($json, true);

printf("## %s Pull requests reviewed by %s\n", count($result), $fullName);
foreach($result as $pr) {
	printf("- [PR#%s](%s) %s, authored by %s\n", $pr['number'], $pr['url'], $pr['title'], formatAuthor($pr['author']['name']));

	$references = KeywordsParser::findReferencedIssues($pr['url'], $pr['body']);
	foreach($references as $reference) {
		printf ("  - Fixes [issue %s](%s) \n", $reference->issueRef, $reference->getUrl());
	}
}

