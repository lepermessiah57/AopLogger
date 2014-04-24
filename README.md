# AopLogger

AopLogger is a module for Zend Framework 2 that allows for logging via Doctrine annotations using the Go! Aop framework

## Installation

 0. While we are still testing you will need to add `"repositories": [
                                                             {
                                                                 "type":"vcs",
                                                                 "url":"https://github.com/VUHL/AopLogger.git"
                                                             }
                                                         ]` to your `composer.json`.
 1. Add ` "vu/AopLogger" : "dev-master"` to your `composer.json` and run `php composer.phar update`.
 2. Add `AopLogger` to your `config/application.config.php` file under the `modules` key.

## Configuration

AopLogger can be configured using the `AopLogger` key in your module configuration.  The following keys can be set with only the first being required.

 1. `Debug` -> when set to `true` this will enable the debug on Go! Aop
 2. `Cache` -> defaulted to null, this can be pointed at a directory for caching the interwoven aspects rather than weaving at runtime
 3. `WhiteList` -> *REQUIRED* this should point to the source directory you wish to inject the aspects
 4. `CustomAspects` -> Any additional aspects you wish to add to the framework.  This will be a list of keys which will be loaded from the service locator
 5. `Disabled` -> Defaults to false.  This allows the application to disable the module for different levels of configuration.  Example - functional testing with the module can cause some issues
### Example
```php
// config/autoload/global.php
'AopLogger' => array(
        'WhiteList' => array(__DIR__ . "/../../module/MyModule"),
        'CustomAspects' => array(
            'EchoAspect',
            'MyControllerAspect'
        )
    )
```

## Documentation

The only aspect provided at the moment is DebugMethod.  This aspect will log the entry point and exit points of any method annotated.  This aspect will use the `php://stderr` stream.

## Examples

```php
use AopLogger\Annotation\DebugMethod;
...
/**
* @DebugMethod
*/
function ..
```