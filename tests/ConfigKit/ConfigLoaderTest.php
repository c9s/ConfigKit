<?php

class ConfigLoaderTest extends PHPUnit_Framework_TestCase
{
    function test()
    {
        $loader = new ConfigKit\ConfigLoader;
        $loader->load('database','tests/ConfigKit/data/database.yml');

        $ds = $loader->database->data_sources->default;
        ok($ds);

        is('root',$ds->user);
        is('123123',$ds->pass);

        $loader->merge('database','tests/ConfigKit/data/site_database.yml');
        $ds = $loader->database->data_sources->default;

        is('testing',$ds->user);
        is('testing',$ds->pass);

        $config1 = $loader->getStashes();
        ok($config1);

        $loader->writeStashes('tests/stashes.php');
        $config = $loader->loadStashes('tests/stashes.php');
        ok($config);
        $this->assertSame($config,$config1);
    }
}

