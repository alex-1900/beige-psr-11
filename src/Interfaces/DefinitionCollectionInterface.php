<?php

namespace Beige\Psr11\Interfaces;

interface DefinitionCollectionInterface
{
    /**
     * Get definition by name and return null if not exist.
     * 
     * @param string $name
     * 
     * @return callable
     */
    public function getDefinition(string $name): ?callable;

    /**
     * Delete a definition.
     * 
     * @param string $name
     */
    public function deleteDefinition(string $name);
}
