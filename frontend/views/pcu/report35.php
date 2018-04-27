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
            'attribute' => 'cid',
            'header' => 'เลขบัตรประชาชน'
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
            'attribute' => 'marrystatus_name',
            'header' => 'สภาพสมรส'
        ],
        
         [
            'attribute' => 'addess',
            'header' => 'ที่อยู่'
        ],
        [
            'attribute' => 'tel',
            'header' => 'เบอร์โทรศัพท์'
        ],
         [
            'attribute' => 'occupation_name',
            'header' => 'อาชีพ'
        ],
         [
            'attribute' => 'vstdate',
            'header' => 'วันที่รับบริการ'
        ],
          [
            'attribute' => 'pttype_name',
            'header' => 'สิทธิ์การรักษา'
        ],
             [
            'attribute' => 'icode',
            'header' => 'รหัส'
        ],
        [
            'attribute' => 'drug_name',
            'header' => 'ชื่อวัคซีน'
        ], 
          [
            'attribute' => 'rxtime',
            'header' => 'เวลาให้บริการวัคซีน'
        ], 
        [
            'attribute' => 'qty',
            'header' => 'จำนวน'
        ],
                
                     
    ]
])
?>

