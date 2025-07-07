<?php

namespace StopOnFirstError;

class Bar {
	public function doBar(): void {
		/** @phpstan-ignore return.void */
		return 1; // first error, ignored
	}

	public function doFooBar(): int {
		return "hello"; // first relevant error
	}
}
