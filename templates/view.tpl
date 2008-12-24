{* $Header: /cvsroot/bitweaver/_bit_tasks/templates/view.tpl,v 1.1 2008/12/24 09:04:37 lsces Exp $ *}
{strip}
<div class="display tasks">
	<div class="header">
		<h1>{$currentInfo.title}</h1>
	</div>

	<div class="body">
		{formfeedback hash=$feedback}

		{if $currentInfo.tickets}
		<div class="row">
			{formlabel label="Tickets" for="ticket"}
			{forminput}
			<table>
				<caption>{tr}List of Current Days enquiries{/tr}</caption>
				<thead>
					<tr>
						<th>Data</th>
						<th>TAG</th>
						<th>Note</th>
					</tr>
				</thead>
				<tbody>
					{section name=ticket loop=$currentInfo.tickets}
						<tr class="{cycle values="even,odd"}" title="{$currentInfo.ticket[ticket].title|escape}">
							<td>
								{$currentInfo.tickets[ticket].ticket_ref|bit_long_date} - {$currentInfo.tickets[ticket].ticket_no}
							</td>
							<td>
								{$currentInfo.tickets[ticket].tags|escape}
							</td>
							<td>
								<span class="actionicon">
									{smartlink ititle="View" ifile="view_ticket.php" ibiticon="icons/accessories-text-editor" ticket_id=$currentInfo.tickets[ticket].ticket_id}
								</span>
								<label for="ev_{$currentInfo.tickets[ticket].ticket_no}">	
									{$currentInfo.tickets[ticket].staff_id}
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
	