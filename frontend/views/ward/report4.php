<?php

/* @var $this yii\web\View */
use kartik\grid\GridView;
use yii\helpers\Html;
use miloschuman\highcharts\Highcharts;

$this->title = $report_name;
//$this->params['breadcrumbs'][] = $this->title;
?>

<div style="display:none">
    <?php
    echo Highcharts::widget([
        'scripts' => [
            'highcharts-more', // enables supplementary chart types (gauge, arearange, columnrange, etc.)
            'themes/grid'        // applies global 'grid' theme to all charts
        ],
    ]);
    ?>  
</div>


<div id="chart"></div>



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
            'attribute' => 'department_name',
            'header' => 'ห้อง'
        ],
        [
            'attribute' => 'refer_date',
            'header' => 'ว/ด/ป ส่ง refer'
        ],
        [
            'attribute' => 'hn',
            'header' => 'HN'
        ],
        [
            'attribute' => 'ptname',
            'header' => 'ชื่อ-สกุล'
        ],
        [
            'attribute' => 'refer_number',
            'header' => 'เลขที่ Refer'
        ],
        [
            'attribute' => 'pre_diagnosis',
            'header' => 'วินิจฉัยโรคขั้นต้น'
        ],
        [
            'attribute' => 'pdx',
            'header' => 'การวินิจฉัยโรคหลัก'
        ],
        [
            'attribute' => 'refer_hospname',
            'header' => 'หน่วยปลายทาง'
        ],
        [
            'attribute' => 'doctor_name',
            'header' => 'แพทย์ผู้สั่ง'
        ],
        [
            'attribute' => 'refername',
            'header' => 'สาเหตุที่ส่งต่อ'
        ],
        [
            'attribute' => 'confirm_text',
            'header' => 'ผลการรักษา'
        ],
      
       
    ]
])
?>

