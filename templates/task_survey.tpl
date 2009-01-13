<div class="navbar">
	<table width="100%">
		{form action="`$smarty.const.TASKS_PKG_URL`index.php" class="survey" legend="Enquiry completion survey"}
			{foreach from=$hidden item=value key=name}
				<input type="hidden" name="{$name}" value="{$value}" />
			{/foreach}
			<input type="hidden" name="SURVEY_phpform_sent" value="1">
			<input type="hidden" name="SURVEY_ticket_id" value="{$ticket_id}">
		    <tr>
			   	<td colspan="4" >
					Followup:
					<input type="radio" name="follow" value=0 {if ($ciret & 0x0c) < 4  }checked{/if} >None&nbsp;
					<input type="radio" name="follow" value=4 {if ($ciret & 0x04) eq 4  }checked{/if} >Refer to Dept.&nbsp;
					<input type="radio" name="follow" value=8 {if ($ciret & 0x08) eq 8  }checked{/if} >Transfer to Dept.&nbsp;
				</td>
		    </tr>
		    <tr>
				<td colspan="2">
					Avoidable:&nbsp;
					<select name="avoid_result">
						<option value="0" {if $avoid eq 0 } selected {/if} >Unavoidable
						<option value="1" {if $avoid eq 1 } selected {/if} >Service Failure
						<option value="2" {if $avoid eq 2 } selected {/if} >Mid Call Transfer
						<option value="3" {if $avoid eq 3 } selected {/if} >Progress Chasing
						<option value="4" {if $avoid eq 4 } selected {/if} >Online Service Available
						<option value="5" {if $avoid eq 5 } selected {/if} >Unclear Mailing
			       	</select>
				</td>
				<td colspan="1">
					Satisfaction:&nbsp;
					<select name="survey_result">
						<option value="0" {if $survey eq 0 } selected {/if} >---
						<option value="1" {if $survey eq 1 } selected {/if} >No
						<option value="2" {if $survey eq 2 } selected {/if} >Yes
		        	</select>
				</td>
		        <td valign="bottom" colspan="1">
{*					{if $userstate > 0 } *}
			      		<button type="submit" class="buttonsc" alt="Save" value="Save" accesskey="S">Finish</button>&nbsp;
{*		        	{/if} *}
		        </td>
			</tr>
		{/form}
	</table>
	<hr>
</div>
