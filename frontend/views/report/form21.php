<?php
/* @var $this yii\web\View */

use kartik\grid\GridView;
use yii\helpers\Html;

$this->title = 'MIS :: ระบบรายงานสารสนเทศ';
?>

<div class="">
    <div class="page-title">
        <div class="title_left">
            <h3></h3>
        </div>

        <div class="title_right">
            <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search for...">
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="button">Go!</button>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>

    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title">
                    <h4><a href="void(0)" onclick = "javascript:(history.go(-1))">หน้าหลัก</a> >> 
                        <?php echo $report_name; ?>
                    </h4>
                    <div class="clearfix"></div>
                </div>

                <br>
                <form novalidate="" id="demo-form2" data-parsley-validate="" class="form-horizontal form-label-left">

                    <div class="form-group">

                        <div class="col-md-4 col-sm-4 col-xs-6 col-md-offset-3">
                            <select id="clinic" class="form-control">
                                <option value=" ">== กรุณาเลือกประเภทคนไข้ ==</option>
                                <option value="1">คนไข้ Diag N183-N185 และไม่มีชื่ออยู่ในทะเบียนคลินิก DM และ HT</option>
                                <option value="2">คนไข้ Diag N183-N185 และมีชื่ออยู่ในทะเบียน DM และ HT </option>
                                <option value="3">คนไข้ Diag N183-N185 มีชื่อในทะเบียน DM แต่ไม่มีชื่อในทะเบียน HT </option>
                                <option value="4">คนไข้ Diag N183-N185 มีชื่อในทะเบียน HT แต่ไม่มีชื่อในทะเบียน DM </option>
                            </select> 
                        </div>


                        <div class="col-md-4 col-sm-4 col-xs-12 col-md-offset-3">
                            <button type="button" class="btn btn-primary" onclick = "javascript:url()"><i class="fa fa-search"></i> ค้นหา</button>
                            <button type="button" class="btn btn-success" onclick = "javascript:(history.go(-1))">ย้อนกลับ</button>
                        </div>

                    </div>


            </div>

            </form>

        </div>
    </div>
</div>
</div>



<script language="javascript">

    //function เรียกหน้ารายงาน
    function url() {

        // Get ค่าจากกล่องเลือกประเภท user
        var e = document.getElementById("clinic");
        var uclinic = e.options[e.selectedIndex].value;

        window.open('http://192.168.1.252:8080/mis/frontend/web/index.php?r=<?= $ctrl ?>/<?= $point ?>&uclinic=' + uclinic + '&details=<?= $details; ?>');
    }


</script>
