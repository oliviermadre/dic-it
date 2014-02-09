dic-it
======

[![Build Status](https://travis-ci.org/oliviermadre/dic-it.png?branch=master)](https://travis-ci.org/oliviermadre/dic-it)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/oliviermadre/dic-it/badges/quality-score.png?s=30dd2847f1e2aa8b6de1795cd8e460ad25097e45)](https://scrutinizer-ci.com/g/oliviermadre/dic-it/)
[![Code Coverage](https://scrutinizer-ci.com/g/oliviermadre/dic-it/badges/coverage.png?s=4e25828bd8fc447a0c40e7c47d49e9ff4ecbc19b)](https://scrutinizer-ci.com/g/oliviermadre/dic-it/)
[![Dependency Status](https://www.versioneye.com/php/oliviermadre:dic-it/dev-master/badge.png)](https://www.versioneye.com/php/oliviermadre:dic-it/dev-master)

DIC-IT is a simple dependency injection container, with extensible activation & injection strategies.


## Setup

The recommended setup is to create a config folder at the root of your repository. All configuration is based on YAML files.

Sample YAML file :

```
parameters:
    MyParameter: 'Some parameter value'
    MyOtherParameter: 42
classes:
    MyServiceName:
        class: \Fully\Qualified\ClassName
        args: [ @MyDependency, %MyParameter, 'Hard-coded value' ]
    MyDependency:
        class: \Fully\Qualified\DependencyClassName
        props:
            MyProperty: %MyOtherParameter
```

## Using includes

The configuration can be split into multiple files to ease management of your dependencies :

```
includes:
    - relative/file.yml
    - relative/another-file.yml
    
classes:
    ...
```

This allows you to separate parameters from service definitions for example.

## Default object life-cycle

By default, all objects are created as non-singleton (this will definitely change) objects, so every time a reference is resolved by the container, a new instance of the requested object is created.

## Managing circular dependencies

By default, circular dependencies are not handled well (stack overflow...) due the default object life-cycle. To enable circular dependencies for a given object, at least one of the two objects must be defined as a singleton. This however will not yield the expected results, so it is *highly* recommended to define both objects involved in the circular dependency as singletons.