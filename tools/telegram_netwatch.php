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

  if(isset($_POST['token'])){
    $token = ($_POST['token']);
    $chid = ($_POST['chatid']);

    $tonwtl = '<?php $nwtl="' . $token . "~|~" . $chid . '";?>';
    $temp = './tools/temp.php';
		$handle = fopen($temp, 'w') or die('Cannot open file:  ' . $temp);
		$data = $tonwtl;
    fwrite($handle, $data);
    echo "<script>window.location='./?netwatch=set-telegram-netwatch&session=" . $session . "'</script>";
  }
}
?>
<div class="row">
<div class="col-8">
<div class="card box-bordered">
  <div class="card-header">
    <h3><i class="fa fa-gear"></i> Telegram Netwatch </h3>
  </div>
  <div class="card-body">
<form autocomplete="off" method="post" action="">  
  <a class="btn bg-warning" href="./?systool=netwatch&session=<?php echo $session;?>"> <i class="fa fa-close"></i> Close</a>
  <button type="submit" class="btn bg-primary" name="save"><i class="fa fa-save"></i> Save</button>
<table class="table">
  <tr>
    <td class="align-middle">Token</td><td><input class="form-control" type="text" autocomplete="off" name="token" required="1" placeholder="xxxxxxxxx:ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghi" value="<?=$nwtoken;?>" autofocus></td>
	</tr>
  <tr>
    <td class="align-middle">Chat ID</td><td><input class="form-control" type="text" autocomplete="off" name="chatid" required="1" placeholder="-1234567890" value="<?=$nwcid;?>" autofocus></td>
  </tr>
</table>
</form>
</div>
</div>
</div>
<div class="col-4">
    <?=$_telegram_netwatch_details?>
</div>
</div>