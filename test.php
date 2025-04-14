<?php

declare(strict_types=1);

/**
 * @template TFoo of "bim" | "boom"
 */
class Car
{
	/**
	 * @param TFoo $foo
	 * @param (TFoo is "bim" ? string : null) $bar
	 */
	public function __construct(private string $foo, private ?string $bar = null)
	{
	}

	public function foo(): string
	{
		return $this->foo;
	}

	public function bar(): ?string
	{
		return $this->bar;
	}

	/**
	 * @param TFoo $foo
	 * @param (TFoo is "bim" ? string : null) $bar
	 */
	public function dooFoo(string $x, $foo, $y, $bar = null, $z = 3) {

	}
}

function doIt() {
	/*
	$bimCar = new Car("bim"); // expected error as bar argument is null by default
	$bimCarWithNullBar = new Car("bim", null);
	$boomCar = new Car("boom");
	$bimCarWithBar = new Car("bim", "test");
	*/
}


function methodCall(Car $car) {
	$car->dooFoo("", "bim", "y", z: 15); // expected error as bar argument is null by default

	$car->dooFoo("", "bim", "y", bar: 4); // $bar expects string|null, int given
	$car->dooFoo("", "bim", "y", bar: "world"); // fine
	$car->dooFoo("", "bim", "y", bar: "world", z: 15); // fine
	$car->dooFoo("", "bim", "y", bar: null, z: 15); // fine
	$car->dooFoo("", "bim", "y", z: 15); // expected error as bar argument is null by default
	$car->dooFoo("", "boom", "y", bar: 4); // fine
	$car->dooFoo("", "boom", bar: 4, y: "y"); // $bar expects string|null, int given
	$car->dooFoo("", "boom", y: "y", z: 15); // fine
}
