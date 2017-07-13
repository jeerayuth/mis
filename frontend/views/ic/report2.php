<?php

/* @var $this yii\web\View */

use kartik\grid\GridView;
use yii\helpers\Html;
use miloschuman\highcharts\Highcharts;

$this->title = $report_name;
//$this->params['breadcrumbs'][] = $this->title;
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
            'attribute' => 'hn',
            'header' => 'HN'
        ],
        [
            'attribute' => 'fname',
            'header' => 'ชื่อ'
        ],
        [
            'attribute' => 'lname',
            'header' => 'สกุล'
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
            'attribute' => 'hb1',
            'header' => 'HB ครั้งที่ 1'
        ],
        [
            'attribute' => 'hb2',
            'header' => 'HB ครั้งที่ 2'
        ],
        [
            'attribute' => 'hb3',
            'header' => 'HB ครั้งที่ 3'
        ],
        [
            'attribute' => 'lab_order_date',
            'header' => 'วันที่สั่ง Anti-HBs ล่าสุด'
        ],
        [
            'attribute' => 'lab_order_result',
            'header' => 'ผล Anti-HBs ล่าสุด'
        ],
        
      
   
    ]
])
?>

