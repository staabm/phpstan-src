<?php

use PhpParser\Lexer\Emulative;
use PhpParser\Node\Expr\BinaryOp\Concat;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor;
use PhpParser\NodeVisitor\CloningVisitor;
use PhpParser\Parser\Php7;
use PhpParser\PrettyPrinter\Standard;

require_once __DIR__.'/vendor/autoload.php';

$lexer = new Emulative([
	'usedAttributes' => [
	'comments',
	'startLine', 'endLine',
	'startTokenPos', 'endTokenPos',
	],
]);

$code = file_get_contents('./tests/PHPStan/Analyser/LegacyNodeScopeResolverTest.php');

$nodeVisitor = new class extends \PhpParser\NodeVisitorAbstract {
	public function leaveNode(\PhpParser\Node $node) {
		if (! $node instanceof ClassMethod) {
			return null;
		}

		if (!str_starts_with($node->name->name, 'test')) {
			return NodeTraverser::REMOVE_NODE;
		}

		$it = $this->extractTestFileName($node);
		foreach($it as $value) {
			var_dump($value);
		}
		//var_dump($this->extractDataProviderMethodName($node));

		return null;
	}

	private function extractTestFileName(ClassMethod $node):Iterator {
		foreach($node->stmts as $stmt) {
			if ($stmt instanceof Expression) {
				if ($stmt->expr instanceof MethodCall) {
					if ($stmt->expr->name->name === 'assertTypes') {
						$args = $stmt->expr->getArgs();

						$testArg = $args[0];

						if ($testArg->value instanceof Concat) {
							yield (new Standard())->prettyPrintExpr($testArg->value);
						}
					}
				}
			}

		}
	}

	private function extractDataProviderMethodName(ClassMethod $node) {
		$docComment = $node->getDocComment();

		if ($docComment === null) {
			return;
		}

		$docString = $docComment->getText();
		if (!preg_match('/\* @dataProvider ([a-zA-Z]+)/', $docString, $matches)) {
			return;
		}

		return $matches[1];
	}
};

$parser = new Php7($lexer);
$traverser = new NodeTraverser();
$traverser->addVisitor(new CloningVisitor());

$oldStmts = $parser->parse($code);
$oldTokens = $lexer->getTokens();

$newStmts = $traverser->traverse($oldStmts);

$nodeTraverser = new NodeTraverser();
$nodeTraverser->addVisitor($nodeVisitor);

$newStmts = $traversedNodes = $nodeTraverser->traverse($newStmts);

$newCode = (new Standard())->printFormatPreserving($newStmts, $oldStmts, $oldTokens);

file_put_contents(__DIR__.'/out.php', $newCode);
