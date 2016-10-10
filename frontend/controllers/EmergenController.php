<?php

namespace frontend\controllers;

class EmergenController extends \yii\web\Controller {
    /* รายงานสรุปทะเบียนหอบหืดแยกตามที่อยู่ */

    public function actionReport1($datestart, $dateend, $details) {

        $report_name = "รายงานจำนวนครั้งในการใช้ยา TRCS";
        $sql = "SELECT
      op.icode,COUNT(distinct(op.vn)) AS total_usage,concat(dr.name,' ',dr.strength,' ',dr.units) AS drugname
FROM
    opitemrece op
    
LEFT OUTER JOIN drugitems dr ON dr.icode = op.icode
WHERE
     op.icode
     IN ('1540018','1540019','1540020','1540021','1540022','1540023','1540024','1540025','1540026','1540029')
AND
     op.vstdate BETWEEN $datestart and $dateend AND vn!=''
GROUP BY op.icode
ORDER BY total_usage DESC ";

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
                    'report_name' => $report_name,
                    'details' => $details,
        ]);
    }

    public function actionReport2($datestart, $dateend, $details) {

        $report_name = "รายงานจำนวนครั้งในการใช้ยา PCEC";
        $sql = "SELECT
      op.icode,COUNT(op.vn) AS total_usage,concat(dr.name,' ',dr.strength,' ',dr.units) AS drugname
FROM
    opitemrece op
LEFT OUTER JOIN drugitems dr ON dr.icode = op.icode
WHERE
     op.icode
     IN ( '1540061','1540062','1540058','1540059','1540060',
          '1540057','1540047','1540048','1540049','1540050',
          '1540054','1540055','1540056','1540061','1540062',
          '1540063','1540064','1540046','1540051','1540052',
          '1540053')
AND
     op.vstdate BETWEEN $datestart and $dateend AND vn!=''
GROUP BY op.icode
ORDER BY total_usage DESC ";

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
                    'report_name' => $report_name,
                    'details' => $details,
        ]);
    }

    public function actionReport3($datestart, $dateend, $details) {

        $report_name = "รายงานจำนวนครั้งในการใช้ยา TETANUS";
        $sql = "SELECT
      op.icode,COUNT(op.vn) AS total_usage,concat(dr.name,' ',dr.strength,' ',dr.units) AS drugname
FROM
    opitemrece op
LEFT OUTER JOIN drugitems dr ON dr.icode = op.icode
WHERE
     op.icode
     IN ('1000295', '1580009', '1580010', '1580011')
AND
     op.vstdate BETWEEN $datestart and $dateend AND vn!=''
GROUP BY op.icode
ORDER BY total_usage DESC ";

        try {
            $rawData = \yii::$app->db->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }

        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => FALSE,
        ]);

        return $this->render('report3', [
                    'dataProvider' => $dataProvider,
                    'report_name' => $report_name,
                    'details' => $details,
        ]);
    }

    public function actionReport4($datestart, $dateend, $details) {

        $report_name = "รายงานป้องกันและแก้ไขปัญหาอุบัติเหตุทางถนน";
        $sql = "select pt.hn,concat(pt.pname,pt.fname,'  ',pt.lname) as pt_name,er.vstdate

from er_regist er

left outer join vn_stat v on v.vn = er.vn
left outer join patient pt on pt.hn = v.hn

where er.vstdate between $datestart and $dateend    and

er.vn in

(
   select nu.vn from  er_nursing_detail nu where nu.vn = er.vn  and
   nu.er_accident_type_id=1
)";

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
                    'report_name' => $report_name,
                    'details' => $details,
        ]);
    }
    
     public function actionReport5($datestart, $dateend, $details) {

        $report_name = "รายงานผู้ป่วยที่รับบริการที่ห้องฉุกเฉิน";
        $sql = "select 

vs.vn as vn,vs.item_money as item_money,e.*,
ert.name as emergency_name,erc.name as dch_name,
o.icd10 as icd10,v.hn,v.vsttime,v.spclty as spclty,
concat(p.pname,p.fname,'  ',p.lname) as pt_name,
ep.name as period_name,d.name as doctor_name,
k.department as name_department 
								
from er_regist  e 											
    left outer join ovst v on v.vn=e.vn 
    left outer join er_emergency_type ert on ert.er_emergency_type=e.er_emergency_type 
    left outer join er_dch_type erc on erc.er_dch_type=e.er_dch_type 
    left outer join ovstdiag o on o.vn = e.vn and o.diagtype='1'
    left outer join kskdepartment k on k.depcode =v.main_dep 
    left outer join patient p on p.hn=v.hn 
    left outer join er_period ep on ep.er_period=e.er_period 
    left outer join  doctor d on d.code= e.er_doctor 
    left outer join  vn_stat vs on vs.vn= e.vn 
where e.vstdate between $datestart and $dateend order by e.vn ";
        
        
$sql2 = " select

ert.name as emergen_type, count(distinct(e.vn))   as count_vn
                
from er_regist  e                       
left outer join er_emergency_type ert on ert.er_emergency_type=e.er_emergency_type 
where e.vstdate between $datestart and $dateend 
 group by   e.er_emergency_type
 order by count(distinct(e.vn)) desc ";

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
                    'rawData2' => $rawData2,   
                    'report_name' => $report_name,
                    'details' => $details,
                    'datestart' => $datestart,
                    'dateend' => $dateend,
        ]);
    }
    
    
     public function actionReport6($datestart, $dateend, $details) {

        $report_name = "อันดับโรคที่พบบ่อยที่ห้องอุบัติเหตุฉุกเฉิน";
        $sql = " select o.icd10 as icd10,ic.name as icd_name,ic.tname,count(o.icd10) as count_all
from er_regist e
left outer join ovstdiag o on o.vn = e.vn and o.diagtype='1'
left outer join icd101 ic on ic.code = o.icd10

where e.vstdate between $datestart and $dateend 
group by o.icd10    order by count_all desc
limit 40 ";

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

        $report_name = "รายงานอันดับการสั่งใช้ยานอกเวลา(20.00น.-07.59น.)ที่ห้องอุบัติเหตุฉุกเฉิน (ไม่นับรวมวันหยุดราชการ)";
        $sql = "SELECT
                    o.icode,d.name as drug_name,sum(o.qty) as sum_qty
                FROM opitemrece    o
                    left outer join patient p on p.hn = o.hn
                    left outer join vn_stat v on v.vn = o.vn
                    left outer join opdscreen c on c.vn = o.vn
                    left outer join drugitems d on d.icode = o.icode
                    left outer join nondrugitems nd on nd.icode = o.icode
                WHERE o.vstdate between $datestart and $dateend
                    and (o.rxtime between '20:00:01' and '23:59:59' or o.rxtime between '00:00:01' and '07:59:59')
                    and o.vn is   not null
                    and o.vstdate not in
                        (
                            select holiday_date from holiday
                        )  
                    and o.icode like '1%'
                    and o.vn in
                        (
                            select vn from er_regist
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

        return $this->render('report7', [
                    'dataProvider' => $dataProvider,
                    'rawData' => $rawData,
                    'report_name' => $report_name,
                    'details' => $details,
        ]);
    }

    
    public function actionReport8($datestart, $dateend, $details) {

        $report_name = "รายงานอันดับการสั่งใช้ยานอกเวลา(16.00น.-07.59น.)ที่ห้องอุบัติเหตุฉุกเฉิน (เฉพาะวันหยุดราชการ)";
        $sql = "SELECT
                    o.icode,d.name as drug_name,sum(o.qty) as sum_qty
                FROM opitemrece    o
                    left outer join patient p on p.hn = o.hn
                    left outer join vn_stat v on v.vn = o.vn
                    left outer join opdscreen c on c.vn = o.vn
                    left outer join drugitems d on d.icode = o.icode
                    left outer join nondrugitems nd on nd.icode = o.icode
                WHERE o.vstdate between $datestart and $dateend
                    and (o.rxtime between '16:00:01' and '23:59:59' or o.rxtime between '00:00:01' and '07:59:59')
                    and o.vn is   not null
                    and o.vstdate  in
                        (
                            select holiday_date from holiday
                        )  
                    and o.icode like '1%'
                    and o.vn in
                        (
                            select vn from er_regist
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

        return $this->render('report8', [
                    'dataProvider' => $dataProvider,
                    'rawData' => $rawData,
                    'report_name' => $report_name,
                    'details' => $details,
        ]);
    }

    
    
    
    
}


















