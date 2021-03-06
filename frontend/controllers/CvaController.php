<?php

namespace frontend\controllers;

use Yii;
use frontend\components\CommonController;

class CvaController extends CommonController {
    public $dep_controller = 'cva';

    public function actionReport1() {
        $this->SaveLog($this->dep_controller, 'report1', $this->getSession());    
            // ตัวแปร $report_name เอาไว้ไปแสดงชื่อรายงานในหน้า view
                $report_name = 'รายงานสรุปคนไข้ทะเบียนคลินิกโรคหลอดเลือดสมอง(CVA) แยกตามที่อยู่ในแต่ละสถานบริการ(คน)';
                $uclinic = '014';

            $sql = " SELECT
                        '1' as hosp_area,'รพ.สต.ตำบลทุ่งหลวง' as hosp_name , th.full_name as address,count(distinct(cm.hn)) as count_hn
                        FROM clinicmember  cm
                        LEFT OUTER JOIN clinic_member_status cs on cs.clinic_member_status_id=cm.clinic_member_status_id
                        LEFT OUTER JOIN provis_typedis pd on pd.code=cs.provis_typedis
                        LEFT OUTER JOIN patient pt ON pt.hn = cm.hn
                        LEFT OUTER JOIN thaiaddress th ON th.addressid = concat(pt.chwpart,pt.amppart,pt.tmbpart)
                        WHERE 
                            cm.hn in(select hn from clinicmember where clinic=$uclinic)
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
                            cm.hn in(select hn from clinicmember where clinic=$uclinic)   
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
                            cm.hn in(select hn from clinicmember where clinic=$uclinic)
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
                            cm.hn in(select hn from clinicmember where clinic=$uclinic)
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
                            cm.hn in(select hn from clinicmember where clinic=$uclinic)

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
                            cm.hn in(select hn from clinicmember where clinic=$uclinic)
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
                            cm.hn in(select hn from clinicmember where clinic=$uclinic)

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
                        'uclinic' => $uclinic,
                        'report_name' => $report_name,
            ]);
       
    }

    
    /* รายงานสรุปทะเบียนเบาหวานแบบ(แสดงรายชื่อคนไข้) */

    public function actionReport2($hosp_area, $uclinic) {
        $this->SaveLog($this->dep_controller, 'report2', $this->getSession());
        // ตัวแปร $get_type เอาไว้ตรวจสอบว่าเป็นคนไข้ dm หรือ dm with ht
        // ตัวแปร $report_name เอาไว้ไปแสดงชื่อรายงานในหน้า view
        $report_name = "";
        $hosp_area_condition = "";
        
        if ($uclinic != "" && $hosp_area != "") {
       
                $report_name = 'รายงานรายชื่อคนไข้ทะเบียนคลินิกโรคหลอดเลือดสมอง(CVA) แยกตามที่อยู่ในแต่ละสถานบริการ(คน)';
                     
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
        pt.cid,cm.regdate,cm.begin_year,
        concat(pt.addrpart,' ม.',pt.moopart,' ',th.full_name) address,
        pt.moopart
        FROM clinicmember  cm
        LEFT OUTER JOIN clinic_member_status cs on cs.clinic_member_status_id=cm.clinic_member_status_id
        LEFT OUTER JOIN provis_typedis pd on pd.code=cs.provis_typedis
        LEFT OUTER JOIN patient pt ON pt.hn = cm.hn
        LEFT OUTER JOIN thaiaddress th ON th.addressid = concat(pt.chwpart,pt.amppart,pt.tmbpart)
        WHERE 
             cm.hn in(select hn from clinicmember where clinic=$uclinic)

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

   
    
    
    
    
    
    }
    
    
    
    
    
    
