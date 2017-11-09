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
            'attribute' => 'pt_name',
            'header' => 'ชื่อ-สกุล'
        ],
         [
            'attribute' => 'age_y',
            'header' => 'อายุ(ปี)'
        ],
             [
            'attribute' => 'age_m',
            'header' => 'อายุ(เดือน)'
        ],
        [
            'attribute' => 'sex',
            'header' => 'เพศ'
        ],
        [
            'attribute' => 'bcg',
            'header' => 'BCG'
        ],
        [
            'attribute' => 'hb1',
            'header' => 'HB1'
        ],
        [
            'attribute' => 'dtphb1',
            'header' => 'DTPHB1'
        ],
        [
            'attribute' => 'opv1',
            'header' => 'OPV1'
        ],
        [
            'attribute' => 'dtphb2',
            'header' => 'DTPHB2'
        ],
         [
            'attribute' => 'opv2',
            'header' => 'OPV2'
        ],
        [
            'attribute' => 'dtphb3',
            'header' => 'DTPHB3'
        ],
        [
            'attribute' => 'opv3',
            'header' => 'OPV3'
        ],
        [
            'attribute' => 'mmr1',
            'header' => 'MMR1'
        ],
        
        
         
                     
    ]
])
?>

