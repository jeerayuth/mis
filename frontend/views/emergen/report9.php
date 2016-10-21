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
    <div class="col-md-5">
        <div id="chart"></div>
    </div>
    <div class="col-md-7">
        <div id="chart2"></div>
    </div>
</div>


<?php


$data = [];
$sum = 0;
foreach($rawData as $r){
    $data[] = [
        'name' => $r['pdx'],
        'y' => intval($r['count_vn']),
    ];
    $sum += $r['count_vn'];
}



$data3 = [];
for ($i = 0; $i < count($rawData3); $i++) {
    $data3[] = [
        'name' => $rawData3[$i]['pre_diagnosis'],
        'y' => $rawData3[$i]['count_vn'] * 1,
    ];
}


$js_data = json_encode($data);
$js_data3 = json_encode($data3);


// chart

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
            text: 'กราฟ 10 อันดับโรคที่ส่ง Refer ที่ห้องอุบัติเหตุฉุกเฉิน(แยกตามผลวินิจฉัยหลักจาก รพ.ต้นทาง)'
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
        chart.renderer.text('', 20, 105)
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


// chart
$this->registerJs(" 
    $(function () {
    $('#chart2').highcharts({
        chart: {
            type: 'column'
        },
         credits: {
            enabled: false
        },
        title: {
            text: 'กราฟ 10 อันดับโรคที่ส่ง Refer ที่ห้องอุบัติเหตุฉุกเฉิน(แยกตามผลวินิจฉัยเบื้องต้นจาก รพ.ต้นทาง)'
        },
        
         yAxis: {
            min: 0,
            title: {
                text: 'จำนวนครั้งการส่ง Refer (ครั้ง)'
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
                    enabled: true
                }
            }
        },
        series: [{
                name: 'รหัสโรค',
                colorByPoint: true,
                data:$js_data3
        }]
     });
    });
");
// จบ chart


?>


<?php

echo GridView::widget([
    'dataProvider' => $dataProvider2,
    'panel' => [
        'heading' => $report_name,
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
            'attribute' => 'department_name',
            'header' => 'ห้อง'
        ],
        [
            'attribute' => 'refer_date',
            'header' => 'ว/ด/ป'
        ],
        [
            'attribute' => 'hn',
            'header' => 'HN'
        ],
        [
            'attribute' => 'ptname',
            'header' => 'ชื่อ-สกุล'
        ],
        [
            'attribute' => 'refer_number',
            'header' => 'เลขที่ Refer'
        ],
        [
            'attribute' => 'pre_diagnosis',
            'header' => 'วินิจฉัยโรคขั้นต้น'
        ],
        [
            'attribute' => 'pdx',
            'header' => 'การวินิจฉัยโรคหลัก'
        ],
        [
            'attribute' => 'refer_hospname',
            'header' => 'หน่วยปลายทาง'
        ],
        [
            'attribute' => 'doctor_name',
            'header' => 'แพทย์ผู้สั่ง'
        ],
        [
            'attribute' => 'refername',
            'header' => 'สาเหตุที่ส่งต่อ'
        ],
        [
            'attribute' => 'confirm_text',
            'header' => 'ผลการรักษา'
        ],
      
       
    ]
])
?>


