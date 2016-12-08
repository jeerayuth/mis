<?php

namespace frontend\controllers;

class PcuController extends \yii\web\Controller {
 
    public function actionReport1($details, $age_id) {
        
         if ($age_id != "") { // เริ่มต้นตรวจสอบ อายุ  
             if ($age_id == 1) {
                $age = '30 and 60';
                $report_name = 'รายงานสรุปหญิงอายุ 30-60 ปี ในเขตรับผิดชอบ';  
             } else if($age_id == 2) {
                $age = '30 and 70';
                $report_name = 'รายงานสรุปหญิงอายุ 30-70 ปี ในเขตรับผิดชอบ';   
             } 
         }

         
        $sql = "SELECT
                    v.village_id,v.village_moo, v.village_name ,                 
                    (
                      select count(p.cid) from person p where p.sex = '2'  
                      and  timestampdiff(year,p.birthdate,curdate()) between  $age 
                      and p.village_id = v.village_id
                    ) as age_min_sex_female
                FROM village v
                WHERE v.village_id != 1 ";
        
 
        
        
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
                    'age_id' => $age_id,
                  
        ]);
        
         
    }
    
    public function actionReport2($village_id,$age_id) {

        
         if ($age_id != "") { // เริ่มต้นตรวจสอบ อายุ  
             if ($age_id == 1) {
                $age = '30 and 60';
                $report_name = 'รายงานสรุปหญิงอายุ 30-60 ปี ในเขตรับผิดชอบ';  
             } else if($age_id == 2) {
                $age = '30 and 70';
                $report_name = 'รายงานสรุปหญิงอายุ 30-70 ปี ในเขตรับผิดชอบ';   
             } 
         }
         
       // $report_name = "รายงานรายชื่อหญิงอายุ 30-70 ปี ในเขตรับผิดชอบ";
        $sql = "select

                concat(p.pname,p.fname,'  ',p.lname) as pt_name ,v.village_moo,v.village_name,h.address,t.full_name,
                timestampdiff(year,p.birthdate,curdate()) as age_year,p.house_regist_type_id, r.house_regist_type_name

                from person p

                left outer join village v on v.village_id = p.village_id
                left outer join thaiaddress t on t.addressid = v.address_id
                left outer join house h on h.house_id = p.house_id
                left outer join house_regist_type r on r.house_regist_type_id = p.house_regist_type_id

                where p.sex = '2'  and  timestampdiff(year,p.birthdate,curdate()) between  $age  and p.village_id = $village_id
                order by timestampdiff(year,p.birthdate,curdate()) ";
    
        
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
                    'village_id' => $village_id,
                    
        ]); 
    }

      public function actionReport3($details) {

        $report_name = "รายงานสรุปประชากรอายุ >=35 ปี ในเขตรับผิดชอบ";
        $sql = "SELECT
                    v.village_id,v.village_moo, v.village_name ,                 
                    (
                      select count(p.cid) from person p where  timestampdiff(year,p.birthdate,curdate())   >=35  and p.village_id = v.village_id
                    ) as age_min_35
                FROM village v
                WHERE v.village_id != 1 ";
     
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
                    'rawData' => $rawData,
                    'report_name' => $report_name,
                    'details' => $details,
        ]);
    }
    
    
    public function actionReport4($village_id) {

        
        $report_name = "รายงานสรุปประชากรอายุ >=35 ปี ในเขตรับผิดชอบ";
        $sql = "select

                concat(p.pname,p.fname,'  ',p.lname) as pt_name ,v.village_moo,v.village_name,h.address,t.full_name,
                timestampdiff(year,p.birthdate,curdate()) as age_year,p.house_regist_type_id, r.house_regist_type_name

                from person p

                left outer join village v on v.village_id = p.village_id
                left outer join thaiaddress t on t.addressid = v.address_id
                left outer join house h on h.house_id = p.house_id
                left outer join house_regist_type r on r.house_regist_type_id = p.house_regist_type_id

                where timestampdiff(year,p.birthdate,curdate())  >=35   and p.village_id = $village_id
                order by timestampdiff(year,p.birthdate,curdate())

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

        return $this->render('report4', [
                    'dataProvider' => $dataProvider,
                    'rawData' => $rawData,
                    'report_name' => $report_name,
                    'village_id' => $village_id,
                    
        ]); 
    }
    
    
    public function actionReport5($details) {

        
        $report_name = "รายงานตรวจสอบ => ผู้ป่วยที่อยู่ใน เวชระเบียน Patient แต่ไม่มีในบัญชี 1 Person";
        $sql = "SELECT 
                    cid,hn,concat(pname,fname,'  ',lname) as pt_name,type_area,
                    addrpart,moopart, thaiaddress.full_name as full_name
                FROM patient
                left outer join thaiaddress on  thaiaddress.addressid = concat(patient.chwpart,patient.amppart,patient.tmbpart)

                WHERE 
                        cid not in(SELECT cid FROM person) and (death='N' or death is NULL or death='  ')
                        
                AND nationality='99' and 
                        (cid not like '0%' and cid<>' ' and cid is not null and cid not like '111111111%')

                ORDER BY
                        patient.chwpart, patient.amppart, patient.tmbpart, patient.moopart ";
        
        
      
        
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
                    'rawData' => $rawData,
                    'report_name' => $report_name,
                    'details' => $details,
                            
        ]); 
    }
    
    
    public function actionReport6($details) {
     
        $report_name = "รายงานตรวจสอบ => ในบัญชี1 Type(1,2) แต่ในเวชระเบียนลงที่อยู่นอกเขต";
        $sql = "SELECT 
                    p.cid,p.hn,concat(p.pname,p.fname,'  ',p.lname) as pt_name,p.type_area,
                    p.addrpart,p.moopart, thaiaddress.full_name as full_name
                FROM patient p
                join person ps on ps.cid=p.cid
                left outer join thaiaddress on  thaiaddress.addressid = concat(p.chwpart,p.amppart,p.tmbpart)
                WHERE 
                    ps.house_regist_type_id in(1,2) AND 
                    (p.death<>'Y' or p.death=' ' or p.death is NULL)
                    and CONCAT(p.chwpart,p.amppart,p.tmbpart,LPAD(moopart,2,0)) not in 
                    (SELECT village_code FROM village)";
   
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
    
    
      public function actionReport7($details) {

        
        $report_name = "รายงานตรวจสอบ => ผู้ป่วย TypeAreaในบัญชี 1 เป็นค่าว่าง";
        $sql = "SELECT
            cid,concat(pname,fname,' ',lname) as person_name, house_regist_type_id
        FROM person
        WHERE 
            house_regist_type_id is null  
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

        
        return $this->render('report7', [
                    'dataProvider' => $dataProvider,
                    'rawData' => $rawData,
                    'report_name' => $report_name,
                    'details' => $details,
                            
        ]); 
    }
    
    
    public function actionReport8($details) {

        
        $report_name = "รายงานตรวจสอบ => สถานะในครอบครัว 1 = เจ้าบ้าน  , 2 = ผู้อาศัย ในบัญชี 1 ว่าง";
        $sql = "SELECT
            cid,concat(pname,fname,' ',lname) as person_name, house_regist_type_id
        FROM person
        WHERE 
            person.person_house_position_id is null or 
            person.person_house_position_id not in(1,2) ";
   
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
    
    
    
     public function actionReport9($details) {

        $report_name = "รายงานตรวจสอบ => การศึกษาว่าง ในบัญชี 1 เป็นค่าว่าง";
        $sql = "SELECT
                cid,concat(pname,fname,' ',lname) as person_name, house_regist_type_id
            FROM person
                WHERE (education=' '  or education is NULL) or 
                education not in(SELECT education FROM education) ";
   
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
    
    
     public function actionReport10($details) {

        $report_name = "รายงานตรวจสอบ =>การศึกษา อายุ 6-12 ปี ไม่ใช่ชั้นประถม ในบัญชี 1 มีผลกับ HDC";
        $sql = "SELECT
                    cid,concat(pname,fname,' ',lname) as person_name, house_regist_type_id,age_y
                FROM person
                WHERE age_y BETWEEN 6 and 12 and education != 
                    (SELECT education FROM education WHERE provis_code=2) ";
   
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
   
   
    public function actionReport11($details) {

        $report_name = "รายงานตรวจสอบ => อาชีพว่าง ในบัญชี 1 เป็นค่าว่าง";
        $sql = "SELECT
            cid,concat(pname,fname,' ',lname) as person_name, house_regist_type_id
        FROM person
        WHERE (occupation=' ' or occupation is null) or
                occupation not in(SELECT occupation FROM occupation) ";
   
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
    
    
     public function actionReport12($details) {

        $report_name = "รายงานตรวจสอบ => คำนำหน้าชื่อว่าง ในบัญชี 1 เป็นค่าว่าง";
        $sql = "SELECT 
                    cid,concat(pname,fname,' ',lname) as person_name, house_regist_type_id 
                FROM person 
                WHERE (pname=' '  or pname is null) " ;
   
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
    
    
      public function actionReport13($details) {

        $report_name = "รายงานตรวจสอบ => สิทธิการรักษา ไม่มีใน pttype ในบัญชี 1 ทำให้ส่งออกไม่ได้";
        $sql = "SELECT
                cid,concat(pname,fname,' ',lname) as person_name, house_regist_type_id
            FROM person
            WHERE (pttype not in(SELECT pttype FROM pttype) or (pttype=' ' and pttype is null)) and (death='N' or death is NULL or death=' ')" ;
   
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
    
    
      public function actionReport14($details) {

        $report_name = "รายงานตรวจสอบ => สิทธิการรักษา ในบัญชี 1 ว่าง";
        $sql = "SELECT
                cid,concat(pname,fname,' ',lname) as person_name, house_regist_type_id
            FROM person
            WHERE (pttype=' ' or pttype is null) " ;
   
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
    
    
    
     public function actionReport15($details) {

        $report_name = "รายงานตรวจสอบ => มีสัญชาติไทย แต่เลขที่บัตรประชาชน ขึ้นต้นด้วย 0 Type 1 , 3";
        $sql = "SELECT
                cid,concat(pname,fname,' ',lname) as person_name, house_regist_type_id
            FROM person
            WHERE nationality='99' and cid LIKE '0%' AND house_regist_type_id in(1,3) and (death='N' or death is NULL or death=' ') " ;
   
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
    
    
     public function actionReport16($details) {

        $report_name = "รายงานตรวจสอบ => คนที่มีบ้านเลขที่บ้าน แต่ไม่มีหลังคาเรือนในระบบ";
        $sql = "SELECT
                cid,concat(pname,fname,' ',lname) as person_name, house_regist_type_id
            FROM person
            WHERE house_id not in(SELECT house_id FROM house) " ;
   
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
    
    
    
     public function actionReport17($details) {

        $report_name = "รายงานตรวจสอบ => คนต่างด้าว ไม่ลงประเภทคนต่างด้าวในบัญชี1";
        $sql = "SELECT cid,concat(pname,fname,' ',lname) as person_name, house_regist_type_id,
                        n.name as nationality_name
                    FROM person p
                    LEFT JOIN person_labor_type l on l.person_labor_type_id=p.person_labor_type_id
                    LEFT JOIN nationality n on n.nationality = p.nationality
                    WHERE p.nationality<>'99' and (p.person_discharge_id is NULL or  p.person_discharge_id=' ' or p.person_discharge_id='9')

                    AND (p.person_labor_type_id=' ' or p.person_labor_type_id is null or p.person_labor_type_id not in(SELECT person_labor_type_id FROM person_labor_type)) " ;

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
    
    
    public function actionReport18($details) {

        $report_name = "รายงานตรวจสอบ => ลงติ๊กเสียชีวิตแล้ว ในบัญชี 1 แต่สถานะยังไม่จำหน่าย";
        $sql = "SELECT cid,concat(pname,fname,' ',lname) as person_name, house_regist_type_id 
                FROM person 
                WHERE death='Y' and (discharge_date is NULL or discharge_date=' ')
                  " ;

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
                    'report_name' => $report_name,
                    'details' => $details,
                            
        ]); 
    }
    
    
    
     public function actionReport19($details) {

        $report_name = "รายงานตรวจสอบ => ลงติ๊กเสียชีวิตแล้ว ในบัญชี 1 แต่สถานะยังมีชีวิตอยู่ ";
        $sql = "SELECT cid,concat(pname,fname,' ',lname) as person_name, house_regist_type_id 
                FROM person 
                WHERE death='Y' and person_discharge_id<>'1' ";
                  

        try {
            $rawData = \yii::$app->db->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }

        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => FALSE,
        ]);

        
        return $this->render('report19', [
                    'dataProvider' => $dataProvider,
                    'rawData' => $rawData,
                    'report_name' => $report_name,
                    'details' => $details,
                            
        ]); 
    }
    
    
     public function actionReport20($datestart, $dateend,$details) {

        $report_name = "รายงานอาการคล้ายไข้หวัดใหญ่ (ILI)";
        $sql = "SELECT
         concat(DAY(v.vstdate),'/',MONTH(v.vstdate),'/',(YEAR(v.vstdate)+543)) as vstdate,
        (select count(n.vn) from vn_stat n where n.vstdate = v.vstdate) as count_all_visit ,

        (
                select count(a.vn)
                       from vn_stat a
                       where (
                                    (a.pdx = 'j00' ) or
                                    (a.dx0 = 'j00' ) or
                                    (a.dx1 = 'j00' ) or
                                    (a.dx2 = 'j00' ) or
                                    (a.dx3 = 'j00' ) or
                                    (a.dx4 = 'j00' ) or
                                    (a.dx5 = 'j00' )

                             ) and a.vstdate = v.vstdate)  as  count_j00 ,



        (
                select count(a.vn)
                       from vn_stat a
                       where (
                                    (a.pdx between 'j020' and  'j029') or
                                    (a.dx0 between 'j020' and  'j029') or
                                    (a.dx1 between 'j020' and  'j029') or
                                    (a.dx2 between 'j020' and  'j029') or
                                    (a.dx3 between 'j020' and  'j029') or
                                    (a.dx4 between 'j020' and  'j029') or
                                    (a.dx5 between 'j020' and  'j029')

                             ) and a.vstdate = v.vstdate) as count_j020_j029     ,



        (
                select count(a.vn)
                       from vn_stat a
                       where (
                                    (a.pdx between 'j060' and  'j069') or
                                    (a.dx0 between 'j060' and  'j069') or
                                    (a.dx1 between 'j060' and  'j069') or
                                    (a.dx2 between 'j060' and  'j069') or
                                    (a.dx3 between 'j060' and  'j069') or
                                    (a.dx4 between 'j060' and  'j069') or
                                    (a.dx5 between 'j060' and  'j069')

                             ) and a.vstdate = v.vstdate) as count_j060_j069 ,


        (
                select count(a.vn)
                       from vn_stat a
                       where (
                                    (a.pdx ='j09') or
                                    (a.dx0 ='j09') or
                                    (a.dx1 ='j09') or
                                    (a.dx2 ='j09') or
                                    (a.dx3 ='j09') or
                                    (a.dx4 ='j09') or
                                    (a.dx5 ='j09')

                             ) and a.vstdate = v.vstdate) as count_j09   ,


        (
                select count(a.vn)
                       from vn_stat a
                       where (
                                    (a.pdx ='j10') or
                                    (a.dx0 ='j10') or
                                    (a.dx1 ='j10') or
                                    (a.dx2 ='j10') or
                                    (a.dx3 ='j10') or
                                    (a.dx4 ='j10') or
                                    (a.dx5 ='j10')

                             ) and a.vstdate = v.vstdate) as count_j10  ,


         (
                select count(a.vn)
                       from vn_stat a
                       where (
                                    (a.pdx ='j11') or
                                    (a.dx0 ='j11') or
                                    (a.dx1 ='j11') or
                                    (a.dx2 ='j11') or
                                    (a.dx3 ='j11') or
                                    (a.dx4 ='j11') or
                                    (a.dx5 ='j11')

                             ) and a.vstdate = v.vstdate) as count_j11



                 /*

        (
                select count(a.vn)
                       from vn_stat a
                       where (
                                    (a.pdx between 'j120' and  'j189') or
                                    (a.dx0 between 'j120' and  'j189') or
                                    (a.dx1 between 'j120' and  'j189') or
                                    (a.dx2 between 'j120' and  'j189') or
                                    (a.dx3 between 'j120' and  'j189') or
                                    (a.dx4 between 'j120' and  'j189') or
                                    (a.dx5 between 'j120' and  'j189')

                             ) and a.vstdate = v.vstdate) as count_j120_j189
                 */


        FROM vn_stat v

        WHERE v.vstdate between $datestart  and $dateend

        GROUP BY v.vstdate

        ORDER BY v.vstdate ";
                  

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
    
  
     public function actionReport21($details) {

        $report_name = "รายงานตรวจสอบ => สถานะสมณะ แต่คำนำหน้าไม่ใช่ สมณะ ";
        $sql = "SELECT
            p.cid,p.pname,concat(p.fname,'  ',p.lname) as person_name ,m.name , 
            p.marrystatus , p.sex,p.house_regist_type_id, s.name as sex

            FROM person p

            JOIN pname n on n.name=p.pname
            JOIN marrystatus m on m.code = p.marrystatus
            JOIN sex s on s.code = p.sex
            
            WHERE  p.marrystatus = 6 and (death='N' or death is NULL or death=' ')
            and n.provis_code not between 800 and 959 ";
                  

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
    
    
    
    public function actionReport22($details) {

        $report_name = "รายงานตรวจสอบ => คำนำหน้าชื่อเป็นพระ แต่สถานะไม่ใช่สมณะ ";
        $sql = "SELECT
            p.cid,p.pname,concat(p.fname,'  ',p.lname) as person_name ,m.name , 
            p.marrystatus , s.name as sex

            FROM person p

            JOIN pname n on n.name=p.pname
            JOIN marrystatus m on m.code = p.marrystatus
            JOIN sex s on s.code = p.sex

            WHERE  p.marrystatus != 6 and (death='N' or death is NULL or death=' ') 
            and n.provis_code  between 800 and 959 ";
                  

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
    
    
    public function actionReport23($details) {

        $report_name = "รายงานตรวจสอบ => ตรวจสอบสถานะเป็นพระ แต่ อายุ ไม่ถึง 20 ปี";
        $sql = "SELECT
                p.cid,p.pname,concat(p.fname,'  ',p.lname) as person_name ,
                m.name , p.marrystatus , p.sex ,p.age_y,  s.name as sex

                FROM person p

                JOIN pname n on n.name=p.pname
                JOIN marrystatus m on m.code = p.marrystatus
                JOIN sex s on s.code = p.sex

                WHERE  p.marrystatus = 6 and (death='N' or death is NULL or death=' ')
                and n.provis_code  between 800 and 959  and n.provis_code != 832  
                and n.provis_code != 863 and p.sex = 1 and p.age_y <= 19 ";


        try {
            $rawData = \yii::$app->db->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }

        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => FALSE,
        ]);

        
        return $this->render('report23', [
                    'dataProvider' => $dataProvider,
                    'rawData' => $rawData,
                    'report_name' => $report_name,
                    'details' => $details,
                            
        ]); 
    }
    
    
   
   
   
   

}
