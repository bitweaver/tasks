{* $Header$ *}
{strip}
<div class="display tasks">
	<div class="header">
		<h1>{$currentInfo.title}</h1>
	</div>

	<div class="body">
		{formfeedback hash=$feedback}

		<div class="control-group">
			<table>
				<thead>
					<tr>
						<th>Time</th>
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
								{$currentInfo.tickets[ticket].reason|escape}
							</td>
							<td>
								<span class="actionicon">
									{smartlink ititle="View" ifile="index.php" ibiticon="icons/accessories-text-editor" content_id=$currentInfo.tickets[ticket].content_id}
								</span>
								<label for="ev_{$currentInfo.tickets[ticket].ticket_no}">	
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
		{pagination}
	</div><!-- end .body -->
</div><!-- end .tasks -->
{/strip}
	