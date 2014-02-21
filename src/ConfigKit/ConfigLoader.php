<?php
namespace ConfigKit;
use ConfigKit\ConfigCompiler;

class ConfigLoader
{
    public $stashes = array();

    public $files = array();

    public function load($section,$file)
    {
        // register to files
        $this->files[ $section ] = array($file);
        return $this->stashes[ $section ] = ConfigCompiler::load($file);
    }

    /**
     * Merge config into one specific section
     *
     * @param string $section section key
     * @param string $file    config file.
     * @return array merged config array
     */
    public function merge($section,$file)
    {
        if( isset($this->stashes[$section]) ) {
            $this->files[ $section ][] = $file;
            $config = ConfigCompiler::load($file);
            return $this->stashes[$section] = array_merge( $this->stashes[$section] , $config );
        } else {
            return $this->load($section,$file);
        }
    }


    /**
     * Write section config to a file.
     *
     * @param string $section
     * @param string $file
     */
    public function writeSection($section,$file)
    {
        if( ! isset($this->stashes[$section]) ) {
            throw new Exception("$section section is not loaded.");
        }
        $config = $this->stashes[$section];
        return ConfigCompiler::write_config($file,$config);
    }


    /**
     * Write config stashes to file.
     *
     * @param string $file
     */
    public function writeStashes($file)
    {
        return ConfigCompiler::write_config($file,$this->stashes);
    }


    /**
     * Load stashes from config file directly.
     *
     * @param string $file
     */
    public function loadStashes($file)
    {
        return $this->stashes = ConfigCompiler::load($file);
    }

    public function getStashes()
    {
        return $this->stashes;
    }

    /**
     * Allow more useful getter
     */
    public function __get($name)
    {
        if( isset( $this->stashes[$name] )) {
            // It must be an array.
            return new Accessor($this->stashes[$name]);
        }
    }

    public function __isset($name) 
    {
        return isset($this->stashes[$name]);
    }


    /**
     * get section stash, returns stash in pure php array.
     *
     * @return array
     */
    public function getSection($name)
    {
        if( isset( $this->stashes[$name] )) {
            // It must be an array.
            return $this->stashes[$name];
        }
    }


    /**
     * get config from the "config key" like:
     *
     *   mail.user
     *   mail.pass
     *
     * @return array
     */
    public function get($section, $key = null)
    {
        if ( isset($this->stashes[$section]) ) {
            $config = new Accessor($this->stashes[ $section ]);
            if ( $key ) {
                return $config->lookup( $key );
            }
            return $config;
        }
    }

    /**
     * Check whether a config file is loaded into a section.
     *
     * @param string $section
     * @return bool
     */
    public function isLoaded($section) {
        return isset($this->stashes[$section]);
    }
}



