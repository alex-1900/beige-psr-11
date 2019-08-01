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
use Beige\Psr11\Interfaces\DefinitionCollectionInterface;

/**
 * Definition collection.
 * 
 * @author Speed Sonic <blldxt@gmail.com>
 */
class DefinitionCollection implements DefinitionCollectionInterface, ArrayAccess
{
    /**
     * Definitions array.
     * 
     * @var array
     */
    private $definitions = [];

    /**
     * @param array $definitions
     */
    public function __construct(array $definitions = [])
    {
        $this->setDefinitions($definitions);
    }

    /**
     * Test if has the definition.
     * 
     * @param string $name
     * 
     * @return bool
     */
    public function hasDefinition(string $name)
    {
        return array_key_exists($name, $this->definitions);
    }

    /**
     * Get definition by name.
     * 
     * @param string $name
     * 
     * @return callable|null
     */
    public function getDefinition(string $name): ?callable
    {
        if (array_key_exists($name, $this->definitions)) {
            return $this->definitions[$name];
        }

        return null;
    }

    /**
     * Set a definition with name.
     * 
     * @param string $name
     * @param callable $definition
     */
    public function setDefinition(string $name, callable $definition)
    {
        $this->definitions[$name] = $definition;
    }

    /**
     * Set definitions.
     * 
     * @param array $definitions
     */
    public function setDefinitions(array $definitions)
    {
        foreach ($definitions as $name => $definition) {
            $this->setDefinition($name, $definition);
        }
    }

    /**
     * Delete a definition.
     * 
     * @param string $name
     */
    public function deleteDefinition(string $name)
    {
        if ($this->hasDefinition($name)) {
            unset($this->definitions[$name]);
        }
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
        return $this->hasDefinition($offset);
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
        return $this->getDefinition($offset);
    }

    /**
     * Assign a value to the specified offset.
     * 
     * @param string $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        $this->setDefinition($offset, $value);
    }

    /**
     * Unset an offset.
     * 
     * @param string $offset
     */
    public function offsetUnset($offset)
    {
        $this->deleteDefinition($offset);
    }
}
