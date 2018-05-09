omote
=====
Omote is an interface to PHP's core library functions that aims to make them slightly more consistent and easier to use.  It is heavily inspired by Clojure and uses much of Clojure's semantics and syntax.

But why?
--------
PHP, as a language that has modernized itself rapidly while maintaining backwards compatibility with two decades of releases, is oftentimes inconsistent, clunky, or downright confusing.

```php
// array_filter expects the collection to be filtered, then the callback...
array_filter([1, 2, 3, 4, 5], function ($x) {
  return $x > 3;
});

// ... but array_map's arguments are the other way around:
array_map(function ($x) {
  return $x + 1;
}, [1, 2, 3, 4, 5]);

// This code (an easy mistake to make since functions can be passed by name)
// will produce a warning and return null:
array_filter([1, 2, 3, 4, 5], "a function that does not exist");

// But this code will work just fine, and filter out any entries that are loosely-equal to FALSE:
array_filter([1, 2, 3, 4, 5]);

// Finally, mapping over associative arrays can be confusing.
// This code will (in PHP7) raise an ArgumentCountError:
$map = ['a' => 1, 'b' => 2, 'c' => 3];
array_filter($map, function ($value, $key) {
  return $value == 1 || $key == 'c';
});

// In order to get this to work, one must provide a third flag argument.
$map = ['a' => 1, 'b' => 2, 'c' => 3];
array_filter($map, function ($value, $key) {
  return $value == 1 || $key == 'c';
}, ARRAY_FILTER_USE_BOTH);
```

The goal of Omote is to provide access to PHP's builtins as if there were no concerns about backwards-compatibility.  The versions of the stdlib found in Omote are:
- consistent: closely related functions should have similar names, namespaces, and type signatures
- explicit: implicit behaviors should be avoided where possible, and mistakes should be obvious via type-checking and exceptions over errors/warnings
- simpler: functions should behave in a logical way without the use of option maps or flags

Additionally, Omote borrows a number of utility functions from Clojure that are missing from PHP and useful in many situations:

```php
use Omote\Collections;

Collections::take(3, [1, 2, 3, 4, 5]);
# => [1, 2, 3]

Collections::drop(3, [1, 2, 3, 4, 5]);
# => [4, 5]

$isEven = function ($x) {
  return $x % 2 === 0;
};

Collections::every($isEven, [2, 4, 6, 8]);
# => true
Collections::every($isEven, [1, 2, 4]);
# => false
```

Should I use this?
------------------
Absolutely not.

License
-------
Distributed under the MIT public license, Copyright © 2018 Ray Ashman Jr.
