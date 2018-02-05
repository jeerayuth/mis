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
            'attribute' => 'an',
            'header' => 'AN'
        ],
        [
            'attribute' => 'pt_name',
            'header' => 'ชื่อ-สกุล'
        ],
          [
            'attribute' => 'birthday',
            'header' => 'วันเกิด'
        ], 
         [
            'attribute' => 'regdate',
            'header' => 'วันที่ Admit'
        ],
        [
            'attribute' => 'dchdate',
            'header' => 'วันที่จำหน่าย'
        ],
        [
            'attribute' => 'age_begin',
            'header' => 'อายุ(ณ วัน Admit)'
        ],
        [
            'attribute' => 'age',
            'header' => 'อายุ(ณ วัน Discharge)'
        ],
       
         
        [
            'attribute' => 'pdx',
            'header' => 'PDX'
        ],
        [
            'attribute' => 'dx0',
            'header' => 'DX0'
        ],
        [
            'attribute' => 'dx1',
            'header' => 'DX1'
        ],
        [
            'attribute' => 'dx2',
            'header' => 'DX2'
        ],
        [
            'attribute' => 'dx3',
            'header' => 'DX3'
        ],
        [
            'attribute' => 'dx4',
            'header' => 'DX4'
        ],
        [
            'attribute' => 'dx5',
            'header' => 'DX5'
        ],
         
         [
            'attribute' => 'lab_items_name',
            'header' => 'ชื่อแลป'
        ],
        
        [
            'attribute' => 'order_date',
            'header' => 'วันที่สั่งแลป'
        ],
        
         [
            'attribute' => 'lab_order_result',
            'header' => 'ผลแลป Micro Bilirubin'
        ],

        
      
   
    ]
])
?>

