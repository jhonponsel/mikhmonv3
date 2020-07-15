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

  $getEmail = $API->comm("/tool/e-mail/print");
  $mdetail =  $getEmail[0];
  $maddr = $mdetail['address'];
  $mport = $mdetail['port'];
  $mtls = $mdetail['start-tls'];
  $mfrom = $mdetail['from'];
  $muser = $mdetail['user'];
  $mpassword = $mdetail['password'];

  if($API->comm("/system/script/print", array("?name" => "BackupEmail"))){
    $cekScript = "found";
    $detailEmail = $API->comm("/system/script/print", array("?name" => "BackupEmail"));
    $getdtEmail = $detailEmail[0];
    $escid = $getdtEmail['.id'];
    $ecomment = $getdtEmail['comment'];
    $detailcomment = explode("-|-", $ecomment);
    $emailto = $detailcomment[0];
    $emailinterval = $detailcomment[1];

    $schEmail = $API->comm("/system/scheduler/print", array("?name" => "BackupEmail"));
    $getschEmail = $schEmail[0];
    $eschid = $getschEmail['.id'];
    $esdisable = $getschEmail['disabled'];
  }else{
    $cekScript = "notfound";
  }
  if(isset($_POST['server'])){
    $server = ($_POST['server']);
    $port = ($_POST['port']);
    $starttls = ($_POST['starttls']);
    $from = ($_POST['from']);
    $user = ($_POST['user']);
    $pass = ($_POST['password']);
    $sendto = ($_POST['sendto']);
    $interval = ($_POST['interval']);

    $emailscript = ':log info "backup beginning now";:local date [/system clock get date];:local day [:pick $date 4 6];:local month [:pick $date 0 3];:local year [:pick $date 7 11];:local datebc "$day-|-$month-|-$year";:global backupfile ("Backup-" . [/system identity get name] . ":$datebc");export file=$backupfile;:log info "backup pausing for 15s";:delay 15s;:log info "backup being emailed";/tool e-mail send to="'.$sendto.'" subject=([/system identity get name] . " Backup") from='.$from.' body=("Ini adalah e-mail otomatis yang dibuat oleh " . [/system identity get name]) file=$backupfile;:delay 30s;/file remove $backupfile;:log info "backup finished"';

    $emailcomment = $sendto."-|-".$interval."-|-";

    $API->comm("/tool/e-mail/set", array(
      "address" => "$server",
      "port" => "$port",
      "start-tls" => "$starttls",
      "from" => "$from",
      "user" => "$user",
      "password" => "$pass"
    ));

    if($cekScript=="notfound"){
      $API->comm('/system/scheduler/add', array(
        "name"     => 'BackupEmail',
        "interval" => $interval,
        "start-time" => '00:00:20',
        "on-event" => "BackupEmail",
        "comment" => "Backup Email",
      ));
      
      $API->comm("/system/script/add", array(
        "name" => "BackupEmail",
        "source" => "$emailscript",
        "comment" => "$emailcomment"
      ));
    }else if($cekScript=="found"){
      $API->comm("/system/script/set", array(
        ".id" => "$escid",
        "source" => "$emailscript",
        "comment" => "$emailcomment"
      ));
      $API->comm("/system/scheduler/set", array(
        ".id" => "$eschid",
        "interval" => "$interval",
        "start-time" => "00:00:20"));
    }
    
    echo "<script>window.location='./?systool=emailbkp&session=".$session."'</script>";
  }
}
?>
<script>
  function PassUser(){
    var x = document.getElementById('passUser');
    if (x.type === 'password') {
    x.type = 'text';
    } else {
    x.type = 'password';
    }}
</script>
<div class="row">
<div class="col-8">
<div class="card box-bordered">
  <div class="card-header">
    <h3><i class="fa fa-envelope"></i> Email Backup </h3>
  </div>
  <div class="card-body">
<form autocomplete="off" method="post" action="">  
  <div>
    <button type="submit" class="btn bg-primary" name="save"><i class="fa fa-save"></i> Save</button>
		<a class="btn bg-danger" <?php if($esdisable=="false"){echo "href=./?disable-scheb=".$eschid."&session=".$session;}else{echo "href=./?enable-scheb=".$eschid."&session=".$session;} ?>> <i class="fa <?php if($esdisable=="false"){echo "fa-lock";}else{echo "fa-unlock";} ?>"></i> <?php if($esdisable=="false"){echo "Disable";}else{echo "Enable";}?></a>
    <div style="float:right;"><b>Status: </b><?php if($esdisable=="false"){echo "<i style='color: #00ACC1;'>" . $_active . "</i>";}else{echo "<i style='color: #F86C6B;'>" . $_inactive . "</i>";}?></div>
  </div>

<table class="table">
  <tr>
    <td class="align-middle">Server</td><td><input class="form-control" type="text" autocomplete="off" name="server" required="1" value="<?=$maddr;?>" autofocus></td>
	</tr>
  <tr>
    <td class="align-middle">Port</td><td><input class="form-control" type="number" autocomplete="off" name="port" required="1" value="<?=$mport;?>" autofocus></td>
  </tr>
  <tr>
    <td class="align-middle">Start TLS</td><td>
			<select class="form-control" id="starttls" name="starttls" required="1">
        <option value="<?=$mtls;?>"><?php if($mtls=="yes"){echo "Yes";}elseif($mtls=="no"){echo "No";}else{echo "TLS Only";} ?></option>
        <option value="yes">Yes</option>
        <option value="no">No</option>
        <option value="tls only">TLS Only</option>
			</select>
		</td>
  </tr>
  <tr>
    <td class="align-middle"><?= $_from?></td><td><input class="form-control" type="text" autocomplete="off" name="from" required="1" value="<?=$mfrom;?>" autofocus></td>
	</tr>
	<tr>
    <td class="align-middle"><?= $_users?></td><td><input class="form-control" type="email"  autocomplete="off" name="user" value="<?=$muser;?>"></td>
  </tr>
  <tr>
    <td class="align-middle"><?=$_password?></td><td>
        <div class="input-group">
          <div class="input-group-11 col-box-10">
            <input class="group-item group-item-l" id="passUser" type="password" name="password" autocomplete="new-password" value="<?=$mpassword;?>" required="1">
          </div>
            <div class="input-group-1 col-box-2">
              <div class="group-item group-item-r pd-2p5 text-center">
              <input title="Show/Hide Password" type="checkbox" onclick="PassUser()">
            </div>
            </div>
        </div>
		</td>
  </tr>
  <tr>
    <td class="align-middle"><?= $_send_to?></td><td><input class="form-control" type="text"  autocomplete="off" name="sendto" value="<?php if($cekScript=="found"){echo $emailto;}else{echo "";} ?>" placeholder="<?= $_send_to?>"></td>
  </tr>
  <tr>
    <td class="align-middle"><?= $_backup_interval?></td><td><input class="form-control" type="text"  autocomplete="off" name="interval" value="<?php if($cekScript=="found"){echo $emailinterval;}else{echo "";} ?>"></td>
  </tr>
</table>
</form>
</div>
</div>
</div>
<div class="col-4">
    <?=$_email_details?>
</div>
</div>