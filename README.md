ConfigKit
=============

[![Build Status](https://travis-ci.org/c9s/php-ConfigKit.png?branch=master)](https://travis-ci.org/c9s/php-ConfigKit)

ConfigKit compiles your readable YAML config file to PHP files automatically.

YAML is clean, smart, easy. but in PHP you need to parse yaml config file for 
every request. Parsing YAML costs much. how to improve it ?

php-ConfigKit is a library for config files and is designed for performance, it
parses yaml config files for the first time, then compiles the config files into php
files, so these config files can be cached in PHP, and can also be
in APC or any other cache backend system.

ConfigKit is simple and fast, all you need to do is defining your config file in
YAML format, then use ConfigKit to load the config file.

ConfigKit uses static methods because static methods are faster than object methods.

It checks if a `{config file}.php` exists, if so, then checks the file
modification time to decide whether to recompile yaml files.

When APC extension is enabled, PHP source code can be cached in APC, so when 
you require the pure php source file, it will be faster then reparsing it from yaml.

The generated config PHP file is like below:

```php
<?php return array (
      'ApplicationName' => 'Phifty',
      'ApplicationID' => 'phifty',
      'ApplicationUUID' => '9fc933c0-70f9-11e1-9095-3c07541dfc0c',
      'Domain' => 'phifty.dev',
```

## Installation

Composer:

```json
{
    "require": { 
        "corneltek/configkit": "1.3.*"
    }
}
```

```sh
$ pear channel-discover pear.corneltek.com
$ pear install corneltek/ConfigKit
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
```

