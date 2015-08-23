ConfigKit
=============

[![Build Status](https://travis-ci.org/c9s/ConfigKit.svg?branch=master)](https://travis-ci.org/c9s/ConfigKit)
[![Latest Stable Version](https://poser.pugx.org/corneltek/configkit/v/stable)](https://packagist.org/packages/corneltek/configkit) 
[![Total Downloads](https://poser.pugx.org/corneltek/configkit/downloads)](https://packagist.org/packages/corneltek/configkit) 
[![Latest Unstable Version](https://poser.pugx.org/corneltek/configkit/v/unstable)](https://packagist.org/packages/corneltek/configkit) 
[![License](https://poser.pugx.org/corneltek/configkit/license)](https://packagist.org/packages/corneltek/configkit)

ConfigKit compiles your readable YAML config file to PHP files automatically.

YAML format is clean, smart, easy. but in PHP, you have to parse yaml config file in 
every request. Parsing YAML costs too much CPU time. How to improve it ?

php-ConfigKit is a library for config files and which is designed for performance, it
parses yaml config files for the first time, then compiles the config files into php
files, so these config files can be cached in PHP, and it can also be
in APC or any other cache backend system.

ConfigKit is simple and fast, all you have to do is defining your config file in
YAML format, then use ConfigKit to load the config file.

ConfigKit uses static methods because static methods are faster than object methods.

It checks if a `{config file}.php` exists, if so, then checks the file
modification time to decide whether to recompile yaml files.

When APC extension is enabled, PHP source code can be cached in APC, so when 
you require the pure php source file, it will be faster then reparsing it from yaml.

A generated config PHP file is like:

```php
<?php return array (
      'ApplicationName' => 'Phifty',
      'ApplicationID' => 'phifty',
      'ApplicationUUID' => '9fc933c0-70f9-11e1-9095-3c07541dfc0c',
      'Domain' => 'phifty.dev',
```


## Requirement

YAML extension


## Installation

Composer:

```json
{
    "require": { 
        "corneltek/configkit": "~1.5"
    }
}
```


## Usage

### ConfigCompiler

To compile a yaml config file and get the config stash:

```php
$config = ConfigCompiler::load('tests/ConfigKit/data/framework.yml');
print_r( $config );
```

To disable stats check (mtime checking):

```php
ConfigCompiler::$statCheck = false;
$config = ConfigCompiler::load('tests/ConfigKit/data/framework.yml');
```


If you want to compile the config file manually, you may call the `compile` static function to do that:

```php
$compiledFile = ConfigCompiler::compile('config/framework.yml');
```

You may also specify the compiled filename in the arguments:

```php
$compiledFile = ConfigCompiler::compile('config/framework.yml', 'config/framework.php');
```

You can also override some config values during the compilation, by using the `override_compile` function:

```php
$compiledFile = ConfigCompiler::override_compile('config/framework.yml', array( 
    'something_should_not_be_in_config_file' => 123123123,
    'something_should_not_be_in_git' => 123123123,
    'something_generated_in_the_runtime' => random(),
));
```

To test if a compiled file needs to be updated (re-compile):

```php
if ( ConfigCompiler::test('config/framework.yml','config/framework.php')) ) {
    ConfigCompiler::compile(....);
} else {
    // already up to date.
}
```



### ConfigLoader

You can manage multiple config files with ConfigLoader 

```php
$loader = new ConfigLoader;
$loader->load( 'framework', 'config/framework.yml' );
$loader->load( 'database', 'config/database.yml' );
```

To get config stash

```php
$paths = $loader->get('framework','web.paths');
$templates = $loader->get('framework','web.templates');
foreach( $paths as $path ) {
    echo $path, "\n";
}
```

To write all config stash into one cache file:

```php
$loader->writeStashes('all.php');
```

To load all config stash back:

```php
$loader->loadStashes('all.php');
```

### Generate AppConfigLoader class


```php
$loader = new ConfigKit\ConfigLoader;
$loader->load('database','tests/data/database.yml');
$loader->load('framework','tests/data/framework.yml');
$appClass = $loader->generateAppClass('MyApp\\AppConfigLoader');
$path = $appClass->generatePsr4ClassUnder('tests');
require_once($path); 
$appConfigLoader = new \MyApp\AppConfigLoader;
```



