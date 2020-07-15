<?php
/*
 *  Copyright (C) 2018 Laksamadi Guko.
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
session_start();
// hide all error
error_reporting(0);
if(!isset($_SESSION["mikhmon"])){
  header("Location:../admin.php?id=login");
}else{
  include_once('/tools/temp.php');
  $tlnw = explode("~|~", $nwtl);
  $nwtoken = $tlnw[0];
  $nwcid = $tlnw[1];
  if($nwtoken=="" || $nwcid==""){
    $nwtoken = $token;
    $nwcid = $cid;
  }

  if(isset($_POST['name'])){
    $host = ($_POST['host']);
    $name = ($_POST['name']);
    $interval = ($_POST['interval']);
    $timeout = ($_POST['timeout']);
    $reportmode = ($_POST['reportmode']);
    $up = ($_POST['up']);
    $down = ($_POST['down']);

    if($reportmode=="telegram"){
      $up = '[/tool fetch url="https://api.telegram.org/bot'.$nwtoken.'/sendmessage?chat_id='.$nwcid.'&text============================%0A%10%10%10%10%10%10%10*Mikhmon%10Netwatch%10Monitor*%0A===========================%0AName : '.$name.'%0AStatus : Normal&parse_mode=markdown" keep-result=no]';
      $down = '[/tool fetch url="https://api.telegram.org/bot'.$nwtoken.'/sendmessage?chat_id='.$nwcid.'& text============================%0A%10%10%10%10%10%10%10*Mikhmon%10Netwatch%10Monitor*%0A===========================%0AName : '.$name.'%0AStatus : Down&parse_mode=markdown" keep-result=no]';
    }
    $comment = $name . "-|-" .$reportmode;

    $API->comm("/tool/netwatch/add", array(
	    "host" => "$host",
	    "interval" => "$interval",
	    "timeout" => "$timeout",
	    "up-script" => "$up",
	    "down-script" => "$down",
	    "comment" => "$comment",
	    ));
    echo "<script>window.location='./?systool=netwatch&session=".$session."'</script>";
  }
}
?>
<div class="row">
<div class="col-8">
<div class="card box-bordered">
  <div class="card-header">
    <h3><i class="fa fa-eye"></i> Add Netwatch </h3> <i id="loader" style="display: none;" ><i class='fa fa-circle-o-notch fa-spin'></i> Processing... </i>
  </div>
  <div class="card-body">
<form autocomplete="off" method="post" action="">  
  <a class="btn bg-warning" href="./?systool=netwatch&session=<?php echo $session;?>"> <i class="fa fa-close"></i> Close</a>
  <button type="submit" onclick="loader()" class="btn bg-primary" name="save"><i class="fa fa-save"></i> Save</button>

<table class="table">
  <tr>
    <td class="align-middle" >Name</td><td><input class="form-control" type="text" autocomplete="off" name="name" required="1" autofocus></td>
	</tr>
  <tr>
    <td class="align-middle">Host</td><td><input class="form-control" type="text" autocomplete="off" name="host" placeholder="xxx.xxx.xx.x" required="1" autofocus></td>
  </tr>
  <tr>
    <td class="align-middle">Interval</td><td><input class="form-control" type="text" autocomplete="off" name="interval" required="1" autofocus></td>
  </tr>
  <tr>
    <td class="align-middle">Timeout</td><td><input class="form-control" type="text" autocomplete="off" name="timeout" required="1" autofocus></td>
  </tr>
  <tr>
    <td class="align-middle">Report Mode</td><td>
      <select class="form-control" onchange="nwReportMode();" id="reportmode" name="reportmode" required="1">
        <option value="">Select...</option>
        <option value="telegram">Telegram</option>
        <option value="manual">Manual</option>
      </select>
    </td>
  </tr>
   <tr style="display:none" id="upf">
    <td class="align-middle">When UP</td><td>
        <textarea class="form-control" name="up" cols="30" rows="7" id="up"></textarea>
		</td>
  </tr>
  <tr style="display:none" id="downf">
    <td class="align-middle">When DOWN</td><td>
        <textarea class="form-control" name="down" cols="30" rows="7" id="down"></textarea>
		</td>
  </tr>
  <tr style="display:none" id="tlgrminfo">
    <td colspan="2">
        <?= $_telegram_info?>
	</td>
  </tr>
</table>
</form>
</div>
</div>
</div>
<div class="col-4">
    <?=$_netwatch_details?>
</div>
</div>
</div>
</div>