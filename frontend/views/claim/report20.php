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
        'name' => $rawData[$i]['pttype_name'],
        'y' => $rawData[$i]['count_vn'] * 1,
    ];
}

$js_data = json_encode($data);

$report_name = $report_name . " ณ ระหว่างวันที่ " .  $date_start . "ถึง " . $date_end;
        
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
                text: 'จำนวน visit คนไข้ opd(ครั้ง) แยกตามสิทธิ์'
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
                name: 'สิทธิ์การรักษา',
                colorByPoint: true,
                data:$js_data
        }]
     });
    });
");
// จบ chart
?>

<br/>

<center>
 <button type="button" class="btn btn-danger" onclick = "javascript:url()"><i class="fa fa-search"></i> คลิกที่นี่!! ดูรายละเอียดค่ารักษาทุกสิทธิ์ </button>
</center>
 
<?php

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'panel' => [
        'heading' => $report_name ,
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
            'attribute' => 'pttype',
            'header' => 'รหัสสิทธิ์'
        ],
        [
            'attribute' => 'pttype_name',
            'header' => 'ชื่อสิทธิ์'
        ],
        [
            'attribute' => 'count_vn',
            'header' => 'ยอดผู้รับบริการ(รายครั้ง)'
        ],
        [
            'attribute' => 'sum_income',
            'header' => 'ยอดเงิน(บาท)'
        ],
         [
            'attribute' => 'sum_uc_money',
            'header' => 'ลูกหนี้ค่ารักษา'
        ],
        
        
        
        
        [
            'attribute' => '',
            'header' => 'รายละเอียดค่ารักษา',
            'format' => 'raw',
            'value' => function($model) use ($date_start,$date_end) {
                $pttype = $model['pttype'];
                $title = 'คลิกดูรายละเอียด';
   
                return Html::a(Html::encode($title), 
                    ['claim/report21', 'pttype' => $pttype,'date_start'=>$date_start,'date_end'=>$date_end],['target'=>'_blank']);
                    }
                ]
      
       
      
    ]
])
?>



<script type="text/javascript">

    //function เรียกหน้ารายงาน
    function url() {
     
        window.open('http://192.168.1.252:8080/mis/frontend/web/index.php?r=claim/report22&date_start=' + <?=$date_start ?> + '&date_end=' + <?=$date_end ?> );
    }




    window.onload = function () {
        //your jQuery code here
        // jquery here     
    };


</script>