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
            'attribute' => 'village_moo',
            'header' => 'หมู่ที่'
        ],
         [
            'attribute' => 'address',
            'header' => 'บ้านเลขที่'
        ],
         [
            'attribute' => 'person_house_position_name',
            'header' => 'ตำแหน่ง'
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
            'attribute' => 'cid',
            'header' => 'CID'
        ],
    
      /*   [
            'attribute' => 'house_regist_type_id',
            'header' => 'Type AREA'
        ], */
    
         [
            'attribute' => 'house_regist_type_name',
            'header' => 'Type Area'
        ],
    
         [
            'attribute' => 'education_name',
            'header' => 'การศึกษา'
        ],
            [
            'attribute' => 'occupation_name',
            'header' => 'อาชีพ'
        ],
         [
            'attribute' => 'marrystatus_name',
            'header' => 'สถานะภาพ'
        ],
        [
            'attribute' => 'person_discharge_name',
            'header' => 'สถานะ'
        ],
         [
            'attribute' => 'comments',
            'header' => 'หมายเหตุ'
        ],
    
        
    
        
          
                     
    ]
])
?>

