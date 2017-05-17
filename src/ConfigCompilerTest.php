<?php

namespace ConfigKit;

class ConfigCompilerTest extends \PHPUnit\Framework\TestCase
{
    public function testParsePhpFormatConfig()
    {
        $config = ConfigCompiler::parse('tests/fixture/php_config.php');
        $this->assertEquals([
            'a' => 123,
        ], $config);
    }

    public function testParseYamlFormatWithoutDashes()
    {
        $config = ConfigCompiler::parse('tests/fixture/yaml_without_dashes.yml');
        $this->assertEquals([
            'ApplicationName' => 'Phifty',
            'ApplicationID' => 'phifty',
            'ApplicationUUID' => '9fc933c0-70f9-11e1-9095-3c07541dfc0c',
        ], $config);
    }

    public function testCompile()
    {
        $compiledFile = ConfigCompiler::compile('tests/fixture/phifty.yml');
        $this->assertEquals('tests/fixture/phifty.php', $compiledFile);
    }

    public function testWriteYaml()
    {
        $config = array( "foo" => 1, "bar" => 2 );
        $outFile = "tests/.test.yml";
        ok(ConfigCompiler::write_yaml($outFile, $config));
        path_ok($outFile);
        $compiledFile = ConfigCompiler::compile($outFile);
        path_ok($compiledFile);
        unlink($outFile);
        unlink($compiledFile);
    }

    public function testLoad()
    {
        $config = ConfigCompiler::load('tests/fixture/phifty.yml');
        ok($config);
        is('Phifty', $config['ApplicationName']);
    }


    /**
     * @depends testLoad
     */
    public function testUnlink()
    {
        $ret = ConfigCompiler::unlink('tests/fixture/phifty.yml');
        $this->assertTrue($ret);
    }
}
