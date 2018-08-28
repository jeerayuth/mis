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


<?php

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'panel' => [
        'heading' => $report_name ,
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
            'attribute' => 'an',
            'header' => 'AN'
        ],
  
         [
            'attribute' => 'pt_name',
            'header' => 'ชื่อ-สกุล'
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
            'attribute' => 'regdate',
            'header' => 'วันที่ Admit' 
        ], 
        [
            'attribute' => 'dchdate',
            'header' => 'วันที่จำหน่าย' 
        ],
      
        
        /*
         [
            'attribute' => 'name',
            'header' => 'ประเภท'
        ],
         [
            'attribute' => 'count_hn',
            'header' => 'จำนวนคน'
        ],
         [
            'attribute' => 'count_visit',
            'header' => 'จำนวนครั้ง'
        ],
         */ 
            
      
    ]
])
?>

