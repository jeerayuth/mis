<?php

namespace frontend\controllers;

use Yii;
use frontend\components\CommonController;

class GeneralController extends CommonController {
    public $dep_controller = 'general';

    public function actionReport1($datestart, $dateend, $details) {
                // save log
        $this->SaveLog($this->dep_controller, 'report1', $this->getSession());

        $report_name = "รายงานคนไข้ที่ไม่ได้มีการลงรหัสวินิจฉัยหลัก";

        $sql = "SELECT
                    v.vstdate,
                    (
                         SELECT count(distinct(s.vn))  FROM vn_stat  s

                        WHERE s.vstdate = v.vstdate  AND (s.pdx='' or s.pdx is null)

                    )  AS count_pdx_empty

                FROM vn_stat v
                WHERE v.vstdate BETWEEN $datestart AND $dateend
                GROUP BY v.vstdate
                ORDER BY v.vstdate  ";

                  


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
    
    
     public function actionReport2($vstdate) {
         
      
                // save log
        $this->SaveLog($this->dep_controller, 'report2', $this->getSession());

        $report_name = "รายงานคนไข้ที่ไม่ได้มีการลงรหัสวินิจฉัยหลักของวันที่ $vstdate";

        $sql = "SELECT
                        v.vn,v.hn,
                        CONCAT(pt.pname,pt.fname,'  ',pt.lname) as pt_name,
                        v.pdx,v.dx0,v.dx1,v.dx2,v.dx3,v.dx4,v.dx5,
                        v.lastvisit,v.income,
                        v.vstdate,
                        ov.main_dep,ks1.department as dep1,
                        ov.cur_dep, ks2.department as dep2,
                        ov.last_dep, ks3.department as dep3

                  FROM vn_stat v

                  LEFT OUTER JOIN  ovst ov ON ov.vn = v.vn
                  LEFT OUTER JOIN  kskdepartment ks1 ON ks1.depcode = ov.main_dep
                  LEFT OUTER JOIN  kskdepartment ks2 ON ks2.depcode = ov.cur_dep
                  LEFT OUTER JOIN  kskdepartment ks3 ON ks3.depcode = ov.last_dep
                  LEFT OUTER JOIN  patient pt ON pt.hn = v.hn

                  WHERE
                       v.vstdate = '$vstdate'  AND
                       (v.pdx = '' or v.pdx is null)
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

        ]);
          
      
    }

    


}
