<?php

namespace FileFamilyTypeSpecifying;

use function PHPStan\Testing\assertType;

class Foo {
	function fileFamilyVariants(string $file) {
		if (is_readable($file)) {
			assertType('non-empty-string', $file);
		}

		if (is_writable($file)) {
			assertType('non-empty-string', $file);
		}
		if (is_writeable($file)) {
			assertType('non-empty-string', $file);
		}

		if (is_executable($file)) {
			assertType('non-empty-string', $file);
		}

		if (is_link($file)) {
			assertType('non-empty-string', $file);
		}

		if (is_file($file)) {
			assertType('non-empty-string', $file);
		}

		if (is_dir($file)) {
			assertType('non-empty-string', $file);
		}

		if (file_exists($file)) {
			assertType('non-empty-string', $file);
		}

		if ($handle = fopen($file, 'r')) {
			assertType('non-empty-string', $file);
		}
		if ($handle = opendir($file, 'r')) {
			assertType('non-empty-string', $file);
		}

		if (file_exists($file)) {
			assertType('non-empty-string', $file);
		}

		if (fileatime($file)) {
			assertType('non-empty-string', $file);
		}
		if (filemtime($file)) {
			assertType('non-empty-string', $file);
		}
		if (filectime($file)) {
			assertType('non-empty-string', $file);
		}

		if (unlink($file)) {
			assertType('non-empty-string', $file);
		}

		assertType('string', $file);
	}

	/**
	 * @param literal-string $literalS
	 * @param non-empty-string $nonES
	 * @param numeric-string $numericS
	 * @return void
	 */
	function stringVariants($literalS, $nonES, $numericS)
	{
		if (is_readable($literalS)) {
			assertType('literal-string&non-empty-string', $literalS);
		}
		if (is_readable($nonES)) {
			assertType('non-empty-string', $nonES);
		}
		if (is_readable($numericS)) {
			assertType('non-empty-string&numeric-string', $numericS);
		}
	}
}
