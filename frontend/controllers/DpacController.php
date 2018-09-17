<?php

namespace frontend\controllers;

use Yii;
use frontend\components\CommonController;

class DpacController extends CommonController {
    public $dep_controller = 'dpac';

    public function actionReport1($details) {
        $this->SaveLog($this->dep_controller, 'report1', $this->getSession());

     $report_name = 'รายงานคนไข้ทะเบียนคลินิครักษ์สุขภาพ (DPAC)แยกตามสถานบริการ';   
             

            $sql = " SELECT
'1' as hosp_area,'รพ.สต.ตำบลทุ่งหลวง' as hosp_name , th.full_name as address,count(distinct(cm.hn)) as count_hn
FROM clinicmember  cm
LEFT OUTER JOIN clinic_member_status cs on cs.clinic_member_status_id=cm.clinic_member_status_id
LEFT OUTER JOIN provis_typedis pd on pd.code=cs.provis_typedis
LEFT OUTER JOIN patient pt ON pt.hn = cm.hn
LEFT OUTER JOIN thaiaddress th ON th.addressid = concat(pt.chwpart,pt.amppart,pt.tmbpart)
WHERE 
    cm.hn in(select hn from clinicmember where clinic='020')

AND pd.code in('3','03')
AND concat(pt.chwpart,pt.amppart,pt.tmbpart) =  '860502'   and pt.moopart in (1,2,3,4,5,6,7,8,9)

GROUP BY th.addressid 


union


SELECT
'2' as hosp_area,'รพ.สต.ตำบลสวนแตง' as hosp_name , th.full_name as address,count(distinct(cm.hn)) as count_hn
FROM clinicmember  cm
LEFT OUTER JOIN clinic_member_status cs on cs.clinic_member_status_id=cm.clinic_member_status_id
LEFT OUTER JOIN provis_typedis pd on pd.code=cs.provis_typedis
LEFT OUTER JOIN patient pt ON pt.hn = cm.hn
LEFT OUTER JOIN thaiaddress th ON th.addressid = concat(pt.chwpart,pt.amppart,pt.tmbpart)
WHERE 
      cm.hn in(select hn from clinicmember where clinic='020')

AND pd.code in('3','03')
AND concat(pt.chwpart,pt.amppart,pt.tmbpart) =  '860503'   and pt.moopart in (2,3,4,5,6,9)

GROUP BY th.addressid



union


SELECT
'3' as hosp_area,'รพ.สต.ตำบลทุ่งคาวัด' as hosp_name , th.full_name as address,count(distinct(cm.hn)) as count_hn
FROM clinicmember  cm
LEFT OUTER JOIN clinic_member_status cs on cs.clinic_member_status_id=cm.clinic_member_status_id
LEFT OUTER JOIN provis_typedis pd on pd.code=cs.provis_typedis
LEFT OUTER JOIN patient pt ON pt.hn = cm.hn
LEFT OUTER JOIN thaiaddress th ON th.addressid = concat(pt.chwpart,pt.amppart,pt.tmbpart)
WHERE 
     cm.hn in(select hn from clinicmember where clinic='020')

AND pd.code in('3','03')
AND concat(pt.chwpart,pt.amppart,pt.tmbpart) =  '860504'   and pt.moopart in (1,2,3,4,6)

GROUP BY th.addressid


union


SELECT
'4' as hosp_area,'รพ.สต.บ้านคลองสง' as hosp_name , th.full_name as address,count(distinct(cm.hn)) as count_hn
FROM clinicmember  cm
LEFT OUTER JOIN clinic_member_status cs on cs.clinic_member_status_id=cm.clinic_member_status_id
LEFT OUTER JOIN provis_typedis pd on pd.code=cs.provis_typedis
LEFT OUTER JOIN patient pt ON pt.hn = cm.hn
LEFT OUTER JOIN thaiaddress th ON th.addressid = concat(pt.chwpart,pt.amppart,pt.tmbpart)
WHERE 
      cm.hn in(select hn from clinicmember where clinic='020')

AND pd.code in('3','03')
AND concat(pt.chwpart,pt.amppart,pt.tmbpart) =  '860501'   and pt.moopart in (8,11,13,15,17,18,20)

GROUP BY th.addressid


union


SELECT
'5' as hosp_area,'รพ.สต.บ้านทับใหม่' as hosp_name , th.full_name as address,count(distinct(cm.hn)) as count_hn
FROM clinicmember  cm
LEFT OUTER JOIN clinic_member_status cs on cs.clinic_member_status_id=cm.clinic_member_status_id
LEFT OUTER JOIN provis_typedis pd on pd.code=cs.provis_typedis
LEFT OUTER JOIN patient pt ON pt.hn = cm.hn
LEFT OUTER JOIN thaiaddress th ON th.addressid = concat(pt.chwpart,pt.amppart,pt.tmbpart)
WHERE 
     cm.hn in(select hn from clinicmember where clinic='020')

AND pd.code in ('3','03')

AND (concat(pt.chwpart,pt.amppart,pt.tmbpart) =  '860501' and pt.moopart in (16,19)
OR concat(pt.chwpart,pt.amppart,pt.tmbpart) =  '860504' and pt.moopart in (5,7,8))
GROUP BY  hosp_area



union


SELECT
'6' as hosp_area,'รพ.สต.บ้านควรผาสุก' as hosp_name , th.full_name as address,count(distinct(cm.hn)) as count_hn
FROM clinicmember  cm
LEFT OUTER JOIN clinic_member_status cs on cs.clinic_member_status_id=cm.clinic_member_status_id
LEFT OUTER JOIN provis_typedis pd on pd.code=cs.provis_typedis
LEFT OUTER JOIN patient pt ON pt.hn = cm.hn
LEFT OUTER JOIN thaiaddress th ON th.addressid = concat(pt.chwpart,pt.amppart,pt.tmbpart)
WHERE 
      cm.hn in(select hn from clinicmember where clinic='020')

AND pd.code in('3','03')
AND concat(pt.chwpart,pt.amppart,pt.tmbpart) =  '860503'   and pt.moopart in (1,7,8,10)

GROUP BY th.addressid



union


SELECT
'7' as hosp_area,'รพ.ละแม' as hosp_name , th.full_name as address,count(distinct(cm.hn)) as count_hn
FROM clinicmember  cm
LEFT OUTER JOIN clinic_member_status cs on cs.clinic_member_status_id=cm.clinic_member_status_id
LEFT OUTER JOIN provis_typedis pd on pd.code=cs.provis_typedis
LEFT OUTER JOIN patient pt ON pt.hn = cm.hn
LEFT OUTER JOIN thaiaddress th ON th.addressid = concat(pt.chwpart,pt.amppart,pt.tmbpart)
WHERE 
      cm.hn in(select hn from clinicmember where clinic='020')

AND pd.code in('3','03')
AND concat(pt.chwpart,pt.amppart,pt.tmbpart) =  '860501'   and pt.moopart in (1,2,3,4,5,6,7,9,10,12,14)

GROUP BY th.addressid

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

            return $this->render('report1', [
                        'dataProvider' => $dataProvider,
                        'rawData' => $rawData,
                        'report_name' => $report_name,
            ]);
      
            
    }

    /* รายงานสรุปทะเบียนเบาหวานแบบ(แสดงรายชื่อคนไข้) */

    public function actionReport2($hosp_area) {
        $this->SaveLog($this->dep_controller, 'report2', $this->getSession());

        $get_type = "";
        $report_name = "";
        $hosp_area_condition = "";
        
        if ($hosp_area != "") {
          
            
            if($hosp_area == 1) {
                $hosp_area_condition = " AND concat(pt.chwpart,pt.amppart,pt.tmbpart) =  '860502'   and pt.moopart in (1,2,3,4,5,6,7,8,9) ";
            } else if ($hosp_area == 2) {
                $hosp_area_condition = " AND concat(pt.chwpart,pt.amppart,pt.tmbpart) =  '860503'   and pt.moopart in (2,3,4,5,6,9)";
            } else if ($hosp_area == 3) {
                $hosp_area_condition = " AND concat(pt.chwpart,pt.amppart,pt.tmbpart) =  '860504'   and pt.moopart in (1,2,3,4,6) "; 
            } else if ($hosp_area == 4) {
                $hosp_area_condition = " AND concat(pt.chwpart,pt.amppart,pt.tmbpart) =  '860501'   and pt.moopart in (8,11,13,15,17,18,20) ";
            } else if ($hosp_area == 5) {
               $hosp_area_condition = " AND (concat(pt.chwpart,pt.amppart,pt.tmbpart) =  '860501'    and pt.moopart in (16,19)
                                        OR concat(pt.chwpart,pt.amppart,pt.tmbpart) =  '860504'     and pt.moopart in (5,7,8)) ";
            } else if ($hosp_area == 6) {
                $hosp_area_condition = " AND concat(pt.chwpart,pt.amppart,pt.tmbpart) =  '860503'   and pt.moopart in (1,7,8,10) ";
            } else if ($hosp_area == 7) {
                $hosp_area_condition = " AND concat(pt.chwpart,pt.amppart,pt.tmbpart) =  '860501'   and pt.moopart in (1,2,3,4,5,6,7,9,10,12,14) ";
            }
                                 
            $sql = "         
                SELECT
                pt.hn as hn,concat(pt.pname,pt.fname,'  ',pt.lname) as pt_name,
                concat( timestampdiff(year,pt.birthday,now()), ' ปี') as age_y,
                pt.cid,
                concat(DAY(cm.regdate),'/',MONTH(cm.regdate),'/',(YEAR(cm.regdate)+543)) as regdate,
                cm.begin_year,
                concat(pt.addrpart,' ม.',pt.moopart,' ',th.full_name) address,
                pt.moopart
                FROM clinicmember  cm
                LEFT OUTER JOIN clinic_member_status cs on cs.clinic_member_status_id=cm.clinic_member_status_id
                LEFT OUTER JOIN provis_typedis pd on pd.code=cs.provis_typedis
                LEFT OUTER JOIN patient pt ON pt.hn = cm.hn
                LEFT OUTER JOIN thaiaddress th ON th.addressid = concat(pt.chwpart,pt.amppart,pt.tmbpart)
                WHERE 
                      cm.hn in(select hn from clinicmember where clinic='020')

                AND pd.code in('3','03')

                $hosp_area_condition

                GROUP BY pt.hn     
                ORDER BY pt.moopart,age_y

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

            return $this->render('report2', [
                        'dataProvider' => $dataProvider,
                        'report_name' => $report_name,
            ]);
        }// จบตรวจสอบการเลือกประเภทคนไข้ในคลินิก
    }

    
    
    
    public function actionReport3($details) {
        $this->SaveLog($this->dep_controller, 'report3', $this->getSession());

        $get_type = "";
        $report_name = "";
        $hosp_area_condition = "";
                   
           
            $sql = "         
                    SELECT
                    pt.hn as hn,concat(pt.pname,pt.fname,'  ',pt.lname) as pt_name,
                    concat( timestampdiff(year,pt.birthday,now()), ' ปี') as age_y,
                    pt.cid,cm.begin_year,
                    concat(DAY(cm.regdate),'/',MONTH(cm.regdate),'/',(YEAR(cm.regdate)+543)) as regdate,
                    concat(pt.addrpart,' ม.',pt.moopart,' ',th.full_name) address,
                    pt.moopart
                    FROM clinicmember  cm
                    LEFT OUTER JOIN clinic_member_status cs on cs.clinic_member_status_id=cm.clinic_member_status_id
                    LEFT OUTER JOIN provis_typedis pd on pd.code=cs.provis_typedis
                    LEFT OUTER JOIN patient pt ON pt.hn = cm.hn
                    LEFT OUTER JOIN thaiaddress th ON th.addressid = concat(pt.chwpart,pt.amppart,pt.tmbpart)
                    WHERE 
                          cm.hn in(select hn from clinicmember where clinic='020')

                    AND pd.code in('3','03')


                    GROUP BY pt.hn     
                    ORDER BY th.addressid,pt.moopart

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

            return $this->render('report3', [
                        'dataProvider' => $dataProvider,
                        'report_name' => $report_name,
            ]);
      
    }

    
    
     public function actionReport4($datestart, $dateend, $details) {
        $this->SaveLog($this->dep_controller, 'report4', $this->getSession());

        $report_name = "รายงานคนไข้ทะเบียนคลินิครักษ์สุขภาพ (และมีชื่อในคลินิก DM) ที่มีผลระดับน้ำตาลในเลือด >= 180 และหรือ hba1c  >= 8";

            $sql = "         
                   SELECT
                            v.vn,v.hn,CONCAT(pt.pname,pt.fname,'  ', pt.lname) as pt_name,
                            concat(pt.addrpart,' ม.',pt.moopart,' ',th.full_name) address,
                            concat(DAY(v.vstdate),'/',MONTH(v.vstdate),'/',(YEAR(v.vstdate)+543)) as vstdate,
                            v.pdx,
                            concat(
                                if(v.dx0 is not null,concat(v.dx0,'   '),' '),
                                if(v.dx1 is not null,concat(v.dx1,'   '),' '),
                                if(v.dx2 is not null,concat(v.dx2,'   '),' '),
                                if(v.dx3 is not null,concat(v.dx3,'   '),' '),
                                if(v.dx4 is not null,concat(v.dx4,'   '),' '),
                                if(v.dx5 is not null,concat(v.dx5,'   '),' ')
                            )  as second_diag,
                            
                            v.age_y,
                            lo.lab_items_code,li.lab_items_name,lo.lab_order_result ,lo.confirm

                      FROM vn_stat v
                      LEFT OUTER JOIN lab_head lh ON lh.vn = v.vn
                      LEFT OUTER JOIN lab_order lo ON lo.lab_order_number = lh.lab_order_number
                      LEFT OUTER JOIN lab_items li ON li.lab_items_code = lo.lab_items_code
                      LEFT OUTER JOIN patient pt ON pt.hn = v.hn
                      LEFT OUTER JOIN thaiaddress th ON th.addressid = concat(pt.chwpart,pt.amppart,pt.tmbpart)
                      WHERE
                           v.vstdate BETWEEN $datestart and $dateend
                                
                      AND  v.hn in (select hn from clinicmember where clinic='020')
                      AND  v.hn in (select hn from clinicmember where clinic='001')

                      AND lo.confirm = 'Y'

                      AND

                      (
                          (lo.lab_items_code = '3001' and lo.lab_order_result >= 180)  OR
                          (lo.lab_items_code = '48' and lo.lab_order_result  >= 8)
                      )


                      ORDER BY v.aid,v.hn,v.vstdate,lo.lab_items_code ";
                        
            

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
            ]);
    
        
    }
    
    
    public function actionReport5($datestart, $dateend, $details) {
        $this->SaveLog($this->dep_controller, 'report4', $this->getSession());

        $report_name = "รายงานคนไข้ทะเบียนคลินิครักษ์สุขภาพ (ไม่มีชื่อในคลินิก DM แต่มี Diag DM) ที่มีผลระดับน้ำตาลในเลือด >= 180 และหรือ hba1c  >= 8";

            $sql = "SELECT
                        v.vn,v.hn,CONCAT(pt.pname,pt.fname,'  ', pt.lname) as pt_name,
                        concat(pt.addrpart,' ม.',pt.moopart,' ',th.full_name) address,
                        concat(DAY(v.vstdate),'/',MONTH(v.vstdate),'/',(YEAR(v.vstdate)+543)) as vstdate,
                        v.pdx,
                        concat(
                                if(v.dx0 is not null,concat(v.dx0,'   '),' '),
                                if(v.dx1 is not null,concat(v.dx1,'   '),' '),
                                if(v.dx2 is not null,concat(v.dx2,'   '),' '),
                                if(v.dx3 is not null,concat(v.dx3,'   '),' '),
                                if(v.dx4 is not null,concat(v.dx4,'   '),' '),
                                if(v.dx5 is not null,concat(v.dx5,'   '),' ')
                            )  as second_diag,                          
                        v.age_y,
                        lo.lab_items_code,li.lab_items_name,lo.lab_order_result ,lo.confirm

                  FROM vn_stat v
                  LEFT OUTER JOIN lab_head lh ON lh.vn = v.vn
                  LEFT OUTER JOIN lab_order lo ON lo.lab_order_number = lh.lab_order_number
                  LEFT OUTER JOIN lab_items li ON li.lab_items_code = lo.lab_items_code
                  LEFT OUTER JOIN patient pt ON pt.hn = v.hn
                  LEFT OUTER JOIN thaiaddress th ON th.addressid = concat(pt.chwpart,pt.amppart,pt.tmbpart)
                  WHERE
                       v.vstdate BETWEEN $datestart and $dateend

                  AND  v.hn in (select hn from clinicmember where clinic='020')
                  AND  v.hn not in (select hn from clinicmember where clinic='001')

                  AND (
                            (v.pdx  BETWEEN 'e100' AND 'e109' OR v.pdx BETWEEN 'e110' AND 'e119')   OR
                            (v.dx0  BETWEEN 'e100' AND 'e109' OR v.pdx BETWEEN 'e110' AND 'e119')   OR
                            (v.dx1  BETWEEN 'e100' AND 'e109' OR v.pdx BETWEEN 'e110' AND 'e119')   OR
                            (v.dx2  BETWEEN 'e100' AND 'e109' OR v.pdx BETWEEN 'e110' AND 'e119')   OR
                            (v.dx3  BETWEEN 'e100' AND 'e109' OR v.pdx BETWEEN 'e110' AND 'e119')   OR
                            (v.dx4  BETWEEN 'e100' AND 'e109' OR v.pdx BETWEEN 'e110' AND 'e119')   OR
                            (v.dx5  BETWEEN 'e100' AND 'e109' OR v.pdx BETWEEN 'e110' AND 'e119')


                       )

                  AND lo.confirm = 'Y'
                  AND
                        (
                            (lo.lab_items_code = '3001' and lo.lab_order_result >= 180)  OR
                            (lo.lab_items_code = '48' and lo.lab_order_result  >= 8)
                        )
                  ORDER BY 
                            v.aid,v.hn,v.vstdate,lo.lab_items_code
         
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

            return $this->render('report5', [
                        'dataProvider' => $dataProvider,
                        'report_name' => $report_name,
            ]);
        
    }
    
    
    
    
    public function actionReport6($datestart, $dateend, $details) {
        $this->SaveLog($this->dep_controller, 'report4', $this->getSession());

        $report_name = "รายงานคนไข้ทะเบียนคลินิครักษ์สุขภาพ (และมีชื่อในคลินิก DM) ที่มีผลระดับน้ำตาลในเลือด ระหว่าง  125 ถึง 179 และหรือ hba1c  ระหว่าง 7 - 7.9 ";

            $sql = "         
                   SELECT
                            v.vn,v.hn,CONCAT(pt.pname,pt.fname,'  ', pt.lname) as pt_name,
                            concat(pt.addrpart,' ม.',pt.moopart,' ',th.full_name) address,
                            concat(DAY(v.vstdate),'/',MONTH(v.vstdate),'/',(YEAR(v.vstdate)+543)) as vstdate,
                            v.pdx,
                            concat(
                                if(v.dx0 is not null,concat(v.dx0,'   '),' '),
                                if(v.dx1 is not null,concat(v.dx1,'   '),' '),
                                if(v.dx2 is not null,concat(v.dx2,'   '),' '),
                                if(v.dx3 is not null,concat(v.dx3,'   '),' '),
                                if(v.dx4 is not null,concat(v.dx4,'   '),' '),
                                if(v.dx5 is not null,concat(v.dx5,'   '),' ')
                            )  as second_diag,
                            
                            v.age_y,
                            lo.lab_items_code,li.lab_items_name,lo.lab_order_result ,lo.confirm

                      FROM vn_stat v
                      LEFT OUTER JOIN lab_head lh ON lh.vn = v.vn
                      LEFT OUTER JOIN lab_order lo ON lo.lab_order_number = lh.lab_order_number
                      LEFT OUTER JOIN lab_items li ON li.lab_items_code = lo.lab_items_code
                      LEFT OUTER JOIN patient pt ON pt.hn = v.hn
                      LEFT OUTER JOIN thaiaddress th ON th.addressid = concat(pt.chwpart,pt.amppart,pt.tmbpart)
                      WHERE
                           v.vstdate BETWEEN $datestart and $dateend
                                
                      AND  v.hn in (select hn from clinicmember where clinic='020')
                      AND  v.hn in (select hn from clinicmember where clinic='001')

                      AND lo.confirm = 'Y'

                      AND

                      (
                          (lo.lab_items_code = '3001' and lo.lab_order_result between '125' and '179')  OR
                          (lo.lab_items_code = '48' and lo.lab_order_result  between '7' and '7.9')
                      )

                      ORDER BY v.aid,v.hn,v.vstdate,lo.lab_items_code ";
                        
            

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
            ]);
    
        
    }
    
    
    
    public function actionReport7($datestart, $dateend, $details) {
        $this->SaveLog($this->dep_controller, 'report4', $this->getSession());

        $report_name = "รายงานคนไข้ทะเบียนคลินิครักษ์สุขภาพ (ไม่มีชื่อในคลินิก DM แต่มี Diag DM) ที่มีผลระดับน้ำตาลในเลือด ระหว่าง 125 ถึง 179 และหรือ hba1c ระหว่าง 7 - 7.9 ";

            $sql = "SELECT
                        v.vn,v.hn,CONCAT(pt.pname,pt.fname,'  ', pt.lname) as pt_name,
                        concat(pt.addrpart,' ม.',pt.moopart,' ',th.full_name) address,
                        concat(DAY(v.vstdate),'/',MONTH(v.vstdate),'/',(YEAR(v.vstdate)+543)) as vstdate,
                        v.pdx,
                        concat(
                                if(v.dx0 is not null,concat(v.dx0,'   '),' '),
                                if(v.dx1 is not null,concat(v.dx1,'   '),' '),
                                if(v.dx2 is not null,concat(v.dx2,'   '),' '),
                                if(v.dx3 is not null,concat(v.dx3,'   '),' '),
                                if(v.dx4 is not null,concat(v.dx4,'   '),' '),
                                if(v.dx5 is not null,concat(v.dx5,'   '),' ')
                            )  as second_diag,                          
                        v.age_y,
                        lo.lab_items_code,li.lab_items_name,lo.lab_order_result ,lo.confirm

                  FROM vn_stat v
                  LEFT OUTER JOIN lab_head lh ON lh.vn = v.vn
                  LEFT OUTER JOIN lab_order lo ON lo.lab_order_number = lh.lab_order_number
                  LEFT OUTER JOIN lab_items li ON li.lab_items_code = lo.lab_items_code
                  LEFT OUTER JOIN patient pt ON pt.hn = v.hn
                  LEFT OUTER JOIN thaiaddress th ON th.addressid = concat(pt.chwpart,pt.amppart,pt.tmbpart)
                  WHERE
                       v.vstdate BETWEEN $datestart and $dateend

                  AND  v.hn in (select hn from clinicmember where clinic='020')
                  AND  v.hn not in (select hn from clinicmember where clinic='001')

                  AND (
                            (v.pdx  BETWEEN 'e100' AND 'e109' OR v.pdx BETWEEN 'e110' AND 'e119')   OR
                            (v.dx0  BETWEEN 'e100' AND 'e109' OR v.pdx BETWEEN 'e110' AND 'e119')   OR
                            (v.dx1  BETWEEN 'e100' AND 'e109' OR v.pdx BETWEEN 'e110' AND 'e119')   OR
                            (v.dx2  BETWEEN 'e100' AND 'e109' OR v.pdx BETWEEN 'e110' AND 'e119')   OR
                            (v.dx3  BETWEEN 'e100' AND 'e109' OR v.pdx BETWEEN 'e110' AND 'e119')   OR
                            (v.dx4  BETWEEN 'e100' AND 'e109' OR v.pdx BETWEEN 'e110' AND 'e119')   OR
                            (v.dx5  BETWEEN 'e100' AND 'e109' OR v.pdx BETWEEN 'e110' AND 'e119')


                       )

                  AND lo.confirm = 'Y'
                  AND
                        (
                            (lo.lab_items_code = '3001' and lo.lab_order_result between  '125' and '179')  OR
                            (lo.lab_items_code = '48' and lo.lab_order_result between '7' and '7.9')
                        )
                  ORDER BY 
                            v.aid,v.hn,v.vstdate,lo.lab_items_code
         
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

            return $this->render('report5', [
                        'dataProvider' => $dataProvider,
                        'report_name' => $report_name,
            ]);
        
    }
    

} // end class