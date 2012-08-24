<?php
use ConfigKit\ConfigCompiler;

class ConfigCompilerTest extends PHPUnit_Framework_TestCase
{
    function testCompile()
    {
        $compiledFile = ConfigCompiler::compile('tests/ConfigKit/data/framework.yml');
        ok($compiledFile);
        is('tests/ConfigKit/data/framework.php', $compiledFile);
    }

    function testLoad()
    {
        $config = ConfigCompiler::load('tests/ConfigKit/data/framework.yml');
        ok($config);
        is('Phifty',$config['ApplicationName']);
    }


    /**
     * @depends testLoad
     */
    function testUnlink()
    {
        ConfigCompiler::unlink('tests/ConfigKit/data/framework.yml');
    }
}

