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
            'attribute' => 'nextdate',
            'header' => 'วันที่นัด'
        ],
        [
            'attribute' => 'sub_group_name',
            'header' => 'แล็ปชุด'
        ],
          [
            'attribute' => 'count_vn',
            'header' => 'จำนวนใบนัด',
            'format' => 'raw',
            'value' => function($model)  {
                $count_vn = $model['count_vn'];
                $nextdate = $model['nextdate'];
                $sub_group_list = $model['sub_group_list']; 
                return Html::a(Html::encode($count_vn), 
                    ['pharmacy/report25', 'nextdate' => $nextdate, 'sub_group_list' => $sub_group_list],['target'=>'_blank']);
                    }
                ],
                        
  
             

      
    ],
])
?>

