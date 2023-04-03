<?php

namespace GenericPdoStatement;

use PDO;
use PDOStatement;
use function PHPStan\Testing\assertType;

class Foo {
	public function fetch(PDO $pdo) {
		/** @var PDOStatement<array{email: string, id: int}> $stmt */
		$stmt = $pdo->query('SELECT email, id FROM mytable', PDO::FETCH_ASSOC);

		assertType('PDOStatement<array{email: string, id: int}>', $stmt);

		foreach ($stmt as $row) {
			assertType('int', $row['id']);
			assertType('string', $row['email']);
		}
	}
}
