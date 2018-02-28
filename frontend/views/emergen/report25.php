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
        'heading' => $report_name.' (หน่วยเป็นครั้ง)',
        'before' => $details,
        'type' => 'primary',
        'after' => 'ประมวลผล ณ วันที่ ' . date('ป็นคน)Y-m-d H:i:s')
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
            'header' => 'วันที่รับบริการ'
        ],
        [
            'attribute' => 'pdx',
            'header' => 'รหัสวินิจฉัยหลัก'
        ],
        [
            'attribute' => 'dx0',
            'header' => 'รหัสวินิจฉัยรอง 1'
        ],
        [
            'attribute' => 'dx1',
            'header' => 'รหัสวินิจฉัยรอง 2'
        ],
        [
            'attribute' => 'dx2',
            'header' => 'รหัสวินิจฉัยรอง 3'
        ],
         [
            'attribute' => 'dx3',
            'header' => 'รหัสวินิจฉัยรอง 4'
        ],
         [
            'attribute' => 'dx4',
            'header' => 'รหัสวินิจฉัยรอง 5'
        ],
        [
            'attribute' => 'dx5',
            'header' => 'รหัสวินิจฉัยรอง 6'
        ],
          [
            'attribute' => 'lastvisit_hour',
            'header' => 'จำนวนชั่วโมงที่ห่างจาก visit ครั้งที่แล้ว'
        ],
          [
            'attribute' => 'er_dch_type',
            'header' => 'สถานภาพ'
        ]
        
          
    ]
])
?>

