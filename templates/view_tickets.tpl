{* $Header$ *}
{strip}
<div class="display tasks">
	<div class="header">
		<h1>{$currentInfo.title}</h1>
	</div>

	<div class="body">
		{formfeedback hash=$feedback}

		<div class="form-group">
			<table>
				<caption>{tr}List of Current Days enquiries{/tr}</caption>
				<thead>
					<tr>
						<th>Date</th>
						<th>Name</th>
						<th>Reason</th>
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
								{$currentInfo.tickets[ticket].forename|escape} {$currentInfo.tickets[ticket].surname|escape}
							</td>
							<td>
								{$currentInfo.tickets[ticket].tags|escape}
							</td>
							<td>
								<span class="actionicon">
									{smartlink ititle="View" ifile="index.php" booticon="icon-edit" content_id=$currentInfo.tickets[ticket].ticket_id}
								</span>
								<label for="ev_{$currentInfo.tickets[ticket].content_id}">	
									{$currentInfo.tickets[ticket].creator_real_name}
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
		</div>

	</div><!-- end .body -->
</div><!-- end .tasks -->
{/strip}
	
