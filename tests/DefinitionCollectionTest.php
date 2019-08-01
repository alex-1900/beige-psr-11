<?php

use Beige\Psr11\DefinitionCollection;

require_once __DIR__ . '/AbstractTest.php';

class DefinitionCollectionTest extends AbstractTest
{
    public function testConstruct()
    {
        $callback = function() {
            return 11;
        };
        $instance = new DefinitionCollection(['a' => $callback]);
        $definitions = $this->getProperty($instance, 'definitions');
        $this->assertEquals(['a' => $callback], $definitions);
    }

    public function testHasDefinition()
    {
        $callback = function() {
            return 11;
        };
        $instance = new DefinitionCollection(['a' => $callback]);
        $result = isset($instance['a']);
        $this->assertTrue($result);
    }

    public function testGetDefinitionHas()
    {
        $callback = function() {
            return 11;
        };
        $instance = new DefinitionCollection(['a' => $callback]);
        $result = $instance['a'];
        $this->assertEquals($callback, $result);
    }

    public function testGetDefinitionNotHas()
    {
        $callback = function() {
            return 11;
        };
        $instance = new DefinitionCollection(['a' => $callback]);
        $result = $instance['b'];
        $this->assertNull($result);
    }

    public function testDelete()
    {
        $callback = function() {
            return 11;
        };
        $instance = new DefinitionCollection(['a' => $callback]);
        unset($instance['a']);
        $definitions = $this->getProperty($instance, 'definitions');
        $this->assertEquals([], $definitions);
    }

    public function testOffsetSet()
    {
        $callback = function() {
            return 11;
        };
        $instance = new DefinitionCollection();
        $instance['a'] = $callback;
        $definitions = $this->getProperty($instance, 'definitions');
        $this->assertEquals(['a' => $callback], $definitions);
    }
}
