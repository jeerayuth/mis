<?php

namespace frontend\controllers;

class PhysicController extends \yii\web\Controller {
    /* รายงานมูลค่าการใช้ยาปฏิชีวนะ */

    public function actionReport1($datestart, $dateend, $details) {

        $report_name = "รายงานจำนวนหัตถการงานกายภาพบำบัด";
        $sql = "SELECT 
                    o.icode,n.name,sum(o.qty) as count_use 
                FROM opitemrece o
                    left outer join nondrugitems n on n.icode = o.icode
                WHERE o.vstdate between $datestart and $dateend
                    and o.icode  in ('3007979','3007955', '3007956','3007962','3007964', '3007957','3007959', '3007969')
                GROUP BY o.icode
                ORDER BY count_use DESC ";
        

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
