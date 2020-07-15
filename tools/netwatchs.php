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

// hide all error
error_reporting(0);
if(!isset($_SESSION["mikhmon"])){
	header("Location:../admin.php?id=login");
}else{


// get user profile
	$getnetwatch = $API->comm("/tool/netwatch/print");
	$TotalNetwatch = count($getnetwatch);
// count user profile
	$countNetwatch = $API->comm("/tool/netwatch/print", array(
		"count-only" => "",));
}
?>
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header align-middle">
    		<h3><i class=" fa fa-eye"></i> Netwatch 
					&nbsp; | &nbsp; <a href="./?netwatch=add-netwatch&session=<?php echo $session;?>" title="Add Netwatch"><i class="fa fa-plus-square"></i> Add</a>
					&nbsp; | &nbsp; <a href="./?netwatch=set-telegram-netwatch&session=<?php echo $session;?>" title="Setting Telegram -> Netwatch"><i class="fa fa-gear"></i> Telegram Netwatch</a>
				</h3>
			</div>
<!-- /.card-header -->
<div class="card-body">
<div class="overflow box-bordered" style="max-height: 75vh"> 			   
<table id="tFilter" class="table table-bordered table-hover text-nowrap">
  <thead>
  <tr> 
		<th style="min-width:50px;" class="text-center" >
		<?php
		if($countNetwatch < 2 ){echo "$countNetwatch item  ";
  		}elseif($countNetwatch > 1){echo "$countNetwatch items   ";}
	?></th>
		<th class="align-middle">Name</th>
		<th class="align-middle">Host</th>
		<th class="align-middle">Interval</th>
		<th class="align-middle">Timeout</th>
		<th class="align-middle">Status</th>
		<th class="align-middle">Since</th>
		<th class="align-middle">Mode</th>
    </tr>
  </thead>
  <tbody>
<?php
for ($i=0; $i<$TotalNetwatch; $i++){

$netwatchdetail = $getnetwatch[$i];
$nwid = $netwatchdetail['.id'];
$nwcomment = $netwatchdetail['comment'];
$nwhost = $netwatchdetail['host'];
$nwinterval = $netwatchdetail['interval'];
$nwtimeout = $netwatchdetail['timeout'];
$nwstatus = $netwatchdetail['status'];
$nwsince = $netwatchdetail['since'];
$nwdisable = $netwatchdetail['disabled'];

$nwname = explode("-|-", $nwcomment)[0];
if(explode("-|-", $nwcomment)[1]=="telegram"){
	$nwmode = "Telegram";
}else{
	$nwmode = "Manual";
}

echo "<tr>";
?>
  <td style='text-align:center;'><i class='fa fa-minus-square text-danger pointer' onclick="if(confirm('Are you sure to delete Netwatch (<?php echo $nwname;?>)?')){window.location='./?remove-nw=<?php echo $nwid;?>&session=<?php echo $session;?>'}else{}" title='Remove <?php echo $tname;?>'></i>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
  <?php
if($nwdisable == "true"){ $tcolor = "#616161"; echo "<a title='Enable User ".$nwname . "'  href='./?enable-nw=".$nwid . "&session=".$session."'><i class='fa fa-lock '></i></a></td>";}else{ $tcolor = "#f3f4f5";echo "<a title='Disable User ".$nwname . "'  href='./?disable-nw=".$nwid . "&session=".$session."'><i class='fa fa-unlock '></i></a></td>";}
echo "<td>$nwname</td>";
echo "<td>$nwhost</td>";
echo "<td>$nwinterval</td>";
echo "<td>$nwtimeout</td>";
echo "<td>$nwstatus</td>";
echo "<td>$nwsince</td>";
echo "<td>$nwmode</td>";
}
?>
  </tbody>
</table>
</div>
</div>
</div>
</div>
</div>