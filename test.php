<?php

require 'vendor/autoload.php';

$subject = 'Aaaaaa Bbb';

preg_replace_callback_array(
	[
		'~A([a]+)~i' => function ($match) {
		var_dump($match);
			\PHPStan\Testing\assertType('list<string>', $match);
		},
		'~B([b]+)?~i' => function ($match) {
			//var_dump($match);
			\PHPStan\Testing\assertType('', $match);
		},
		'/(foo)?(bar)?(baz)?/' => function ($match) {
			//var_dump($match);
			\PHPStan\Testing\assertType('', $match);
		},
	],
	$subject
);
