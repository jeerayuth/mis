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
<h4>
อ้างอิง: ตรวจสอบรหัสสิทธิ์ที่ใช้จับคู่ระหว่างโปรแกรมทางบัญชีกับโปรแกรม HOSxP &nbsp;
<a href="http://192.168.1.252:8080/mis/files_upload/pttype_lamae11381.pdf">>>> คลิกที่นี่ <<<</a>
</h4>

<?php

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'panel' => [
        'heading' => $report_name ,
        'before' => "",
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
            'header' => 'ชื่อบัญชี',
        ],
        [
            'attribute' => 'q1',
            'header' => 'รหัสบัญชี',
            'format' => 'text'
        ],
         [
            'attribute' => 'q2',
            'header' => 'รหัสบัญชี'
        ],
        [
            'attribute' => 'sum_income',
            'header' => 'จำนวนเงิน(บาท)',

        ],
         [
            'attribute' => 'sum_uc_money',
            'header' => 'ลูกหนี้ค่ารักษา(บาท)',
               'format'=>'text', 
        ],
          [
            'attribute' => 'count_visit',
            'header' => 'จำนวนรับบริการ(ครั้ง)'
              
        ],
   
        
        [
            'attribute' => '',
            'header' => 'รายละเอียดค่ารักษา',
            'format' => 'raw',
            'value' => function($model) use ($date_start,$date_end) {
                $ptt_code = $model['ptt_code'];
                $ptt_type = $model['ptt_type'];
                $head = $model['pttype_name'];
                $cup_status = $model['cup_status'];
                $title = 'คลิกดูรายละเอียด';
   
                return Html::a(Html::encode($title), 
                    ['claim/report28', 'head'=>$head ,'ptt_code' => $ptt_code  ,'ptt_type' => $ptt_type,'date_start'=>$date_start,'date_end'=>$date_end,'cup_status'=>$cup_status],['target'=>'_blank']);
                    }
                ]
        
        
      
       
      
    ]
])
?>

