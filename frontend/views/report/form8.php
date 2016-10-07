<?php
/* @var $this yii\web\View */

//use yii\jui\DatePicker;
use kartik\datecontrol\Module;
use kartik\date\DatePicker;
use kartik\datecontrol\DateControl;

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
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">ช่วงวันที่ <span class="required">*</span>
                        </label>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <?php
                            echo DatePicker::widget([
                                'name' => 'from_date',
                                'type' => DatePicker::TYPE_RANGE,
                                'name2' => 'to_date',
                                'pluginOptions' => [
                                    'autoclose' => true,
                                    'format' => 'dd-mm-yyyy'
                                ]
                            ]);
                            ?>
                        </div>
                    </div>


                    <div class="form-group">

                        <div class="col-md-3 col-sm-3 col-xs-6 col-md-offset-3">
                            <select id="diag_id" class="form-control">
                                <option value="">กรุณาเลือกกลุ่มวินิจฉัยโรค</option>
                                <option value="1">Diag V01 ถึง Y98</option>
                                <option value="2">Diag A00 ถึง A69</option>
                                <option value="3">Diag J069</option>
                                <option value="4">Diag A099</option>
                                <option value="5">Diag K529</option>
                            </select> 
                        </div>
                    </div>


                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                            <button type="button" class="btn btn-primary" onclick = "javascript:url()"><i class="fa fa-search"></i> ค้นหา</button>
                            <button type="button" class="btn btn-success" onclick = "javascript:(history.go(-1))">ย้อนกลับ</button>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>


<script type="text/javascript">

    //function เรียกหน้ารายงาน
    function url() {

        // Get ค่าจากกล่องเลือกประเภท user
        var e = document.getElementById("diag_id");
        var diag_id = e.options[e.selectedIndex].value;
        //var datestart = document.getElementById("datestart").value;
        //var dateend = document.getElementById("dateend").value;

        //ตัดเครื่องหมาย - ออก แล้วส่ง datestart&dateend ไปยัง url ที่ต้องการ
         //ตัดเครื่องหมาย - ออก แล้วส่ง datestart&dateend ไปยัง url ที่ต้องการ
         
         if(diag_id == '') {
             alert("กรุณาเลือกรหัสวินิจฉัยโรคที่ต้องการดูรายงานด้วยครับ");
             return false;
         }
         
        d1 = $('#w0').val();
        var arr1 = d1.split("-");
        s1 = arr1[0];
        s2 = arr1[1];
        s3 = arr1[2] - 543;
        datestart = s3 + s2 + s1;


        d2 = $('#w0-2').val();
        var arr2 = d2.split("-");
        m1 = arr2[0];
        m2 = arr2[1];
        m3 = arr2[2] - 543;
        dateend = m3 + m2 + m1;

        window.open('http://192.168.1.252:8080/mis/frontend/web/index.php?r=<?= $ctrl ?>/<?= $point ?>&diag_id=' + diag_id + '&datestart=' + datestart + '&dateend=' + dateend + '&details=<?= $details; ?>');
    }




    window.onload = function () {
        //your jQuery code here
        // jquery here     
    };


</script>
