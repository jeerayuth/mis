<?php

namespace frontend\controllers;

class PcuController extends \yii\web\Controller {
 
    public function actionReport1($details) {

        $report_name = "รายงานสรุปหญิงอายุ 30-70 ปี ในเขตรับผิดชอบ";
        $sql = "SELECT
                    v.village_id,v.village_moo, v.village_name ,                 
                    (
                      select count(p.cid) from person p where p.sex = '2'  and  timestampdiff(year,p.birthdate,curdate()) between  30 and 70  and p.village_id = v.village_id
                    ) as age_min_30_70_sex_female
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
        ]);
    }
    
    public function actionReport2($village_id) {

        
        $report_name = "รายงานรายชื่อหญิงอายุ 30-70 ปี ในเขตรับผิดชอบ";
        $sql = "select

                concat(p.pname,p.fname,'  ',p.lname) as pt_name ,v.village_moo,v.village_name,h.address,t.full_name,
                timestampdiff(year,p.birthdate,curdate()) as age_year,p.house_regist_type_id, r.house_regist_type_name

                from person p

                left outer join village v on v.village_id = p.village_id
                left outer join thaiaddress t on t.addressid = v.address_id
                left outer join house h on h.house_id = p.house_id
                left outer join house_regist_type r on r.house_regist_type_id = p.house_regist_type_id

                where p.sex = '2'  and  timestampdiff(year,p.birthdate,curdate()) between  30 and 70  and p.village_id = $village_id
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
        WHERE house_regist_type_id is null or house_regist_type_id=' ' ";
   
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

        $report_name = "รายงานตรวจสอบ => ";
        $sql = "" ;
   
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
    
   
   
   

}
