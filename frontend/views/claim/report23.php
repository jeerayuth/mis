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
            'attribute' => 'an',
            'header' => 'AN'
        ],
        [
            'attribute' => 'hn',
            'header' => 'HN'
        ],
          [
            'attribute' => 'pt_name',
            'header' => 'ชื่อ-สกุล'
        ],
          [
            'attribute' => 'pttype',
            'header' => 'รหัสสิทธิ์'
        ],        
        [
            'attribute' => 'regdate',
            'header' => 'วันที่ admit'
        ],
         [
            'attribute' => 'dchdate',
            'header' => 'วันที่ discharge'
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
        ],
        [
            'attribute' => 'dx5',
            'header' => 'dx5'
        ],
         [
            'attribute' => 'income',
            'header' => 'ค่าใช้จ่าย'
        ],
         [
            'attribute' => 'rw',
            'header' => 'RW'
        ],
       
          
    ]
])
?>

