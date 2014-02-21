<?php

class AccessorTest extends PHPUnit_Framework_TestCase
{
    public function testConfigConstructor() {
        $config = new ConfigKit\Accessor([
            'Product' => [
                'image' => [
                    'width' => 100,
                    'height' => 200,
                ],
                'zoom_image' => 1,
            ],
        ]);
        ok($config);
        return $config;
    }


    /**
     * @depends testConfigConstructor
     */
    public function testConfigLookupForScalarValue($config)
    {
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
        $accessor = $config->lookup('Product.image');
        ok($accessor);
        class_ok('ConfigKit\\Accessor', $accessor);
        is( 100,  $accessor['width'] );
        is( 200,  $accessor['height'] );
        is( 100,  $accessor->width );
        is( 200,  $accessor->height );
    }
}

