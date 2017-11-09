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
        
          [
            'attribute' => 'laje1',
            'header' => 'LAJE1'
        ],
        
          [
            'attribute' => 'dtp4',
            'header' => 'DTP4'
        ],
        
          [
            'attribute' => 'opv4',
            'header' => 'OPV4'
        ],
        
          [
            'attribute' => 'laje2',
            'header' => 'LAJE2'
        ],
         [
            'attribute' => 'mmr2',
            'header' => 'MMR2'
        ],
         [
            'attribute' => 'dtp5',
            'header' => 'DTP5'
        ],
         [
            'attribute' => 'opv5',
            'header' => 'OPV5'
        ],
         [
            'attribute' => 'vstdate',
            'header' => 'วันที่รับบริการ'
        ],
        
           [
            'attribute' => 'pdx',
            'header' => 'PDX'
        ],
          [
            'attribute' => 'dx0',
            'header' => 'dx0'
        ],
          [
            'attribute' => 'dx1',
            'header' => 'dx1'
        ],
          [
            'attribute' => 'dx2',
            'header' => 'dx2'
        ],
          [
            'attribute' => 'dx3',
            'header' => 'dx3'
        ],
          [
            'attribute' => 'dx4',
            'header' => 'dx4'
        ], [
            'attribute' => 'dx5',
            'header' => 'dx5'
        ],
        [
            'attribute' => 'op0',
            'header' => 'op0'
        ],
        [
            'attribute' => 'op1',
            'header' => 'op1'
        ],
        
        [
            'attribute' => 'op2',
            'header' => 'op2'
        ],
        
        [
            'attribute' => 'op3',
            'header' => 'op3'
        ],
        
        [
            'attribute' => 'op4',
            'header' => 'op4'
        ],
        
        [
            'attribute' => 'op5',
            'header' => 'op5'
        ],
        
        
        
        
        
         
                     
    ]
])
?>

