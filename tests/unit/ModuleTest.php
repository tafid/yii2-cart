<?php

/*
 * Cart module for Yii2
 *
 * @link      https://github.com/hiqdev/yii2-cart
 * @package   yii2-cart
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2016, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\yii2\cart\tests\unit;

use hiqdev\yii2\cart\Module;
use hiqdev\yii2\cart\ShoppingCart;
use Yii;
use yii\helpers\Url;
use yii\web\Application;

/**
 * Module test suite.
 */
class ModuleTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Module
     */
    protected $object;

    protected function setUp()
    {
        $application = new Application([
            'id'       => 'mock',
            'basePath' => dirname(dirname(__DIR__)),
            'modules'  => [
                'cart' => [
                    'class' => Module::class,
                ],
            ],
            'components' => [
                'urlManager' => [
                    'enablePrettyUrl' => true,
                    'showScriptName'  => false,
                ],
            ],
        ]);
        $this->object = Yii::$app->getModule('cart');
    }

    protected function tearDown()
    {
    }

    public function testGetInstance()
    {
        $module = Module::getInstance();
        $this->assertInstanceOf(Module::class, $module);
        $this->assertSame($this->object, $module);
    }

    public function testGetCart()
    {
        $this->assertInstanceOf(ShoppingCart::class, $this->object->getCart());
        $this->assertSame(Yii::$app->getModule('cart')->get('cart'), $this->object->cart);
    }

    public function testCreateUrl()
    {
        $this->assertStringEndsWith(Url::to('/cart/cart/index'),             $this->object->createUrl());
        $this->assertStringEndsWith(Url::to('/cart/cart/something'),         $this->object->createUrl('something'));
        $this->assertStringEndsWith(Url::to(['/cart/cart/order', 'a' => 2]), $this->object->createUrl(['order', 'a' => 2]));
        $this->assertStringEndsWith(Url::to(['/cart/test/order', 'a' => 2]), $this->object->createUrl(['test/order', 'a' => 2]));
    }

    protected $testString = 'a and b';

    public function testOrderButton()
    {
        $this->object->setOrderButton($this->testString);
        $this->assertSame($this->testString, $this->object->getOrderButton());
        $this->object->setOrderButton(function () { return $this->testString; });
        $this->assertSame($this->testString, $this->object->getOrderButton());
    }

    public function testPaymentMethods()
    {
        $this->object->setPaymentMethods($this->testString);
        $this->assertSame($this->testString, $this->object->getPaymentMethods());
        $this->object->setPaymentMethods(function () { return $this->testString; });
        $this->assertSame($this->testString, $this->object->getPaymentMethods());
    }
}
