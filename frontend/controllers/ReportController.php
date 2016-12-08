<?php

namespace frontend\controllers;

use yii;
use yii\data\SqlDataProvider;

class ReportController extends \yii\web\Controller {

    public function actionIndex() {
        $session = Yii::$app->session;
        return $this->render('//report/index');
    }

    public function actionGrid($dep_id = null, $dep_name = null) {

        if (!empty($dep_id)) {

            $dataProvider = new SqlDataProvider([
                'sql' => 'SELECT * ' .
                ' FROM lamaereports ' .
                ' WHERE lamaedepartment_id=:dep_id ' .
                ' AND status=:status ',
                'params' => [':dep_id' => $dep_id, ':status' => 'enable'],
                'pagination' => [
                    'pageSize' => 100,
                ],
            ]);

            // returns an array of data rows
            $models = $dataProvider->getModels();
        }

        return $this->render('//report/grid', ['models' => $models, 'dep_name' => $dep_name]);
    }

    public function actionForm($controller = null, $form_id = null, $pointer = null, $report_name = null, $details = null) {
        //Function เลือก รูปแบบฟอร์มค้นหาข้อมูล
        $view = null;
        $point = null;
        $ctrl = null;
        $txt = null;


        switch ($form_id) {
            case "form1" :                  //รูปแบบฟอร์มค้นหาแบบที่ 1 แบบระหว่างวันที่
                $ctrl = $controller;
                $view = $form_id;
                $point = $pointer;
                $txt = $details;
                break;

            case "form2" :                  //รูปแบบฟอร์มค้นหาแบบที่ 2 แบบไม่มีวันที่
                $ctrl = $controller;
                $view = $form_id;
                $point = $pointer;
                $txt = $details;
                break;

            case "form3" :                  //รูปแบบฟอร์มค้นหาแบบที่ 3 แสดงวันที่ 
                $ctrl = $controller;
                $view = $form_id;
                $point = $pointer;
                $txt = $details;
                break;

            default:
                $ctrl = $controller;
                $view = $form_id;
                $point = $pointer;
                $txt = $details;
        }

        return $this->render($view, ['ctrl' => $ctrl, 'point' => $point, 'report_name' => $report_name, 'details' => $txt]);
    }

}
