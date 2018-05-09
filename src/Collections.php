<?php
declare(strict_types=1);
namespace Omote;

class Collections
{
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
     * @param array $coll The collection to be filtered.
     */
    public static function filter($f, array $coll) : array
    {
        if (!is_callable($f)) {
            $type = gettype($f);
            throw new \InvalidArgumentException("Argument 1 to Collections:filter must be callable, $type given");
        }

        if (empty($coll)) {
            return [];
        }

        return array_filter($coll, $f, ARRAY_FILTER_USE_BOTH);
    }
}
