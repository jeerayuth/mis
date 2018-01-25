<?php

namespace frontend\controllers;

use Yii;
use frontend\components\CommonController;

class AncController extends CommonController {
    
    public $dep_controller = 'anc';
    
    public function actionReport1($datestart, $dateend, $details) {
          // save log
        $this->SaveLog($this->dep_controller, 'report1', $this->getSession());

        $report_name = "รายงานจำนวนครั้งหญิงตั้งครรภ์ ได้รับยา Triferdine";

        $sql = "SELECT 
                    v.vn,v.hn,concat(p.pname,p.fname,'  ',p.lname) as pt_name,
                    v.age_y,v.pdx,
                    concat(DAY(v.vstdate),'/',MONTH(v.vstdate),'/',(YEAR(v.vstdate)+543)) as vstdate, 
                    o.icode , d.name
                FROM vn_stat v
                    left outer join opitemrece o on o.vn = v.vn
                    left outer join drugitems d on d.icode = o.icode
                    left outer join patient p on p.hn = v.hn
                WHERE 
                    v.vstdate between $datestart and $dateend and
                    v.pdx in ('Z340','Z349')  and o.icode = '1550014'
                GROUP BY v.vn ";

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

        $report_name = "รายงานจำนวนครั้งหญิงตั้งครรภ์ ที่มีผลแลป hematocrit < 33%";

        $sql = "SELECT
                    v.vn,v.hn,concat(p.pname,p.fname,'  ',p.lname) as pt_name,
                    v.age_y,v.pdx,
                    concat(DAY(v.vstdate),'/',MONTH(v.vstdate),'/',(YEAR(v.vstdate)+543)) as vstdate ,
                    li.lab_items_name,lo.lab_order_result
                FROM vn_stat v
                    left outer join patient p on p.hn = v.hn
                    left outer join lab_head lh on lh.vn = v.vn
                    left outer join lab_order lo on lo.lab_order_number = lh.lab_order_number
                    left outer join lab_items li on li.lab_items_code = lo.lab_items_code
                WHERE 
                    v.vstdate between $datestart and $dateend and
                    v.pdx in ('Z340','Z349')   and (lo.lab_items_code = '2098' and lo.lab_order_result < 33)
                GROUP BY v.vn ";

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

        $report_name = "รายงานหญิงตั้งครรภ์ทุกรายได้รับการคัดกรองภาวะเสี่ยงและพบภาวะเสี่ยง";

        $sql = "SELECT
                    a.person_anc_id,a.person_id,
                    a.person_anc_no,
                    concat(DAY(a.anc_register_date),'/',MONTH(a.anc_register_date),'/',(YEAR(a.anc_register_date)+543)) as anc_register_date ,
                    a.anc_register_staff,
                    ps.cid,
                    concat(ps.pname,ps.fname,'  ',ps.lname) as person_name,
                    p.person_anc_classifying_item_id, p.check_value,
                    p.update_datetime
                FROM person_anc   a
                    left outer join person_anc_classifying p on p.person_anc_id = a.person_anc_id
                    left outer join person ps on ps.person_id = a.person_id
                WHERE p.update_datetime  between  $datestart and $dateend and p.check_value = 'Y'       
                GROUP BY a.person_id
                ORDER BY p.update_datetime ";

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
    
    
    public function actionReport4($datestart, $dateend, $details) {
          // save log
        $this->SaveLog($this->dep_controller, 'report4', $this->getSession());

        $report_name = "รายงานจำนวนครั้งหญิงตั้งครรภ์ ได้รับการตรวจแลป hematocrit,vdrl,anti-hiv,HBsAg,OF,DCIP";

        $sql = "SELECT
                    v.vn,v.hn,concat(p.pname,p.fname,'  ',p.lname) as pt_name,
                    v.age_y,v.pdx,
                    concat(DAY(v.vstdate),'/',MONTH(v.vstdate),'/',(YEAR(v.vstdate)+543)) as vstdate ,
                    li.lab_items_name,lo.lab_order_result
                FROM vn_stat v
                    left outer join patient p on p.hn = v.hn
                    left outer join lab_head lh on lh.vn = v.vn
                    left outer join lab_order lo on lo.lab_order_number = lh.lab_order_number
                    left outer join lab_items li on li.lab_items_code = lo.lab_items_code
                WHERE 
                    v.vstdate between $datestart and $dateend and
                    v.pdx in ('Z340','Z349')   and 
                    lo.lab_items_code  in ('2098','2146','2178','2174','2126','2128')
                ORDER BY v.vn ";

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
                    'details' => $details,
        ]);
    }
    
    
    
    public function actionReport5($details) {
          // save log
        $this->SaveLog($this->dep_controller, 'report5', $this->getSession());

        $report_name = "รายงานทะเบียนหญิงตั้งครรภ์และหญิงหลังคลอด 6 สัปดาห์(HN บัญชี1 กับ HN ห้องบัตร ไม่ตรงกัน)";

        $sql = "SELECT
                    p.cid,concat(pt.pname,pt.fname,'  ',pt.lname) as pt_name,
                    h.address,h.road,v.village_moo,v.village_name,
                    t.full_name as full_address_name,p.patient_hn as person_hn ,
                    pt.hn as patient_hn
                FROM person_anc a
                left outer join person p on p.person_id = a.person_id
                left outer join house h on h.house_id = p.house_id
                left outer join village v on v.village_id = p.village_id
                left outer join labor_status ats on ats.labor_status_id = a.labor_status_id
                left outer join thaiaddress t on t.addressid = v.address_id
                left outer join patient pt on pt.cid = p.cid

                WHERE (a.discharge <> 'Y' or a.discharge IS NULL) AND pt.hn != p.patient_hn
                ORDER BY  pt.hn  ";
            
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
    
    


}
