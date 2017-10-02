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
            'attribute' => 'an',
            'header' => 'AN'
        ],
        [
            'attribute' => 'pt_name',
            'header' => 'ชื่อ-สกุลผู้ป่วย'
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
            'attribute' => 'pttype',
            'header' => 'สิทธิ์การรักษา'
        ],

         [
            'attribute' => 'pdx',
            'header' => 'รหัสโรค'
        ],
          [
            'attribute' => 'sentfromward',
            'header' => 'ส่งจากตึก'
        ],
         [
            'attribute' => 'sentfromdoctor',
            'header' => 'รับจากแพทย์'
        ],
          [
            'attribute' => 'doc_name',
            'header' => 'หมายเหตุ'
        ],
             
    ]
])
?>

