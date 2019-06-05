<?php

namespace frontend\controllers;


use Yii;
use frontend\components\CommonController;

class AccountController extends CommonController {
    
    public $dep_controller = 'account';
 
    public function actionReport1($datestart, $dateend, $details) {
       
        // save log
        $this->SaveLog($this->dep_controller, 'report1', $this->getSession());
        
        $report_name = "รายงานผู้ป่วยค้างชำระ";
        $sql = "SELECT
            concat(DAY(r.arrear_date),'/',MONTH(r.arrear_date),'/',(YEAR(r.arrear_date)+543)) as arrear_date_thai,
            r.arrear_date,r.arrear_time,r.hn,
            concat(p.pname,p.fname,'  ',p.lname) as ptname,
            r.amount,r.paid,o.pttype, t.name as pttype_name
        FROM rcpt_arrear r
        LEFT OUTER JOIN ovst o ON o.vn=r.vn
        LEFT OUTER JOIN patient p ON p.hn=r.hn
        LEFT OUTER JOIN pttype t ON t.pttype = o.pttype
        WHERE
             r.arrear_date BETWEEN $datestart and $dateend AND r.paid ='N'
        ORDER BY r.arrear_id ";
        

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
            
        $stry1= date("Y",strtotime($datestart));
        $strm1= date("m",strtotime($datestart));
        $strd1= date("d",strtotime($datestart));
        
        $stry2= date("Y",strtotime($dateend));
        $strm2= date("m",strtotime($dateend));
        $strd2= date("d",strtotime($dateend));
        
        $d1 = $stry1.'-'.$strm1.'-'.$strd1.' 00:00:01';
        $d2 = $stry2.'-'.$strm2.'-'.$strd2.' 23:59:59';
        
        $report_name = "รายงานการยกเลิกใบแจ้งหนี้";
        $sql = "SELECT 
                    r.* ,concat(p.pname,p.fname,' ',p.lname) as ptname,
                    opdu.name as name_staff,
                    concat(DAY(r.debt_date),'/',MONTH(r.debt_date),'/',(YEAR(r.debt_date)+543)) as debt_date
                FROM 
                    rcpt_debt_cancel r
                LEFT OUTER JOIN patient p ON p.hn = r.hn
                LEFT OUTER JOIN opduser opdu ON opdu.loginname=r.staff
                WHERE 
                    r.cancel_datetime BETWEEN '$d1' and '$d2'
                ORDER BY 
                    r.debt_cancel_id ";
        
   

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
   

}
