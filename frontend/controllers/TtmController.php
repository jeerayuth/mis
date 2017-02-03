<?php

namespace frontend\controllers;
use Yii;
use frontend\components\CommonController;

class TtmController extends CommonController {
    
    public $dep_controller = 'ttm';

    public function actionReport1($datestart, $dateend, $details) {
                // save log
        $this->SaveLog($this->dep_controller, 'report1', $this->getSession());

        $report_name = "รายงานจำนวนหัตถการงานแพทย์แผนไทย";
        $sql = "SELECT
                    h.health_med_treatment_subtype_name,
                    count(s.health_med_treatment_subtype_id)   AS count_treatment_subtype,
                    count(distinct(hs.hn))  as count_hn
                FROM health_med_service_treatment  s
                LEFT OUTER JOIN health_med_treatment_subtype h on h.health_med_treatment_subtype_id = s.health_med_treatment_subtype_id
                LEFT OUTER JOIN health_med_service hs on hs.health_med_service_id = s.health_med_service_id
                WHERE
                    s.health_med_service_id in
                    (
                        select ss.health_med_service_id from  health_med_service  ss
                        where ss.health_med_service_id  = s.health_med_service_id
                        and ss.service_date between $datestart and $dateend 
                    )
                GROUP BY s.health_med_treatment_subtype_id
                ORDER BY count_treatment_subtype desc ";
        

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

     
    public function actionReport2($datestart, $dateend, $details) {
                // save log
        $this->SaveLog($this->dep_controller, 'report2', $this->getSession());

        $report_name = "รายงานสรุปจำนวนการรับบริการงานแพทย์แผนไทย";
        $sql = "SELECT 
                    h.hn,concat(p.pname,p.fname,'  ',p.lname) as pt_name,
                    s.health_med_service_type_name,h.service_time ,
                    concat(DAY(h.service_date),'/',MONTH(h.service_date),'/',(YEAR(h.service_date)+543)) as service_date,
                    t.health_med_treatment_type_name ,r.health_med_service_result_name
                FROM health_med_service  h   
                    left outer join patient p on p.hn = h.hn
                    left outer join health_med_service_type s on s.health_med_service_type_id = h.health_med_service_type_id
                    left outer join health_med_treatment_type   t on t.health_med_treatment_type_id = h.health_med_treatment_type_id
                    left outer join health_med_service_result r on r.health_med_service_result_id = h.health_med_service_result_id
                WHERE h.service_date between $datestart and $dateend  ";
        
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
    
    
    public function actionReport3($datestart, $dateend, $details) {
                // save log
        $this->SaveLog($this->dep_controller, 'report3', $this->getSession());

        $report_name = "รายงานผู้มารับบริการที่มีการสั่งยาแพทย์แผนไทยแต่ไม่ได้ไปรับบริการที่แพทย์แผนไทย";
        $sql = "SELECT
                    o.hn ,concat(p.pname,p.fname,'  ',p.lname) as pt_name,
                    concat(i.name,' ',i.strength,' x ',i.packqty,' ',i.units) as drug_name,
                    o.qty, d.name as doctor_name,
                    concat(DAY(o.vstdate),'/',MONTH(o.vstdate),'/',(YEAR(o.vstdate)+543)) as vstdate
                FROM opitemrece  o
                    left outer join patient p on p.hn = o.hn
                    left outer join doctor d on d.code = o.doctor
                    left outer join drugitems i on i.icode = o.icode
                WHERE o.vstdate BETWEEN $datestart and $dateend  AND

                o.vn != '' and   (o.icode in

                (
                       select  d.icode from  drugitems d
                        where d.icode = o.icode and did like '4%'
                )

                and o.vn not in

                (
                      select  h.vn  from health_med_service  h
                      where h.vn = o.vn
                )

                )
                
                GROUP BY o.vn ";
        
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
   

}
