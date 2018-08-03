<?php

namespace frontend\controllers;

use Yii;
use frontend\components\CommonController;

class PharmacyController extends CommonController {

    public $dep_controller = 'pharmacy';

    public function actionReport1($datestart, $dateend, $details) {
        // save log
        $this->SaveLog($this->dep_controller, 'report1', $this->getSession());

        $report_name = "รายงานมูลค่าการใช้ยาปฏิชีวนะ";
        $sql = "SELECT
        px.icode as icode,d.name as drug_name,d.units as drug_unit,
        d.unitprice as unit_price,
        d.unitcost as unit_cost,
        sum(px.qty) as total_use,
        sum(
            IF(px.unitprice <> 0,px.unitprice*px.qty,d.unitprice*px.qty)
            ) as sum_price ,

        sum(
            IF(px.cost <> 0,px.cost*px.qty,d.unitcost*px.qty)
            ) as sum_cost ,
        count(px.icode) as count_icode

FROM
        opitemrece px

LEFT OUTER JOIN drugitems d ON px.icode=d.icode

WHERE px.icode IN ('1000028','1000030','1460566','1510007','1000034',
'1460057','1460071','1430502','1460570','1000060','1520919','1520908',
'1510026','1000082','1510027','1000084','1000085','1480609','1000140',
'1520034','1000188','1460235','1440207','1000221','1000231','1000235',
'1000233','1510065','1000267','1540028','1550007','1540017','1520919',
'1560011','1580019','1590011','1550008')

AND px.vstdate BETWEEN $datestart AND $dateend

GROUP BY  px.icode
ORDER BY  sum_cost DESC ";

        try {
            $rawData = \yii::$app->db->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }

        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => FALSE,
        ]);

        return $this->render('report1', [
                    'dataProvider' => $dataProvider,
                    'rawData' => $rawData,
                    'report_name' => $report_name,
                    'details' => $details,
        ]);
    }

    public function actionReport2($diag_id, $datestart, $dateend, $details) {

        // save log
        $this->SaveLog($this->dep_controller, 'report2', $this->getSession());

        $diag1 = '';
        $diag2 = '';

        if ($diag_id != '') {
            if ($diag_id == 1) {
                $diag1 = 'V01';
                $diag2 = 'Y98';
            } else if ($diag_id == 2) {
                $diag1 = 'A00';
                $diag2 = 'A69';
            } else if ($diag_id == 3) {
                $diag1 = 'J069';
                $diag2 = 'J069';
            } else if ($diag_id == 4) {
                $diag1 = 'A099';
                $diag2 = 'A099';
            } else if ($diag_id == 5) {
                $diag1 = 'K529';
                $diag2 = 'K529';
            } else if ($diag_id == 6) {
                $diag1 = 'A090';
                $diag2 = 'A090';
            }
        }

        $report_name = "รายงานผู้มารับบริการแยกตามกลุ่มรหัสวินิจฉัยโรค  และมีการสั่งใช้ยา Antibiotic";


        $sql = "SELECT d.icode,d.name as drug_name,d.units as drug_unit,
            
        d.unitprice,d.unitcost,count(o.icode) as count_icode,

      sum(o.qty) as total_use,
      sum(
            IF(o.unitprice <> 0,o.unitprice*o.qty,d.unitprice*o.qty)
            ) as sum_price ,

      sum(
            IF(o.cost <> 0, o.cost*o.qty,d.unitcost*o.qty)
            ) as sum_cost

            FROM vn_stat  v

            left outer join opitemrece o on o.vn = v.vn
            left outer join drugitems d on d.icode = o.icode

            WHERE v.vstdate BETWEEN $datestart and $dateend   and

             (
                    (v.pdx between '$diag1' and '$diag2') or
                    (v.dx0 between '$diag1' and '$diag2') or
                    (v.dx1 between '$diag1' and '$diag2') or
                    (v.dx2 between '$diag1' and '$diag2') or
                    (v.dx3 between '$diag1' and '$diag2') or
                    (v.dx4 between '$diag1' and '$diag2') or
                    (v.dx5 between '$diag1' and '$diag2')
             )

             and o.icode in  ('1000028','1000030','1460566','1510007','1000034',
            '1460057','1460071','1430502','1460570','1000060','1520919','1520908',
            '1510026','1000082','1510027','1000084','1000085','1480609','1000140',
            '1520034','1000188','1460235','1440207','1000221','1000231','1000235',
            '1000233','1510065','1000267','1540028','1550007','1540017','1520919',
            '1560011','1580019','1590011','1550008')

            group by o.icode
            order by  sum_cost desc  ";


        try {
            $rawData = \yii::$app->db->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }

        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => FALSE,
        ]);

        return $this->render('report2', [
                    'dataProvider' => $dataProvider,
                    'rawData' => $rawData,
                    'report_name' => $report_name,
                    'details' => $details,
        ]);
    }

    public function actionReport3($diag_id, $datestart, $dateend, $details) {
        // save log
        $this->SaveLog($this->dep_controller, 'report3', $this->getSession());
    }

    public function actionReport4($datestart, $dateend, $details) {
        // save log
        $this->SaveLog($this->dep_controller, 'report4', $this->getSession());

        $report_name = "รายงาน High  Alert Drug";
        $sql = "SELECT
                o.vstdate,o.hn,concat(p.pname,p.fname,'  ',p.lname) as pt_name,
                o.vn,
                
                 IF(o.an != '',o.an,'')  as an ,
                   
                
                o.icode,d.name as drug_name

                FROM opitemrece o

                left outer join patient p on p.hn = o.hn
                left outer join drugitems d on d.icode = o.icode

                WHERE o.vstdate between $datestart and $dateend
                AND o.icode in ('1000010','1000118','1000113','1000114','1000058',
                '1000176','1000177','1000244','1000196','1000236','1550017','1460142')
                
                ORDER BY o.icode,o.vstdate ";

        try {
            $rawData = \yii::$app->db->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }

        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => FALSE,
        ]);


        return $this->render('report4', [
                    'dataProvider' => $dataProvider,
                    'rawData' => $rawData,
                    'report_name' => $report_name,
                    'details' => $details,
        ]);
    }

    public function actionReport5($datestart, $dateend, $details) {
        // save log
        $this->SaveLog($this->dep_controller, 'report5', $this->getSession());

        $report_name = "รายงานผลการใช้ยาปฏิชีวนะ ในผู้ป่วยติดเชื้อดื้อยา";
        $report_name2 = "กราฟสรุปจำนวนครั้งการสั่งใช้ใช้ยาปฏิชีวนะ ในผู้ป่วยติดเชื้อดื้อยา";
        $report_name3 = "กราฟสรุปจำนวนครั้งการ Admit ที่มีการสั่งใช้ยาปฏิชีวนะ ในผู้ป่วยติดเชื้อดื้อยา";


        $sql = " select
o.hn,o.an,concat(p.pname,p.fname, '  ',p.lname) as pt_name,o.vstdate,
a.pdx ,concat(a.dx0,'   ',a.dx1,'   ',a.dx2,'   ',a.dx3,'   ',a.dx4,'   ',a.dx5) as diag_second,
d.name as drug_name

from opitemrece  o

left outer join an_stat a on a.an = o.an
left outer join patient p on p.hn = o.hn
left outer join drugitems d on d.icode = o.icode
where o.icode in ('1560011','1580019','1590011','1560013')
and o.vstdate between $datestart and $dateend
order by  o.hn ,o.vstdate ";


        $sql2 = "select
o.icode,d.name as drug_name, count(o.icode) as count_use , count(distinct(o.an)) as count_an

from opitemrece  o

left outer join an_stat a on a.an = o.an
left outer join patient p on p.hn = o.hn
left outer join drugitems d on d.icode = o.icode

where o.icode in ('1560011','1580019','1590011','1560013')

and o.vstdate between $datestart and $dateend

group by o.icode ";

        try {
            $rawData = \yii::$app->db->createCommand($sql)->queryAll();
            $rawData2 = \yii::$app->db->createCommand($sql2)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }

        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => FALSE,
        ]);


        $dataProvider2 = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData2,
            'pagination' => FALSE,
        ]);


        return $this->render('report5', [
                    'dataProvider' => $dataProvider,
                    'dataProvider2' => $dataProvider2,
                    'rawData' => $rawData,
                    'rawData2' => $rawData2,
                    'report_name' => $report_name,
                    'report_name2' => $report_name2,
                    'report_name3' => $report_name3,
                    'details' => $details,
        ]);
    }

    public function actionReport6($datestart, $dateend, $details) {
        // save log
        $this->SaveLog($this->dep_controller, 'report6', $this->getSession());

        $report_name = "รายงาน 100 อันดับมูลค่าการใช้ยาตามราคาทุน";
        $sql = "SELECT s.icode,s.name as drug_name,s.units as drug_unit,s.unitprice,s.unitcost,sum(o.qty) as count_use,
        sum(IF(o.unitprice <> 0,o.unitprice*o.qty,s.unitprice*o.qty)) as sum_price ,
        sum(IF(o.cost <> 0, o.cost*o.qty,s.unitcost*o.qty)) as  sum_cost
        FROM opitemrece o
        left outer join drugitems s on s.icode=o.icode
        WHERE o.vstdate between $datestart and $dateend
        and o.icode like '1%'
        GROUP BY o.icode ,s.name
        ORDER BY sum_cost desc
        LIMIT 100 ";

        try {
            $rawData = \yii::$app->db->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }

        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => FALSE,
        ]);

        return $this->render('report6', [
                    'dataProvider' => $dataProvider,
                    'rawData' => $rawData,
                    'report_name' => $report_name,
                    'details' => $details,
        ]);
    }

    public function actionReport7($datestart, $dateend, $timestart, $timeend,$details) {
        // save log
        $this->SaveLog($this->dep_controller, 'report7', $this->getSession());

        $report_name = "รายงานจ่ายยานอกเวลา 16.01น. - 07.59น.(รวมวันหยุดราชการ)";
        
        $default_time = " and (o.rxtime between '16:00:01' and '23:59:59' or o.rxtime between '00:00:01' and '07:59:59') ";
        
        if($timestart !='' and $timeend != '') {
            $default_time = " and (o.rxtime between '$timestart' and '$timeend' ) "; 
        } else {
             $default_time = " and (o.rxtime between '16:00:01' and '23:59:59' or o.rxtime between '00:00:01' and '07:59:59') ";       
        }
    
        
        $sql = "select

            o.vn,o.hn,
            concat(DAY(o.vstdate),'/',MONTH(o.vstdate),'/',(YEAR(o.vstdate)+543)) as vstdate ,o.rxtime,
            concat(p.pname,p.fname,'  ',p.lname) as pt_name,v.age_y,c.bw as weight, c.cc as cc,v.pdx,
            concat(v.dx0,v.dx1,v.dx2,v.dx3,v.dx4,v.dx5) as diag_second,o.icode,
            concat(d.name,' ',d.strength,' x ',d.packqty,' ',d.units) as drug_name,
            nd.name as non_drug_name,o.qty,
            u.shortlist

            from opitemrece    o

            left outer join patient p on p.hn = o.hn
            left outer join vn_stat v on v.vn = o.vn
            left outer join opdscreen c on c.vn = o.vn
            left outer join drugitems d on d.icode = o.icode
            left outer join nondrugitems nd on nd.icode = o.icode
            left outer join drugusage u on u.drugusage = o.drugusage

            where o.vstdate between $datestart and $dateend
            
            $default_time
                
            and o.vn is   not null
            
          /*  and o.vstdate not in
            (
                select holiday_date from holiday
            ) */  
            and o.icode like '1%'
             order by o.vn,o.vstdate ";

        try {
            $rawData = \yii::$app->db->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }

        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => FALSE,
        ]);

        return $this->render('report7', [
                    'dataProvider' => $dataProvider,
                    'rawData' => $rawData,
                    'report_name' => $report_name,
                    'details' => $details,
        ]);
    }

    public function actionReport8($datestart, $dateend, $timestart, $timeend,$details) {
        // save log
        $this->SaveLog($this->dep_controller, 'report8', $this->getSession());

        $report_name = "รายงานจำนวน visit ที่จ่ายยานอกเวลา 16.01น. - 07.59น.(รวมวันหยุดราชการ)";
        
          $default_time = " and (o.rxtime between '16:00:01' and '23:59:59' or o.rxtime between '00:00:01' and '07:59:59') ";
        
        if($timestart !='' and $timeend != '') {
            $default_time = " and (o.rxtime between '$timestart' and '$timeend' ) "; 
        } else {
             $default_time = " and (o.rxtime between '16:00:01' and '23:59:59' or o.rxtime between '00:00:01' and '07:59:59') ";       
        }
        
        $sql = "SELECT
                    'จำนวนครั้ง visit' as name,
                    count(distinct(o.vn)) as count_visit

                FROM opitemrece    o

                left outer join patient p on p.hn = o.hn
                left outer join vn_stat v on v.vn = o.vn
                left outer join opdscreen c on c.vn = o.vn
                left outer join drugitems d on d.icode = o.icode
                left outer join nondrugitems nd on nd.icode = o.icode
                left outer join drugusage u on u.drugusage = o.drugusage

                WHERE o.vstdate between $datestart and $dateend
                    
                $default_time

                and o.vn is   not null

              /*  and o.vstdate not in
                (
                    select holiday_date from holiday
                ) */  
                and o.icode like '1%'  ";


        try {
            $rawData = \yii::$app->db->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }

        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => FALSE,
        ]);

        return $this->render('report8', [
                    'dataProvider' => $dataProvider,
                    'rawData' => $rawData,
                    'report_name' => $report_name,
                    'details' => $details,
        ]);
    }

    public function actionReport9($diag_id, $datestart, $dateend, $details) {
        // save log
        $this->SaveLog($this->dep_controller, 'report9', $this->getSession());

        $diag1 = '';
        $diag2 = '';

        if ($diag_id != '') {
            if ($diag_id == 1) {
                $diag1 = 'V01';
                $diag2 = 'Y98';
            } else if ($diag_id == 2) {
                $diag1 = 'A00';
                $diag2 = 'A69';
            } else if ($diag_id == 3) {
                $diag1 = 'J069';
                $diag2 = 'J069';
            } else if ($diag_id == 4) {
                $diag1 = 'A099';
                $diag2 = 'A099';
            } else if ($diag_id == 5) {
                $diag1 = 'K529';
                $diag2 = 'K529';
            } else if ($diag_id == 6) {
                $diag1 = 'A090';
                $diag2 = 'A090';
            }
        }


        $report_name = "รายงานผู้มารับบริการแยกตามกลุ่มรหัสวินิจฉัยโรค";
        $sql = "
                SELECT
                    'จำนวน visit ผู้ป่วยนอก (แต่ละ visit มีการสั่งใช้ยา Antibiotic)' as name, 
                    count(distinct(v.vn)) as count_diag
                FROM vn_stat v
                left outer join opitemrece o on o.vn = v.vn
                WHERE o.rxdate between $datestart and $dateend  AND
                        (
                            (v.pdx between '$diag1' and '$diag2') or
                            (v.dx0 between '$diag1' and '$diag2') or
                            (v.dx1 between '$diag1' and '$diag2') or
                            (v.dx2 between '$diag1' and '$diag2') or
                            (v.dx3 between '$diag1' and '$diag2') or
                            (v.dx4 between '$diag1' and '$diag2') or
                            (v.dx5 between '$diag1' and '$diag2')
                        )

                 AND o.icode in  ('1000028','1000030','1460566','1510007','1000034',
                            '1460057','1460071','1430502','1460570','1000060','1520919','1520908',
                            '1510026','1000082','1510027','1000084','1000085','1480609','1000140',
                            '1520034','1000188','1460235','1440207','1000221','1000231','1000235',
                            '1000233','1510065','1000267','1540028','1550007','1540017','1520919',
                            '1560011','1580019','1590011','1550008')


                UNION


                SELECT
                'จำนวน visit ผู้ป่วยนอก (แต่ละ visit ทั้งที่มีการสั่งใช้/ไม่สั่งใช้ยา Antibiotic)' as name, count(distinct(v.vn)) as count_diag
                FROM vn_stat v
                left outer join opitemrece o on o.vn = v.vn
                WHERE v.vstdate between $datestart and $dateend    AND
                            (
                            (v.pdx between '$diag1' and '$diag2') or
                            (v.dx0 between '$diag1' and '$diag2') or
                            (v.dx1 between '$diag1' and '$diag2') or
                            (v.dx2 between '$diag1' and '$diag2') or
                            (v.dx3 between '$diag1' and '$diag2') or
                            (v.dx4 between '$diag1' and '$diag2') or
                            (v.dx5 between '$diag1' and '$diag2')
                        )


                UNION
                

                SELECT
                'จำนวน admit ผู้ป่วยใน (แต่ละ admit มีการสั่งใช้ยา Antibiotic)' as name, count(distinct(a.an)) as count_diag
                FROM an_stat a
                left outer join opitemrece o on o.an = a.an
                WHERE o.rxdate between $datestart and $dateend    AND
                                (
                                    (a.pdx between '$diag1' and '$diag2') or
                                    (a.dx0 between '$diag1' and '$diag2') or
                                    (a.dx1 between '$diag1' and '$diag2') or
                                    (a.dx2 between '$diag1' and '$diag2') or
                                    (a.dx3 between '$diag1' and '$diag2') or
                                    (a.dx4 between '$diag1' and '$diag2') or
                                    (a.dx5 between '$diag1' and '$diag2')
                             )

                 AND o.icode in  ('1000028','1000030','1460566','1510007','1000034',
                            '1460057','1460071','1430502','1460570','1000060','1520919','1520908',
                            '1510026','1000082','1510027','1000084','1000085','1480609','1000140',
                            '1520034','1000188','1460235','1440207','1000221','1000231','1000235',
                            '1000233','1510065','1000267','1540028','1550007','1540017','1520919',
                            '1560011','1580019','1590011','1550008')



                UNION



                SELECT
                'จำนวน admit ผู้ป่วยใน (แต่ละ admit  ทั้งที่มีการสั่งใช้/ไม่สั่งใช้ยา Antibiotic)' as name, count(distinct(a.an)) as count_diag
                FROM an_stat a
                left outer join opitemrece o on o.an = a.an
                WHERE a.dchdate between $datestart and $dateend     AND
                                 (
                                    (a.pdx between '$diag1' and '$diag2') or
                                    (a.dx0 between '$diag1' and '$diag2') or
                                    (a.dx1 between '$diag1' and '$diag2') or
                                    (a.dx2 between '$diag1' and '$diag2') or
                                    (a.dx3 between '$diag1' and '$diag2') or
                                    (a.dx4 between '$diag1' and '$diag2') or
                                    (a.dx5 between '$diag1' and '$diag2')
                             )    ";




        try {
            $rawData = \yii::$app->db->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }

        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => FALSE,
        ]);

        return $this->render('report9', [
                    'dataProvider' => $dataProvider,
                    'rawData' => $rawData,
                    'report_name' => $report_name,
                    'details' => $details,
        ]);
    }

    public function actionReport10($datestart, $dateend, $details) {
        // save log
        $this->SaveLog($this->dep_controller, 'report10', $this->getSession());

        $report_name = "รายงานจำนวน visit ที่ได้รับยา Glibenclamide";
        $sql = "SELECT
                    concat(DAY(v.vstdate),'/',MONTH(v.vstdate),'/',(YEAR(v.vstdate)+543)) as vstdate
                    ,v.vn,v.hn,concat(pt.pname,pt.fname,'  ',pt.lname) as pt_name,
                    v.pdx,v.dx0,v.dx1,v.dx2,v.dx3,v.dx4,v.dx5 , op.bw as bw,v.age_y ,
                    'eGFR' as lab_items_name,
                    (
                        select
                              lo.lab_order_result from lab_head lh
                        left outer join lab_order lo on lo.lab_order_number = lh.lab_order_number
                        where lh.vn = v.vn  and lo.lab_items_code = '3248' and lo.lab_order_result != ''
                     ) as lab_order_result ,
                    om.icode ,concat(d.name,' ',d.strength,' x ',d.packqty,' ',d.units) as drug_name,
                    om.qty ,d.units
                FROM vn_stat v
                left outer join opdscreen op on op.vn = v.vn
                left outer join patient pt on pt.hn = v.hn
                left outer join opitemrece om on om.vn = v.vn
                left outer join drugitems d on d.icode = om.icode

                WHERE
                v.vstdate between $datestart and $dateend
                and om.icode in ('1460402')
                ORDER BY v.vn,v.vstdate ";

        try {
            $rawData = \yii::$app->db->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }

        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => FALSE,
        ]);

        return $this->render('report10', [
                    'dataProvider' => $dataProvider,
                    'rawData' => $rawData,
                    'report_name' => $report_name,
                    'details' => $details,
        ]);
    }

    public function actionReport11($datestart, $dateend, $details) {
        // save log
        $this->SaveLog($this->dep_controller, 'report11', $this->getSession());

        $report_name = "รายงานจำนวน visit ที่ได้รับยา METFORMIN";
        $sql = "SELECT
                    concat(DAY(v.vstdate),'/',MONTH(v.vstdate),'/',(YEAR(v.vstdate)+543)) as vstdate
                    ,v.vn,v.hn,concat(pt.pname,pt.fname,'  ',pt.lname) as pt_name,
                    v.pdx,v.dx0,v.dx1,v.dx2,v.dx3,v.dx4,v.dx5 , op.bw as bw,v.age_y ,
                    'eGFR' as lab_items_name,
                    (
                        select
                              lo.lab_order_result from lab_head lh
                        left outer join lab_order lo on lo.lab_order_number = lh.lab_order_number
                        where lh.vn = v.vn  and lo.lab_items_code = '3248' and lo.lab_order_result != ''
                     ) as lab_order_result ,
                    om.icode ,concat(d.name,' ',d.strength,' x ',d.packqty,' ',d.units) as drug_name,
                    om.qty ,d.units
                FROM vn_stat v
                left outer join opdscreen op on op.vn = v.vn
                left outer join patient pt on pt.hn = v.hn
                left outer join opitemrece om on om.vn = v.vn
                left outer join drugitems d on d.icode = om.icode

                WHERE
                v.vstdate between $datestart and $dateend
                and om.icode in ('1430101')
                ORDER BY v.vn,v.vstdate ";


        try {
            $rawData = \yii::$app->db->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }

        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => FALSE,
        ]);

        return $this->render('report11', [
                    'dataProvider' => $dataProvider,
                    'rawData' => $rawData,
                    'report_name' => $report_name,
                    'details' => $details,
        ]);
    }

    public function actionReport12($datestart, $dateend, $details) {
        // save log
        $this->SaveLog($this->dep_controller, 'report12', $this->getSession());

        $report_name = "รายงานจำนวน visit ที่ได้รับยา Diclofenac,Mefenamic,Ibuprofen";
        $sql = "SELECT
                    concat(DAY(v.vstdate),'/',MONTH(v.vstdate),'/',(YEAR(v.vstdate)+543)) as vstdate
                    ,v.vn,v.hn,concat(pt.pname,pt.fname,'  ',pt.lname) as pt_name,
                    v.pdx,v.dx0,v.dx1,v.dx2,v.dx3,v.dx4,v.dx5 , op.bw as bw,v.age_y ,
                    'eGFR' as lab_items_name,
                    (
                        select
                              lo.lab_order_result from lab_head lh
                        left outer join lab_order lo on lo.lab_order_number = lh.lab_order_number
                        where lh.vn = v.vn  and lo.lab_items_code = '3248' and lo.lab_order_result != ''
                     ) as lab_order_result ,
                    om.icode ,concat(d.name,' ',d.strength,' x ',d.packqty,' ',d.units) as drug_name,
                    om.qty ,d.units
                FROM vn_stat v
                left outer join opdscreen op on op.vn = v.vn
                left outer join patient pt on pt.hn = v.hn
                left outer join opitemrece om on om.vn = v.vn
                left outer join drugitems d on d.icode = om.icode

                WHERE
                v.vstdate between $datestart and $dateend
                and om.icode in ('1000110','1000182','1000154')
                ORDER BY v.vn,v.vstdate ";


        try {
            $rawData = \yii::$app->db->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }

        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => FALSE,
        ]);

        return $this->render('report12', [
                    'dataProvider' => $dataProvider,
                    'rawData' => $rawData,
                    'report_name' => $report_name,
                    'details' => $details,
        ]);
    }

    public function actionReport13($datestart, $dateend, $details) {
        // save log
        $this->SaveLog($this->dep_controller, 'report13', $this->getSession());

        $report_name = "รายงานจำนวน visit ที่ได้รับยา Diclofenac และ Mefenamic และIbuprofen ร่วมกัน";
        $sql = "SELECT
                    concat(DAY(v.vstdate),'/',MONTH(v.vstdate),'/',(YEAR(v.vstdate)+543)) as vstdate
                    ,v.vn,v.hn,concat(pt.pname,pt.fname,'  ',pt.lname) as pt_name,
                    v.pdx,v.dx0,v.dx1,v.dx2,v.dx3,v.dx4,v.dx5 , op.bw as bw,v.age_y ,
                    'eGFR' as lab_items_name,
                    (
                        select
                              lo.lab_order_result from lab_head lh
                        left outer join lab_order lo on lo.lab_order_number = lh.lab_order_number
                        where lh.vn = v.vn  and lo.lab_items_code = '3248' and lo.lab_order_result != ''
                     ) as lab_order_result ,
                    om.icode ,concat(d.name,' ',d.strength,' x ',d.packqty,' ',d.units) as drug_name,
                    om.qty ,d.units
                FROM vn_stat v
                left outer join opdscreen op on op.vn = v.vn
                left outer join patient pt on pt.hn = v.hn
                left outer join opitemrece om on om.vn = v.vn
                left outer join drugitems d on d.icode = om.icode

                WHERE
                v.vstdate between $datestart and $dateend
                and (om.icode='1000110' and om.icode='1000182' and om.icode='1000154')
                ORDER BY v.vn,v.vstdate ";


        try {
            $rawData = \yii::$app->db->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }

        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => FALSE,
        ]);

        return $this->render('report13', [
                    'dataProvider' => $dataProvider,
                    'rawData' => $rawData,
                    'report_name' => $report_name,
                    'details' => $details,
        ]);
    }

    public function actionReport14($datestart, $dateend, $details) {
        // save log
        $this->SaveLog($this->dep_controller, 'report14', $this->getSession());

        $report_name = "รายงานจำนวนคนที่ได้รับยาในกลุ่มยาที่กำหนด";
        $sql = "SELECT  
                    o.icode,d.name as drug_name,count(distinct(o.hn))  as count_hn
                FROM opitemrece  o
                LEFT OUTER JOIN drugitems d on d.icode = o.icode
                WHERE
                    o.rxdate BETWEEN $datestart and $dateend
                AND o.icode IN ('1430101','1460402','1000154','1000110','1000182')
                GROUP BY o.icode ";


        try {
            $rawData = \yii::$app->db->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }

        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => FALSE,
        ]);

        return $this->render('report14', [
                    'dataProvider' => $dataProvider,
                    'rawData' => $rawData,
                    'report_name' => $report_name,
                    'details' => $details,
        ]);
    }

    public function actionReport15($rxtime_id, $datestart, $dateend, $details) {

        // save log
        $this->SaveLog($this->dep_controller, 'report15', $this->getSession());
        $rxtime = "";

        if ($rxtime_id != '') {
            if ($rxtime_id == 1) {
                $report_name = "รายงานการสั่งใช้ยานอกเวลาที่ห้องอุบัติเหตุฉุกเฉิน(ช่วงเวลา 16.01น. ถึง 17.00น.)";
                $rxtime = " '16:01:01' and '17:00:59' ";
            } else if ($rxtime_id == 2) {
                $report_name = "รายงานการสั่งใช้ยานอกเวลาที่ห้องอุบัติเหตุฉุกเฉิน(ช่วงเวลา 17.01 น. ถึง 18.00น.)";
                $rxtime = " '17:01:00' and '18:00:59' ";
            } else if ($rxtime_id == 3) {
                $report_name = "รายงานการสั่งใช้ยานอกเวลาที่ห้องอุบัติเหตุฉุกเฉิน(ช่วงเวลา 18.01 น. ถึง 19.00น.)";
                $rxtime = " '18:01:00' and '19:00:59' ";
            } else if ($rxtime_id == 4) {
                $report_name = "รายงานการสั่งใช้ยานอกเวลาที่ห้องอุบัติเหตุฉุกเฉิน(ช่วงเวลา 19.01 น. ถึง 20.00น.)";
                $rxtime = " '19:01:00' and '20:00:59' ";
            }
        }


        $sql = "SELECT
                    o.icode,d.name as drug_name,sum(o.qty) as sum_qty

                FROM opitemrece    o
                    left outer join patient p on p.hn = o.hn
                    left outer join vn_stat v on v.vn = o.vn
                    left outer join opdscreen c on c.vn = o.vn
                    left outer join drugitems d on d.icode = o.icode
                    left outer join nondrugitems nd on nd.icode = o.icode
                
                WHERE o.rxdate between $datestart and $dateend  and o.rxtime between $rxtime
     
                    and o.icode like '1%'              
                    and o.rxdate not in
                        (
                            select holiday_date from holiday
                         )
                        
                GROUP BY  o.icode
                ORDER BY  sum_qty  desc ";


        try {
            $rawData = \yii::$app->db->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }

        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => FALSE,
        ]);


        return $this->render('report15', [
                    'dataProvider' => $dataProvider,
                    'rawData' => $rawData,
                    'report_name' => $report_name,
                    'details' => $details,
        ]);
    }

    public function actionReport16($rxtime_id, $datestart, $dateend, $details) {

        // save log
        $this->SaveLog($this->dep_controller, 'report16', $this->getSession());
        $rxtime = "";

        if ($rxtime_id != '') {
            if ($rxtime_id == 1) {
                $report_name = "รายงานจำนวน visit คนไข้สั่งใช้ยา ที่ห้องอุบัติเหตุฉุกเฉิน(ช่วงเวลา 16.01น. ถึง 17.00น.)";
                $rxtime = " '16:01:01' and '17:00:59' ";
            } else if ($rxtime_id == 2) {
                $report_name = "รายงานจำนวน visit คนไข้สั่งใช้ยา ที่ห้องอุบัติเหตุฉุกเฉิน(ช่วงเวลา 17.01 น. ถึง 18.00น.)";
                $rxtime = " '17:01:00' and '18:00:59' ";
            } else if ($rxtime_id == 3) {
                $report_name = "รายงานจำนวน visit คนไข้สั่งใช้ยา ที่ห้องอุบัติเหตุฉุกเฉิน(ช่วงเวลา 18.01 น. ถึง 19.00น.)";
                $rxtime = " '18:01:00' and '19:00:59' ";
            } else if ($rxtime_id == 4) {
                $report_name = "รายงานจำนวน visit คนไข้สั่งใช้ยา ที่ห้องอุบัติเหตุฉุกเฉิน(ช่วงเวลา 19.01 น. ถึง 20.00น.)";
                $rxtime = " '19:01:00' and '20:00:59' ";
            }
        }


        $sql = "SELECT
                    count(distinct(o.vn)) +   count(distinct(o.an))      as count_visit
                FROM opitemrece    o
                    left outer join patient p on p.hn = o.hn
                    left outer join vn_stat v on v.vn = o.vn
                    left outer join opdscreen c on c.vn = o.vn
                    left outer join drugitems d on d.icode = o.icode
                    left outer join nondrugitems nd on nd.icode = o.icode
                
                WHERE o.rxdate between $datestart and $dateend  and o.rxtime between $rxtime
   
                    and o.icode like '1%'
                    
                    and o.rxdate not in
                        (
                            select holiday_date from holiday
                         )  ";


        try {
            $rawData = \yii::$app->db->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }

        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => FALSE,
        ]);


        return $this->render('report16', [
                    'dataProvider' => $dataProvider,
                    'rawData' => $rawData,
                    'report_name' => $report_name,
                    'details' => $details,
        ]);
    }

    public function actionReport17($datestart, $dateend, $details) {
        // save log
        $this->SaveLog($this->dep_controller, 'report17', $this->getSession());

        $report_name = "รายงานมูลค่าการใช้ยาทั้งหมด";
        $sql = "SELECT s.icode,concat(s.name,'(',s.strength,')') as drug_name,s.units as drug_unit,s.unitprice,s.unitcost,sum(o.qty) as count_use,
        sum(IF(o.unitprice <> 0,o.unitprice*o.qty,s.unitprice*o.qty)) as sum_price ,
        sum(IF(o.cost <> 0, o.cost*o.qty,s.unitcost*o.qty)) as  sum_cost
        FROM opitemrece o
        left outer join drugitems s on s.icode=o.icode
        WHERE o.vstdate between $datestart and $dateend
        and o.icode like '1%'
        GROUP BY o.icode ,s.name
        ORDER BY sum_cost desc ";

        try {
            $rawData = \yii::$app->db->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }

        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => FALSE,
        ]);

        return $this->render('report17', [
                    'dataProvider' => $dataProvider,
                    'rawData' => $rawData,
                    'report_name' => $report_name,
                    'details' => $details,
        ]);
    }

    public function actionReport18($datestart, $dateend, $details) {
        // save log
        $this->SaveLog($this->dep_controller, 'report18', $this->getSession());

        $report_name = "รายงานคนไข้จิตเวช(ผู้ที่มีรหัสวินิจฉัย F00-F99)";
        $sql = "SELECT
                    v.vn,v.hn,concat(p.pname,p.fname,'  ',p.lname) as pt_name,
                    concat(DAY(v.vstdate),'/',MONTH(v.vstdate),'/',(YEAR(v.vstdate)+543)) as vstdate,
                    v.pdx,o.bw as weight,
                    o.pulse ,concat(o.bps,' / ',o.bpd) as bps_bpd,
                     concat(
                           if(v.dx0 is not null,concat(v.dx0,'   '),'  '),
                           if(v.dx1 is not null,concat(v.dx1,'   '),'  '),
                           if(v.dx2 is not null,concat(v.dx2,'   '),'  '),
                           if(v.dx3 is not null,concat(v.dx3,'   '),'  '),
                           if(v.dx4 is not null,concat(v.dx4,'   '),'  '),
                           if(v.dx5 is not null,concat(v.dx5,'   '),'  ')
                           )  as second_diag,  
                           
                    GROUP_CONCAT(DISTINCT concat('[ ',d.name, du.shortlist ,' สั่งใช้=',om.qty, ' ]') SEPARATOR ', ')  as drug,
                    GROUP_CONCAT(DISTINCT concat('[ ',li.lab_items_name,'=', lo.lab_order_result, ' ]') SEPARATOR ', ')  as lab
                
                FROM vn_stat v
                left outer join patient p  on p.hn = v.hn
                left outer join opdscreen o on o.vn = v.vn
                left outer join opitemrece om on om.vn = v.vn
                left outer join drugitems d on d.icode = om.icode
                left outer join drugusage du on du.drugusage = om.drugusage
                
                left outer join lab_head lh on lh.vn = v.vn
                left outer join lab_order lo on lo.lab_order_number = lh.lab_order_number
                left outer join lab_items li on li.lab_items_code = lo.lab_items_code
                

                WHERE v.vstdate BETWEEN $datestart and $dateend   and
                                                       
                    (
                          v.pdx between 'f00' and 'f99' or
                          v.dx0 between 'f00' and 'f99' or
                          v.dx1 between 'f00' and 'f99' or
                          v.dx2 between 'f00' and 'f99' or
                          v.dx3 between 'f00' and 'f99' or
                          v.dx4 between 'f00' and 'f99' or
                          v.dx5 between 'f00' and 'f99'
                    )
                   
                    
                GROUP BY v.vn
             
                ";


        try {
            $rawData = \yii::$app->db->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }

        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => FALSE,
        ]);

        return $this->render('report18', [
                    'dataProvider' => $dataProvider,
                    'rawData' => $rawData,
                    'date_start' => $datestart,
                    'date_end' => $dateend,
                    'report_name' => $report_name,
                    'details' => $details,
        ]);
    }

    public function actionReport19($hn, $vn, $pt_name, $date_start, $date_end) {

        $sql = "SELECT 
                    o.icode,
                    d.name as drug_name,
                    o.qty,
                    du.shortlist
                FROM opitemrece o
                LEFT OUTER JOIN drugitems d ON d.icode = o.icode
                LEFT OUTER JOIN drugusage du on du.drugusage = o.drugusage
                
                WHERE o.vn = $vn AND o.icode like '1%' ";
            
        
        $sql_lab = "SELECT         
                    lh.vn,lh.hn,lh.lab_order_number,lh.report_date ,
                    lo.lab_items_code,li.lab_items_name,lo.confirm,lo.lab_order_result,
                    concat(DAY(lh.order_date),'/',MONTH(lh.order_date),'/',(YEAR(lh.order_date)+543)) as order_date
                FROM lab_head  lh
                    left outer join lab_order lo on lo.lab_order_number = lh.lab_order_number
                    left outer join lab_items li on li.lab_items_code = lo.lab_items_code
                WHERE 
                    lh.vn =  $vn  and lo.confirm = 'Y' ";
                 

        
        try {
            $rawData = \yii::$app->db->createCommand($sql)->queryAll();
            $rawData2 = \yii::$app->db->createCommand($sql_lab)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }

        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => FALSE,
        ]);
        $dataProvider2 = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData2,
            'pagination' => FALSE,
        ]);

        return $this->render('report19', [
                    'dataProvider' => $dataProvider,
                    'dataProvider2' => $dataProvider2,
                    'rawData' => $rawData,
                    'hn' => $hn,
                    'pt_name' => $pt_name,
        ]);
    }
    
    
    public function actionReport20($datestart, $dateend, $details) {
        // save log
        $this->SaveLog($this->dep_controller, 'report20', $this->getSession());

        $report_name = "รายงานคนไข้สั่งใช้ยา Clozapine(B)(รหัสยา 1580029,1580030) ";
        $sql = "SELECT
                      o.vn,o.hn,
                      if(o.an is not null,o.an,' ') as an,
                      CONCAT(p.pname,p.fname,'  ',p.lname) as pt_name,
                      CONCAT(DAY(o.vstdate),'/',MONTH(o.vstdate),'/',(YEAR(o.vstdate)+543)) as vstdate,
                      GROUP_CONCAT(concat('[ ',o.icode,' ',d.name ,' สั่งใช้=',o.qty, ' ]') SEPARATOR ', ')  as drug1  ,
                      GROUP_CONCAT(concat('[ ',d.name, du.shortlist ,' สั่งใช้=',o.qty, ' ]') SEPARATOR ', ')  as drug2
                FROM opitemrece o
                LEFT OUTER JOIN drugitems d ON d.icode = o.icode
                LEFT OUTER JOIN patient p ON p.hn = o.hn
                LEFT OUTER JOIN drugusage du on du.drugusage = o.drugusage
                WHERE
                     o.vstdate BETWEEN $datestart and $dateend  AND
                     o.icode IN ('1580029','1580030')
                GROUP BY o.vn
                ORDER BY o.hn,o.vstdate  ";

               

        try {
            $rawData = \yii::$app->db->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }

        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => FALSE,
        ]);

        return $this->render('report20', [
                    'dataProvider' => $dataProvider,
                    'rawData' => $rawData,
                    'report_name' => $report_name,
                    'details' => $details,
        ]);
    }
    
    
    
    public function actionReport21($datestart, $dateend, $details) {
        // save log
        $this->SaveLog($this->dep_controller, 'report21', $this->getSession());

        $report_name = "การสั่งจ่ายยาใช้ยาเสพติดและวัตุถุออกฤทธ์ประเภท 2 (รหัสยา 1460028,1580020,1460536,1000196,1000236)";
        $sql = "SELECT s.icode,s.name as drug_name,s.units as drug_unit,s.unitprice,s.unitcost,sum(o.qty) as count_use,
        sum(IF(o.unitprice <> 0,o.unitprice*o.qty,s.unitprice*o.qty)) as sum_price ,
        sum(IF(o.cost <> 0, o.cost*o.qty,s.unitcost*o.qty)) as  sum_cost
        FROM opitemrece o
        left outer join drugitems s on s.icode=o.icode
        WHERE o.vstdate between $datestart and $dateend
        and o.icode in (1460028,1580020,1460536,1000196,1000236)
        GROUP BY o.icode ,s.name
        ORDER BY sum_cost desc ";

        try {
            $rawData = \yii::$app->db->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }

        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => FALSE,
        ]);

        return $this->render('report21', [
                    'dataProvider' => $dataProvider,
                    'rawData' => $rawData,
                    'report_name' => $report_name,
                    'details' => $details,
        ]);
    }
    
    
     public function actionReport22($datestart, $dateend, $details) {
        // save log
        $this->SaveLog($this->dep_controller, 'report22', $this->getSession());

        $report_name = "รายงานมูลค่าการใช้ยา ARV";
        $sql = "SELECT
                        px.icode as icode,d.name as drug_name,d.units as drug_unit,
                        d.unitprice as unit_price,
                        d.unitcost as unit_cost,
                        sum(px.qty) as total_use,
                        sum(
                            IF(px.unitprice <> 0,px.unitprice*px.qty,d.unitprice*px.qty)
                            ) as sum_price ,

                        sum(
                            IF(px.cost <> 0,px.cost*px.qty,d.unitcost*px.qty)
                            ) as sum_cost ,
                        count(px.icode) as count_icode

                FROM
                        opitemrece px

                LEFT OUTER JOIN drugitems d ON px.icode=d.icode

                WHERE px.icode IN ('1570019','1590008','1430501','1570020','1580014',
                '1600148','1460563','1520002','1600159','1470510',
                '1580013','1460526','1520001','1600180','1520901','1520003','1470004','1460176','1510035')

                AND px.vstdate BETWEEN $datestart AND $dateend

                GROUP BY  px.icode
                ORDER BY  sum_cost DESC ";

        
        try {
            $rawData = \yii::$app->db->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }

        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => FALSE,
        ]);

       
        return $this->render('report22', [
                    'dataProvider' => $dataProvider,
                    'rawData' => $rawData,
                    'report_name' => $report_name,
                    'details' => $details,
        ]); 
    }
    
    
    
    
    

}
