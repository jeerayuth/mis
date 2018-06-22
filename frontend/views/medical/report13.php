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
            'attribute' => 'vn',
            'header' => 'VN'
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
            'attribute' => 'vstdate_thai',
            'header' => 'วันที่รับบริการ'
        ],
         [
            'attribute' => 'icd10',
            'header' => 'รหัสวินิจฉัย'
        ],
         [
            'attribute' => 'diagtype',
            'header' => 'Diag Type'
        ],
       
       
                     
    ]
])
?>

