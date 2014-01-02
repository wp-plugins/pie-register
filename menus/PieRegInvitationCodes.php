<?php
//$piereg['invitation_code_usage']

if( $_POST['notice'] ){
	echo '<div id="message" class="updated fade"><p><strong>' . $_POST['notice'] . '.</strong></p></div>';
}
?>
<script type="text/javascript">
function confirmDel(id)
{
	var conf = window.confirm("Are you sure?");
	if(conf)
	{
		document.getElementById("invi_del_id").value = id;
		document.getElementById("del_form").submit();
	}
}
function changeStatus(id)
{
	document.getElementById("status_id").value = id;
	document.getElementById("status_form").submit();	
}
</script>
<form method="post" action="" id="del_form">
  <input type="hidden" id="invi_del_id" name="invi_del_id" value="0" />
</form>
<form method="post" action="" id="status_form">
  <input type="hidden" id="status_id" name="status_id" value="0" />
</form>
<div id="container">
  <div class="right_section">
    <div class="invitation">
      <h2>Invitation Codes</h2>
      <form method="post" action="">
        <ul>
          <li>
            <div class="fields">
              <h2>Guideline</h2>
              <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse..</p>
            </div>
          </li>
          <li>
            <div class="fields">
              <label>Enable Invitation Codes</label>
              <div class="radio_fields">
                <input id="enable_invitation_codes_yes" type="radio" value="1" name="enable_invitation_codes" <?=($piereg['enable_invitation_codes']=="1")?'checked="checked"':''?> />
                <label for="enable_invitation_codes_yes">Yes</label>
                <input id="enable_invitation_codes_no" type="radio" value="0" name="enable_invitation_codes" <?=($piereg['enable_invitation_codes']=="0")?'checked="checked"':''?> />
                <label for="enable_invitation_codes_no">No</label>
              </div>
              <span class="quotation">Set this to Yes if you want users to register only by your defined invitaion codes. You will have to add invitation code field in the form editor.</span> </div>
          </li>
          <li>
            <div class="fields">
              <h3>Insert Code</h3>
              <textarea id="piereg_codepass" name="piereg_codepass"></textarea>
              <span class="note"><strong>Note:</strong> Each Code will be on a Separate Line.</span> </div>
          </li>
          <li>
            <div class="fields">
              <h3>Usage</h3>
              <input style="float:left;" value=""  type="text" name="invitation_code_usage" class="input_fields2" />
               <span style="float:left;clear:both;" class="note">Number of time a particular code can be used for registration.</span> 
            </div>
          </li>
          <li>
            <p class="submit">
              <input name="Submit" style="background: #464646;color: #ffffff;border: 0;cursor: pointer;padding: 5px 0px 5px 0px;margin-top: 15px;
min-width: 113px;float:right;" value="<?php _e('Save Changes','piereg');?>" type="submit" />
            </p>
          </li>
        </ul>
      </form>
  <table border="1" cellspacing="0" cellpadding="10">
        <tr>
          <th width="4%">#</th>
          <th width="74%" align="left">Code Name</th>
          <th width="6%">Usage</th>
          <th width="6%">Used</th>
          <th width="10%">Action</th>
        </tr>
        <? 
	$codes = $wpdb->get_results( "SELECT * FROM $codetable order by name asc" );
	if(count($codes) > 0)
	{
		$a = 1;
		foreach($codes as $c)
		{
		?>
        <tr>
          <td align="center"><?=$a?></td>
          <td align="left"><?=$c->name?></td>
          <td align="center"><?=$c->usage?></td>
          <td align="center"><?=$c->count?></td>
          <td align="center"><a onclick="changeStatus(<?=$c->id?>);" href="javascript:;" class="<?=($c->status==1) ? "active"  : "inactive";?>"></a> <a class="delete" href="javascript:;" onclick="confirmDel(<?=$c->id?>);" title="Delete"></a></td>
        </tr>
        <?	
		$a++;
		}	
	}
?>
      </table>
      <!--<div class="pagination">
        <ul>
          <li><a href="#">1</a></li>
          <li><a href="#">2</a></li>
          <li class="active"><a href="#">3</a></li>
          <li><a href="#">4</a></li>
          <li><a href="#">5</a></li>
          <li><a href="#">6</a></li>
          <li><a href="#">7</a></li>
        </ul>
      </div>-->
    </div>
  </div>
</div>
