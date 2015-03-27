<?php namespace NewUp\Tests\Configuration;

use NewUp\Configuration\ConfigurationWriter;

class ConfigurationWriterTest extends \PHPUnit_Framework_TestCase {

    protected $defaultConfigurationItems = [
        'first'  => 'first-value',
        'second' => 'second-value'
    ];

    public function testFirstReturnsFirstItemInCollection()
    {
        $w = new ConfigurationWriter($this->defaultConfigurationItems);
        $this->assertEquals('first-value', $w->first());
    }

    public function testWriterReturnsCorrectJson()
    {
        $w = new ConfigurationWriter($this->defaultConfigurationItems);
        $this->assertEquals(json_encode($this->defaultConfigurationItems), $w->toJson());
    }

}