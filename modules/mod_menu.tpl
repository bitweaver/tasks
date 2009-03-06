{* $Header: /cvsroot/bitweaver/_bit_tasks/modules/mod_menu.tpl,v 1.2 2009/03/06 08:07:02 lsces Exp $ *}
{strip}
{if $gBitSystem->isPackageActive( 'tasks' )}
	{bitmodule title="$moduleTitle" name="task_menu_items"}
		<ul>
			{if $gBitUser->hasPermission( 'p_tasks_view' )}
				<li><a class="item" href="{$smarty.const.TASKS_PKG_URL}view.php">{biticon iname="folder-open" iexplain="View Tasks" ilocation=menu}</a></li>
			{/if}

			{if $gBitUser->hasPermission( 'p_tasks_create' )}
				<li><a class="item" href="{$smarty.const.TASKS_PKG_URL}add_enquiry.php">{biticon iname="document-new" iexplain="Create Enquiry" ilocation=menu}</a></li>
			{/if}

			{if $gBitUser->hasPermission( 'p_tasks_supervise' )}
				<li><a class="item" href="{$smarty.const.TASKS_PKG_URL}logon_list.php">{biticon iname="preferences-system" iexplain="Logon Management" ilocation=menu}</a></li>
			{/if}

			{if $gBitUser->hasPermission( 'p_tasks_admin' )}
				<li><a class="item" href="{$smarty.const.TASKS_PKG_URL}admin_terminals.php">{biticon iname="computer" iexplain="Admin terminals" ilocation=menu}</a></li>
			{/if}
		</ul>
	{/bitmodule}
{/if}
{/strip}
	