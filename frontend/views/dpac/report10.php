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
            'attribute' => 'address',
            'header' => 'ที่อยู่'
        ],[
            'attribute' => 'vstdate',
            'header' => 'วันที่รับบริการ'
        ],[
            'attribute' => 'pdx',
            'header' => 'รหัสวินิจฉัยหลัก'
        ],[
            'attribute' => 'second_diag',
            'header' => 'รหัสวินิจฉัยรอง'
        ],[
            'attribute' => 'age_y',
            'header' => 'อายุ(ปี)'
        ],[
            'attribute' => 'bps',
            'header' => 'BPS'
        ],[
            'attribute' => 'bpd',
            'header' => 'BPD'
        ],
    
    ]
])
?>

