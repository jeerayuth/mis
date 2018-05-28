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
            'header' => 'อายุ'
        ],
      /*  [
            'attribute' => 'cid',
            'header' => 'เลขบัตรประชาชน'
        ], */
        [
            'attribute' => 'moopart',
            'header' => 'หมู่บ้าน',
        ],
        [
            'attribute' => 'address',
            'header' => 'ที่อยู่',
        ],
        [
            'attribute' => 'hometel',
            'header' => 'เบอร์โทรติดต่อ',
        ],
        [
            'attribute' => 'first_diag',
            'header' => 'Diag N181-N185 ครั้งแรก',
        ],
         [
            'attribute' => 'first_date',
            'header' => 'Diag N181-N185 วันแรก',
        ],
        [
            'attribute' => 'last_diag',
            'header' => 'Diag N181-N185 ครั้งล่าสุด',
        ],  [
            'attribute' => 'last_date',
            'header' => 'Diag N181-N185 วันล่าสุด',
        ]
        
        
    ]
])
?>

