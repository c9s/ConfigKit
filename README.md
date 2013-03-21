ConfigKit
=============

[![Build Status](https://travis-ci.org/c9s/php-ConfigKit.png?branch=master)](https://travis-ci.org/c9s/php-ConfigKit)

ConfigKit let you use fast but readable YAML config file for your PHP application.

YAML is clean, smart, easy. but in PHP you need to parse yaml config file for 
every request. Parsing YAML costs much. how to improve it ?

php-ConfigKit is a library for config files and is designed for web frameworks, it
parses yaml config files for the first time, then compiles config files into php
source files, so these config files can be cached in pure php, and can also be
in APC or any other cache backend system.

ConfigKit is simple, what you only need to do is defining your config file in
YAML format, then use ConfigKit to load the config file.

It checks if a `{config file}.php` exists, if so, then checks the file
modification time to decide whether to recompile yaml files.

When APC extension is enabled, PHP source code can be cached in APC, so when 
you require the pure php source file, it will be faster then reparsing it from yaml.

The generated config cache is like below:

```php
<?php return array (
      'ApplicationName' => 'Phifty',
      'ApplicationID' => 'phifty',
      'ApplicationUUID' => '9fc933c0-70f9-11e1-9095-3c07541dfc0c',
      'Domain' => 'phifty.dev',
```

## Installation

From composer:

```json
{
    "require": { 
        "corneltek/configkit": "dev-master"
    }
}
```

```sh
    pear channel-discover pear.corneltek.com
    pear install corneltek/ConfigKit
```

## Usage

### ConfigCompiler

To compile a yaml config file and get the config stash:

```php
$config = ConfigCompiler::load('tests/ConfigKit/data/framework.yml');
print_r( $config );
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

