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
   
   

}
