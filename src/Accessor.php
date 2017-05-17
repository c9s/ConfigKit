<?php
namespace ConfigKit;
use ArrayAccess;
use ArrayIterator;
use IteratorAggregate;

class Accessor
    implements ArrayAccess, IteratorAggregate
{

    public $config = array();

    public $cache = array();

    public function __construct(array $config = array())
    {
        $this->config = $config;
    }

    public function getIterator() 
    {
        return new ArrayIterator($this->config);
    }
    
    public function offsetSet($name,$value)
    {
        $this->config[ $name ] = $value;
    }
    
    public function offsetExists($name)
    {
        return isset($this->config[ $name ]);
    }
    
    public function offsetGet($name)
    {
        if (isset($this->config[$name])) {
            if (is_array($this->config[$name])) {
                return new self($this->config[$name]);
            }
            return $this->config[ $name ];
        }
    }
    
    public function offsetUnset($name)
    {
        unset($this->config[$name]);
    }
    
    public function toArray()
    {
        return $this->config;
    }

    public function isEmpty()
    {
        return null === $this->config || empty( $this->config );
    }

    public function __get($name)
    {
        return $this->offsetGet($name);
    }


    /**
     * lookup config value
     *
     * @param string $key config key
     * @return mixed
     */
    public function lookup( $key )
    {
        if ( isset($this->cache[$key]) ) {
            return $this->cache[$key];
        }

        if ( isset($this->config[ $key ]) ) {
            if ( is_array( $this->config[ $key ] ) ) {
                return $this->cache[$key] = new self($this->config[ $key ]);
            }
            return $this->cache[$key] = $this->config[ $key ];
        }
        if ( strchr( $key , '.' ) !== false ) {
            $parts = explode( '.' , $key );
            $ref = $this->config;
            while ( $refKey = array_shift( $parts ) ) {
                if ( is_array($ref) && isset($ref[ $refKey ]) ) {
                    $ref = & $ref[ $refKey ];
                    continue;
                } else {
                    return null;
                }
            }
            if ( is_array($ref) ) {
                return $this->cache[$key] = new self($ref);
            }
            return $this->cache[$key] = $ref;
        }
        return null;
    }
    
}

