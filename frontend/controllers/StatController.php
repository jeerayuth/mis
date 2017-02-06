<?php

namespace frontend\controllers;

use Yii;
use frontend\components\CommonController;

class StatController extends CommonController {

    public function actionReport1($controller, $pointer,$report_name) {
   
        $report_name = "สถิติการใช้งานรายงาน";

        $sql = "SELECT
                    lg.controller,lg.report,lg.username,op.name as fullname,
                    COUNT(lg.username) AS count_use
                FROM lamaereportslog lg
                LEFT OUTER JOIN opduser op ON op.loginname = lg.username
                WHERE 
                    lg.controller = '$controller' AND report= '$pointer'
                GROUP BY lg.username ";

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
                    //'details' => $details,
        ]);
    }

}
