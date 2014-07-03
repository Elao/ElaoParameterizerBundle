ElaoParameterizerBundle
=======================

[![Latest Stable Version](https://poser.pugx.org/elao/parameterizer-bundle/v/stable.svg)](https://packagist.org/packages/elao/parameterizer-bundle) [![Total Downloads](https://poser.pugx.org/elao/parameterizer-bundle/downloads.svg)](https://packagist.org/packages/elao/parameterizer-bundle) [![Latest Unstable Version](https://poser.pugx.org/elao/parameterizer-bundle/v/unstable.svg)](https://packagist.org/packages/elao/parameterizer-bundle) [![License](https://poser.pugx.org/elao/parameterizer-bundle/license.svg)](https://packagist.org/packages/elao/parameterizer-bundle)

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Elao/ElaoParameterizerBundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Elao/ElaoParameterizerBundle/?branch=master)

Description:
------------

This bundle integrates ElaoParameterizer in your project, which is a clean and easy way to graphically handle php parameters using [dat.GUI](http://workshop.chromeexperiments.com/examples/gui/#1--Basic-Usage)

Installation:
-------------

Add ElaoParameterizerBundle in your composer.json (you would better use it as a development requirement):

```
{
    "require-dev": {
        "elao/elao/parameterizer-bundle": "1.0.*"
    }
}
```

Now tell composer to download the bundle by running the command:

```
$ php composer.phar update elao/parameterizer
```

How to use it:
--------------

Have a look on [ElaoParameterizer](https://github.com/Elao/ElaoParameterizer) to understand Patterns/Parameters concepts and usages.

You can declare patterns :

#### Programatically

```
// Get service
$parameterizer = $this->get('elao_parameterizer');

$parameterizer
    ->create('foo')
        ->create('bar', 'value')
        ->create('baz', 456, array(
            'label'   => 'Baz',
            'choices' => array(123, 456, 789)
        ));
```

#### In config.yml

```
elao_parameterizer:
    patterns:
        foo:
            parameters:
                bar: value
                baz:
                    value: 456
                    options:
                        label:   Baz
                        choices: [123, 456, 789]
```

#### In services

```
<service id="foo.parameters"
     class="%elao_parameterizer.pattern.class%"
     factory-service="elao_parameterizer.factory"
     factory-method="createPattern"
>
    <tag name="elao_parameterizer.pattern" />
    <argument>foo</argument>
    <call method="create">
        <argument>bar</argument>
        <argument>value</argument>
    </call>
    <call method="create">
        <argument>baz</argument>
        <argument>456</argument>
        <argument type="collection">
            <argument key="label">Baz</argument>
            <argument key="choices" type="collection">
                <argument>123</argument>
                <argument>456</argument>
                <argument>789</argument>
            </argument>
        </argument>
    </call>
</service>
```
