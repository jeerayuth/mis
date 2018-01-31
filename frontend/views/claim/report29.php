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
        'heading' => $head . '/ ' . $report_name,
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
            'attribute' => 'ward_name',
            'header' => 'ตึก'
        ],
        [
            'attribute' => 'an',
            'header' => 'AN'
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
            'attribute' => 'regdate',
            'header' => 'วันที่ Admit'
        ],
        [
            'attribute' => 'dchdate',
            'header' => 'วันที่จำหน่าย'
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
            'attribute' => 'inc01',
            'header' => 'หมวด01 ค่าบริการชันสูตรโรค'
        ],
         [
            'attribute' => 'inc02',
            'header' => 'หมวด02 ค่าบริการคลังเลือด'
        ],
         [
            'attribute' => 'inc03',
            'header' => 'หมวด03 ค่าบริการพยาธิวิทยา'
        ],
         [
            'attribute' => 'inc04',
            'header' => 'หมวด04 ค่าบริการรังสีวิทยา'
        ],
         [
            'attribute' => 'inc05',
            'header' => 'หมวด05 ค่าบริการตรวจวินิจฉัยอื่นๆ'
        ],
         [
            'attribute' => 'inc06',
            'header' => 'หมวด06 ค่าบริการผ่าตัด'
        ],
         [
            'attribute' => 'inc07',
            'header' => 'หมวด07 ค่าบริการระงับความรู้สึก'
        ],
         [
            'attribute' => 'inc08',
            'header' => 'หมวด08 ค่าอวัยวะเทียม'
        ],
         [
            'attribute' => 'inc09',
            'header' => 'หมวด09 ค่าอุปกรณ์บำบัดโรค'
        ],
         [
            'attribute' => 'inc10',
            'header' => 'หมวด10 ค่ายาเคมีบำบัด'
        ],
         [
            'attribute' => 'inc11',
            'header' => 'หมวด11 ค่าบริการทันตกรรม'
        ],
         [
            'attribute' => 'inc12',
            'header' => 'หมวด12 ค่ายาและเวชภัณฑ์'
        ],
         [
            'attribute' => 'inc13',
            'header' => 'หมวด13 ค่าบริการเวชกรรมฟื้นฟู'
        ],
         [
            'attribute' => 'inc14',
            'header' => 'หมวด14 ค่าบริการบำบัดรักษาอื่นๆ'
        ],
         [
            'attribute' => 'inc15',
            'header' => 'หมวด15 ค่าบริการหอผู้ป่วยหนัก'
        ],
         [
            'attribute' => 'inc16',
            'header' => 'หมวด16 ค่าห้องและอาหาร'
        ],
         [
            'attribute' => 'inc17',
            'header' => 'หมวด17 ค่าบริการทางการแพทย์ บัตรทอง'
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

