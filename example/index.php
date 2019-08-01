<?php
use Beige\Psr11\Container;
use Beige\Psr11\DefinitionCollection;

require __DIR__ . '/../vendor/autoload.php';

$definitionCollection = new DefinitionCollection();

$definitionCollection->setDefinition('aa', function($c) {
    return 2222;
});

$container = new Container([], $definitionCollection);

unset($container['aa']);

echo $container['aa'];
