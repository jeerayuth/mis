<?php

namespace frontend\controllers;

class PharmacyController extends \yii\web\Controller {
    /* รายงานมูลค่าการใช้ยาปฏิชีวนะ */

    public function actionReport1($datestart, $dateend, $details) {

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
'1560011','1580019','1590011')

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
'1560011','1580019','1590011')

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
        
    }

    public function actionReport4($datestart, $dateend, $details) {
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
where o.icode in ('1560011','1580019','1590011')
and o.vstdate between $datestart and $dateend
order by  o.hn ,o.vstdate ";

        
        $sql2 = "select
o.icode,d.name as drug_name, count(o.icode) as count_use , count(distinct(o.an)) as count_an

from opitemrece  o

left outer join an_stat a on a.an = o.an
left outer join patient p on p.hn = o.hn
left outer join drugitems d on d.icode = o.icode

where o.icode in ('1560011','1580019','1590011')

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
    
    
     public function actionReport7($datestart, $dateend, $details) {

        $report_name = "รายงานจ่ายยานอกเวลา 16.01น. - 07.59น.(ไม่รวมวันหยุด)";
        $sql = "select

            o.vn,o.hn,
            concat(DAY(o.vstdate),'/',MONTH(o.vstdate),'/',(YEAR(o.vstdate)+543)) as vstdate ,o.rxtime,
            concat(p.pname,p.fname,'  ',p.lname) as pt_name,v.age_y,c.bw as weight, c.cc as cc,v.pdx,
            concat(v.dx0,v.dx1,v.dx2,v.dx3,v.dx4,v.dx5) as diag_second,o.icode,d.name as drug_name, nd.name as non_drug_name,o.qty,
            u.shortlist

            from opitemrece    o

            left outer join patient p on p.hn = o.hn
            left outer join vn_stat v on v.vn = o.vn
            left outer join opdscreen c on c.vn = o.vn
            left outer join drugitems d on d.icode = o.icode
            left outer join nondrugitems nd on nd.icode = o.icode
            left outer join drugusage u on u.drugusage = o.drugusage

            where o.vstdate between $datestart and $dateend
            and (o.rxtime between '16:00:01' and '23:59:59' or o.rxtime between '00:00:01' and '07:59:59')

            and o.vn is   not null
            
            and o.vstdate not in
            (
                select holiday_date from holiday
            )  
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


}
