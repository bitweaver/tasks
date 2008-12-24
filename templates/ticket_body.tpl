<table width="100%">
<? if ( $caller_id and $serving < 30000 ) { ?>
  <tr>
    <td class="dataleft" colspan="1">Name</td>
    <td class="dataleft" colspan="4">
      <input type=text class="dataRO" name="TITLE" size=4 readonly=false value=" <? echo $clret->fields[0]; ?> ">
      <input type=text class="dataRO" name="FORENAME" size=32 readonly=false value=" <? echo $clret->fields[2]; ?> ">
      <input type=text class="dataRO" name="SURNAME" size=32 readonly=false value=" <? echo $clret->fields[1]; ?> ">
    </td>
  </tr>
  <tr>
    <td class="dataleft" colspan="1">Company</td>
    <td class="dataleft" colspan="4"><input type=text class="dataRO" name="COMPANY" size=40 readonly=false value=" <? echo $clret->fields[13]; ?> "></td>
  </tr>
  <tr>
    <td class="dataleft" colspan="1">NI</td>
    <td class="dataleft" colspan="1"><input type=text class="dataRO" name="NI" size=11 maxlength=9  readonly=false value=" <? echo $clret->fields[4]; ?> "></td>
    <td class="dataleft" colspan="1"><? echo HBIS_ID; ?> <input type=text class="dataRO" name="HBIS" size=11 maxlength=10 readonly=false value=" <? echo $clret->fields[3]; ?> "></td>
    <td class="dataleft" colspan="2">Special Needs <input type=text class="dataRO" name="SPECIALNEEDS" size=6 maxlength=5 readonly=false value=" <? echo $clret->fields[14]; ?> "></td>
  </tr>
  <tr>
    <td class="dataleft" colspan="1">House</td>
    <td class="dataleft" colspan="4"><input type=text class="dataRO" name="ADDRESS" size=64 readonly=false value=" <? echo $clret->fields[5]; ?> "></td>
  </tr>
  <tr>
    <td class="dataleft" colspan="1">Road</td>
    <td class="dataleft" colspan="4"><input type=text class="dataRO" name="ADD2" size=48 readonly=false value=" <? echo $clret->fields[6]; ?> "></td>
  </tr>
  <tr>
    <td class="dataleft" colspan="1">Area</td>
    <td class="dataleft" colspan="4"><input type=text class="dataRO" name="ADD3" size=48 readonly=false value=" <? echo $clret->fields[7]; ?> "></td>
  </tr>
  <tr>
    <td class="dataleft" colspan="1">Town</td>
    <td class="dataleft" colspan="4"><input type=text class="dataRO" name="TOWN" size=32 readonly=false value=" <? echo $clret->fields[8]; ?> "></td>
  </tr>
  <tr>
    <td class="dataleft" colspan="1">County</td>
    <td class="dataleft" colspan="4"><input type=text class="dataRO" name="COUNTY" size=20 readonly=false value=" <? echo $clret->fields[9]; ?> "></td>
  </tr>
  <tr>
    <td class="dataleft" colspan="1">Postcode</td>
    <td class="dataleft" colspan="4"><input type=text class="dataRO" name="POSTCODE" size=20 readonly=false value=" <? echo $clret->fields[10]; ?> "></td>
  </tr>
  <tr>
    <td class="dataleft" colspan="1">Memo</td>
    <td class="dataleft" colspan=5>
	  <textarea class="dataRO" name="MEMO" rows=3 cols=80 readonly=false><? echo $clret->fields[12]; ?></textarea>
    </td>
  </tr>
  <tr>
    <td class="dataleft" colspan="1">Note</td>
    <td class="dataleft" colspan=4>
	  <input type=text class="dataRO" name="NOTE" size=60 maxlength=40 readonly=false value=" <? echo $clret->fields[11]; ?> ">
    </td>
    <td class="dataright">
	  <button type="button" class="buttonsc" style="width:100;" alt="Edit detail" value="Edit" accesskey="E" onClick="window.location='clientedit.php'">
      Edit Detail</button>
    </td>  
  </tr>
  </table>

  <? if ( $serving ) { ?>
    <table width="100%">
      <th>
        Ticket Detail
      </th>
    </table>
    <table width="100%">
      <tr>
        <td class="button" width="50">
          <a class="button" accesskey="T" href="#" onmouseover="menu.show('mTag', '', this, 50, -340)" onmouseout="menu.hide('mTag')">Tag</a>
        </td>
	    <td class="button" width="4">
 	     &nbsp;
 	    </td>
	    <td class="dataleft">
          <input type=text class="dataRO" name="TAG" size=40 readonly=true value=" <? echo $ciret->fields[2]; ?> ">
        </td>
        <? if ( $ciret->fields[3] ) { ?>
        <td class="dataleft">
          Appointment&nbsp;
          <input type=text class="dataRO" name="APPOINT_ID" size=10 readonly=true value=" <? echo $ciret->fields[3]; ?> ">
        </td>
        <? } ?>
      </tr>
    </table>
    <table width="100%">
      <tr>
        <td class="dataleft">
          Arrived&nbsp;<input type=text class="dataRO" name="ARRIVED" size=6 readonly=true value=" <? echo $ciret->fields[4]; ?> ">
        </td>
        <? if ( $ciret->fields[8] != -1 ) { ?>
        <td class="dataleft">
          Currently At&nbsp;
          <input type=text class="dataRO" name="ROOM" size=20 readonly=true value=" <? echo $ciret->fields[9]; ?> ">
        </td>
        <? } else { ?>
        <td class="dataleft">
          Cleared As&nbsp;
          <input type=text class="dataRO" name="APPOINT_ID" size=30 readonly=true value=" <? echo $ciret->fields[7].' at '.$ciret->fields[5]; ?> ">
        </td>
		<? } ?>
      </tr>
    </table>
    <table width="100%">
    <? $form->draw_header(); ?> 
      <tr>
        <td class="dataleft" colspan="1" >Ticket Note</td>
		<td colspan="4"><? $form->fields["NOTE"]->draw(); ?></td>
        <td valign="bottom" colspan="1">
	      <input type="hidden" name="CURRENT_DAY_phpform_sent" value="1">
	      <button type="submit" class="buttonsc" alt="Save" value="Save" accesskey="S">Save</button>&nbsp;
          <button type="reset"  class="buttonsc" alt="Cancel" value="Cancel" accesskey="C">Cancel</button>
        </td>
      </tr>
    <? $form->draw_footer(); ?> 
    </table>

    <table width="100%">
      <th>
        Ticket Activity
      </th>
        <p></p>
	    <?  rs2html( $tran_ret, 'border=2 cellpadding=3', array('Time','At','Wait/Serve','Processed By')) ?>
      </tr>
    </table>
  <? } ?>

  <table width="100%">
    <th>
      History
    </th>
      <p></p>
      <?  rs2html( $hist_ret, 'border=2 cellpadding=3', array('Date','Office','Processed By','Note','Tags')) ?>
    </tr>
  </table>

  <table width="100%">
    <th>
      Appointments
    </th>
      <p></p>
      <?  rs2html( $appt_ret, 'border=2 cellpadding=3', array('Date','Time','Officer','Type','Office','Room','Visit','Note'), false) ?>
    </tr>
  </table>
  <? }
   else if ( $caller_id ) { 
?>
<table width="100%" border="0" cellspacing="0" cellpadding="3">
<form method="post" action="ticket.php" name="VISITOR" enctype="multipart/form-data">
  <tr>
    <td class="dataleft" colspan="1">Name</td>
    <td class="dataleft" colspan="4">
      <select name="VTITLE">
        <option <? if ( $clret->fields[0] == "    " ) { echo selected ;} ?> value=" "   >&nbsp;</option>
        <option <? if ( $clret->fields[0] == "Mr  " ) { echo selected ;} ?> value="Mr"  >Mr    </option>
        <option <? if ( $clret->fields[0] == "Mrs " ) { echo selected ;} ?> value="Mrs" >Mrs   </option>
        <option <? if ( $clret->fields[0] == "Ms  " ) { echo selected ;} ?> value="Ms"  >Ms    </option>
        <option <? if ( $clret->fields[0] == "Miss" ) { echo selected ;} ?> value="Miss">Miss  </option>
        <option <? if ( $clret->fields[0] == "Dr  " ) { echo selected ;} ?> value="Dr"  >Dr    </option>
      </select>
      <input type=text class="data" name="VFORENAME" size=32 value="<? echo $clret->fields[2]; ?>" onChange="javascript:this.value=this.value.toUpperCase();">
      <input type=text class="data" name="VSURNAME" size=32 value="<? echo $clret->fields[1]; ?>" onChange="javascript:this.value=this.value.toUpperCase();">
    </td>
  </tr>
  <tr>
    <td class="dataleft" colspan="1" >Company</td>
    <td class="dataleft" colspan=4>
	  <input type=text class="data" name="VCOMPANY" size=60 maxlength=40 value="<? echo $clret->fields[13]; ?>" onChange="javascript:this.value=this.value.toUpperCase();">
    </td>
  <tr>
  <tr>
    <td class="dataleft" colspan="1" >Guest Of</td>
    <td class="dataleft" colspan=4>
	  <input type=text class="data" name="VVISIT" size=60 maxlength=40 value="<? echo $ciret->fields[10]; ?>" onChange="javascript:this.value=this.value.toUpperCase();">
    </td>
  <tr>
  </tr>
    <td class="dataleft" colspan="1">Memo</td>
    <td class="dataleft" colspan=4>
	  <textarea class="data" name="VMEMO" rows=3 cols=80><? echo $ciret->fields[11]; ?></textarea>
    </td>
  </tr>
  <tr>
    <td class="dataleft" colspan="1"> </td>
    <td class="dataright" colspan=4> 
      <input type="hidden" name="VISITOR_phpform_sent" value="1">
      <button type="submit" class="buttonsc" alt="Save" value="Save" accesskey="S">Save</button>&nbsp;
      <button type="reset"  class="buttonsc" alt="Cancel" value="Cancel" accesskey="C">Cancel</button>
    </td>
  </tr>
</form>
</table>
<? }
   else { 
?>
<table width="100%" border="0" cellspacing="0" cellpadding="3">
<tr>
  <td colspan="4">Please search for caller details using the options above</td>
</tr>
<? if ( SHOW_ANON_TAGS ) { ?> 
<tr>
  <td colspan="1" class="dataleft" width="100">
    &nbsp;
  </td>
  <td colspan="1" class="button" width="50">
    <a class="button" accesskey="T" href="#" onmouseover="menu.show('mTag', '', this, 50, -60)" onmouseout="menu.hide('mTag')">Tag</a>
  </td>
  <td colspan="1" class="button" width="50">
    &nbsp;
  </td>
  <td colspan="1" class="dataleft">
    <input type=text class="dataRO" name="TAG" size=40 readonly=true value=" <? echo $ciret->fields[2]; ?> ">
  </td>
</tr>
<? } ?>
<tr>
<? $form->action = "ticket.php";
    $form->draw_header();
?>   
  <td class="dataleft" colspan="1" >Ticket Note</td>
  <td colspan="4"><? $form->fields["NOTE"]->draw(); ?></td>
  <td colspan="1" valign="bottom">
	<input type="hidden" name="ticket_id" value=" <? echo $ticket_id ?> ">
	<input type="hidden" name="CURRENT_DAY_phpform_sent" value="1">
	<button type="submit" class="buttonsc" alt="Save" value="Save" accesskey="S">Save</button>&nbsp;
    <button type="reset"  class="buttonsc" alt="Cancel" value="Cancel" accesskey="C">Cancel</button>
  </td>
<? $form->draw_footer(); ?> 
</tr>
</table>