# Beige PSR-11

[![GitHub license](https://img.shields.io/github/license/alienwow/SnowLeopard.svg)](https://github.com/alienwow/SnowLeopard/blob/master/LICENSE)
[![LICENSE](https://img.shields.io/badge/license-Anti%20996-blue.svg)](https://github.com/996icu/996.ICU/blob/master/LICENSE)
[![Coverage 100%](https://img.shields.io/azure-devops/coverage/swellaby/opensource/25.svg)](https://github.com/speed-sonic/beige-route)

## 一个 PSR-11 标准容器
The container implementation of PSR-11.

## 简介
Beige PSR-11 是一个轻量级的 PSR-11 标准容器。它的目标是将容器的使用变得更加简单和纯粹，它能够接受任意类型的数据，并将 Definition 分离。

## 安装
```
composer require beige/psr-11
```

## 使用
引入并创建容器的实例：
```php
use Beige\Psr11\Container;

$container = new Container();
```

### 读写操作
可以在容器初始化的时候向容器写入数据，数据项的索引必须是字符串类型：
```php
$container = new Container([
    'foo' => 'bar'
]);
```

`Beige\Psr11\Container` 实现了 `ArrayAccess` 接口，支持以数组的形式操作数据：
```php
$container['foo'] = 'bar';
isset($container['foo']);  // true
unset($container['foo']);
```
如果根据索引无法找到数据，Container 会抛出一个 `Beige\Psr11\Exception\NotFoundException` 异常

### 定义 (Definition)
