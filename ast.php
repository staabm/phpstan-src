<?php

/*
$s = '123';
preg_match('/(?P<num>\d+)/', $s, $matches, PREG_UNMATCHED_AS_NULL);
var_dump($matches);
return;

$s = 'c';
preg_match('/(a|b)|(?:c)/', $s, $matches, PREG_UNMATCHED_AS_NULL);
var_dump($matches); // NULL in matches!

return;
*/
require_once  __DIR__.'/vendor/autoload.php';


//$ast      = $compiler->parse('/(?P<num>\d+)/');

$parser = new RegexCapturingGroupsParser();
var_dump($parser->parse('/Price: (?:£|€)(\d+)/'));
var_dump($parser->parse('/(a|b)|(?:c)/'));
var_dump($parser->parse('/(foo)(bar)(baz)*/'));
var_dump($parser->parse('/(foo)(bar)(baz)?/'));
var_dump($parser->parse('/(foo)(bar)(baz){0,3}/'));
var_dump($parser->parse('/(?J)(?<Foo>[a-z]+)|(?<Foo>[0-9]+)/'));

class RegexCapturingGroupsParser {
	public function parse(string $regex) {
// 1. Read the grammar.
		$grammar  = new Hoa\File\Read('hoa://Library/Regex/Grammar.pp');

// 2. Load the compiler.
		$compiler = Hoa\Compiler\Llk\Llk::load($grammar);

// 3. Lex, parse and produce the AST.
		$ast      = $compiler->parse($regex);

		echo "-------------------\n\n\n";

		var_dump($regex);

		// 4. Dump the result.
		$dump     = new Hoa\Compiler\Visitor\Dump();
		echo $dump->visit($ast);

		$groups = [];
		$this->walk($ast, $groups, 0, 0);

		return $groups;
	}

	private function walk(\Hoa\Compiler\Llk\TreeNode $ast, array &$groups, int $inAlternation, int $inOptionalQuantification)
	{
		if ($ast->getId() === '#capturing') {
			$groups[] = [$ast->getId(), 'optional' => $inAlternation > 0 || $inOptionalQuantification > 0];
		}

		if ($ast->getId() === '#alternation') {
			$inAlternation++;
		}

		if ($ast->getId() === '#quantification') {
			$lastChild = $ast->getChild($ast->getChildrenNumber() - 1);
			$value = $lastChild->getValue();

			if ($value['token'] === 'n_to_m' && str_contains($value['value'], '{0,')) {
				$inOptionalQuantification++;
			} elseif ($value['token'] === 'zero_or_one') {
				$inOptionalQuantification++;
			} elseif ($value['token'] === 'zero_or_more') {
				$inOptionalQuantification++;
			}
		}

		foreach ($ast->getChildren() as $child) {
			$this->walk($child, $groups, $inAlternation, $inOptionalQuantification);
		}

	}

}
