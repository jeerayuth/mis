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
            'attribute' => 'cid',
            'header' => 'บัตรประชาชน'
        ],
         [
            'attribute' => 'hn',
            'header' => 'HN'
        ],
          [
            'attribute' => 'vst_date',
            'header' => 'วันที่รับบริการ'
        ],
           [
            'attribute' => 'vst_time',
            'header' => 'เวลารับบริการ'
        ],
          [
            'attribute' => 'patient_name',
            'header' => 'ชื่อ-สกุล'
        ], 
        [
            'attribute' => 'pdx',
            'header' => 'การวินิจฉัยหลัก'
        ],
        [
            'attribute' => 'second_diag',
            'header' => 'การวินิจฉัยรอง'
        ],
        [
            'attribute' => 'icd9',
            'header' => 'หัตถการ(ICD9)'
        ],
        [
            'attribute' => 'doc_name',
            'header' => 'แพทย์'
        ],
        [
            'attribute' => 'licenseno',
            'header' => 'ทะเบียน'
        ],
         [
            'attribute' => 'v_drug',
            'header' => 'ค่ายา'
        ],
         [
            'attribute' => 'v_xray',
            'header' => 'x-ray'
        ],
         [
            'attribute' => 'v_lab',
            'header' => 'Lab'
        ],
        [
            'attribute' => 'v_icd9',
                'header' => 'หัตถการ'
        ],
            [
            'attribute' => 'v_other',
                'header' => 'อื่นๆ'
        ],
        [
            'attribute' => 'item_money',
            'header' => 'ค่าบริการ'
        ],
       
      
       
          
    ]
])
?>

