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

// enable netwatch
if($enablenetwatch != ""){
	$API->comm("/tool/netwatch/set", array(
		".id"=> "$enablenetwatch",
		"disabled" => "no",
	));
	
	echo "<script>window.location='./?systool=netwatch&session=".$session."'</script>";
}
	
// disable netwatch
elseif($disablenetwatch != ""){
	$API->comm("/tool/netwatch/set", array(
		".id"=> "$disablenetwatch",
		"disabled" => "yes",
	));

	echo "<script>window.location='./?systool=netwatch&session=".$session."'</script>";
}

// remove netwatch
elseif($removenetwatch != ""){
	$API->comm("/tool/netwatch/remove", array(
		".id"=> "$removenetwatch"
	));

	echo "<script>window.location='./?systool=netwatch&session=".$session."'</script>";
}

?>