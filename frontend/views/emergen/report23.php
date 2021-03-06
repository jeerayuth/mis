<?php

/* @var $this yii\web\View */

use kartik\grid\GridView;
use yii\helpers\Html;
use miloschuman\highcharts\Highcharts;

$this->title = $report_name1;
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
foreach($rawData1 as $r){
    $data[] = [
        'name' => $r['type_name'],
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
            text: '$report_name1'
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
                    'dataProvider' => $dataProvider1,
                    'panel' => [
                        'heading' => $report_name1,
                        'before' => $details,
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
                            'attribute' => 'type_name',
                            'header' => 'ประเภทผู้ป่วย'
                        ],
           
                          [
                            'attribute' => 'count_vn',
                            'header' => 'จำนวนครั้งรับบริการทั้งหมด',
                            'format' => 'raw',
                            'value' => function($model) use ($datestart,$dateend) {
                                $er_pt_type = $model['er_pt_type'];
                                $count_vn = $model['count_vn'];
                                return Html::a(Html::encode($count_vn), 
                                    ['emergen/report24', 'er_pt_type' => $er_pt_type, 'datestart' => $datestart, 'dateend' => $dateend],['target'=>'_blank']);
                                    }
                        ]

                    ]
                ])

                ?>

  
   








