<?php

namespace StopOnFirstError;

class Bar {
	public function doBar(): void {
		return 1; // error
	}

	public function doFooBar(): int {
		return "hello"; // error
	}
}
