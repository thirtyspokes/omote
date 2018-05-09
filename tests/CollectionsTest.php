<?php
declare(strict_types=1);

use Omote\Collections;
use PHPUnit\Framework\TestCase;

final class CollectionsTest extends TestCase
{
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
}
