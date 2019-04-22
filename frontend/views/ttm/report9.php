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
            'header' => 'ชื่อ-สกุล(ผู้ป่วย)'
        ],
         [
            'attribute' => 'drug_name',
            'header' => 'ชื่อยา'
        ],
         [
            'attribute' => 'qty',
            'header' => 'จำนวนสั่งใช้'
        ],
         [
            'attribute' => 'shortlist',
            'header' => 'วิธีใช้'
        ],
           [
            'attribute' => 'vstdate',
            'header' => 'วันที่รับบริการ'
        ],
        [
            'attribute' => 'doctor_name',
            'header' => 'แพทย์'
        ],
          [
            'attribute' => 'lab_Glucose_FBS',
            'header' => 'ประวัติ GLUCOSE FBS'
        ],
          [
            'attribute' => 'lab_HbA1C',
            'header' => 'ประวัติ HbA1C'
        ],
         
              
    ]
])
?>

