{* $Header$ *}
{strip}
<ul>
	{if $userstate > 0 }
			<li><a class="item" href="{$smarty.const.TASKS_PKG_URL}index.php?refer=1">{booticon iname="icon-cloud-download"   iexplain="Refer to waiting list" ilocation=menu}</a></li>
			<li><a class="item" href="{$smarty.const.TASKS_PKG_URL}index.php?finish=1">{booticon iname="icon-arrow-right"   iexplain="Finish" ilocation=menu}</a></li>
			<li><a class="item" href="{$smarty.const.TASKS_PKG_URL}find_citizen.php">{booticon iname="icon-arrow-right"   iexplain="Find existing citizen" ilocation=menu}</a></li>
			<li><a class="item" href="{$smarty.const.TASKS_PKG_URL}add_citizen.php">{booticon iname="icon-arrow-right"   iexplain="Create new citizen" ilocation=menu}</a></li>
	{/if}
	{if !$userstate or $userstate eq 0  }
		{if $gBitUser->hasPermission( 'p_tasks_view' )}
			<li><a class="item" href="{$smarty.const.TASKS_PKG_URL}view.php">{booticon iname="icon-file" iexplain="View Queues" ilocation=menu}</a></li>
			<li><a class="item" href="{$smarty.const.TASKS_PKG_URL}view_tickets.php">{booticon iname="icon-file" iexplain="View Tasks" ilocation=menu}</a></li>
		{/if}
	
		{if $gBitUser->hasPermission( 'p_tasks_create' )}
			<li><a class="item" href="{$smarty.const.TASKS_PKG_URL}add_enquiry.php?type=1">{booticon iname="icon-print"   iexplain="Create Ticket" ilocation=menu}</a></li>
			<li><a class="item" title="" href="add_enquiry.php?type=2">{booticon iname="icon-file" iexplain="Create Enquiry" ilocation=menu}</a>
				<ul>
					<li><a class="item" title="" href="add_enquiry.php?type=2">{booticon iname="icon-phone" iexplain="Telephone" ilocation=menu}</a></li>
					<li><a class="item" title="" href="add_enquiry.php?type=3">{booticon iname="icon-envelope" iexplain="eMessage" ilocation=menu}</a></li>
					<li><a class="item" title="" href="add_enquiry.php?type=4">{booticon iname="icon-truck" iexplain="Mail" ilocation=menu}</a></li>
				</ul>
			</li>
			<li><a class="item" href="{$smarty.const.TASKS_PKG_URL}add_enquiry.php?pass=1">{booticon iname="icon-picture" iexplain="Visitor Pass" ilocation=menu}</a></li>
		{/if}
	
		{if $gBitUser->hasPermission( 'p_tasks_supervise' )}
			<li><a class="item" href="{$smarty.const.TASKS_PKG_URL}logon_list.php">{booticon iname="icon-user" iexplain="Manage Logon" ilocation=menu}</a></li>
		{/if}
	
		{if $gBitUser->hasPermission( 'p_tasks_admin' )}
			<li><a class="item" href="{$smarty.const.TASKS_PKG_URL}admin_terminals.php">{booticon iname="icon-desktop" iexplain="Admin terminals" ilocation=menu}</a></li>
		{/if}
	{/if}
	{if $userstate < 0 }
			<li><a class="item" href="{$smarty.const.TASKS_PKG_URL}find_citizen.php">{booticon iname="icon-arrow-right"   iexplain="Find existing citizen" ilocation=menu}</a></li>
			<li><a class="item" href="{$smarty.const.TASKS_PKG_URL}add_citizen.php">{booticon iname="icon-arrow-right"   iexplain="Create new citizen" ilocation=menu}</a></li>
	{/if}
</ul>
{/strip}
