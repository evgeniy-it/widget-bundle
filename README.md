Widget Bundle
==========

Widget bundle is something similar to modx snippets, makes your content managment system powerful.


# Installation

With composer:
```
composer require evgeniy-it/widget-bundle
```
Widget will be ready to work

# Getting started

Render widget via such a shortcode: 
```
[[widget_name? &setting=`1` &settingArray=`[1,2,3]` &settingAssocArray=`{'item1':'value1','item2':'value2'}`]] 
```
Widget also can be nested multiple times:
```
[[widget_name? &nestedSetting=`[[widget_name2? &setting1=`value1` &setting2=`[[widget3]]`]]`]]
```

For parsing and rendering use twig widget filter, it will find all widgets, parse and process them:
```twig
{{ some_string_var|widget}}
```

Be sure about widget syntax:

* widget name must be ended with ?
* any option must start with &
* any option value must be wrapped in \`
* a widget must be started with [[ and ends with ]]

#Using:
   
##Default widgets:

### Simple widget

This widget just renders setting variables into specified template. If template is not specified it will render only option `&content`.
You can specify template as well. Specify  template whether as a link or as a  string..

#####Example:

Using default template:
```twig
{{ '[[simple? &content=`Hello world`]]'|widget }}
```
or nested
```twig
{{ '[[simple? &content=`[[simple? &content=`Hello world`]]`]]'|widget }}
```
Will output:
```
Hello world
```
Using custom template:
```twig
{{ '[[simple? &setting1=`value1` &setting2=`value2` &setting3=`value3` &template=`{{setting1}} - {{setting2}} - {{setting3}}`]]'|widget }}
```
or
```twig
{{ '[[simple? &setting1=`value1` &setting2=`value2` &setting3=`value3` &template=`AppBundle:Widget:simple.html.twig`]]'|widget }}
```
```twig
{# AppBundle:Widget:simple.html.twig#}
{{setting1}} - {{setting2}} - {{setting3}}
```

Will output:
```
value1 - value2 - value3
```

### Repository widget

Renders collection of doctrine models

Params:
* tpl - one item element template
* model - entity model class
* function - function that will be called
* args - function args. json format

other params:
* beforeTpl - contents piece of code that is being shown before item code structure 
* afterTpl - contents piece of code that is being shown after item code structure 

in the specified template will be available those vars:
* item - fetched entity model
* idx (int) - ordinal Number
* isFirst (bool) -  if the element is the first
* isLast (bool) - if it's the last element

Example:

```php
<?php

namespace App\Bundle\ProductBundle\Entity;


class Product
{
    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $slug;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $active = false;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Bundle\ShopBundle\Entity\ProductCategory", mappedBy="product", cascade={"persist"})
     */
    private $categories;
//...

```

```twig
{{ '[[repository? &model=`AppProductBundle:Product` &function=`findBySlug` &args=`["testslug"]` &tpl=`{{item.name}}` &tpl=`{{idx+1}}. {{item.name}}<br/>`]]'|widget }}
```
will output:

```
1. product 1
2. product 3
3. product 3
...
n. product n
```
