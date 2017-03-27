# Yii2 proxy list

Generate and store proxy list from site foxtools.ru

## Installation

The preferred way to install this extension is through composer.

Either run

```php composer.phar require michaeldomo/php-proxy-list "~1.0.0"```

or add

```"require michaeldomo/php-proxy-list": "~1.0.0"```

to the require section of your composer.json file.

## Usage

```
$path = '@runtime/cache';
//cachetime in seconds, default 7200
$cachetime = 9600;
$model = new \michaeldomo\proxylist\FoxtoolsAdapter($path, $cachetime);
$list = $model->getProxyList();
```

or you can add your own adapter.
