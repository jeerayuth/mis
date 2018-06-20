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


<?php

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'panel' => [
        'heading' => $report_name ,
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
            'attribute' => 'cid',
            'header' => 'เลขบัตรประชาชน'
        ],
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
            'attribute' => 'pdx',
            'header' => 'รหัสวินิจฉัยหลัก'
        ],
        [
            'attribute' => 'second_diag',
            'header' => 'รหัสวินิจฉัยรอง'
        ],
         [
            'attribute' => 'doc_name',
            'header' => 'แพทย์'
        ],
        [
            'attribute' => 'licenseno',
            'header' => 'ทะเบียน'
        ],
        
         [
            'attribute' => 'pttype',
            'header' => 'รหัสสิทธิ์'
        ],
         [
            'attribute' => 'pttype_name',
            'header' => 'ชื่อสิทธิ์'
        ],
         [
            'attribute' => 'income',
            'header' => 'ค่าใช้จ่ายรวม'
        ],
         [
            'attribute' => 'total_amount',
            'header' => 'total_amount'
        ],
           [
            'attribute' => 'er_visit',
            'header' => 'Visit ที่ ER'
        ],
        [
            'attribute' => 'approve_code',
            'header' => 'เลข Approve Code จากเครื่อง EDC' 
        ],
      
        
        /*
         [
            'attribute' => 'name',
            'header' => 'ประเภท'
        ],
         [
            'attribute' => 'count_hn',
            'header' => 'จำนวนคน'
        ],
         [
            'attribute' => 'count_visit',
            'header' => 'จำนวนครั้ง'
        ],
         */ 
            
      
    ]
])
?>

