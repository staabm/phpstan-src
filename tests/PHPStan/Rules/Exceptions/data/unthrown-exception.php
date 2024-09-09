<?php

namespace UnthrownException;

class Foo
{

	public function doFoo(): void
	{
		try {
			$foo = 1;
		} catch (\Throwable $e) {
			// pass
		}
	}

	public function doBar(): void
	{
		try {
			$foo = 1;
		} catch (\Exception $e) {
			// pass
		}
	}

	/** @throws \InvalidArgumentException */
	public function throwIae(): void
	{

	}

	public function doBaz(): void
	{
		try {
			$this->throwIae();
		} catch (\InvalidArgumentException $e) {

		} catch (\Exception $e) {
			// dead
		} catch (\Throwable $e) {
			// not dead
		}
	}

	public function doLorem(): void
	{
		try {
			$this->throwIae();
		} catch (\RuntimeException $e) {
 			// dead
		} catch (\Throwable $e) {

		}
	}

	public function doIpsum(): void
	{
		try {
			$this->throwIae();
		} catch (\Throwable $e) {

		}
	}

	public function doDolor(): void
	{
		try {
			throw new \InvalidArgumentException();
		} catch (\InvalidArgumentException $e) {

		} catch (\Throwable $e) {

		}
	}

	public function doSit(): void
	{
		try {
			try {
				\ThrowPoints\Helpers\maybeThrows();
			} catch (\InvalidArgumentException $e) {

			}
		} catch (\InvalidArgumentException $e) {

		}
	}

	/**
	 * @throws \InvalidArgumentException
	 * @throws \DomainException
	 */
	public function doAmet()
	{

	}

	public function doAmet1()
	{
		try {
			$this->doAmet();
		} catch (\InvalidArgumentException $e) {

		} catch (\DomainException $e) {

		} catch (\Throwable $e) {
			// not dead
		}
	}

	public function doAmet2()
	{
		try {
			throw new \InvalidArgumentException();
		} catch (\InvalidArgumentException $e) {

		} catch (\DomainException $e) {
			// dead
		} catch (\Throwable $e) {
			// dead
		}
	}

	public function doConsecteur()
	{
		try {
			if (false) {

			} elseif ($this->doAmet()) {

			}
		} catch (\InvalidArgumentException $e) {

		}
	}

}

class InlineThrows
{

	public function doFoo()
	{
		try {
			/** @throws \InvalidArgumentException */
			echo 1;
		} catch (\InvalidArgumentException $e) {

		}
	}

	public function doBar()
	{
		try {
			/** @throws \InvalidArgumentException */
			$i = 1;
		} catch (\InvalidArgumentException $e) {

		}
	}

}

class TestDateTime
{

	public function doFoo(): void
	{
		try {
			new \DateTime();
		} catch (\Exception $e) {

		}
	}

	public function doBar(): void
	{
		try {
			new \DateTime('now');
		} catch (\Exception $e) {

		}
	}

	public function doBaz(string $s): void
	{
		try {
			new \DateTime($s);
		} catch (\Exception $e) {

		}
	}

	/**
	 * @phpstan-param 'now'|class-string $s
	 */
	public function doSuperBaz(string $s): void
	{
		try {
			new \DateTime($s);
		} catch (\Exception $e) {

		}
	}

}

class TestDateInterval
{

	public function doFoo(): void
	{
		try {
			new \DateInterval('invalid format');
		} catch (\Exception $e) {

		}
	}

	public function doBar(): void
	{
		try {
			new \DateInterval('P10D');
		} catch (\Exception $e) {

		}
	}

	public function doBaz(string $s): void
	{
		try {
			new \DateInterval($s);
		} catch (\Exception $e) {

		}
	}

	/**
	 * @phpstan-param 'P10D'|class-string $s
	 */
	public function doSuperBaz(string $s): void
	{
		try {
			new \DateInterval($s);
		} catch (\Exception $e) {

		}
	}

}

class TestIntdiv
{

	public function doFoo(): void
	{
		try {
			intdiv(1, 1);
			intdiv(1, -1);
		} catch (\ArithmeticError $e) {

		}
		try {
			intdiv(PHP_INT_MIN, -1);
		} catch (\ArithmeticError $e) {

		}
		try {
			intdiv(1, 0);
		} catch (\ArithmeticError $e) {

		}
	}

	public function doBar(int $int): void
	{
		try {
			intdiv($int, 1);
		} catch (\ArithmeticError $e) {

		}
		try {
			intdiv($int, -1);
		} catch (\ArithmeticError $e) {

		}
	}

	public function doBaz(int $int): void
	{
		try {
			intdiv(1, $int);
		} catch (\ArithmeticError $e) {

		}
		try {
			intdiv(PHP_INT_MIN, $int);
		} catch (\ArithmeticError $e) {

		}
	}

}

class TestSimpleXMLElement
{

	public function doFoo(): void
	{
		try {
			new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><root></root>');
		} catch (\Exception $e) {

		}
	}

	public function doBar(): void
	{
		try {
			new \SimpleXMLElement('foo');
		} catch (\Exception $e) {

		}
	}

	public function doBaz(string $string): void
	{
		try {
			new \SimpleXMLElement($string);
		} catch (\Exception $e) {

		}
	}

}

class TestReflectionClass
{

	public function doFoo(): void
	{
		try {
			new \ReflectionClass(\DateTime::class);
		} catch (\Exception $e) {

		}
	}

	public function doBar(): void
	{
		try {
			new \ReflectionClass('ThisIsNotARealClass');
		} catch (\Exception $e) {

		}
	}

	public function doBaz(string $string): void
	{
		try {
			new \ReflectionClass($string);
		} catch (\Exception $e) {

		}
	}

	/**
	 * @param \DateTime|\DateTimeImmutable|class-string<\DateTime> $rightClassOrObject
	 * @param \DateTime|\DateTimeImmutable|string $wrongClassOrObject
	 */
	public function doThing(object $foo, $rightClassOrObject, $wrongClassOrObject): void
	{
		try {
			new \ReflectionClass($foo);
		} catch (\Exception $e) {

		}
		try {
			new \ReflectionClass($rightClassOrObject);
		} catch (\Exception $e) {

		}
		try {
			new \ReflectionClass($wrongClassOrObject);
		} catch (\Exception $e) {

		}
	}
}

class TestReflectionFunction
{

	public function doFoo(): void
	{
		try {
			new \ReflectionFunction('is_string');
		} catch (\Exception $e) {

		}
	}

	public function doBar(): void
	{
		try {
			new \ReflectionFunction('foo');
		} catch (\Exception $e) {

		}
	}

	public function doBaz(string $string): void
	{
		try {
			new \ReflectionFunction($string);
		} catch (\Exception $e) {

		}
	}

}

class TestReflectionMethod
{
	/**
	 * @param class-string<\DateTimeInterface> $foo
	 */
	public function doFoo($foo): void
	{
		try {
			new \ReflectionMethod(\DateTime::class, 'format');
		} catch (\Exception $e) {

		}
		try {
			new \ReflectionMethod($foo, 'format');
		} catch (\Exception $e) {

		}
	}

	public function doBar(): void
	{
		try {
			new \ReflectionMethod('foo', 'format');
		} catch (\Exception $e) {

		}
		try {
			new \ReflectionMethod(\DateTime::class, 'foo');
		} catch (\Exception $e) {

		}
	}

	public function doBaz(string $string): void
	{
		try {
			new \ReflectionMethod($string, $string);
		} catch (\Exception $e) {

		}
		try {
			new \ReflectionMethod(\DateTime::class, $string);
		} catch (\Exception $e) {

		}
		try {
			new \ReflectionMethod($string, 'foo');
		} catch (\Exception $e) {

		}
	}

}

class TestReflectionProperty
{
	public $foo;

	public function doFoo(): void
	{
		try {
			new \ReflectionProperty(self::class, 'foo');
		} catch (\Exception $e) {

		}
	}

	public function doBar(): void
	{
		try {
			new \ReflectionProperty(self::class, 'bar');
		} catch (\Exception $e) {

		}
		try {
			new \ReflectionProperty(\DateTime::class, 'bar');
		} catch (\Exception $e) {

		}
	}

	public function doBaz(string $string): void
	{
		try {
			new \ReflectionProperty($string, $string);
		} catch (\Exception $e) {

		}
		try {
			new \ReflectionProperty(self::class, $string);
		} catch (\Exception $e) {

		}
		try {
			new \ReflectionProperty($string, 'foo');
		} catch (\Exception $e) {

		}
	}

}

class ExceptionGetMessage
{

	public function doFoo(\Exception $e)
	{
		try {
			echo $e->getMessage();
		} catch (\Exception $t) {

		}
	}

	public function doBar(string $s)
	{
		try {
			$this->{'doFoo' . $s}();
		} catch (\InvalidArgumentException $e) {

		}
	}

}

class TestCaseInsensitiveClassNames
{

	public function doFoo(): void
	{
		try {
			new \SimpleXmlElement('<?xml version="1.0" encoding="UTF-8"?><root></root>');
		} catch (\Exception $e) {

		}
	}

	public function doBar(): void
	{
		try {
			new \SimpleXmlElement('foo');
		} catch (\Exception $e) {

		}
	}

	public function doBaz(string $string): void
	{
		try {
			new \SimpleXmlElement($string);
		} catch (\Exception $e) {

		}
	}

}

/** @throws void */
function acceptCallable(callable $cb): void
{

}

/**
 * @throws void
 * @param-later-invoked-callable $cb
 */
function acceptCallableAndCallLater(callable $cb): void
{

}

class CallCallable
{

	/**
	 * @throws void
	 */
	public function doFoo(callable $cb): void
	{
		try {
			$cb();
		} catch (\Exception $e) {

		}
	}

	public function passCallableToFunction(): void
	{
		try {
			// immediately called by default
			acceptCallable(function () {
				throw new \InvalidArgumentException();
			});
		} catch (\InvalidArgumentException $e) {

		}
	}

	public function passCallableToFunction2(): void
	{
		try {
			// later called thanks to @param-later-invoked-callable
			acceptCallableAndCallLater(function () {
				throw new \InvalidArgumentException();
			});
		} catch (\InvalidArgumentException $e) {

		}
	}

	/** @throws void */
	public function acceptCallable(callable $cb): void
	{

	}

	public function passCallableToMethod(): void
	{
		try {
			// later called by default
			$this->acceptCallable(function () {
				throw new \InvalidArgumentException();
			});
		} catch (\InvalidArgumentException $e) {

		}
	}

	/**
	 * @throws void
	 * @param-immediately-invoked-callable $cb
	 */
	public function acceptAndCallCallable(callable $cb): void
	{

	}

	public function passCallableToMethod2(): void
	{
		try {
			// immediately called thanks to @param-immediately-invoked-callable
			$this->acceptAndCallCallable(function () {
				throw new \InvalidArgumentException();
			});
		} catch (\InvalidArgumentException $e) {

		}
	}

}

class ExtendsCallCallable extends CallCallable
{

	public function acceptAndCallCallable(callable $cb): void
	{

	}

	public function passCallableToMethod2(): void
	{
		try {
			// immediately called thanks to @param-immediately-invoked-callable
			$this->acceptAndCallCallable(function () {
				throw new \InvalidArgumentException();
			});
		} catch (\InvalidArgumentException $e) {

		}
	}

}

class ExtendsCallCallable2 extends CallCallable
{

	/**
	 * @param callable $cb
	 */
	public function acceptAndCallCallable(callable $cb): void
	{

	}

	public function passCallableToMethod2(): void
	{
		try {
			// immediately called thanks to @param-immediately-invoked-callable
			$this->acceptAndCallCallable(function () {
				throw new \InvalidArgumentException();
			});
		} catch (\InvalidArgumentException $e) {

		}
	}

}

class ExtendsCallCallable3 extends CallCallable
{

	/**
	 * @param callable $cb
	 * @param-later-invoked-callable $cb
	 */
	public function acceptAndCallCallable(callable $cb): void
	{

	}

	public function passCallableToMethod2(): void
	{
		try {
			// later called thanks to @param-later-invoked-callable
			$this->acceptAndCallCallable(function () {
				throw new \InvalidArgumentException();
			});
		} catch (\InvalidArgumentException $e) {

		}
	}

}

class TestIntdivWithRange
{
	/**
	 * @param int          $int
	 * @param int<min, -1> $negativeInt
	 * @param int<1, max>  $positiveInt
	 */
	public function doFoo(int $int, int $negativeInt, int $positiveInt): void
	{
		try {
			intdiv($int, $positiveInt);
			intdiv($positiveInt, $negativeInt);
			intdiv($negativeInt, $positiveInt);
			intdiv($positiveInt, $positiveInt);
		} catch (\ArithmeticError $e) {

		}
		try {
			intdiv($int, $negativeInt);
		} catch (\ArithmeticError $e) {

		}
		try {
			intdiv($negativeInt, $negativeInt);
		} catch (\ArithmeticError $e) {

		}
		try {
			intdiv($positiveInt, $int);
		} catch (\ArithmeticError $e) {

		}
		try {
			intdiv($negativeInt, $int);
		} catch (\ArithmeticError $e) {

		}
		try {
			intdiv($int, '-1,5');
		} catch (\ArithmeticError $e) {

		}
	}

}

class TestBcdiv
{

	/**
	 * @param int<-5, 5> $rangeWithZero
	 * @param int<0, max> $zeroOrMore
	 * @param int<1, max> $oneOrMore
	 */
	public function doFoo(mixed $m, string $s, int $i, int $rangeWithZero, int $zeroOrMore, int $oneOrMore): void
	{
		try {
			bcdiv(1, $i);
		} catch (\DivisionByZeroError $e) {}

		try {
			bcdiv(1, $rangeWithZero);
		} catch (\DivisionByZeroError $e) {}
		try {
			bcdiv(1, $zeroOrMore);
		} catch (\DivisionByZeroError $e) {}
		try {
			bcdiv(1, $oneOrMore);
		} catch (\DivisionByZeroError $e) {}

		try {
			bcdiv(1, $s);
		} catch (\DivisionByZeroError $e) {}
		try {
			if ($m !== '0') { // test subtractable
				bcdiv(1, $m);
			}
		} catch (\DivisionByZeroError $e) {}
	}
}
