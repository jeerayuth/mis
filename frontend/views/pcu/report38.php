<?php

/* @var $this yii\web\View */

use kartik\grid\GridView;
use yii\helpers\Html;

$this->title = $report_name;
//$this->params['breadcrumbs'][] = $this->title;
?>



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
            'attribute' => 'village_moo',
            'header' => 'หมู่ที่'
        ],
         [
            'attribute' => 'village_name',
            'header' => 'หมู่บ้าน'
        ],
        [
            'attribute' => 'cid',
            'header' => 'เลขบัตรประชาชน'
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
            'attribute' => 'house_regist_type_id',
            'header' => 'TypeArea'
        ],
    
        
          [
            'attribute' => 'count_1b5',
            'header' => '1B5*',
            'format' => 'raw',
            'value' => function($model) use ($datestart,$dateend,$details)  {
                $cid = $model['cid'];
                $count_1b5 = $model['count_1b5'];
                return Html::a(Html::encode($count_1b5), 
                    ['pcu/report39', 'cid' => $cid, 'datestart' => $datestart,'dateend'=>$dateend,'details'=>$details],['target'=>'_blank']);
                    }
                ],
        
        
         [
            'attribute' => 'count_1b6',
            'header' => '1B6*',
            'format' => 'raw',
            'value' => function($model) use ($datestart,$dateend,$details) {
                $cid = $model['cid'];
                $count_1b6 = $model['count_1b6'];
                return Html::a(Html::encode($count_1b6), 
                    ['pcu/report40', 'cid' => $cid,'datestart' => $datestart,'dateend'=>$dateend,'details'=>$details],['target'=>'_blank']);
                    }
                ],
       
      
       
        
    ]
])
?>

