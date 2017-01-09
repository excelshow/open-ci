<nav class="top-nav">
	{foreach from=$NAVIGATOR_LIST key=key item=item}
		<a class="top-nav-ticket {if $smarty.session.template == $key}active{/if}" href="http://{$smarty.session.appId}{$item.url}"><span>{$item.title}</span></a>
	{/foreach}
</nav>
