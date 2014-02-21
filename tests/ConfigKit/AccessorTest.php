<?php

class AccessorTest extends PHPUnit_Framework_TestCase
{
    public function testConfigConstructor() {
        $config = new ConfigKit\Accessor(array(
            'Product' => array(
                'image' => array(
                    'width' => 100,
                    'height' => 200,
                ),
                'zoom_image' => 1,
            ),
        ));
        ok($config);
        return $config;
    }

    /**
     * @depends testConfigConstructor
     */
    public function testIterator($config) {
        $accessor = $config->lookup('Product.image');

        $cnt = 0;
        foreach( $accessor as $key => $value ) {
            ok( $key , 'key' );
            ok( $value, 'value' );
            $cnt++;
        }
        is(2, $cnt);
    }

    /**
     * @depends testConfigConstructor
     */
    public function testArrayAccessInterface($config) {
        $accessor = $config->lookup('Product.image');
        ok( isset($accessor['width']) );
        ok( isset($accessor['height']) );

        ok( ! isset($accessor['foo']) );
        ok( ! isset($accessor['bar']) );

        ok( $accessor['height'] );
        ok( $accessor['width'] );
    }


    /**
     * @depends testConfigConstructor
     */
    public function testConfigLookupForScalarValue($config)
    {
        is( 100, $config->lookup('Product.image.width') );
        is( 200, $config->lookup('Product.image.height') );

        // with cache
        is( 100, $config->lookup('Product.image.width') );
        is( 200, $config->lookup('Product.image.height') );
    }

    /**
     * @depends testConfigConstructor
     */
    public function testConfigLookupForNonArrayParent($config)
    {
        is( 1, $config->lookup('Product.zoom_image') );
        is( null, $config->lookup('Product.zoom_image.width') );
    }

    /**
     * @depends testConfigConstructor
     */
    public function testReturnAccessor($config)
    {
        $accessor = $config->lookup('Product');
        ok($accessor);
        class_ok('ConfigKit\\Accessor', $accessor);

        $accessor = $config->lookup('Product.image');
        ok($accessor);
        class_ok('ConfigKit\\Accessor', $accessor);
        is( 100,  $accessor['width'] );
        is( 200,  $accessor['height'] );
        is( 100,  $accessor->width );
        is( 200,  $accessor->height );
    }
}

