# Base value object with string only

Note: requires PHP 5.4+

```php
<?php
use Granam\Strict\String\StringObject;
use Granam\Strict\String\Exceptions\WrongParameterType;

$string = new StringObject('12345');

// foo
echo $string;

try {
  new StringObject(12345);
} catch (WrongParameterType $stringException) {
  // Strict string has to get a string value. Integer is not a string.
  die('Something get wrong: ' . $stringException->getMessage());
}
```
