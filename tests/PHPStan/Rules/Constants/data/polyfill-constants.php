<?php

function doFoo()
{
	// polyfill with wrong value type
	if (!defined("FILTER_SANITIZE_ADD_SLASHES")) {
		define("FILTER_SANITIZE_ADD_SLASHES", false);
	}

	// polyfill with wrong integer value
	if(!defined("FILTER_SANITIZE_ADD_SLASHES")) {
		define("FILTER_SANITIZE_ADD_SLASHES",123);
	}

	// polyfill with correct value
	if(!defined("FILTER_SANITIZE_ADD_SLASHES")) {
		define("FILTER_SANITIZE_ADD_SLASHES",523);
	}

	define("SOME_OTHER_CONSTANT",false);
}

