<?php

/* @var $this yii\web\View */

use kartik\grid\GridView;
use yii\helpers\Html;
use miloschuman\highcharts\Highcharts;

$this->title = '';
//$this->params['breadcrumbs'][] = $this->title;
?>


<?php

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'panel' => [
        'heading' =>  "HN:". $hn ." ชื่อ-สกุล ". $pt_name . " / รายละเอียดการสั่งใช้ยา" ,
        'before' => " ",
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
            'attribute' => 'icode',
            'header' => 'รหัสยา'
        ],
        [
            'attribute' => 'drug_name',
            'header' => 'ชื่อยา'
        ],
          [
            'attribute' => 'shortlist',
            'header' => 'วิธีใช้'
        ],
         [
            'attribute' => 'qty',
            'header' => 'จำนวนที่สั่งใช้'
        ],
       
       
    ]
])
?>


<?php

echo GridView::widget([
    'dataProvider' => $dataProvider2,
    'panel' => [
        'heading' =>  "HN:". $hn ." ชื่อ-สกุล ". $pt_name . " / รายละเอียดผลแลป" ,
        'before' => " ",
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
            'attribute' => 'order_date',
            'header' => 'วันที่สั่งแลป'
        ],
        [
            'attribute' => 'lab_items_name',
            'header' => 'ชื่อแลป'
        ],
          [
            'attribute' => 'confirm',
            'header' => 'ยืนยันผลแลป'
        ],
         [
            'attribute' => 'lab_order_result',
            'header' => 'ผลแลป'
        ],
       
       
    ]
])
?>


