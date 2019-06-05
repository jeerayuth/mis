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
            'attribute' => 'debt_date',
            'header' => 'วันที่'
        ],
        [
            'attribute' => 'debt_time',
            'header' => 'เวลา'
        ],
        [
            'attribute' => 'hn',
            'header' => 'HN'
        ],
         [
            'attribute' => 'vn',
            'header' => 'VN/AN'
        ],
        [
            'attribute' => 'ptname',
            'header' => 'ชื่อ-สกุล'
        ],
        [
            'attribute' => 'name_staff',
            'header' => 'ผู้ยกเลิก'
        ],
        [
            'attribute' => 'total_amount',
            'header' => 'จำนวนเงิน(บาท)'
        ],
        [
            'attribute' => 'reason',
            'header' => 'สาเหตุ'
        ],
    ]
])
?>

