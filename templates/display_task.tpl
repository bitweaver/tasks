<div class="body">
	<div class="content">

		<div class="row">
			{formlabel label="Issued" for="department"}
			{forminput}
				{$taskInfo.ticket_ref|bit_short_time} {$taskInfo.ticket_no|bit_short_time}
			{/forminput}
		</div>
		<div class="row">
			{formlabel label="Department" for="department"}
			{forminput}
				{$taskInfo.dept_title|escape} 
			{/forminput}
		</div>
		<div class="row">
			{formlabel label="Reason" for="department"}
			{forminput}
				{$taskInfo.reason|escape} 
			{/forminput}
		</div>
		<div class="row">
			{formlabel label="Entered By" for="staff_id"}
			{forminput}
				{$taskInfo.creator_real_name|escape} 
			{/forminput}
		</div>
		<div class="row">
			{formlabel label="Last updated by" for="department"}
			{forminput}
				{$taskInfo.modifier_real_name|escape} 
			{/forminput}
		</div>
	</div><!-- end .content -->
</div><!-- end .body -->