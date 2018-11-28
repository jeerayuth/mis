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
            'attribute' => 'rxdate',
            'header' => 'วันที่'
        ],
        [
            'attribute' => 'department',
            'header' => 'หน่วยงาน'
        ],
        [
            'attribute' => 'icode',
            'header' => 'รหัสยา'
        ],
        [
            'attribute' => 'drugname',
            'header' => 'ชื่อยา'
        ],
           
           [
            'attribute' => 'drug_unit',
            'header' => 'หน่วย'
        ],
          [
            'attribute' => 'unit_price',
            'header' => 'ราคาขาย(ล่าสุด)/หน่วย'
        ],
         [
            'attribute' => 'unit_cost',
            'header' => 'ราคาทุน(ล่าสุด)/หน่วย'
        ],
         [
            'attribute' => 'count_icode',
            'header' => 'จำนวนครั้งที่สั่ง'
        ],
        [
            'attribute' => 'sum_qty',
            'header' => 'ปริมาณที่สั่ง'
        ],  
        [
            'attribute' => 'sum_price',
            'header' => 'มูลค่าการใช้(ราคาขาย)'
        ],
         [
            'attribute' => 'sum_cost',
            'header' => 'มูลค่าการใช้(ราคาทุน)'
        ],
       
      
    ]
])
?>

