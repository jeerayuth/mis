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
            'attribute' => 'report_name',
            'header' => 'ชื่อแบบฟอร์ม'
        ],
        [
            'attribute' => 'loginname',
            'header' => 'ผู้สั่งพิมพ์'
        ],
        [
            'attribute' => 'dep_name',
            'header' => 'หน่วยงาน'
        ],
         [
            'attribute' => 'access_date_time',
            'header' => 'วันที่สั่งพิมพ์'
        ]

        
    ]
])
?>

