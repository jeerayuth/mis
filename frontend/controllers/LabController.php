<?php

namespace frontend\controllers;

use Yii;
use frontend\components\CommonController;

class LabController extends CommonController {

    public $dep_controller = 'lab';

    public function actionReport1($hn, $details) {
        // save log
        $this->SaveLog($this->dep_controller, 'report1', $this->getSession());

        $report_name = "รายงานประวัติการสั่งแลปตาม HN คนไข้";

        $sql = "
                SELECT
                      lh.hn,CONCAT(pt.pname,pt.fname,'  ',pt.lname) as pt_name,
                      s.name as sex_name,timestampdiff(year,pt.birthday,lh.order_date) as age_y,
                      pt.hometel,
                      lh.lab_order_number,lh.vn,
                      concat(DAY(lh.order_date),'/',MONTH(lh.order_date),'/',(YEAR(lh.order_date)+543))  as order_date,
                      concat(DAY(lh.report_date),'/',MONTH(lh.report_date),'/',(YEAR(lh.report_date)+543))  as report_date,
                      lh.department,
                      if(lh.result_note is not null,lh.result_note, '-') as result_note ,
                      lh.report_lock_computer ,
                      lo.lab_items_code,li.lab_items_name,lo.lab_order_result,
                      k.department as department_name
                FROM
                    lab_head lh

                LEFT OUTER JOIN patient pt ON pt.hn = lh.hn
                LEFT OUTER JOIN lab_order lo ON lo.lab_order_number = lh.lab_order_number
                LEFT OUTER JOIN lab_items li ON li.lab_items_code = lo.lab_items_code
                LEFT OUTER JOIN sex s ON s.code = pt.sex
                LEFT OUTER JOIN kskdepartment k ON k.depcode = lh.order_department


                WHERE         
                     lo.lab_items_code  in ('14','15','16','17','18','20','22','23','25',
                                            '3104','3121','3122','3123','3124','3125','3126',
                                            '3129','3288','3290','3293','3296','3299','3397',
                                            '3399','3401')
                 AND lh.hn = $hn
                 AND lo.confirm = 'Y'

                ORDER BY lh.order_date DESC";

        
         

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

}
