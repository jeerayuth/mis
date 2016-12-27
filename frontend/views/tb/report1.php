<?php

/* @var $this yii\web\View */

use kartik\grid\GridView;
use yii\helpers\Html;
//use miloschuman\highcharts\Highcharts;

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
            'attribute' => 'entry_date',
            'header' => 'วันที่คัดกรอง'
        ],
         [
            'attribute' => 'clinic',
            'header' => 'คลินิก'
        ],
        [
            'attribute' => 'condition_1',
            'header' => 'อาการไอผิดปกติ(Any cough) > 2 wks'
        ],
        [
            'attribute' => 'condition_2',
            'header' => 'อาการไข้ภายใน 1 เดือน'
        ],
        [
            'attribute' => 'condition_3',
            'header' => 'น้ำหนักลดลงเกิน 5% ของน้ำหนักตัวภายใน 1 เดือน'
        ],
        [
            'attribute' => 'condition_4',
            'header' => 'เหงื่อออกมากผิดปกติตอนกลางคืน มากกว่า 3สัปดาห์ภายใน 1 เดือน'
        ],
        [
            'attribute' => 'condition_5',
            'header' => 'ต่อมน้ำเหลืองบริเวณคอโตมากกว่า 2 เซนติเมตร'
        ],
        [
            'attribute' => 'staff',
            'header' => 'คัดกรองโดย'
        ],        
    ]
])
?>

