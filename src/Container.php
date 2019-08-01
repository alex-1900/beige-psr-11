<?php

/*
 * This file is part of the beige-container package.
 *
 * (c) Speed Sonic <blldxt@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Beige\Psr11;

use ArrayAccess;
use InvalidArgumentException;
use Psr\Container\ContainerInterface;
use Beige\Psr11\Exception\NotFoundException;
use Beige\Psr11\Interfaces\DefinitionCollectionInterface;

/**
 * PSR-11 container.
 * 
 * @author Speed Sonic <blldxt@gmail.com>
 */
class Container implements ContainerInterface, ArrayAccess
{
    /**
     * Entries map.
     * 
     * @var array
     */
    private $entries;

    /**
     * Definition collection.
     * 
     * @var DefinitionCollectionInterface|null
     */
    private $definitionCollection;

    /**
     * @param array $entries
     * @param DefinitionCollectionInterface|null $definitions
     */
    public function __construct(
        array $entries = [],
        DefinitionCollectionInterface $definitionCollection = null
    ) {
        $this->definitionCollection = $definitionCollection;

        foreach ($entries as $id => $value) {
            $this->set($id, $value);
        }

        $this->entries = [
            static::class => $this,
            ContainerInterface::class => $this
        ];
    }

    /**
     * Set entries with name.
     * 
     * @param string $id
     * @param mixed $value
     */
    public function set($id, $value)
    {
        if (! is_string($id)) {
            throw new InvalidArgumentException('The first parameter of '.static::class . 'get must be string.');
        }
        $this->entries[$id] = $value;
    }

    /**
     * Get entry by name.
     * 
     * @param string $id
     * 
     * @return mixed
     */
    public function get($id)
    {
        if (! is_string($id)) {
            throw new InvalidArgumentException('The first parameter of '.static::class . 'get must be string.');
        }

        if (array_key_exists($id, $this->entries)) {
            return $this->entries[$id];
        }

        $value = $this->make($id, [$this]);
        $this->entries[$id] = $value;
        return $value;
    }

    /**
     * If the container has the entry with id.
     * 
     * @param string $id
     * 
     * @return bool
     */
    public function has($id)
    {
        if (! is_string($id)) {
            throw new InvalidArgumentException('The first parameter of '.static::class . 'has must be string.');
        }

        if (array_key_exists($id, $this->entries)) {
            return true;
        }

        if ($this->getDefinition($id)) {
            return true;
        }

        return false;
    }

    public function delete($id)
    {
        if (! is_string($id)) {
            throw new InvalidArgumentException('The first parameter of '.static::class . 'delete must be string.');
        }

        if (array_key_exists($id, $this->entries)) {
            unset($this->entries[$id]);
        }

        if ($this->definitionCollection instanceof DefinitionCollectionInterface) {
            $this->definitionCollection->deleteDefinition($id);
        }
    }

    /**
     * Build a new entry by definition id and parameters.
     * 
     * @param string $id
     * @param array $parameters
     * 
     * @return mixed
     */
    public function make(string $id, array $parameters = [])
    {
        $definition = $this->getDefinition($id);
        if ($definition) {
            return $this->resolveDefinition($definition, $parameters);
        }

        throw new NotFoundException("No definition found for '$id'");
    }

    /**
     * Whether an offset exists.
     * 
     * @param string $offset
     * 
     * @return bool
     */
    public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    /**
     * Offset to retrieve.
     * 
     * @param string $offset
     * 
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * Assign a value to the specified offset.
     * 
     * @param string $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    /**
     * Unset an offset.
     * 
     * @param string $offset
     */
    public function offsetUnset($offset)
    {
        $this->delete($offset);
    }

    /**
     * Get definition if exist.
     * 
     * @param string $id
     * 
     * @return mixed|bool
     */
    private function getDefinition($id)
    {
        if ($this->definitionCollection instanceof DefinitionCollectionInterface) {
            return $this->definitionCollection->getDefinition($id);
        }
        return false;
    }

    /**
     * Resolve the definition to entry.
     * 
     * @param callable $definition
     * @param array $parameters
     * 
     * @return mixed
     */
    private function resolveDefinition(callable $definition, array $parameters = [])
    {
        return call_user_func($definition, $this, ...$parameters);
    }
}
