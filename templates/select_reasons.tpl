<div class="depts">
	<div id="menu-mDept" class="menuDiv">
		{section name=dept loop=$departments}
			<li><a href="index.php?new_dept={$departments[dept].que_no}">{$departments[dept].title}</a></li>
		{/section}
		<li>&nbsp;</li>
		<li><a href="index.php?new_clear=4096">Faulty Ticket</a></li>
		<li><a href="index.php?new_clear=4097&TicID=<?echo $ticket_id;?>">NoShow</a></li>
	</div>

	{if $tags }
	<div id="menu-mTag" class="menuTag">
 		{foreach item=tag from=$tags}
			<li><a href="index.php?new_tag={$tag.tag_no}">{$tag.title}</a></li>
		{/foreach}
	</div>
	{/if}
	
	{if $subtags }
	<div id="menu-mSub" class="menuSub">
 		{foreach item=subtag from=$subtags}
			<li><a href="index.php?new_tag={$subtag.tag_no}">{$subtag.title}</a></li> 
		{/foreach}
	</div>
	{/if}

</div><!-- end .nav -->

