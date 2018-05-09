<?php
declare(strict_types=1);

use Omote\Collections;
use PHPUnit\Framework\TestCase;

final class CollectionsTest extends TestCase
{
    public function testTakeBehavesCorrectly()
    {
        $a = [1, 2, 3, 4, 5];

        $this->assertEquals([1, 2, 3], Collections::take(3, $a));
        $this->assertEquals([1], Collections::take(1, $a));
        $this->assertEquals([], Collections::take(0, $a));
        $this->assertEquals([1, 2, 3, 4, 5], Collections::take(5, $a));
        $this->assertEquals([1, 2, 3, 4, 5], Collections::take(100, $a));
        $this->assertEquals([], Collections::take(1, []));
    }

    public function testDropBehavesCorrectly()
    {
        $a = [1, 2, 3, 4, 5];

        $this->assertEquals([4, 5], Collections::drop(3, $a));
        $this->assertEquals([], Collections::drop(5, $a));
        $this->assertEquals([1, 2, 3, 4, 5], Collections::drop(0, $a));
        $this->assertEquals([], Collections::drop(3, []));
        $this->assertEquals([], Collections::drop(100, $a));
    }

    public function testDropDoesNotMutateArray()
    {
        $a = [1, 2, 3, 4, 5];
        $b = Collections::drop(3, $a);

        $this->assertEquals([1, 2, 3, 4, 5], $a);
    }

    public function testEveryBehavesCorrectly()
    {
        $isEven = function ($x) {
            return $x % 2 === 0;
        };

        $this->assertEquals(true, Collections::every($isEven, [2, 4, 6, 8]));
        $this->assertEquals(false, Collections::every($isEven, [1, 2, 4]));
        $this->assertEquals(true, Collections::every($isEven, []));
    }

    public function testFilterBehavesCorrectly()
    {
        $a = [1, 2, 3, 4, 5];
        $b = function ($x) {
            return $x <= 3;
        };

        $this->assertEquals(array_filter($a, $b), Collections::filter($b, $a));
    }

    public function testFilterThrowsExceptionsIfGivenInvalidArgument()
    {
        $coll = [1, 2, 3, 4, 5];
        $f = "hello";

        $this->expectException(InvalidArgumentException::class);
        Collections::filter($f, $coll);
    }

    public function testFilterSuppliesKeyAndValueWithoutFlag()
    {
        $coll = ['a' => 1, 'b' => 2, 'c' => 3];
        $f = function ($value, $key) {
            return $key === 'a';
        };

        $this->assertEquals(['a' => 1], Collections::filter($f, $coll));
    }

    public function testMapBehavesCorrectly()
    {
        $coll = [1, 2, 3, 4, 5];
        $f = function ($x) {
            return $x++;
        };

        $this->assertEquals(array_map($f, $coll), Collections::map($f, $coll));

        $a = [1, 1, 1, 1, 1];
        $b = [1, 1, 1, 1, 1];
        $c = [1, 1, 1, 1, 1];

        $f = function($x, $y, $z) {
            return $x + $y + $z;
        };

        $this->assertEquals(array_map($f, $a, $b, $c), Collections::map($f, $a, $b, $c));
    }
}
