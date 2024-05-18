<?php

/*
$s = '123';
preg_match('/(?P<num>\d+)/', $s, $matches);
var_dump($matches);
return;

$s = 'c';
preg_match('/(a|b)|(?:c)/', $s, $matches);
var_dump($matches); // NULL in matches!

return;
*/
require_once  __DIR__.'/vendor/autoload.php';


//$ast      = $compiler->parse('/(?P<num>\d+)/');


function test(int $count, int $expected) {
	if ($count !== $expected) {
		throw new \Exception("Expected $expected, got $count");
	}

	echo "OK\n";
}

(function () {
	$parser = new RegexCapturingGroupsParser();

	test($parser->countNonOptionalGroups('/(?n)(\d+)/'), 1); // not hoa parsable
	test($parser->countNonOptionalGroups('  /Price: (£|€)\d+/ i u'), 1);
	test($parser->countNonOptionalGroups('/Price: (?:£|€)(\d+)/'), 1);
	test($parser->countNonOptionalGroups('/Price: (£|€)(\d+)/i'), 2);
	test($parser->countNonOptionalGroups('/(a|b)|(?:c)/'), 0);
	test($parser->countNonOptionalGroups('/(foo)(bar)(baz)+/'), 3);
	test($parser->countNonOptionalGroups('/(foo)(bar)(baz)*/'), 2);
	test($parser->countNonOptionalGroups('/(foo)(bar)(baz)?/'), 2);
	test($parser->countNonOptionalGroups('/(foo)(bar)(baz){0,3}/'), 2);
	test($parser->countNonOptionalGroups('/(foo)(bar)(baz){2,3}/'), 3);
	// test($parser->countNonOptionalGroups('/(?J)(?<Foo>[a-z]+)|(?<Foo>[0-9]+)/'));
})();

class RegexCapturingGroupsParser {
	public function countNonOptionalGroups(string $regex):int {
// 1. Read the grammar.
		$grammar  = new Hoa\File\Read(__DIR__.'/conf/RegexGrammar.pp');

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
		return $this->walk($ast, 0, 0);
	}

	private function walk(\Hoa\Compiler\Llk\TreeNode $ast, int $inAlternation, int $inOptionalQuantification): int
	{
		if (
			$ast->getId() === '#capturing'
			&& !($inAlternation > 0 || $inOptionalQuantification > 0)
		) {
			return 1;
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

		$count = 0;
		foreach ($ast->getChildren() as $child) {
			$count += $this->walk($child, $inAlternation, $inOptionalQuantification);
		}

		return $count;

	}

}
