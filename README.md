# PHPViewLanguageAPI

ViewLanguage is a markup language designed to eliminate scripting in views. This is achieved by:
- interfacing scripting variables, through expressions. 
	Example:
	My ip is: ${request.client.ip}.
- interfacing scripting logics, through tags.
	Example:
	<standard:foreach var="${request.client}" key="${keyName}" value="${valueName}">
	    Value for ${keyName} is ${valueName}.
	</standard:foreach>
