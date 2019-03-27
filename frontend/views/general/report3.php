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
        'before' => '',
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
            'header' => 'อายุ(ปี)'
        ],
         [
            'attribute' => 'address',
            'header' => 'ที่อยู่'
        ],
       
         [
            'attribute' => 'vstdate',
            'header' => 'วันที่รับบริการ'
        ],
        [
            'attribute' => 'entry_date',
            'header' => 'วันที่บันทึกข้อมูลคัดกรองบุหรี่'
        ],
        [
            'attribute' => 'CLINIC1',
            'header' => 'โรคประจำตัว1'
        ],
        [
            'attribute' => 'CLINIC2',
            'header' => 'โรคประจำตัว2'
        ],
        [
            'attribute' => 'CLINIC3',
            'header' => 'โรคประจำตัว3'
        ],
        [
            'attribute' => 'CLINIC4',
            'header' => 'โรคประจำตัว4'
        ],
        [
            'attribute' => 'CLINIC5',
            'header' => 'โรคประจำตัว5'
        ],
        [
            'attribute' => 'CLINIC6',
            'header' => 'โรคประจำตัว6'
        ],
        [
            'attribute' => 'CLINIC7',
            'header' => 'โรคประจำตัว7'
        ],
        [
            'attribute' => 'CLINIC8',
            'header' => 'โรคประจำตัว8'
        ],
         [
            'attribute' => 'CLINIC9',
            'header' => 'โรคประจำตัว9'
        ],
        [
            'attribute' => 'condition_5',
            'header' => 'โรคประจำตัว อื่นๆ'
        ],
        [
            'attribute' => 'condition_1',
            'header' => 'ประเภทบุหรี่'
        ],
        [
            'attribute' => 'condition_2',
            'header' => 'ส่งคลินิกอดบุหรี่'
        ], 
        [
            'attribute' => 'condition_3',
            'header' => 'ประเมิลผล'
        ],
        [
            'attribute' => 'condition_4',
            'header' => 'เลิกเมื่อ'
        ]
        
          
    ]
])
?>

