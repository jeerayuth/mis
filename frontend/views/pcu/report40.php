<?php

/* @var $this yii\web\View */

use kartik\grid\GridView;
use yii\helpers\Html;

$this->title = $report_name;
//$this->params['breadcrumbs'][] = $this->title;
?>



<?php

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'panel' => [
        'heading' => $report_name,
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
            'attribute' => 'pt_name',
            'header' => 'ชื่อ-สกุล'
        ],
         [
            'attribute' => 'vstdate',
            'header' => 'วันที่ visit ที่ถูกเชื่อมโยงเพื่อบันทึก SpecialPP'
        ],
         [
            'attribute' => 'entry_datetime',
            'header' => 'วันที่บันทึก SpecialPP'
        ],
        [
            'attribute' => 'pp_special_type_name',
            'header' => 'PP SPECIAL TYPE'
        ],
        
       
        
    ]
])
?>

