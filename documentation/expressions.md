An expression is a ViewLanguage representation of a *scripting variable*. Syntax for an expression is:

```html
${variableName}
```
where **variableName** can be:

| Description | ViewLanguage Example | PHP Translation |
| --- | --- | --- |
| a scalar variable | ${foo} | $foo |
| an array variable, where hierarchy is represented by dots | ${foo.bar} | $foo["bar"] |
| a back-end helper function (native or user-defined) | ${htmlspecialchars(${foo.bar})} | htmlspecialchars($foo["bar"]) |
| a short if using ternary operators | ${(${foo.bar}!=3?"Y":"N")} | ($foo["bar"]!=3?"Y":"N") |

A very powerful feature is the **ability to nest expressions**: writing expressions whose key(s) are expressions themselves. This can go at any depth and it is very useful when iterating through more than one list and linking a one to another's key/value association! Example: 
```html
<span>${foo.bar.${baz}}</span>
```
which in PHP translates to:
```php
<span>echo $foo["bar"][$baz];</span>
```
