<?php

namespace frontend\controllers;

use Yii;
use frontend\components\CommonController;

class NephController extends CommonController {

    public $dep_controller = 'neph';

    public function actionReport1($uclinic) {
        // save log
        $this->SaveLog($this->dep_controller, 'report1', $this->getSession());

        $report_name = "รายงานคนไข้ Diag N183-N185 แยกตามสถานบริการ";
        
         if ($uclinic != "") { // เริ่มต้นตรวจสอบประเภทคนไข้ในคลินิก
            // ตัวแปร $get_type เอาไว้ตรวจสอบว่าเป็นคนไข้ dm หรือ dm with ht
            // ตัวแปร $report_name เอาไว้ไปแสดงชื่อรายงานในหน้า view
            if ($uclinic == 1) {
                $report_name = 'รายงานคนไข้ Diag N183-N185 และไม่มีชื่ออยู่ในทะเบียน DM และ HT แยกตามสถานบริการ(คน)';
                
                $get_type = 'p.hn not IN ( SELECT hn FROM clinicmember WHERE clinic = "001" AND clinic_member_status_id != 3)
                  AND
                     p.hn not IN ( SELECT hn FROM clinicmember WHERE clinic = "002" AND clinic_member_status_id != 3) ';

            } else if ($uclinic == 2) {
                $report_name = 'รายงานคนไข้ Diag N183-N185 และมีชื่ออยู่ในทะเบียน DM และ HT แยกตามสถานบริการ(คน)';
                
                $get_type = 'p.hn  IN ( SELECT hn FROM clinicmember WHERE clinic = "001" AND clinic_member_status_id != 3)
                  AND
                     p.hn  IN ( SELECT hn FROM clinicmember WHERE clinic = "002" AND clinic_member_status_id != 3) ';

            } else if ($uclinic == 3) {
                 $report_name = 'รายงานคนไข้ Diag N183-N185 มีชื่อในทะเบียน DM แต่ไม่มีชื่อในทะเบียน HT แยกตามสถานบริการ(คน)';
                
                 $get_type = 'p.hn  IN ( SELECT hn FROM clinicmember WHERE clinic = "001" AND clinic_member_status_id != 3)
                  AND
                     p.hn  NOT IN ( SELECT hn FROM clinicmember WHERE clinic = "002" AND clinic_member_status_id != 3) ';

            } else if ($uclinic == 4) {
                 $report_name = 'รายงานคนไข้ Diag N183-N185 มีชื่อในทะเบียน HT แต่ไม่มีชื่อในทะเบียน DM แยกตามสถานบริการ(คน)';
                
                 $get_type = 'p.hn  NOT IN ( SELECT hn FROM clinicmember WHERE clinic = "001" AND clinic_member_status_id != 3)
                  AND
                     p.hn  IN ( SELECT hn FROM clinicmember WHERE clinic = "002" AND clinic_member_status_id != 3) ';

            }

            
        $sql = "SELECT
                        '1' as hosp_area,'รพสต.ทุ่งหลวง' as hosp_name , 
                        th.full_name as address,count(distinct(v.hn)) as count_hn

                  FROM vn_stat v

                  LEFT OUTER JOIN patient p ON p.hn = v.hn
                  LEFT OUTER JOIN sex s ON s.code = v.sex
                  LEFT OUTER JOIN thaiaddress th ON th.addressid = concat(p.chwpart,p.amppart,p.tmbpart)

                  WHERE
                        v.vstdate BETWEEN '2012-10-01' and now()

                  AND

                     (
                        v.pdx BETWEEN 'N183' AND 'N185' OR
                        v.dx0 BETWEEN 'N183' AND 'N185' OR
                        v.dx1 BETWEEN 'N183' AND 'N185' OR
                        v.dx2 BETWEEN 'N183' AND 'N185' OR
                        v.dx3 BETWEEN 'N183' AND 'N185' OR
                        v.dx4 BETWEEN 'N183' AND 'N185' OR
                        v.dx5 BETWEEN 'N183' AND 'N185'

                     )


                  AND
                  
                    $get_type

                  AND concat(p.chwpart,p.amppart,p.tmbpart) =  '860502'   and p.moopart in (1,2,3,4,5,6,7,8,9)

                  GROUP BY th.addressid



            UNION


                  SELECT

                        '2' as hosp_area,'รพสต.สวนแตง' as hosp_name , 
                        th.full_name as address,count(distinct(v.hn)) as count_hn

                  FROM vn_stat v

                  LEFT OUTER JOIN patient p ON p.hn = v.hn
                  LEFT OUTER JOIN sex s ON s.code = v.sex
                  LEFT OUTER JOIN thaiaddress th ON th.addressid = concat(p.chwpart,p.amppart,p.tmbpart)

                  WHERE
                        v.vstdate BETWEEN '2012-10-01' and now()

                  AND

                     (
                        v.pdx BETWEEN 'N183' AND 'N185' OR
                        v.dx0 BETWEEN 'N183' AND 'N185' OR
                        v.dx1 BETWEEN 'N183' AND 'N185' OR
                        v.dx2 BETWEEN 'N183' AND 'N185' OR
                        v.dx3 BETWEEN 'N183' AND 'N185' OR
                        v.dx4 BETWEEN 'N183' AND 'N185' OR
                        v.dx5 BETWEEN 'N183' AND 'N185'

                     )


                  AND
                  
                    $get_type
                        
                  AND concat(p.chwpart,p.amppart,p.tmbpart) =  '860503'   and p.moopart in (2,3,4,5,6,9)

                  GROUP BY th.addressid



            UNION



                  SELECT

                        '3' as hosp_area,'รพสต.ทุ่งคาวัด' as hosp_name ,
                        th.full_name as address,count(distinct(v.hn)) as count_hn

                  FROM vn_stat v

                  LEFT OUTER JOIN patient p ON p.hn = v.hn
                  LEFT OUTER JOIN sex s ON s.code = v.sex
                  LEFT OUTER JOIN thaiaddress th ON th.addressid = concat(p.chwpart,p.amppart,p.tmbpart)

                  WHERE
                        v.vstdate BETWEEN '2012-10-01' and now()

                  AND

                     (
                        v.pdx BETWEEN 'N183' AND 'N185' OR
                        v.dx0 BETWEEN 'N183' AND 'N185' OR
                        v.dx1 BETWEEN 'N183' AND 'N185' OR
                        v.dx2 BETWEEN 'N183' AND 'N185' OR
                        v.dx3 BETWEEN 'N183' AND 'N185' OR
                        v.dx4 BETWEEN 'N183' AND 'N185' OR
                        v.dx5 BETWEEN 'N183' AND 'N185'

                     )


                  AND
                  
                    $get_type
                        
                  AND concat(p.chwpart,p.amppart,p.tmbpart) =  '860504'   and p.moopart in (1,2,3,4,6)

                  GROUP BY th.addressid





            UNION



                  SELECT

                        '4' as hosp_area,'รพสต.คลองสง' as hosp_name , 
                        th.full_name as address,count(distinct(v.hn)) as count_hn

                  FROM vn_stat v

                  LEFT OUTER JOIN patient p ON p.hn = v.hn
                  LEFT OUTER JOIN sex s ON s.code = v.sex
                  LEFT OUTER JOIN thaiaddress th ON th.addressid = concat(p.chwpart,p.amppart,p.tmbpart)

                  WHERE
                        v.vstdate BETWEEN '2012-10-01' and now()

                  AND

                     (
                        v.pdx BETWEEN 'N183' AND 'N185' OR
                        v.dx0 BETWEEN 'N183' AND 'N185' OR
                        v.dx1 BETWEEN 'N183' AND 'N185' OR
                        v.dx2 BETWEEN 'N183' AND 'N185' OR
                        v.dx3 BETWEEN 'N183' AND 'N185' OR
                        v.dx4 BETWEEN 'N183' AND 'N185' OR
                        v.dx5 BETWEEN 'N183' AND 'N185'

                     )


                  AND
                  
                   $get_type
                       
                  AND concat(p.chwpart,p.amppart,p.tmbpart) =  '860501'   and p.moopart in (8,11,13,15,17,18,20)

                  GROUP BY th.addressid




            UNION



                  SELECT

                        '5' as hosp_area,'รพสต.ทับใหม่' as hosp_name , 
                        th.full_name as address,count(distinct(v.hn)) as count_hn

                  FROM vn_stat v

                  LEFT OUTER JOIN patient p ON p.hn = v.hn
                  LEFT OUTER JOIN sex s ON s.code = v.sex
                  LEFT OUTER JOIN thaiaddress th ON th.addressid = concat(p.chwpart,p.amppart,p.tmbpart)

                  WHERE
                        v.vstdate BETWEEN '2012-10-01' and now()

                  AND

                     (
                        v.pdx BETWEEN 'N183' AND 'N185' OR
                        v.dx0 BETWEEN 'N183' AND 'N185' OR
                        v.dx1 BETWEEN 'N183' AND 'N185' OR
                        v.dx2 BETWEEN 'N183' AND 'N185' OR
                        v.dx3 BETWEEN 'N183' AND 'N185' OR
                        v.dx4 BETWEEN 'N183' AND 'N185' OR
                        v.dx5 BETWEEN 'N183' AND 'N185'

                     )


                  AND
                  
                     $get_type
                         
                  AND (concat(p.chwpart,p.amppart,p.tmbpart) =  '860501' and p.moopart in (16,19)
                  OR concat(p.chwpart,p.amppart,p.tmbpart) =  '860504' and p.moopart in (5,7,8))

                  GROUP BY  hosp_area





            UNION



                  SELECT

                        '6' as hosp_area,'รพสต.ควนผาสุก' as hosp_name , 
                        th.full_name as address,count(distinct(v.hn)) as count_hn

                  FROM vn_stat v

                  LEFT OUTER JOIN patient p ON p.hn = v.hn
                  LEFT OUTER JOIN sex s ON s.code = v.sex
                  LEFT OUTER JOIN thaiaddress th ON th.addressid = concat(p.chwpart,p.amppart,p.tmbpart)

                  WHERE
                        v.vstdate BETWEEN '2012-10-01' and now()

                  AND

                     (
                        v.pdx BETWEEN 'N183' AND 'N185' OR
                        v.dx0 BETWEEN 'N183' AND 'N185' OR
                        v.dx1 BETWEEN 'N183' AND 'N185' OR
                        v.dx2 BETWEEN 'N183' AND 'N185' OR
                        v.dx3 BETWEEN 'N183' AND 'N185' OR
                        v.dx4 BETWEEN 'N183' AND 'N185' OR
                        v.dx5 BETWEEN 'N183' AND 'N185'

                     )


                  AND
                  
                     $get_type
                         
                  AND concat(p.chwpart,p.amppart,p.tmbpart) =  '860503'   and p.moopart in (1,7,8,10)

                  GROUP BY th.addressid



            UNION


                  SELECT

                        '7' as hosp_area,'รพ.ละแม' as hosp_name , 
                        th.full_name as address,count(distinct(v.hn)) as count_hn

                  FROM vn_stat v

                  LEFT OUTER JOIN patient p ON p.hn = v.hn
                  LEFT OUTER JOIN sex s ON s.code = v.sex
                  LEFT OUTER JOIN thaiaddress th ON th.addressid = concat(p.chwpart,p.amppart,p.tmbpart)

                  WHERE
                        v.vstdate BETWEEN '2012-10-01' and now()

                  AND

                     (
                        v.pdx BETWEEN 'N183' AND 'N185' OR
                        v.dx0 BETWEEN 'N183' AND 'N185' OR
                        v.dx1 BETWEEN 'N183' AND 'N185' OR
                        v.dx2 BETWEEN 'N183' AND 'N185' OR
                        v.dx3 BETWEEN 'N183' AND 'N185' OR
                        v.dx4 BETWEEN 'N183' AND 'N185' OR
                        v.dx5 BETWEEN 'N183' AND 'N185'

                     )


                  AND
                  
                    $get_type
                        
                  AND concat(p.chwpart,p.amppart,p.tmbpart) =  '860501'   and p.moopart in (1,2,3,4,5,6,7,9,10,12,14)

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
    } else {  // จบตรวจสอบการเลือกประเภทคนไข้ในคลินิก
            echo "กรุณาเลือกประเภทคนไข้ด้วยครับ";
        }
               
    } // end function 
    
    
    
     public function actionReport2($hosp_area, $uclinic) {
        $this->SaveLog($this->dep_controller, 'report2', $this->getSession());
        // ตัวแปร $get_type เอาไว้ตรวจสอบว่าเป็นคนไข้ dm หรือ dm with ht
        // ตัวแปร $report_name เอาไว้ไปแสดงชื่อรายงานในหน้า view
        $get_type = "";
        $report_name = "";
        $hosp_area_condition = "";
        
     
        if ($uclinic != "" && $hosp_area != "") {
                    
             if ($uclinic == 1) {
                $report_name = 'รายงานคนไข้ Diag N183-N185 และไม่มีชื่ออยู่ในทะเบียน DM และ HT แยกตามสถานบริการ(คน)';
                
                $get_type = 'p.hn not IN ( SELECT hn FROM clinicmember WHERE clinic = "001" AND clinic_member_status_id != 3)
                  AND
                     p.hn not IN ( SELECT hn FROM clinicmember WHERE clinic = "002" AND clinic_member_status_id != 3) ';

            } else if ($uclinic == 2) {
                $report_name = 'รายงานคนไข้ Diag N183-N185 และมีชื่ออยู่ในทะเบียน DM และ HT แยกตามสถานบริการ(คน)';
                
                $get_type = 'p.hn  IN ( SELECT hn FROM clinicmember WHERE clinic = "001" AND clinic_member_status_id != 3)
                  AND
                     p.hn  IN ( SELECT hn FROM clinicmember WHERE clinic = "002" AND clinic_member_status_id != 3) ';

            } else if ($uclinic == 3) {
                 $report_name = 'รายงานคนไข้ Diag N183-N185 มีชื่อในทะเบียน DM แต่ไม่มีชื่อในทะเบียน HT แยกตามสถานบริการ(คน)';
                
                 $get_type = 'p.hn  IN ( SELECT hn FROM clinicmember WHERE clinic = "001" AND clinic_member_status_id != 3)
                  AND
                     p.hn  NOT IN ( SELECT hn FROM clinicmember WHERE clinic = "002" AND clinic_member_status_id != 3) ';

            } else if ($uclinic == 4) {
                 $report_name = 'รายงานคนไข้ Diag N183-N185 มีชื่อในทะเบียน HT แต่ไม่มีชื่อในทะเบียน DM แยกตามสถานบริการ(คน)';
                
                 $get_type = 'p.hn  NOT IN ( SELECT hn FROM clinicmember WHERE clinic = "001" AND clinic_member_status_id != 3)
                  AND
                     p.hn  IN ( SELECT hn FROM clinicmember WHERE clinic = "002" AND clinic_member_status_id != 3) ';

            }

            
            
            if($hosp_area == 1) {
                $hosp_area_condition = " AND concat(p.chwpart,p.amppart,p.tmbpart) =  '860502'   and p.moopart in (1,2,3,4,5,6,7,8,9) ";
            } else if ($hosp_area == 2) {
                $hosp_area_condition = " AND concat(p.chwpart,p.amppart,p.tmbpart) =  '860503'   and p.moopart in (2,3,4,5,6,9)";
            } else if ($hosp_area == 3) {
                $hosp_area_condition = " AND concat(p.chwpart,p.amppart,p.tmbpart) =  '860504'   and p.moopart in (1,2,3,4,6) "; 
            } else if ($hosp_area == 4) {
                $hosp_area_condition = " AND concat(p.chwpart,p.amppart,p.tmbpart) =  '860501'   and p.moopart in (8,11,13,15,17,18,20) ";
            } else if ($hosp_area == 5) {
               $hosp_area_condition = " AND (concat(p.chwpart,p.amppart,p.tmbpart) =  '860501'    and p.moopart in (16,19)
                                        OR concat(p.chwpart,p.amppart,p.tmbpart) =  '860504'     and p.moopart in (5,7,8)) ";
            } else if ($hosp_area == 6) {
                $hosp_area_condition = " AND concat(p.chwpart,p.amppart,p.tmbpart) =  '860503'   and p.moopart in (1,7,8,10) ";
            } else if ($hosp_area == 7) {
                $hosp_area_condition = " AND concat(p.chwpart,p.amppart,p.tmbpart) =  '860501'   and p.moopart in (1,2,3,4,5,6,7,9,10,12,14) ";
            }
            
            
           
            $sql = "         
                     SELECT
                    p.hn as hn,concat(p.pname,p.fname,'  ',p.lname) as pt_name,
                    concat( timestampdiff(year,p.birthday,now()), ' ปี') as age_y,
                    p.cid,
                    concat(p.addrpart,' ม.',p.moopart,' ',th.full_name) address,
                    p.moopart,p.hometel,
                     (
                        select 
                             ov.icd10
                        from ovstdiag ov
                        where 
                        ov.icd10 BETWEEN 'N183' AND 'N185' and
                        ov.vstdate between '2012-10-01' and now()                 
                        and ov.hn = p.hn                        
                        order by ov.vstdate desc
                        limit 1
                        
                        
                    ) as last_diag,
                    (
                        select 
                            ov.vstdate
                        from ovstdiag ov
                        where 
                        ov.icd10 BETWEEN 'N183' AND 'N185' and
                        ov.vstdate between '2012-10-01' and now()                 
                        and ov.hn = p.hn                        
                        order by ov.vstdate desc
                        limit 1
                        
                        
                    ) as last_date,
                    

                    (
                        select 
                             ov.icd10
                        from ovstdiag ov
                        where 
                        ov.icd10 BETWEEN 'N183' AND 'N185' and
                        ov.vstdate between '2012-10-01' and now()                 
                        and ov.hn = p.hn                        
                        order by ov.vstdate asc
                        limit 1
                        
                        
                    ) as first_diag,
                    

                     (
                        select 
                             ov.vstdate
                        from ovstdiag ov
                        where 
                        ov.icd10 BETWEEN 'N183' AND 'N185' and
                        ov.vstdate between '2012-10-01' and now()                 
                        and ov.hn = p.hn                        
                        order by ov.vstdate asc
                        limit 1
                        
                        
                    ) as first_date
                    


                    FROM vn_stat  v


                    LEFT OUTER JOIN patient p ON p.hn = v.hn
                    LEFT OUTER JOIN sex s ON s.code = v.sex
                    LEFT OUTER JOIN thaiaddress th ON th.addressid = concat(p.chwpart,p.amppart,p.tmbpart)


                    WHERE

                    
                       v.vstdate BETWEEN '2012-10-01' and now()

                  AND

                     (
                        v.pdx BETWEEN 'N183' AND 'N185' OR
                        v.dx0 BETWEEN 'N183' AND 'N185' OR
                        v.dx1 BETWEEN 'N183' AND 'N185' OR
                        v.dx2 BETWEEN 'N183' AND 'N185' OR
                        v.dx3 BETWEEN 'N183' AND 'N185' OR
                        v.dx4 BETWEEN 'N183' AND 'N185' OR
                        v.dx5 BETWEEN 'N183' AND 'N185'

                     )

                   AND

                    $get_type

                    $hosp_area_condition


                    GROUP BY p.hn
                    ORDER BY p.moopart,age_y


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
        }// จบตรวจสอบการเลือกประเภทคนไข้
          
          
         
         
    } // end function
    
    
    

    
} // end class
