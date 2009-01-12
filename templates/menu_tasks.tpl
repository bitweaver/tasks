{* $Header: /cvsroot/bitweaver/_bit_tasks/templates/menu_tasks.tpl,v 1.2 2009/01/12 14:38:35 lsces Exp $ *}
{strip}
<ul>
	{if $gBitUser->hasPermission( 'p_tasks_view' )}
		<li><a class="item" href="{$smarty.const.TASKS_PKG_URL}view.php">{biticon iname="document-new" iexplain="View Tasks" ilocation=menu}</a></li>
	{/if}

	{if $gBitUser->hasPermission( 'p_tasks_create' )}
		<li><a class="item" href="{$smarty.const.TASKS_PKG_URL}add_enquiry.php?type=1">{biticon iname="document-print" iexplain="Create Ticket" ilocation=menu}</a></li>
		<li><a class="item" title="" href="add_enquiry.php?type=2">{biticon iname="document-new" iexplain="Create Enquiry" ilocation=menu}</a>
			<ul>
				<li><a class="item" title="" href="add_enquiry.php?type=2">{biticon iname="phone" iexplain="Telephone" ilocation=menu}</a></li>
				<li><a class="item" title="" href="add_enquiry.php?type=3">{biticon iname="internet-mail" iexplain="eMessage" ilocation=menu}</a></li>
				<li><a class="item" title="" href="add_enquiry.php?type=4">{biticon iname="emblem-mail" iexplain="Mail" ilocation=menu}</a></li>
			</ul>
		</li>
		<li><a class="item" href="{$smarty.const.TASKS_PKG_URL}add_enquiry.php?pass=1">{biticon iname="emblem-photos" iexplain="Visitor Pass" ilocation=menu}</a></li>
	{/if}

	{if $gBitUser->hasPermission( 'p_tasks_supervise' )}
		<li><a class="item" href="{$smarty.const.TASKS_PKG_URL}logon_list.php">{biticon iname="user" iexplain="Manage Logon" ilocation=menu}</a></li>
	{/if}

	{if $gBitUser->hasPermission( 'p_tasks_admin' )}
		<li><a class="item" href="{$smarty.const.TASKS_PKG_URL}admin_terminals.php">{biticon iname="input-keyboard" iexplain="Admin terminals" ilocation=menu}</a></li>
	{/if}
</ul>
{/strip}