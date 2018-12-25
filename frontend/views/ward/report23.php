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
            'attribute' => 'hn',
            'header' => 'HN'
        ],
        [
            'attribute' => 'an',
            'header' => 'AN'
        ],
        [
            'attribute' => 'pt_name',
            'header' => 'ชื่อ-สกุล'
        ],
        
         [
            'attribute' => 'regdate',
            'header' => 'วันที่ Admit'
        ],
        [
            'attribute' => 'dchdate',
            'header' => 'วันที่จำหน่าย'
        ],  
        [
            'attribute' => 'admdate',
            'header' => 'จำนวนวันนอน'
        ],
          [
            'attribute' => 'count_bed',
            'header' => 'เตียงอัตโนมัติ(ครั้ง)',
            'format' => 'raw',
            'value' => function($model)  {
                $an = $model['an'];
                $count_bed = $model['count_bed'];             
                return Html::a(Html::encode($count_bed), 
                    ['ward/report24', 'an' => $an],['target'=>'_blank']);
                    }
        ],

        [
            'attribute' => 'count_nurse',
            'header' => 'บริการพยาบาลทั่วไป IPD(ครั้ง)',
            'format' => 'raw',
            'value' => function($model)  {
                $an = $model['an'];
                $count_nurse = $model['count_nurse'];             
                return Html::a(Html::encode($count_nurse), 
                    ['ward/report25', 'an' => $an],['target'=>'_blank']);
                    }
        ],
             [
            'attribute' => 'count_room',
            'header' => 'ห้องพิเศษ',
            'format' => 'raw',
            'value' => function($model)  {
                $an = $model['an'];
                $count_room = $model['count_room'];             
                return Html::a(Html::encode($count_room), 
                    ['ward/report26', 'an' => $an],['target'=>'_blank']);
                    }
        ]
      
      
        
      
   
    ]
])
?>

