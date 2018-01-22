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
        'heading' => $head . ' / ' . $report_name,
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
            'attribute' => 'department_name',
            'header' => 'แผนก'
        ],
        [
            'attribute' => 'hn',
            'header' => 'HN'
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
            'attribute' => 'vstdate',
            'header' => 'วันที่รับบริการ'
        ],
         [
            'attribute' => 'pdx',
            'header' => 'รหัสวินิจฉัยหลัก'
        ],
         [
            'attribute' => 'second_diag',
            'header' => 'รหัสวินิจฉัยรอง'
        ],
        [
            'attribute' => 'pttype',
            'header' => 'รหัสสิทธิ์'
        ],
        [
            'attribute' => 'pttype_name',
            'header' => 'ชื่อสิทธิ์'
        ],
        [
            'attribute' => 'hospmain',
            'header' => 'รหัสสถานพยาบาลหลัก(ตอนเปิด visit)'
        ],
        [
            'attribute' => 'income',
            'header' => 'รวมค่าใช้จ่าย'
        ],
        [
            'attribute' => 'uc_money',
            'header' => 'ลูกหนี้'
        ],
        [
            'attribute' => 'net_total',
            'header' => 'ชำระแล้ว'
        ],
    ]
])
?>

