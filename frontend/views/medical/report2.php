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


<div id="chart"></div>



<?php
//เตรียมชุดข้อมูลไปใส่ให้กราฟ แกน x,y
$data = [];
for ($i = 0; $i < count($rawData); $i++) {
    $data[] = [
        'name' => $rawData[$i]['group_date'],
        'y' => $rawData[$i]['count_an_ipd'] * 1,
    ];
}

$js_data = json_encode($data);



// chart
$this->registerJs(" 
    $(function () {
    $('#chart').highcharts({
        chart: {
            type: 'column'
        },
         credits: {
            enabled: false
        },
        title: {
            text: '$report_name'
        },
        
         yAxis: {
            min: 0,
            title: {
                text: 'จำนวน admit คนไข้ ipd (ครั้ง)'
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
                name: 'เดือน',
                colorByPoint: true,
                data:$js_data
        }]
     });
    });
");
// จบ chart
?>





<?php

echo GridView::widget([
    'dataProvider' => $dataProvider,
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
            'attribute' => 'group_date',
            'header' => 'เดือนที่รับบริการ'
        ],
        [
            'attribute' => 'count_an_ipd',
            'header' => 'จำนวนการรับบริการ(ครั้ง)'
        ],
        [
            'attribute' => 'count_hn_ipd',
            'header' => 'จำนวนการรับบริการ(คน)'
        ],
        [
            'attribute' => 'count_in_year',
            'header' => 'รายใหม่ในปี'
        ],

        [
            'attribute' => 'wunnon',
            'header' => 'รวมวันนอน'
        ],
         [
            'attribute' => 'income',
            'header' => 'ค่ารักษา(บาท)'
        ],
        [
            'attribute' => 'sum_adjrw',
            'header' => 'ผลรวม Adjust RW'
        ],
          
    ]
])
?>

