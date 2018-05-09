<?php
declare(strict_types=1);
namespace Omote;

class Collections
{
    /**
     * Given an integer, returns the first $n elements of $coll. If
     * there are fewer than $n elements in $coll, returns all of $coll, and if
     * $n is 0 or $coll is empty, returns an empty array.
     *
     * @param int   $n The number of elements to take.
     * @param array $coll The collection to take from.
     */
    public static function take(int $n, array $coll) : array
    {
        if ($n < 0) {
            throw new \InvalidArgumentException('Argument 1 to Collections::take cannot be less than zero');
        }

        if (empty($coll) || $n === 0) {
            return [];
        }

        if ($n >= count($coll)) {
            return $coll;
        }

        $result = [];

        for ($i = 0; $i < $n; $i++) {
            $result[] = $coll[$i];
        }

        return $result;
    }

    /**
     * Given an integer $n, returns a new collection made from dropping the first
     * $n elements of $coll.  If $coll is empty or $n is greater than the number of elements in $coll,
     * returns an empty array.  If $n is zero, returns $coll unchanged.
     *
     * @param int   $n The number of elements to drop.
     * @param array $coll The collection to drop elements from.
     */
    public static function drop(int $n, array $coll) : array
    {
        if ($n < 0) {
            throw new \InvalidArgumentException('Argument 1 to Collections::take cannot be less than zero');
        }

        if (empty($coll) || $n > count($coll)) {
            return [];
        }

        if ($n === 0) {
            return $coll;
        }

        $result = [];
        for ($i = $n; $i < count($coll); $i++) {
            $result[] = $coll[$i];
        }

        return $result;
    }

    /**
     * Given a predicate $f, returns true if every element in $coll returns
     * true when applied to $f, or if $coll is empty (see https://en.wikipedia.org/wiki/Vacuous_truth).
     * Otherwise, returns false.
     *
     * @param callable $f The predicate to test on $coll.
     * @param array    $coll The collection to test.
     */
    public static function every($f, array $coll) : bool
    {
        if (!is_callable($f)) {
            $type = gettype($f);
            throw new \InvalidArgumentException("Argument 1 to Collections::every must be callable, $type given");
        }

        if (empty($coll)) {
            return true;
        }

        foreach ($coll as $element) {
            if (!$f($element)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Given a predicate function, returns all elements of $coll for which the predicate
     * returns true.
     *
     * Differs from the core array_filter in three ways:
     * - order of arguments (in the Clojure style, function then collection)
     * - will throw an InvalidArgumentException if the first argument is not callable
     *   instead of raising a warning
     * - always uses the ARRAY_FILTER_USE_BOTH flag, meaning that when filtering an associative array,
     *   you may use a function of two arguments to get both the value and the key for each item in $coll.
     *
     * @param callable $f The filtering function to be applied to the collection.
     * @param array    $coll The collection to be filtered.
     */
    public static function filter($f, array $coll) : array
    {
        if (!is_callable($f)) {
            $type = gettype($f);
            throw new \InvalidArgumentException("Argument 1 to Collections::filter must be callable, $type given");
        }

        if (empty($coll)) {
            return [];
        }

        return array_filter($coll, $f, ARRAY_FILTER_USE_BOTH);
    }

    /**
     * Returns an array composed of the results of successively applying
     * function $f to the members of the supplied collections.  If the collection
     * arguments are not all the same length, will only consume values up to the length
     * of the shortest collection.
     *
     * The function supplied as $f should have an arity equal to the number of collections
     * supplied as $coll - i.e., the callable should accept three arguments if three collections
     * are to be mapped over.
     *
     * @param callable $f The function to apply to the collections.
     * @param array    $coll,... The collections to be mapped over.
     */
    public static function map($f, array ...$coll) : array
    {
        if (!is_callable($f)) {
            $type = gettype($f);
            throw new \InvalidArgumentException("Argument 1 to Collections::map must be callable, $type given");
        }

        if (empty($coll)) {
            return [];
        }

        // map should only consume as many items from the source arrays
        // as are present in the shortest member.
        $inputs = [];
        $shortest = count($coll[0]);

        foreach ($coll as $element) {
            if (count($element) < $shortest) {
                $shortest = count($element);
            }
        }

        foreach ($coll as $element) {
            $inputs[] = self::take($shortest, $element);
        }

        $args = [$f];
        $args = array_merge($args, $inputs);
        return call_user_func_array("array_map", $args);
    }
}
