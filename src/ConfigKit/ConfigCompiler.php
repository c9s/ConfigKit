<?php
/**
 *  use ConfigKit\ConfigCompiler;
 *  $compiled = ConfigCompiler::compile('source_file.yml' , 'compiled_file.php');
 *  $config = ConfigCompiler::load('source_file.yml', 'compiled_file.php');
 *  $config = ConfigCompiler::load('source_file.yml');
 */
namespace ConfigKit;
use Exception;

class ConfigFileException extends Exception {  }

class ConfigCompiler
{
    static $statCheck = true;

    public static function _compile_file($sourceFile,$compiledFile) 
    {
        $content = file_get_contents($sourceFile);
        if( strpos($content,'---') === 0 ) {
            $config = \yaml_parse($content);
        } elseif(strpos($content,'{') === 0 ) {
            $config = \json_decode($content);
        } elseif(strpos($content,'<?php') === 0 ) {
            $config = require $sourceFile;
        } else {
            throw new ConfigFileException('Unknown file format.');
        }
        self::write_config($compiledFile,$config);

        // inline apc cache
        if (extension_loaded('apc')) {
            apc_store($sourceFile . filemtime($sourceFile) , $config);
        }
        return $config;
    }

    public static function write_config($compiledFile, $config)
    {
        if ( file_put_contents( $compiledFile , '<?php return ' . var_export($config,true) . ';' ) === false ) {
            throw new ConfigFileException("Can not write config file.");
        }
    }

    public static function compile($sourceFile,$compiledFile = null) { 
        if ( ! $compiledFile ) {
            // to .php
            $compiledFile = \futil_replace_extension($sourceFile, 'php');
        }
        if( ! file_exists($compiledFile)
            || (file_exists($compiledFile) 
                && \futil_mtime_compare($sourceFile, $compiledFile) > 0 )
            ) {
            self::_compile_file($sourceFile,$compiledFile);
        }
        return $compiledFile;
    }

    public static function load($sourceFile,$compiledFile = null) 
    {
        $cacheKey = $sourceFile . filemtime($sourceFile);
        if (extension_loaded('apc')) {
            if ( $cache = apc_fetch($cacheKey) ) {
                return $cache;
            }
        }

        if ( ! self::$statCheck ) {
            if ( file_exists($compiledFile) ) {
                return require $compiledFile;
            }
        }

        $file = self::compile($sourceFile,$compiledFile);
        return require $file;
    }

    public static function unlink($sourceFile,$compiledFile = null) {
        $file = self::compile($sourceFile,$compiledFile);
        return unlink($file);
    }
}
