<?php

/* @var $this yii\web\View */

use kartik\grid\GridView;
use yii\helpers\Html;
use miloschuman\highcharts\Highcharts;

$this->title = $report_name;
//$this->params['breadcrumbs'][] = $this->title;
?>

<div style="display:none">
    <?php
    echo Highcharts::widget([
        'scripts' => [
            'highcharts-more', // enables supplementary chart types (gauge, arearange, columnrange, etc.)
            'themes/grid'        // applies global 'grid' theme to all charts
        ],
    ]);
    ?>  
</div>

  <div class="row">
    <div class="col-md-12">
        <div id="chart"></div>
    </div>

</div>

<br/>


<?php
//เตรียมชุดข้อมูลไปใส่ให้กราฟ แกน x,y
//เตรียมชุดข้อมูลไปใส่ให้กราฟ แกน x,y
$data = [];
$sum = 0;
foreach($rawData as $r){
    $data[] = [
        'name' => $r['dch_type_name'],
        'y' => intval($r['count_vn']),
    ];
    $sum += $r['count_vn'];
}

$js_data = json_encode($data);





// chart
$this->registerJs(" 
    $(function () {
    $('#chart').highcharts({
        chart: {
            type: 'pie',
        },
         credits: {
            enabled: false
        },
        title: {
            text: '$report_name'
        },
        
        tooltip: {
            enabled: false,
        },
                         
         yAxis: {
            min: 0,
            title: {
                text: 'จำนวน'
            },
            
        },
         xAxis: {
            type: 'category'
        },
       legend: {
            enabled: true
        },

        plotOptions: {
            series: {
                borderWidth: 0,
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.y:.0f} ครั้ง',
                }
            }
        },
        series: [{
                name: 'จำนวน',
                colorByPoint: true,
                data:$js_data
        }]
     },  function(chart) { // on complete
        var total = $sum;    
        chart.renderer.text('รวม: ' + total + ' ครั้ง', 20, 105)
            .css({
                color: '#4572A7',
                fontSize: '16px'
            })
        .add();
        });
    });
");
// จบ chart



?>


<br/>

           
           <?php
                echo GridView::widget([
                    'dataProvider' => $dataProvider,
                    'panel' => [
                        'heading' => $report_name,
                        'before' => '',
                        'type' => 'primary',
                        'after' => 'ประมวลผล ณ วันที่ ' . date('Y-m-d H:i:s')
                    ],
                    'export' => [
                        'fontAwesome' => true,
                        'showConfirmAlert' => false,
                        'target' => GridView::TARGET_BLANK
                    ],
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        [
                            'attribute' => 'dch_type_name',
                            'header' => 'ประเภทการจำหน่ายผู้ป่วย'
                        ],
                         [
                            'attribute' => 'count_vn',
                            'header' => 'จำนวนครั้งการรับบริการ'
                        ],
           
                          

                    ]
                ])

                ?>

  
   <br/>
   
   
   
<br/>

           
           <?php
                echo GridView::widget([
                    'dataProvider' => $dataProvider2,
                    'panel' => [
                        'heading' => $report_name,
                        'before' => '',
                        'type' => 'primary',
                        'after' => 'ประมวลผล ณ วันที่ ' . date('Y-m-d H:i:s')
                    ],
                    'export' => [
                        'fontAwesome' => true,
                        'showConfirmAlert' => false,
                        'target' => GridView::TARGET_BLANK
                    ],
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        [
                            'attribute' => 'hn',
                            'header' => 'HN'
                        ],
                         [
                            'attribute' => 'pt_name',
                            'header' => 'ชื่อ-สกุล'
                        ],
                         [
                            'attribute' => 'vstdate',
                            'header' => 'วันที่รับบริการ'
                        ],
                         [
                            'attribute' => 'type_name',
                            'header' => 'ประเภทผู้ป่วย'
                        ],
                          
                         [
                            'attribute' => 'dch_type_name',
                            'header' => 'สถานะการจำหน่าย'
                        ],
                          

                    ]
                ])

                ?>









