{include file="bitpackage:tasks/select_reasons.tpl"}
<div class="display task">
	<div class="header">
		<h1>{$taskInfo.title}</h1>
	</div>

	<div class="body">
		{if $taskInfo}
			{if $taskInfo.department eq 0}
				{include file="bitpackage:tasks/select_department.tpl"}
			{else}
				{include file="bitpackage:tasks/display_task.tpl"}
			{/if}
		{/if}

		{if $citizenInfo or $backoffice}
			{include file="bitpackage:tasks/task_survey.tpl"}
		{/if}

		{if $citizenInfo}
			{include file="bitpackage:citizen/citizen_header.tpl"}
			{include file="bitpackage:citizen/citizen_date_bar.tpl"}
			{include file="bitpackage:citizen/display_citizen.tpl"}
		{else}
			{include file="bitpackage:tasks/citizen_search.tpl"}
		{/if}
	</div>
</div> {* end .task *}
