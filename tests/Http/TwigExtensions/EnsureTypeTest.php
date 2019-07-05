<?php

declare(strict_types=1);

namespace BuzzingPixel\Tests\Http\TwigExtensions;

use App\Http\TwigExtensions\EnsureType;
use App\Noop;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;
use Twig\TwigFunction;

class EnsureTypeTest extends TestCase
{
    /** @var EnsureType */
    private $obj;

    protected function setUp() : void
    {
        $this->obj = new EnsureType();
    }

    public function testGetFunctions() : void
    {
        $functions = $this->obj->getFunctions();

        self::assertIsArray($functions);

        self::assertCount(1, $functions);

        $function = $functions[0];

        self::assertInstanceOf(TwigFunction::class, $function);

        self::assertSame('ensureType', $function->getName());

        $callable = $function->getCallable();

        self::assertInstanceOf(EnsureType::class, $callable[0]);

        self::assertSame('__invoke', $callable[1]);
    }

    public function testEnsureTypeInteger() : void
    {
        $integer = 1;
        $float   = 1.1;

        $this->obj->__invoke($integer, 'int');
        $this->obj->__invoke($integer, 'integer');

        $exception = null;

        try {
            $this->obj->__invoke($float, 'int');
        } catch (InvalidArgumentException $e) {
            $exception = $e;
        }

        self::assertInstanceOf(InvalidArgumentException::class, $exception);

        $exception = null;

        try {
            $this->obj->__invoke($float, 'integer');
        } catch (InvalidArgumentException $e) {
            $exception = $e;
        }

        self::assertInstanceOf(InvalidArgumentException::class, $exception);
    }

    public function testEnsureTypeBoolean() : void
    {
        $boolean = true;
        $float   = 1.1;

        $this->obj->__invoke($boolean, 'bool');
        $this->obj->__invoke($boolean, 'boolean');

        $exception = null;

        try {
            $this->obj->__invoke($float, 'bool');
        } catch (InvalidArgumentException $e) {
            $exception = $e;
        }

        self::assertInstanceOf(InvalidArgumentException::class, $exception);

        $exception = null;

        try {
            $this->obj->__invoke($float, 'boolean');
        } catch (InvalidArgumentException $e) {
            $exception = $e;
        }

        self::assertInstanceOf(InvalidArgumentException::class, $exception);
    }

    public function testEnsureTypeFloat() : void
    {
        $float = 2.2;
        $bool  = true;

        $this->obj->__invoke($float, 'float');
        $this->obj->__invoke($float, 'double');

        $exception = null;

        try {
            $this->obj->__invoke($bool, 'float');
        } catch (InvalidArgumentException $e) {
            $exception = $e;
        }

        self::assertInstanceOf(InvalidArgumentException::class, $exception);

        $exception = null;

        try {
            $this->obj->__invoke($bool, 'double');
        } catch (InvalidArgumentException $e) {
            $exception = $e;
        }

        self::assertInstanceOf(InvalidArgumentException::class, $exception);
    }

    public function testEnsureTypeString() : void
    {
        $string = 'foo';
        $bool   = true;

        $this->obj->__invoke($string, 'string');

        $exception = null;

        try {
            $this->obj->__invoke($bool, 'string');
        } catch (InvalidArgumentException $e) {
            $exception = $e;
        }

        self::assertInstanceOf(InvalidArgumentException::class, $exception);
    }

    public function testEnsureTypeArray() : void
    {
        $arr  = [];
        $bool = true;

        $this->obj->__invoke($arr, 'array');

        $exception = null;

        try {
            $this->obj->__invoke($bool, 'array');
        } catch (InvalidArgumentException $e) {
            $exception = $e;
        }

        self::assertInstanceOf(InvalidArgumentException::class, $exception);
    }

    public function testEnsureTypeObject() : void
    {
        $obj  = new stdClass();
        $bool = true;

        $this->obj->__invoke($obj, 'object');

        $exception = null;

        try {
            $this->obj->__invoke($bool, 'object');
        } catch (InvalidArgumentException $e) {
            $exception = $e;
        }

        self::assertInstanceOf(InvalidArgumentException::class, $exception);
    }

    public function testEnsureInstanceOf() : void
    {
        $noop = new Noop();
        $bool = true;

        $this->obj->__invoke($noop, Noop::class);

        $exception = null;

        try {
            $this->obj->__invoke($bool, Noop::class);
        } catch (InvalidArgumentException $e) {
            $exception = $e;
        }

        self::assertInstanceOf(InvalidArgumentException::class, $exception);
    }
}
