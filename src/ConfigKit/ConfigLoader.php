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
     * write section config to a file.
     */
    public function writeSection($section,$file)
    {
        if( ! isset($this->stashes[$section]) ) {
            throw new Exception("$section section is not loaded.");
        }
        $config = $this->stashes[$section];
        return ConfigCompiler::write_config($file,$config);
    }

    public function writeStashes($file)
    {
        return ConfigCompiler::write_config($file,$this->stashes);
    }


    /**
     * Allow more useful getter
     */
    function __get($name)
    {
        if( isset( $this->stashes[$name] )) {
            // It must be an array.
            return new Accessor($this->stashes[$name]);
        }
    }

    function __isset($name) 
    {
        return isset($this->stashes[$name]);
    }


    /**
     * get section stash, returns stash in pure php array.
     *
     * @return array
     */
    function getSection($name)
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
    function get($section, $key = null)
    {
        /*
        if( isset( $this->getterCache[ $key ] ) ) 
            return $this->getterCache[ $key ];
         */
        $config = $this->getSection( $section );
        if( $key == null ) 
        {
            if( ! empty($config) )
                return new Accessor($config);
            return null;
        }

        if( isset($config[ $key ]) ) 
        {
            if( is_array( $config[ $key ] ) ) {
                if( empty($config[ $key ]) )
                    return null;
                return new Accessor($config[ $key ]);
            }
            return $config[ $key ];
        }

        if( false !== strchr( $key , '.' ) ) 
        {
            $parts = explode( '.' , $key );
            $ref = $config;
            while( $ref_key = array_shift( $parts ) ) {
                if( ! isset($ref[ $ref_key ]) )
                    return null;
                $ref = & $ref[ $ref_key ];
            }

            if( is_array( $ref ) ) {
                if( empty($ref) )
                    return null;
                return new Accessor($ref);
            }
            return $ref;
        }
        return null;
    }

    public function isLoaded($sectionId) {
        return isset($this->stashes[$sectionId]);
    }
}



