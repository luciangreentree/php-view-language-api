# PHPViewLanguageAPI

ViewLanguage is a markup language standard inspired by Java JSTL and C Compiler, designed to eliminate scripting in views by:

- interfacing scripting variables, through expressions.	Example:<br />
	<i>My ip is: ${request.client.ip}</i>.
- interfacing scripting logics, through tags. Example:<br />
	<i>&lt;std:foreach var="${request.client}" key="${keyName}" value="${valueName}"&gt;<br />
	&nbsp;&nbsp;&nbsp;Value for ${keyName} is ${valueName}.<br />
	&lt;/std:foreach&gt;</i>

The most elegant solution for keeping views scriptless is to have all scripting replaced by a language that functions as an extension of HTML! Via compilers, pseudo-HTML tags will be internally translated into relevant programming language code when output is being constructed (same way as macros are expanded into C code when a C program is compiled). This insures views are not just framework independed, but programming-language independent as well. This API is the PHP compiler for ViewLanguage, able to translate ViewLanguage templates, tags and expressions into PHP-powered HTML. It does so by recursively aggregating final view from templates/tags referred then caching aggregate view so that next time, if none of its parts changed, compilation is skipped and cached file is returned instead.

Read more here:<br />
http://www.lucinda-framework.com/view-language
