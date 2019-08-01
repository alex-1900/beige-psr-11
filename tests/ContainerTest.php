<?php

use Beige\Psr11\Interfaces\DefinitionCollectionInterface;
use Beige\Psr11\Container;
use Psr\Container\ContainerInterface;
use Beige\Psr11\Exception\NotFoundException;
use Beige\Psr11\DefinitionCollection;

require_once __DIR__ . '/AbstractTest.php';

class ContainerTest extends AbstractTest
{
    public function testConstruct()
    {
        /** @var DefinitionCollectionInterface $dc */
        $dc = $this->createMock(DefinitionCollectionInterface::class);
        $instance = new Container(['a' => 1, Container::class => 2], $dc);
        $definitionCollection = $this->getProperty($instance, 'definitionCollection');
        $entries = $this->getProperty($instance, 'entries');
        $this->assertEquals($definitionCollection, $dc);
        $this->assertEquals($entries, [
            Container::class => $instance,
            ContainerInterface::class => $instance,
            'a' => 1
        ]);
    }

    public function testSet()
    {
        /** @var DefinitionCollectionInterface $dc */
        $dc = $this->createMock(DefinitionCollectionInterface::class);
        $instance = new Container([], $dc);

        $instance['a'] = 1;
        $entries = $this->getProperty($instance, 'entries');
        $this->assertEquals([
            Container::class => $instance,
            ContainerInterface::class => $instance,
            'a' => 1
        ], $entries);
    }

    public function testGet()
    {
        /** @var DefinitionCollectionInterface $dc */
        $dc = $this->createMock(DefinitionCollectionInterface::class);
        $instance = new Container(['a' => 1], $dc);

        $a = $instance['a'];
        $this->assertEquals(1, $a);
    }

    public function testGetWithDefinition()
    {
        $callback = function() {
            return 1;
        };
        $dc = new DefinitionCollection(['a' => $callback]);
        $instance = new Container([], $dc);

        $a = $instance->get('a');
        $this->assertEquals(1, $a);
        $entries = $this->getProperty($instance, 'entries');
        $this->assertEquals($entries, [
            Container::class => $instance,
            ContainerInterface::class => $instance,
            'a' => 1
        ]);
    }

    /**
     * @expectedException \Beige\Psr11\Exception\NotFoundException
     */
    public function testGetNotFoundException()
    {
        /** @var DefinitionCollectionInterface $dc */
        $dc = $this->createMock(DefinitionCollectionInterface::class);
        $instance = new Container(['a' => 1], $dc);

        $a = $instance['b'];
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetTypeException()
    {
        /** @var DefinitionCollectionInterface $dc */
        $dc = $this->createMock(DefinitionCollectionInterface::class);
        $instance = new Container(['a' => 1], $dc);

        $a = $instance[1];
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testHasTypeException()
    {
        /** @var DefinitionCollectionInterface $dc */
        $dc = $this->createMock(DefinitionCollectionInterface::class);
        $instance = new Container(['a' => 1], $dc);

        $a = isset($instance[1]);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testDeleteTypeException()
    {
        /** @var DefinitionCollectionInterface $dc */
        $dc = $this->createMock(DefinitionCollectionInterface::class);
        $instance = new Container(['a' => 1], $dc);

        $a = $instance->delete(1);
    }

    public function testHasWithDefinition()
    {
        $callback = function() {
            return 1;
        };
        $dc = new DefinitionCollection(['a' => $callback]);
        $instance = new Container([], $dc);

        $result = isset($instance['a']);
        $this->assertTrue($result);
    }

    public function testHasWithEntries()
    {
        $dc = new DefinitionCollection();
        $instance = new Container(['a' => 1], $dc);

        $result = isset($instance['a']);
        $this->assertTrue($result);
    }

    public function testHasNot()
    {
        $dc = new DefinitionCollection();
        $instance = new Container(['a' => 1], $dc);

        $result = isset($instance['b']);
        $this->assertFalse($result);
    }

    public function testDelete()
    {
        $dc = new DefinitionCollection();
        $instance = new Container(['a' => 1], $dc);

        unset($instance['a']);
        $entries = $this->getProperty($instance, 'entries');
        $this->assertEquals($entries, [
            Container::class => $instance,
            ContainerInterface::class => $instance
        ]);
    }

    /**
     * @expectedException \Beige\Psr11\Exception\NotFoundException
     */
    public function testDefinitionFalse()
    {
        $instance = new Container();
        $instance->make('a');
    }
}
