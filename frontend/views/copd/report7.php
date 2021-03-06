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
            'header' => 'อายุ'
        ],
        [
            'attribute' => 'moopart',
            'header' => 'หมู่บ้าน',
        ],
        [
            'attribute' => 'address',
            'header' => 'ที่อยู่',
        ],
        [
            'attribute' => 'vstdate',
            'header' => 'วันที่รับบริการ',
        ],
        [
            'attribute' => 'pdx',
            'header' => 'รหัสวินิจฉัยหลัก',
        ],
        [
            'attribute' => 'dx0',
            'header' => 'DX0',
        ],
        [
            'attribute' => 'dx1',
            'header' => 'DX1',
        ],
        [
            'attribute' => 'dx2',
            'header' => 'DX2',
        ],
        [
            'attribute' => 'dx3',
            'header' => 'DX3',
        ],
        [
            'attribute' => 'dx4',
            'header' => 'DX4',
        ],
        [
            'attribute' => 'dx5',
            'header' => 'DX5',
        ],
    ]
])
?>

