	<div class="navbar">
		{form action="`$smarty.const.TASKS_PKG_URL`citizen_search.php" class="find" legend="Find in Citizen entries"}
			{foreach from=$hidden item=value key=name}
				<input type="hidden" name="{$name}" value="{$value}" />
			{/foreach}
			<input type="hidden" name="sort_mode" value="{$sort_mode|default:surname_desc}" />
			{biticon ipackage="icons" iname="edit-find" iexplain="Search"} &nbsp;
			<label>{tr}Name{/tr}:&nbsp;<input size="20" type="text" name="find_name" value="{$find_name|default:$smarty.request.find_name|escape}" /></label> &nbsp;
			<label>{tr}Organisation{/tr}:&nbsp;<input size="20" type="text" name="find_org" value="{$find_org|default:$smarty.request.find_org|escape}" /></label> &nbsp;
			<label>{tr}Street{/tr}:&nbsp;<input size="20" type="text" name="find_street" value="{$find_street|default:$smarty.request.find_street|escape}" /></label> &nbsp;
			<label>{tr}Postcode{/tr}:&nbsp;<input size="10" type="text" name="find_postcode" value="{$find_postcode|default:$smarty.request.find_postcode|escape}" /></label> &nbsp;
			<input type="submit" name="search" value="{tr}Find{/tr}" />&nbsp;
			<input type="button" onclick="location.href='{$smarty.server.PHP_SELF}{if $hidden}?{/if}{foreach from=$hidden item=value key=name}{$name}={$value}&amp;{/foreach}'" value="{tr}Reset{/tr}" />
		{/form}
	</div>

