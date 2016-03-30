# Base value object with string only

Note: requires PHP 5.4+

```php
<?php
use GranamString\StringObject;
use GranamString\Exceptions\WrongParameterType;

$string = new StringObject(12345.678);
echo $string; // string '12345.678'

try {
  new StringObject(fopen('foo'));
} catch (WrongParameterType $stringException) {
  // Expected scalar or object with \_\_toString method on strict mode, got resource.
  die('Something get wrong: ' . $stringException->getMessage());
}
```
