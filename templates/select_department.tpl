<div class="body">
	<div class="content">
		{section name=dept loop=$departments}
			<li><a href="index.php?new_dept={$departments[dept].que_no}">{$departments[dept].title}</a></li>
		{/section}
	</div><!-- end .content -->
</div><!-- end .body -->
