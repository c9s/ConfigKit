<?php
use ConfigKit\ConfigCompiler;

class ConfigCompilerTest extends PHPUnit_Framework_TestCase
{
    public function testCompile()
    {
        $compiledFile = ConfigCompiler::compile('tests/ConfigKit/data/framework.yml');
        ok($compiledFile);
        is('tests/ConfigKit/data/framework.php', $compiledFile);
    }

    public function testWriteYaml() {
        $config = array( "foo" => 1, "bar" => 2 );
        $outFile = "tests/.test.yml";
        ok( ConfigCompiler::write_yaml($outFile, $config) );
        path_ok($outFile);
        $compiledFile = ConfigCompiler::compile($outFile);
        path_ok($compiledFile);
        unlink($outFile);
        unlink($compiledFile);
    }

    public function testLoad()
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

