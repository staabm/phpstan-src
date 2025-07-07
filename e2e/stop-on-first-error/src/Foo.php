<?php

namespace StopOnFirstError;

class Foo {
	public function doBar(): void {
		return 1; // error
	}

	public function doFooBar(): int {
		return "hello"; // error
	}
}
