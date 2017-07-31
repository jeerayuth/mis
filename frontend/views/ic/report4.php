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
            'attribute' => 'age_y',
            'header' => 'อายุ(ปี)'
        ],
        [
            'attribute' => 'addrpart',
            'header' => 'บ้านเลขที่'
        ],
        [
            'attribute' => 'moopart',
            'header' => 'หมู่ที่'
        ],
        [
            'attribute' => 'full_name',
            'header' => 'ที่อยู่'
        ], 
        [
            'attribute' => 'regdate',
            'header' => 'วันที่ admit'
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
            'attribute' => 'rxdate',
            'header' => 'วันที่สั่งใช้ยา'
        ],
  
        [
            'attribute' => 'pdx',
            'header' => 'รหัสวินิจฉัยหลัก'
        ],             
        [
            'attribute' => 'dx0',
            'header' => 'dx0'
        ],
        [
            'attribute' => 'dx1',
            'header' => 'dx1'
        ],
        [
            'attribute' => 'dx2',
            'header' => 'dx2'
        ],
        [
            'attribute' => 'dx3',
            'header' => 'dx3'
        ],
        [
            'attribute' => 'dx4',
            'header' => 'dx4'
        ],
        [
            'attribute' => 'dx5',
            'header' => 'dx5'
        ],
        [
            'attribute' => 'drug_name',
            'header' => 'รายการยา'
        ],
        [
            'attribute' => 'hemo_cs',
            'header' => 'Hemoculture'
        ],
        [
            'attribute' => 'pus_cs',
            'header' => 'pus c/s'
        ],
        [
            'attribute' => 'sputum_cs',
            'header' => 'Sputum c/s'
        ],
        [
            'attribute' => 'stool_cs',
            'header' => 'Stool c/s'
        ],
        [
            'attribute' => 'urine_cs',
            'header' => 'Urine c/s'
        ],

    ]
])
?>

