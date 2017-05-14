<standard:if condition="${user.id}==0">
	<import file="header_guests"/>
<standard:else>
	<import file="header_users"/>
</standard:if>
Welcome!!!<br/>