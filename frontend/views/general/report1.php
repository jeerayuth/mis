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
            'attribute' => 'vstdate',
            'header' => 'วันที่รับบริการ'
        ],
       
         [
            'attribute' => 'count_pdx_empty',
            'header' => 'ไม่มีรหัสวินิจฉัยหลัก(PDX)'
        ],[
            'attribute' => '',
            'header' => '',
            'format' => 'raw',
            'value' => function($model) {
                $vstdate = $model['vstdate'];
                return Html::a(Html::encode('คลิกดูรายละเอียด'), 
                    ['general/report2', 'vstdate' => $vstdate],['target'=>'_blank']);
                    }
                ]


        
          
    ]
])
?>

