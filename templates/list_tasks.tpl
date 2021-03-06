	<div class="content">

		<div class="control-group">
			{formlabel label="Issued" for="department"}
			{forminput}
				{$taskInfo.ticket_ref|bit_short_time} {$taskInfo.ticket_no}
			{/forminput}
		</div>
		<div class="control-group">
			{formlabel label="Department" for="department"}
			{forminput}
				<a class="button" accesskey="D" href="#" onmouseover="menu.show('mDept', '', this, 50, -10)" onmouseout="menu.hide('mDept')">{$taskInfo.dept_title|escape}</a>
			{/forminput}
		</div>
		<div class="control-group">
			{formlabel label="Reason" for="reason"}
			{forminput}
				<a class="button" accesskey="S" href="#" onmouseover="menu.show('mTag', '', this, 0, -20)" onmouseout="menu.hide('mTag')">Tag</a>&nbsp;-&nbsp;<a class="button" href="#" onmouseover="menu.show('mSub', '', this, 50, -10)" onmouseout="menu.hide('mSub')">{$taskInfo.reason|escape}</a>
			{/forminput}
		</div>
		<div class="control-group">
			{formlabel label="Entered By" for="staff_id"}
			{forminput}
				{$taskInfo.creator_real_name|escape} 
			{/forminput}
		</div>
		<div class="control-group">
			{formlabel label="Last updated by" for="department"}
			{forminput}
				{$taskInfo.modifier_real_name|escape} 
			{/forminput}
		</div>
	</div><!-- end .content -->
