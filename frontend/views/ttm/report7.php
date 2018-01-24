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
            'header' => 'เลขที่ HN'
        ],  
        [
            'attribute' => 'pt_name',
            'header' => 'ชื่อ-สกุล(ผู้ป่วย)'
        ],
         [
            'attribute' => 'vstdate',
            'header' => 'วันที่รับบริการ'
        ],
        [
            'attribute' => 'pttype_name',
            'header' => 'สิทธิผู้ป่วย'
        ],
        [
            'attribute' => 'health_med_operation_item_name',
            'header' => 'รายการให้บริการ'
        ],            
        [
            'attribute' => 'billcode',
            'header' => 'รหัส billcode',
        ],
         [
            'attribute' => 'input1',
            'header' => 'อัตราค่าบริการ',
        ],
        [
            'attribute' => 'input2',
            'header' => 'ส่วนแบ่งรายรับไม่เกิน 60% : ราย',
        ],
          [
            'attribute' => 'dorcor_name',
            'header' => 'ชื่อผู้ปฏิบัติงาน',
        ],
        [
            'attribute' => 'input3',
            'header' => 'ลายมือชื่อผู้ปฏิบัติงาน',
        ],
        [
            'attribute' => 'input4',
            'header' => 'ลายมือชื่อหัวหน้าผู้ควบคุม',
        ],

       
       
    ]
])
?>

