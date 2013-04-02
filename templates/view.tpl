{* $Header$ *}
{strip}
<div class="display tasks">
	<div class="header">
		<h1>{$currentInfo.title}</h1>
	</div>

	<div class="body">
		{formfeedback hash=$feedback}

		{if $currentInfo.queues}
		<div class="control-group">
			<table>
				<caption>{tr}List of Current Days outstanding enquiries{/tr}</caption>
				<thead>
					<tr>
						<th>Queue</th>
						<th>Title</th>
						<th>Number Waiting</th>
						<th>Average Wait</th>
						<th>Call</th>
					</tr>
				</thead>
				<tbody>
					{section name=queue loop=$currentInfo.queues}
						<tr class="{cycle values="even,odd"}" title="{$currentInfo.queues[queue].title|escape}">
							<td>
								{$currentInfo.queues[queue].queue_id}
							</td>
							<td>
								<a href="{$currentInfo.queues[queue].display_url}">{$currentInfo.queues[queue].title|escape}</a>
							</td>
							<td>
								{$currentInfo.queues[queue].no_waiting}
							</td>
							<td>
								{$currentInfo.queues[queue].avg_wait}
							</td>
							<td>
								<span class="actionicon">
									{smartlink ititle="View" ifile="get_queue.php" ibiticon="icons/go-up" queue_id=$currentInfo.queues[queue].queue_id}
								</span>
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

		{/if}
	</div><!-- end .body -->
</div><!-- end .tasks -->
{/strip}
	