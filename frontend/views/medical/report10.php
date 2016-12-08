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
        'before' => 'details',
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
            'attribute' => 'an',
            'header' => 'AN'
        ],
         [
            'attribute' => 'hn',
            'header' => 'HN'
        ],
        [
            'attribute' => 'rent_user',
            'header' => 'ผู้ยืม'
        ],
         [
            'attribute' => 'rent_date',
            'header' => 'วันที่ยืม'
        ],
         [
            'attribute' => 'checkin',
            'header' => 'สถานะการคืน'
        ],
        [
            'attribute' => 'return_date',
            'header' => 'วันที่คืน'
        ],
       
      
             
          
    ]
])
?>

