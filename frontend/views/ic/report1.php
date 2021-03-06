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
            'attribute' => 'fname',
            'header' => 'ชื่อ'
        ],
        [
            'attribute' => 'lname',
            'header' => 'สกุล'
        ],
        [
            'attribute' => 'hn',
            'header' => 'HN'
        ],
        [
            'attribute' => 'age_y',
            'header' => 'อายุ'
        ],
         [
            'attribute' => 'vstdate',
            'header' => 'วันที่รับบริการ'
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
            'attribute' => 'weight',
            'header' => 'น้ำหนัก'
        ],
        [
            'attribute' => 'height',
            'header' => 'ส่วนสูง'
        ],
           [
            'attribute' => 'bps',
            'header' => 'BPS'
        ],  
         [
            'attribute' => 'bpd',
            'header' => 'BPD'
        ], 
         [
            'attribute' => 'bmi',
            'header' => 'BMI'
        ],
         [
            'attribute' => 'waist',
            'header' => 'เอว'
        ],
        [
            'attribute' => 'xray_cxr',
            'header' => 'CXR'
        ],
        [
            'attribute' => 'UA',
            'header' => 'UA'
        ],
        [
            'attribute' => 'CBC',
            'header' => 'CBC'
        ],
        [
            'attribute' => 'BUN',
            'header' => 'BUN'
        ],
        [
            'attribute' => 'Creatinine',
            'header' => 'Creatinine'
        ],
        [
            'attribute' => 'Glucose_FPG',
            'header' => 'Glucose(FPG)'
        ],
        [
            'attribute' => 'SGOT_AST',
            'header' => 'SGOT(AST)'
        ],
        [
            'attribute' => 'SGPT_ALT',
            'header' => 'SGPT(ALT)'
        ],
        [
            'attribute' => 'ALP',
            'header' => 'ALP'
        ],
        [
            'attribute' => 'Cholesterol',
            'header' => 'Cholesterol'
        ],
        [
            'attribute' => 'HDL_C',
            'header' => 'HDL-C'
        ],
        [
            'attribute' => 'LDL_C',
            'header' => 'LDL-C'
        ],
        [
            'attribute' => 'Triglyceride',
            'header' => 'Triglyceride'
        ],
        [
            'attribute' => 'HbA1C',
            'header' => 'HbA1C'
        ],
        [
            'attribute' => 'Anti_HBs',
            'header' => 'Anti-HBs'
        ],
        [
            'attribute' => 'HBsAg',
            'header' => 'HBsAg '
        ],
        [
            'attribute' => 'pmh',
            'header' => 'pmh'
        ],
        [
            'attribute' => 'EKG',
            'header' => 'EKG'
        ],

      
   
    ]
])
?>

