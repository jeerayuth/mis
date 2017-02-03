<?php

namespace frontend\controllers;

use Yii;
use frontend\components\CommonController;

class StatController extends CommonController {

    //public $dep_controller = 'stat';

    public function actionReport1($datestart, $dateend, $details) {
        // save log
        //$this->SaveLog($this->dep_controller, 'report1', $this->getSession());

        $report_name = "สถิติการใช้งานระบบสารสนเทศ";

        $sql = "";

       
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
