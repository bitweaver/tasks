{* $Header: /cvsroot/bitweaver/_bit_tasks/templates/menu_tasks.tpl,v 1.1 2008/12/24 09:04:37 lsces Exp $ *}
{strip}
<ul>
	{if $gBitUser->hasPermission( 'p_tasks_view' )}
		<li><a class="item" href="{$smarty.const.TASKS_PKG_URL}view.php">{biticon iname="document-new" iexplain="View Tasks" ilocation=menu}</a></li>
	{/if}

	{if $gBitUser->hasPermission( 'p_tasks_create' )}
		<li><a class="item" href="{$smarty.const.TASKS_PKG_URL}add_enquiry.php">{biticon iname="document-new" iexplain="Create Enquiry" ilocation=menu}</a></li>
	{/if}

	{if $gBitUser->hasPermission( 'p_tasks_supervise' )}
		<li><a class="item" href="{$smarty.const.TASKS_PKG_URL}logon_list.php">{biticon iname="go-up" iexplain="Upload Files" ilocation=menu}</a></li>
	{/if}

	{if $gBitUser->hasPermission( 'p_tasks_admin' )}
		<li><a class="item" href="{$smarty.const.TASKS_PKG_URL}admin_terminals.php">{biticon iname="go-up" iexplain="Admin terminals" ilocation=menu}</a></li>
	{/if}
</ul>
{/strip}