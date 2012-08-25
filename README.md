ConfigKit
=============

use fast but readable YAML config file for your project.

YAML is clean, smart, easy. but in PHP you need parse yaml config file for 
every request, and parsing YAML costs much. then, how to improve it ?

ConfigKit is a library for config files and is designed for web frameworks, it
parses yaml config files for the first time, then compiles configs into php
source files, so configs can be cached in pure php, and can be in APC or any
other cache backend system.

ConfigKit is pretty simple, you only need to define your config file in YAML format,
then use ConfigKit to load the config file.

It checks if a `{config file}.php` exists, if so, then checks the file
modification time to decide whether to recompile yaml files.

When APC extension is enabled, PHP source code can be cached in APC, so when 
you require the pure php source file, it will be faster then reparsing it from yaml.

The generated config cache is like below:

    <?php return array (
      'ApplicationName' => 'Phifty',
      'ApplicationID' => 'phifty',
      'ApplicationUUID' => '9fc933c0-70f9-11e1-9095-3c07541dfc0c',
      'Domain' => 'phifty.dev',

## API

### ConfigCompiler

To compile a yaml config file and get the config stash:

    $config = ConfigCompiler::load('tests/ConfigKit/data/framework.yml');
    print_r( $config );

### ConfigLoader

You can manage multiple config files with ConfigLoader 

    $loader = new ConfigLoader;
    $loader->load( 'framework', 'config/framework.yml' );
    $loader->load( 'database', 'config/database.yml' );

To get config stash

    $paths = $loader->get('framework','web.paths');
    $templates = $loader->get('framework','web.templates');


