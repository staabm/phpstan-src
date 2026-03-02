<?php

// see https://cli.github.com/manual/gh_pr_list

require_once 'report-classes.php';

$author = 'staabm';
$authorHandle = '@staabm';
$fullName = 'Markus Staab';
$dateRange = '2026-02-01..2026-02-28';

exec("gh pr list --state merged --limit 500 --json number,title,author,createdAt,reviews,url,body --search 'sort:updated-desc is:pr user:phpstan author:$author created:$dateRange'", $output);
$json = implode("", $output);
$result = json_decode($json, true);

printf("## %s Pull requests authored by %s\n", count($result), $fullName);
foreach($result as $pr) {
	printf("- [PR#%s](%s) %s\n", $pr['number'], $pr['url'], $pr['title']);

	$references = KeywordsParser::findReferencedIssues($pr['url'], $pr['body']);
	foreach($references as $reference) {
		printf ("  - Fixes [issue %s](%s) \n", $reference->issueRef, $reference->getUrl());
	}
}

unset($output);
exec("gh pr list --state merged --limit 500 --json number,title,author,createdAt,reviews,url,body --search 'sort:updated-desc is:pr user:phpstan reviewed-by:$author -author:$author merged:$dateRange'", $output);
$json = implode("", $output);
$result = json_decode($json, true);

printf("## %s Pull requests reviewed by %s\n", count($result), $fullName);
foreach($result as $pr) {
	printf("- [PR#%s](%s) %s, authored by %s\n", $pr['number'], $pr['url'], $pr['title'], formatAuthor($pr['author']['name']));

	$references = KeywordsParser::findReferencedIssues($pr['url'], $pr['body']);
	foreach($references as $reference) {
		printf ("  - Fixes [issue %s](%s) \n", $reference->issueRef, $reference->getUrl());
	}
}

