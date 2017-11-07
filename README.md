# PHPViewLanguageAPI

ViewLanguage is a markup language designed to eliminate scripting in views. This is achieved by:
- interfacing scripting variables, through expressions.	Example:<br />
	<i>My ip is: ${request.client.ip}</i>.
- interfacing scripting logics, through tags. Example:<br />
	<i>&lt;std:foreach var="${request.client}" key="${keyName}" value="${valueName}"&gt;<br />
	&nbsp;&nbsp;&nbsp;Value for ${keyName} is ${valueName}.<br />
	&lt;/std:foreach&gt;</i>

The most elegant solution for keeping views scriptless, as employed by JSP applications, is to have all scripting replaced by a language that functions as an extension of HTML. Via plugins, tags will be internally translated into relevant programming language code when output is being constructed. This insures views are not only programming-language independent, but also framework independent, which means their data can be interpreted in endless fashions, according to one’s particular needs. Regardless of view language employed, the logic how such plugins work stays the same:

1. Builds response based on request.
2. Calls plugin to translate view language code from response into native programming language code.
3. Displays response.

Full documentation:<br />
http://www.lucinda-framework.com/view-language
