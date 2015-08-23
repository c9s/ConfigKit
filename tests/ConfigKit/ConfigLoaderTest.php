<?php

class ConfigLoaderTest extends PHPUnit_Framework_TestCase
{


    public function testCodeGen()
    {
        $loader = new ConfigKit\ConfigLoader;
        $loader->load('database','tests/data/database.yml');
        $loader->load('framework','tests/data/framework.yml');
        $appClass = $loader->generateAppClass('MyApp\\AppConfigLoader');

        // $path = $appClass->getPsr0ClassPath();
        $path = $appClass->generatePsr4ClassUnder('tests');

        $this->assertFileExists($path);

        require_once($path); 

        $appConfigLoader = new \MyApp\AppConfigLoader;

        $sectionConfig = $appConfigLoader->getDatabaseSection();
        $this->assertNotEmpty($sectionConfig);

        $sectionConfig = $appConfigLoader->getFrameworkSection();
        $this->assertNotEmpty($sectionConfig);


        unlink($path);
    }

    public function testConfigLoader()
    {
        $loader = new ConfigKit\ConfigLoader;
        $loader->load('database','tests/data/database.yml');
        $loader->load('framework','tests/data/framework.yml');

        $ds = $loader->database->data_sources->default;
        ok($ds);

        $this->assertEquals('root',$ds->user);
        $this->assertEquals('123123',$ds->pass);

        $loader->merge('database','tests/data/site_database.yml');
        $ds = $loader->database->data_sources->default;

        $this->assertEquals('testing',$ds->user);
        $this->assertEquals('testing',$ds->pass);

        $config1 = $loader->getStashes();
        $this->assertNotEmpty($config1);

        $loader->writeStashes('tests/stashes.php');
        $config = $loader->loadStashes('tests/stashes.php');
        $this->assertNotEmpty($config);
        $this->assertSame($config,$config1);
    }
}

