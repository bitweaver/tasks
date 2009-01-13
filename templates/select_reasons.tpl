<div id="menu-mDept" class="menuDiv">
	{section name=dept loop=$departments}
		<div><a href="index.php?new_dept={$departments[dept].que_no}">{$departments[dept].title}</a></div>
	{/section}
	<div>&nbsp;</div>
	<div><a href="index.php?new_clear=4096">Faulty Ticket</a></div>
	<div><a href="index.php?new_clear=4097">NoShow</a></div>
</div>

{if $tags }
	<div id="menu-mTag" class="menuTag">
		{foreach item=tag from=$tags}
			<div><a href="index.php?new_tag={$tag.tag_no}">{$tag.title}</a></div>
		{/foreach}
	</div>
{/if}
	
{if $subtags }
	<div id="menu-mSub" class="menuSub">
 		{foreach item=subtag from=$subtags}
			<div><a href="index.php?new_tag={$subtag.tag_no}">{$subtag.title}</a></div> 
		{/foreach}
	</div>
{/if}
