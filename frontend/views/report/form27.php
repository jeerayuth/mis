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

                        <div class="col-md-3 col-sm-3 col-xs-6 col-md-offset-3">
                            <select id="village_id" class="form-control">
                                <option value="">กรุณาเลือกหมู่บ้าน</option>
                                <option value="2">หมู่ที่ 1 ปากน้ำละแม</option>
                                <option value="3">หมู่ที่ 2 ท่าแพ</option>
                                <option value="4">หมู่ที่ 3 หาดสูง</option>
                                <option value="5">หมู่ที่ 4 ทรายทอง</option>
                                <option value="6">หมู่ที่ 5 แหลมสันติ</option>
                                <option value="7">หมู่ที่ 6 ทุ่งสวรรค์</option>
                                <option value="8">หมู่ที่ 7 ควนทัง</option>
                                <option value="9">หมู่ที่ 9 ลุ่มพวาฝั่งซ้าย</option>
                                <option value="10">หมู่ที่ 10 ลุ่มพวาฝั่งขวา</option>
                                <option value="11">หมู่ที่ 12 ทุ่งขี้หนอน</option>
                                <option value="12">หมู่ที่ 14 มาดยาว</option>                           
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
        var e = document.getElementById("village_id");
        var village_id = e.options[e.selectedIndex].value;
        
        //var datestart = document.getElementById("datestart").value;
        //var dateend = document.getElementById("dateend").value;

        //ตัดเครื่องหมาย - ออก แล้วส่ง datestart&dateend ไปยัง url ที่ต้องการ
         //ตัดเครื่องหมาย - ออก แล้วส่ง datestart&dateend ไปยัง url ที่ต้องการ
         
         if(village_id == '') {
             alert("กรุณาเลือกหมู่บ้านที่ต้องการดูรายงานด้วยครับ");
             return false;
         }
                 
        window.open('http://192.168.1.252:8080/mis/frontend/web/index.php?r=<?= $ctrl ?>/<?= $point ?>&village_id=' + village_id + '&details=<?= $details; ?>');
    }




    window.onload = function () {
        //your jQuery code here
        // jquery here     
    };


</script>
