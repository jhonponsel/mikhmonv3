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

    // routeros api
    include_once('../lib/routeros_api.class.php');
    include_once('../lib/formatbytesbites.php');
    $API = new RouterosAPI();
    $API->debug = false;
    $API->connect($iphost, $userhost, decrypt($passwdhost));

    $getmrDate = $API->comm("/system/clock/print");
    $thisBL = $getmrDate[0]['date'];
    $thisM = ucfirst(substr($thisBL,0,3));
    $thisY = substr($thisBL,-4);
    
    $getDataMr = $API->comm("/system/script/print", array("?name" => "RekapPendapatan"));
    $dataDetail = $getDataMr[0];

    $mrMonth = $dataDetail['comment'];
    $mrValue = $dataDetail['source'];

    $getData_thisM = $API->comm("/system/script/print", array("?name" => "ReportPendapatan"));
    $dataDetail_thisM = $getData_thisM[0];
    $mrValue_thisM = $dataDetail_thisM['source'];

    $mrMonth = str_replace("-|-", "'", $mrMonth);
    $mrM = substr($mrMonth, -5);
    if($mrM=="'Dec'"){$a = $thisY-1;$b = $thisY;$thisY = $a." - ".$b;}
}
?>

<div class="card">
<div class="card-header"><h3><i class="fa fa-area-chart"></i> <?= $_income_recap?></h3></div>
    <div class="card-body">
        <div class="row">
            <script src="./js/highcharts/highcharts.js"></script>
            <script src="./js/highcharts/themes/hc.<?= $theme; ?>.js"></script>
            <script src="./js/highcharts/modules/exporting.js"></script>
            <div class="col-12">
                <select class="dropd pd-5" id="chartType" onchange="chartTypeChange();">
                    <option value="">Select Chart Type...</option>
                    <option value="line">Line Chart</option>
                    <option value="column">Bar Chart</option>
                </select>
            </div>
            <div class="col-12" id="container_recap"></div>
            <script type="text/javascript">

                var $mV = "<? echo $mrValue;?>";

                if($mV=="0"){
                    var cat = [<?= "'".$thisM."'";?>];
                    var datas = [<?= $mrValue_thisM;?>];
                }else{
                    var cat = [<?= $mrMonth.", '".$thisM."'";?>];
                    var datas = [<?= $mrValue.",".$mrValue_thisM;?>];
                }
                
                var theme = '<?= $theme; ?>';
                var currency = '<?= $currency;?>';

                var chart = Highcharts.chart('container_recap', {
                    chart: {
                    height: 500,
                    type: 'column',
                    },
                    
                    colors: (theme == 'dark' ? ['#44A9A8', '#A9FF97', '#FF7474', '#90B1D8', '#C3FFFF', '#FF197F', '#FFC3FF', '#6ED854', '#F86C6C', '#90B1D8', '#C3FFFF', '#44A9A8'] : ['#5CBAE6', '#B6D957', '#FAC364', '#8CD3FF', '#D998CB', '#F2D249', '#93B9C6', '#CCC5A8', '#52BACC', '#DBDB46', '#98AAFB', '#D998CB']),

                    title: {
                        text: '<?= $_income_recap . ' : ' . $thisY;?>'
                    },

                    subtitle: {
                        text: '<?= $hotspotname;?>'
                    },

                    exporting: {
                        enabled: true,
                        buttons: {
                            contextButton: {
                                symbolStroke: (theme == 'dark' ? '#f3f4f5' : theme == 'pink' ? '#666666' : '#666666'),
                                theme: {
                                    fill: (theme == 'dark' ? '#3A4149' : theme == 'pink' ? '#FFE6F1': '#FFFFFF'),
                                    states: {
                                        hover: {
                                            fill: (theme == 'dark' ? '#343B41' : theme == 'pink' ? '#FFE6F1': '#F2F2F2')
                                        },
                                        select: {
                                            fill: (theme == 'dark' ? '#343B41' : theme == 'pink' ? '#FFE6F1': '#F2F2F2')
                                        }
                                    }
                                }
                            }
                        }
                    },

                    xAxis: {
                        tickInterval: 1,
                        categories: cat,
                    },

                    yAxis: {
                        title: {
                            text: '<?= $_total_income;?>'
                        },
                        labels: {
                            formatter: function() {
                                s = this.value;
                                
                                if(s<1000000){
                                    s = this.value / 1000 + 'k';
                                }else if(s >=1000000 && s < 1000000000){
                                    s = this.value/ 1000000 + (currency == 'Rp' ? 'jt' : 'M');
                                }else{
                                    s = this.value/ 1000000000 + (currency == 'Rp' ? 'M' : 'B');
                                }
                                return s;
                            }
                        }
                    },

                    legend: {
                        layout: 'horizontal',
                        align: 'center',
                        verticalAlign: 'bottom',
                    },

                    
                    plotOptions: {
                        series: {
                            label: {
                                connectorAllowed: false,
                            }
                        }
                    },

                    series: [{
                        name: '<?=$_income;?>',
                        colorByPoint: true,
                        showInLegend: false,
                        data: datas
                    }],

                    tooltip: {
                        formatter:function(){
                            var header = '<b style="color: #20a8d8;"><?= $_total_income;?></b><br/>'
                            s = header + '<?=$_month;?>: <b>' + this.x + '</b><br/>' + '<?=$_income;?>: <b><?= $currency;?> '+Highcharts.numberFormat(this.point.y,0,',','.')+'</b><br/>';
                            return s;
                        }
                    },
                    
                    responsive: {
                        rules: [{
                            condition: {
                                maxWidth: 500
                            },
                            chartOptions: {
                                legend: {
                                    layout: 'horizontal',
                                    align: 'center',
                                    verticalAlign: 'bottom',
                                }
                            }
                        }]
                    }
                });
            </script>

            <script>
                function chartTypeChange() {
                    var chartType = document.getElementById("chartType").value;
                    if(chartType=="line"){
                        chart.update({
                            chart: {
                                type: 'area',

                            },
                            series: [{
                                name: '<?=$_income;?>',
                                colorByPoint: false,
                                showInLegend: true,
                                animation: true,
                            }],
                        });
                        chart.redraw();
                    }else if(chartType=="column"){
                        chart.series[0].remove();
                        chart.addSeries({
                            data:  datas
                        });
                        chart.update({
                            colors: (theme == 'dark' ? ['#44A9A8', '#A9FF97', '#FF7474', '#90B1D8', '#C3FFFF', '#FF197F', '#FFC3FF', '#6ED854', '#F86C6C', '#90B1D8', '#C3FFFF', '#44A9A8'] : ['#5CBAE6', '#B6D957', '#FAC364', '#8CD3FF', '#D998CB', '#F2D249', '#93B9C6', '#CCC5A8', '#52BACC', '#DBDB46', '#98AAFB', '#D998CB']),
                            chart: {
                                type: 'column'
                            },

                            series: [{
                                colorByPoint: true,
                                showInLegend: false,
                            }],
                        });
                    }

                }
            </script>
        </div>
    </div>