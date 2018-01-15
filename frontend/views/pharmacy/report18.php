<?php

/* @var $this yii\web\View */

use kartik\grid\GridView;
use yii\helpers\Html;
use miloschuman\highcharts\Highcharts;

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
            'attribute' => '',
            'header' => 'ยา/แลป',
            'format' => 'raw',
            'value' => function($model) use ($date_start, $date_end) {
                $vn = $model['vn'];  
                $hn = $model['hn'];
                $pt_name = $model['pt_name'];
                $title = '[รายละเอียด]';

                return Html::a(Html::encode($title), ['pharmacy/report19', 'hn' => $hn,'vn' => $vn,'pt_name' => $pt_name, 'date_start' => $date_start, 'date_end' => $date_end], ['target' => '_blank']);
            }
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
            'attribute' => 'vstdate',
            'header' => 'วันที่รับบริการ'
        ],
        [
            'attribute' => 'weight',
            'header' => 'น้ำหนัก'
        ],
        [
            'attribute' => 'bps_bpd',
            'header' => 'BP'
        ],
        [
            'attribute' => 'pulse',
            'header' => 'อัตราเต้นชีพจร'
        ],
        [
            'attribute' => 'pdx',
            'header' => 'รหัสวินิจฉัยหลัก'
        ],
         [
            'attribute' => 'second_diag',
            'header' => 'รหัสวินิจฉัยรอง'
        ],
        [
            'attribute' => 'drug',
            'header' => 'รายการยาที่สั่ง'
        ]
       
    ]
])
?>

