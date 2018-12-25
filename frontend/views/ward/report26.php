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
        'heading' => '',
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
            'attribute' => 'icode',
            'header' => 'รหัสค่าใช้จ่าย'
        ],
        
         [
            'attribute' => 'nondrugname',
            'header' => 'ชื่อค่าใช้จ่าย'
        ],
        [
            'attribute' => 'rxdate',
            'header' => 'วันที่สั่งใช้'
        ],  
     
   
    ]
])
?>

