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
            'attribute' => 'hn',
            'header' => 'HN'
        ],
          [
            'attribute' => 'pt_name',
            'header' => 'ชื่อ-สกุล'
        ],
           [
            'attribute' => 'sex_name',
            'header' => 'เพศ'
        ],
           [
            'attribute' => 'age_y',
            'header' => 'อายุ'
        ],[
            'attribute' => 'hometel',
            'header' => 'เบอร์โทรศัพท์'
        ],
        [
            'attribute' => 'order_date',
            'header' => 'วันที่สั่งแลป'
        ],[
            'attribute' => 'report_date',
            'header' => 'วันที่รายงานผล'
        ],[
            'attribute' => 'lab_order_number',
            'header' => 'เลขที่สั่ง'
        ],[
            'attribute' => 'result_note',
            'header' => 'LAB NOTE'
        ],
        [
            'attribute' => 'lab_items_name',
            'header' => 'ชื่อแลป'
        ],[
            'attribute' => 'lab_order_result',
            'header' => 'ผลแลป'
        ],
       [
            'attribute' => 'department_name',
            'header' => 'หน่วยงานที่สั่ง'
        ],
       
    ]
])
?>

