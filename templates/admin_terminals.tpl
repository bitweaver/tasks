{* $Header: /cvsroot/bitweaver/_bit_tasks/templates/admin_terminals.tpl,v 1.1 2008/12/24 09:04:37 lsces Exp $ *}
{strip}
<div class="display tasks">
	<div class="header">
		<h1>{$currentInfo.title}</h1>
	</div>

	<div class="body">
		{formfeedback hash=$feedback}

		{if $currentInfo.logged_staff}
		<div class="row">
			{formlabel label="Tickets" for="ticket"}
			{forminput}
			<table>
				<caption>{tr}List of current staff logon's{/tr}</caption>
				<thead>
					<tr>
						<th>Data</th>
						<th>TAG</th>
						<th>Note</th>
					</tr>
				</thead>
				<tbody>
					{section name=staff loop=$currentInfo.logged_staff}
						<tr class="{cycle values="even,odd"}" title="{$currentInfo.logged_staff[staff].title|escape}">
							<td>
								{$currentInfo.logged_staff[staff].ticket_ref|bit_long_date} - {$currentInfo.logged_staff[staff].ticket_no}
							</td>
							<td>
								{$currentInfo.logged_staff[staff].tags|escape}
							</td>
							<td>
								<span class="actionicon">
									{smartlink ititle="View" ifile="view_ticket.php" ibiticon="icons/accessories-text-editor" ticket_id=$currentInfo.tickets[ticket].ticket_id}
								</span>
								<label for="ev_{$currentInfo.logged_staff[staff].ticket_no}">	
									{$currentInfo.logged_staff[staff].staff_id}
								</label>
							</td>
						</tr>
					{sectionelse}
						<tr class="norecords">
							<td colspan="3">
								{tr}No records found{/tr}
							</td>
						</tr>
					{/section}
				</tbody>
			</table>
			{/forminput}
		</div>

		{/if}
	</div><!-- end .body -->
</div><!-- end .tasks -->
{/strip}