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
if (!isset($_SESSION["mikhmon"])) {
  header("Location:../admin.php?id=login");
} else {

  // load session MikroTik
  $session = $_GET['session'];

  // lang
  include('../include/lang.php');
  include('../lang/'.$langid.'.php');

  // load config
  include('../include/config.php');
  include('../include/readcfg.php');

  // routeros api
  include_once('../lib/routeros_api.class.php');
  $API = new RouterosAPI();
  $API->debug = false;
  $API->connect($iphost, $userhost, decrypt($passwdhost));

  $idbl = $_GET['idbl'];
  $thisM = substr($idbl,0,3);
  $thisY = substr($idbl,-4);

  $idbls = array(1 => "jan", "feb", "mar", "apr", "may", "jun", "jul", "aug", "sep", "oct", "nov", "dec");
  $idblf = array(1 => "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");

  $month = array_search($thisM, $idbls);
  $month = $idblf[$month];

  $getData = $API->comm("/system/script/print", array(
    "?owner" => "$idbl",
  ));
  $TotalReg = count($getData);
  $incomeTotal = 0;

  if($TotalReg>1){
    $item = 'Items';
  }else{
    $item = 'Item';
  }

  // memanggil library FPDF
  require('../lib/fpdf/fpdf.php');
  // intance object dan memberikan pengaturan halaman PDF

  class PDF extends FPDF{
    function Footer(){
      $this->SetY(-10);
      $this->SetFont('Arial', '', 8);
      $this->SetY(-10);
      $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}', 0, 0, 'R');
    }
  }

  $pdf = new PDF('L','mm','A4');
  // membuat halaman baru
  $pdf->setMargins(30,30);
  $pdf->AliasNbPages();
  $pdf->AddPage();
  $pdf->setFont('Arial', 'B', 18);
  $pdf->Cell(0,0, strtoupper($_sales_report_data),0,0,'C');
  $pdf->Ln(6);
  $pdf->setFont('Arial', 'B', 12);
  $pdf->Cell(0,0, $hotspotname . ' | ' . $dnsname,0,0,'C');
  $pdf->Ln();

  // Memberikan space kebawah agar tidak terlalu rapat
  $pdf->Cell(10,7,'',0,1);

  $pdf->setFont('Arial', '', 10);
  $pdf->Cell(25, 6, $_period, 0, 0, 'L');
  $pdf->Cell(5,6,':',0,0);
  $pdf->Cell(25, 6, $month .' ' . $thisY, 0, 0, 'L');

  $pdf->Ln();
  $pdf->Cell(25, 6, $_date_print, 0, 0, 'L');
  $pdf->Cell(5,6,':',0,0);
  $pdf->Cell(25, 6, date('d/m/Y'), 0, 0, 'L');
  $pdf->Cell(185,6, $_sold_voucher . ' : ' . $TotalReg . ' ' . $item,0,0, 'R');
  $pdf->Ln(6);

  // Heading Tabel
  $pdf->SetFillColor(221, 221, 221);
  $pdf->SetFont('Arial','B',10);
  $pdf->Cell(10,8,'No',0,0,'L',true);
  $pdf->Cell(45,8,$_date,0,0,'L',true);
  $pdf->Cell(40,8,$_user_name,0,0,'L',true);
  $pdf->Cell(50,8,$_profile,0,0,'L',true);
  $pdf->Cell(50,8,$_comment,0,0,'L',true);
  $pdf->Cell(45,8,$_price,0,1,'L',true);

  $pdf->SetFillColor(247, 247, 247);
  $pdf->SetFont('Arial','',10);
  // Data
  $fill = false;
  for ($i=0; $i < $TotalReg ; $i++) { 
    $getname = explode("-|-", $getData[$i]['name']);
    $pdf->Cell(10,6,$i+1,0,0,'L', $fill);
    $pdf->Cell(45,6,$getname[0],0,0,'L', $fill);
    $pdf->Cell(40,6,$getname[2],0,0,'L', $fill);
    $pdf->Cell(50,6,$getname[7],0,0,'L', $fill);
    $pdf->Cell(50,6,$getname[8],0,0,'L', $fill);
    $pdf->Cell(10,6,$currency,0,0,'L', $fill);
    $pdf->Cell(35,6,number_format($getname[3],2,",","."),0,1,'R', $fill);
    $incomeTotal+=$getname[3];
    $fill = !$fill;
  }
  // Data Total
  $pdf->SetFillColor(221, 221, 221);
  $fill = true;
  $pdf->SetFont('Arial','B',10);
  $pdf->Cell(55,8,'Total',0,0,'L', $fill);
  $pdf->Cell(40,8,'',0,0,'L', $fill);
  $pdf->Cell(50,8,'',0,0,'L', $fill);
  $pdf->Cell(50,8,'',0,0,'L', $fill);
  $pdf->Cell(10,8,$currency,0,0,'L', $fill);
  $pdf->Cell(35,8,number_format($incomeTotal,2,",","."),0,1,'R', $fill);
  
  $pdf->Output('I', $_report.'-'.$month .' ' . $thisY.'.pdf');

}
?>