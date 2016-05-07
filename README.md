# Debug

[![Latest Stable Version](https://poser.pugx.org/fastd/debug/v/stable)](https://packagist.org/packages/fastd/debug) [![Total Downloads](https://poser.pugx.org/fastd/debug/downloads)](https://packagist.org/packages/fastd/debug) [![Latest Unstable Version](https://poser.pugx.org/fastd/debug/v/unstable)](https://packagist.org/packages/fastd/debug) [![License](https://poser.pugx.org/fastd/debug/license)](https://packagist.org/packages/fastd/debug)

支持自定义错误页面，自定义响应格式，响应头，支持 fatal error 捕捉，日志纪录等功能。

## 要求

* PHP 7+

## Composer

```json
{
    "fastd/debug": "2.0.x-dev"
}
```

## 使用

```php
use FastD\Debug\Debug;
Debug::enable();
trigger_error('demo');
```

日志使用 `Monolog\Logger`, 完全自定义 `Monolog\Logger` 日志，直接注入到 `FastD/Debug` 对象中即可

```php
$logger = new \Monolog\Logger('test');
$stream = new \Monolog\Handler\StreamHandler(__DIR__ . '/test.log');
$stream->setFormatter(new Monolog\Formatter\LineFormatter("[%datetime%] >> %level_name%: >> %message% >> %context% >> %extra%\n"));
$logger->pushHandler($stream);

$debug = \FastD\Debug\Debug::enable(false, $logger);

throw new \FastD\Debug\Exceptions\Http\NotFoundHttpException(404);
```

自定义错误页面内容

```php
\FastD\Debug\Debug::enable();

class PageException extends \FastD\Debug\Exceptions\Http\HttpException
{
    /**
     * Returns response content.
     *
     * @return string
     */
    public function getContent()
    {
        return file_get_contents(__DIR__ . '/demo.html');
    }
}

throw new PageException('custom');
```

自定义错误页面使用自定义异常类一样的做法，可以更加灵活处理。

### 自定义异常

自定义异常只需要继承 `FastD\Debug\Exceptions\Http\HttpException` 对象，对类中的 `getStatusCode`, `getHeaders`, `getContent` 三个方法重写即可

可参考例子:

自定义响应格式: [json.php](examples/json.php)
自定义页面: [page.php](examples/page.php)

## Testing

```
cd path/to/debug
composer install
phpunit
```

## License MIT
