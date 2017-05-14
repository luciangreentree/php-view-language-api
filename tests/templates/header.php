<std:if condition="${user.id}==0">
	<import file="header_guests"/>
<std:else>
	<import file="header_users"/>
</std:if>
Welcome!!!<br/>