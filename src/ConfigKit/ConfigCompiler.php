<?php
/**
 *  use ConfigKit\ConfigCompiler;
 *  $compiled = ConfigCompiler::compile('source_file.yml' , 'compiled_file.php');
 *  $config = ConfigCompiler::load('source_file.yml', 'compiled_file.php');
 *  $config = ConfigCompiler::load('source_file.yml');
 */
namespace ConfigKit;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Dumper;
use Exception;


global $extensionSupport;
$extensionSupport = extension_loaded('yaml');

class ConfigFileException extends Exception {  }

class ConfigCompiler
{
    static $statCheck = true;

    public static function format_supported($filename)
    {
        $extension = futil_get_extension($filename);
        return in_array($extension, array('yml','yaml','json'));
    }

    public static function parse($sourceFile) {
        $content = file_get_contents($sourceFile);
        if (strpos($content,'---') === 0) {
            global $extensionSupport;
            if ( $extensionSupport ) {
                return \yaml_parse($content);
            } else {
                return Yaml::parse($content);
            }
        } elseif (strpos($content,'{') === 0) {
            return \json_decode($content);
        } elseif (strpos($content,'<?php') === 0 ) {
            return require $sourceFile;
        }
        throw new ConfigFileException('Unknown file format.');
    }

    public static function _compile_file($sourceFile,$compiledFile, $overrideConfig = null)
    {
        $config = self::parse($sourceFile);

        if ($overrideConfig) {
            if (! is_array($overrideConfig)) {
                throw new InvalidArgumentException("overrideConfig must be an array.");
            }
            $config = array_merge($config, $overrideConfig);
        }
        self::write($compiledFile,$config);

        // inline apc cache
        if (extension_loaded('apc')) {
            apc_store($sourceFile . filemtime($sourceFile) , $config);
        }
        return $config;
    }

    /**
     * Write config array into the YAML file. using Symfony YAML component.
     */
    public static function write_yaml($yamlFile, $config) {
        $yaml = '';
        if (extension_loaded('yaml')) {
            $yaml = yaml_emit($config, YAML_UTF8_ENCODING);
        } else {
            $yaml = "---\n" . Yaml::dump($config, $inline = true, $exceptionOnInvalidType = true);
        }
        return file_put_contents($yamlFile, $yaml);
    }

    public static function write($compiledFile, $config)
    {
        if ( file_put_contents( $compiledFile , '<?php return ' . var_export($config,true) . ';' ) === false ) {
            throw new ConfigFileException("Can not write config file.");
        }
    }


    /**
     * Test if a the source file is updated, and the compiled cache file needs 
     * to be updated.
     *
     * @param path $sourceFile 
     * @param path $compiledFile
     *
     * @return bool true means compilation is needed. false means we can ignore it.
     */
    public static function test($sourceFile, $compiledFile) {
        if (file_exists($compiledFile)) {
            return \futil_mtime_compare($sourceFile, $compiledFile) > 0;
        }
        return true;
    }

    public static function compiled_filename($sourceFile) {
        return futil_replace_extension($sourceFile, 'php');
    }


    /**
     * Compile the source file to cache file.
     *
     * @param path $sourceFile 
     * @param path $compiledFile
     *
     * @return path the compiled file path
     */
    public static function compile($sourceFile, $compiledFile = null) { 
        if ( ! $compiledFile ) {
            $compiledFile = self::compiled_filename($sourceFile);
        }
        if (self::test($sourceFile, $compiledFile)) {
            self::_compile_file($sourceFile,$compiledFile);
        }
        return $compiledFile;
    }


    /**
     * override the original config and compile to cache.
     */
    public static function override_compile($sourceFile, $overrideConfig, $compiledFile = null) {
        if ( ! $compiledFile ) {
            $compiledFile = \futil_replace_extension($sourceFile, 'php');
        }
        if (self::test($sourceFile, $compiledFile)) {
            self::_compile_file($sourceFile,$compiledFile, $overrideConfig);
        }
        return $compiledFile;
    }


    public static function load($sourceFile, $compiledFile = null, $statCheck = true)
    {
        $cacheKey = $sourceFile . filemtime($sourceFile);
        if (extension_loaded('apc')) {
            if ($cache = apc_fetch($cacheKey)) {
                return $cache;
            }
        }
        if ((!$statCheck || !self::$statCheck) && ($compiledFile && file_exists($compiledFile)) ) {
            return require $compiledFile;
        }
        $file = self::compile($sourceFile, $compiledFile);
        return require $file;
    }

    public static function unlink($sourceFile,$compiledFile = null) {
        $file = self::compile($sourceFile,$compiledFile);
        return unlink($file);
    }
}
