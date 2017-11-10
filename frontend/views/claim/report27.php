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
            'attribute' => 'pttype_name',
            'header' => 'ชื่อบัญชี'
        ],
        [
            'attribute' => 'q1',
            'header' => 'รหัสบัญชี'
        ],
         [
            'attribute' => 'q2',
            'header' => 'รหัสบัญชี'
        ],
        [
            'attribute' => 'sum_income',
            'header' => 'จำนวนเงิน(บาท)'
        ],
         [
            'attribute' => 'sum_uc_money',
            'header' => 'ลูกหนี้ค่ารักษา(บาท)'
        ],
          [
            'attribute' => 'count_visit',
            'header' => 'จำนวนรับบริการ(ครั้ง)'
        ],
        
        
      
       
      
    ]
])
?>

