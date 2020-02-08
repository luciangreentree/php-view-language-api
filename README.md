# View Language API

*Documentation below refers to latest API version, available in branch [v3.0.0](https://github.com/aherne/php-view-language-api/tree/v3.0.0)! For older version in master branch, please check [Lucinda Framework](https://www.lucinda-framework.com/view-language).*

This API is the PHP compiler for ViewLanguage templating engine, a markup language inspired by JSP&JSTL @ Java that acts like an extension of HTML standard, designed to completely eliminate the need for scripting in views by:

- interfacing variables through **[expressions](https://www.lucinda-framework.com/view-language/expressions)**.
- interfacing logics (control structures, repeating html segments) through **[tags](https://www.lucinda-framework.com/view-language/tags)**

In order to achieve its goals, following steps need to be observed:

- **[configuration](#configuration)**: setting up an XML file where templating is configured
- **[compilation](#compilation)**: using [Lucinda\Templating\Wrapper](https://github.com/aherne/php-view-language-api/blob/v3.0.0/src/Wrapper.php) to read above XML and compile a template

API is fully PSR-4 compliant, only requiring PHP7.1+ interpreter and SimpleXML extension. To quickly see how it works, check:

- **[installation](#installation)**: describes how to install API on your computer, in light of steps above
- **[unit tests](#unit-tests)**: API has 100% Unit Test coverage, using [UnitTest API](https://github.com/aherne/unit-testing) instead of PHPUnit for greater flexibility
- **[expressions](https://www.lucinda-framework.com/view-language/expressions)**: shows how to define variables in ViewLanguage and how are they resolved to PHP
- **[tags](https://www.lucinda-framework.com/view-language/tags)**: shows how to use control structures and extend HTML standard with parameterized tags
- **[examples](#examples)**: shows an example how to template with ViewLanguage, including explanations for each step

## Configuration

To configure this API you must have a XML with a **templating** tag inside:

```xml
<templating compilations_path="..." tags_path="..." templates_path="..." templates_extension="..." />
```

Where:

- **compilations_path**: (mandatory) path into which PHP compilations of ViewLanguage templates are saved 
- **tags_path**: (optional) path into which ViewLanguage tag libraries are located
- **templates_path**: (optional) path into which templates are located
- **templates_extension**: (optional) template files extension. If not set, "html" is assumed!

Example:

```xml
<templating compilations_path="compilations" tags_path="application/taglib" templates_path="application/views" templates_extension="html"/>
```

## Compilation

Once you have completed step above, you need to instantiate [Lucinda\Templating\Wrapper](https://github.com/aherne/php-view-language-api/blob/v3.0.0/src/Wrapper.php) in order to be able to compile templates later on:

```php
$wrapper = new Lucinda\SQL\Wrapper(simplexml_load_file(XML_FILE_NAME));
```

Object has following method that can be used to compile one or more templates:

| Method | Arguments | Returns | Description |
| --- | --- | --- | --- |
| compile | string $template, array $data | string | Compiles ViewLanguage template into HTML and returns result |
 
 
### How are templates compiled?
 
As in any other templating language, compilation first traverses the tree of dependencies ever deeper and assembles result into a PHP file then produces an HTML by binding it to data received by user. It thus involves following steps:

- if a PHP compilation for *$template* argument exists, checks if elements referenced inside have changed since it was last updated. If it doesn't exist or it changed:
    - parses **[<import>](https://www.lucinda-framework.com/view-language/macro-tags#import)** tags recursively (backing-up **[<escape>](https://www.lucinda-framework.com/view-language/macro-tags#escape)** tag bodies in compilation file to be excluded from parsing) and appends results to compilation file
    - parses **[<namespace>](https://www.lucinda-framework.com/view-language/macro-tags#namespace)** tags defined in templates, to know where to locate user-defined tag libraries not defined in default taglib folder
    - parses **[library tags](https://www.lucinda-framework.com/view-language/tags#libraries)** (which may be [standard](https://www.lucinda-framework.com/view-language/standard-tags) or [user-defined]()) recursively (backing-up **[<escape>](https://www.lucinda-framework.com/view-language/macro-tags#escape)** tag bodies in compilation file to be excluded from parsing) and replaces them with relevant PHP/HTML code in compilation file.
    - parses **[expressions](https://www.lucinda-framework.com/view-language/expressions)** and replaces them with relevant PHP code in compilation file.
    - restores backed up **[<escape>](https://www.lucinda-framework.com/view-language/macro-tags#escape)** tags bodies (if any) in compilation file
    - caches new compilation on disk along with a checksum of its parts (templates, tags) for future validations
- in output buffer, loads compilation file, binds it to *$data* supplied by user and produces a final HTML out of it 
     
Since the whole process is somewhat performance hungry, PHP compilation files will be cached on disk and returned directly on next requests unless one of its components (template or tag) has changed. This makes API able to compile in around 0.001 sec amortised time, thus bringing no performance taxation whatsoever but all the advantages of an elegant view!

## Installation

First choose a folder where API will be installed then write this command there using console:

```console
composer require lucinda/templating
```

Then create a *configuration.xml* file holding configuration settings (see [configuration](#configuration) above) and a *index.php* file in project root with following code:

```php
require(__DIR__."/vendor/autoload.php");
$wrapper = new Lucinda\SQL\Wrapper(simplexml_load_file("configuration.xml"));
```

To compile a template:

```php
$html = $wrapper->compile(TEMPLATE_NAME, USER_DATA);
```

Where:

- TEMPLATE_NAME is the *base template* that must obey following rules:
    - must be a path to a file located in *templates_path* (see **[configuration](#configuration)**)
    - file it points to must have *templates_extension* (see **[configuration](#configuration)**)
    - because of above, must not include extension
- USER_DATA is a list of values from back-end to be accessed in template, obeying following rules:
	- must be an array
	- entry keys must be strings or integers (the usual PHP requirements)
	- entry values must be scalars or arrays
	- if array is multidimensional, keys and values in siblings must obey same rules as above

To display results:

```php
header("Content-Type: text/html; charset=UTF-8");
echo $html;
```

## Unit Tests

For tests and examples, check following files/folders in API sources:

- [unit-tests.sql](https://github.com/aherne/php-view-language-api/blob/v3.0.0/unit-tests.xml): SQL commands you need to run ONCE on server (assuming MySQL) before unit tests execution
- [test.php](https://github.com/aherne/php-view-language-api/blob/v3.0.0/test.php): runs unit tests in console
- [unit-tests.xml](https://github.com/aherne/php-view-language-api/blob/v3.0.0/unit-tests.xml): sets up unit tests and mocks "sql" tag
- [tests](https://github.com/aherne/php-view-language-api/tree/v3.0.0/tests): unit tests for classes from [src](https://github.com/aherne/php-view-language-api/tree/v3.0.0/src) folder

## Examples

Assuming *configuration.xml* (see **[configuration](#configuration)** and **[installation](#installation)**) is: 
 
 ```xml
<xml>
	<templating compilations_path="compilations" tags_path="application/taglib" templates_path="application/views" templates_extension="html"/>
</xml>
```

Let's create a *application/views/index.html* template with following body:

```html
<import file="header"/>
Hello, dear ${data.author}! Author of:
<ul>
    <:foreach var="${data.apis}" key="name" val="url">
    <my:api id="${name}" link="${url}"/>
    </:foreach>
</ul>
<import file="footer"/>
```
 
What above does is:

- loads body of a *application/views/header.html* template
- echoes value of *author* array key of $data via **[expressions](https://www.lucinda-framework.com/view-language/expressions)**
- loops through value of *apis* array key of $data via **[<:foreach>](https://www.lucinda-framework.com/view-language/standard-tags#:foreach)**
- on every iteration, loads body of *application/taglib/my/api.html* template (user tag), binding attributes to values
- loads body of a *application/views/footer.html* template

Contents of *application/views/header.html* file:

```html
<html>
    <head>
        <title>View Language API Tutorial</title>
    </head>
    <body>
```

Contents of *application/taglib/my/api.html* file:

```html
<li><a href="$[id]">$[link]</a></li>
```

Contents of *application/views/footer.html*:

```html    
    </body>
</html/>
```

As one can see above, templates depend on variables to be received from back-end (*author* & *apis*), both keys of **$data** array. Assuming value of latter is:

```php
$data = [
	"author" => "Lucian Popescu", 
	"apis" => ["View Language API" => "https://www.lucinda-framework.com/view-language", "STDOUT MVC API" => "https://www.lucinda-framework.com/stdout-mvc"]
];
```

Now let's compile *application/views/index.html* template (see **[compilation](#compilation)**) and bind it to *$data*:

```php
require(__DIR__."/vendor/autoload.php");
$wrapper = new Lucinda\SQL\Wrapper(simplexml_load_file("configuration.xml"));
$html = $wrapper->compile("index", $data);
```

First, ViewLanguage base template is compiled into PHP, results saved in a **compilations/index.php** file (in case it doesn't exist already) with following body:

```html
<html>
    <head>
        <title>View Language API Tutorial</title>
    </head>
    <body>
        Hello, <?php echo $data["author"]; ?>, author of:
        <ul>
            <?php foreach($data["apis"] as $name=>$url) { ?>
            <li><a href="<?php echo $url; ?>"><?php echo $name; ?></a></li>
            <?php } ?>
        </ul>
    </body>
</html/>
```

Then above file is loaded in output buffer and bound with $data, so the final HTML returned will be:

```html
<html>
    <head>
        <title>View Language API Tutorial</title>
    </head>
    <body>
        Hello, Lucian Popescu, author of:
        <ul>
            <li><a href="https://www.lucinda-framework.com/view-language">View Language API</a></li>
            <li><a href="https://www.lucinda-framework.com/stdout-mvc">STDOUT MVC API</a></li>
        </ul>
    </body>
</html/>
```
