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
   
    public function actionReport4($datestart, $dateend, $details) {
                // save log
        $this->SaveLog($this->dep_controller, 'report4', $this->getSession());

        $report_name = "รายงานมูลค่าการสั่งใช้ยาแพทย์แผนไทยทั้งหมด";
        $report_name_graph = "รายงาน 10 อันดับมูลค่าการสั่งใช้ยาแพทย์แผนไทย";
         
        $sql = "SELECT
                    o.icode,d.name as drug_name,sum(o.qty) as sum_qty,
                     sum(o.sum_price) as sum_price,
                     sum(o.cost * o.qty ) as sum_cost,
                     count(distinct(o.hn)) as count_hn,
                     count(o.hn) as count_useage
                FROM opitemrece o
                LEFT OUTER JOIN drugitems d ON d.icode = o.icode
                WHERE 
                    o.vstdate BETWEEN $datestart and $dateend  AND 
                    d.did like '4%'
                GROUP BY o.icode
                ORDER BY sum_price DESC ";
        
        $sql_graph = "SELECT
                    o.icode,d.name as drug_name,sum(o.qty) as sum_qty,
                     sum(o.sum_price) as sum_price
                FROM opitemrece o
                LEFT OUTER JOIN drugitems d ON d.icode = o.icode
                WHERE 
                    o.vstdate BETWEEN $datestart and $dateend  AND 
                    d.did like '4%'
                GROUP BY o.icode
                ORDER BY sum_price DESC
                LIMIT 10 ";
        

        try {
            $rawData = \yii::$app->db->createCommand($sql)->queryAll();
            $rawData_graph = \yii::$app->db->createCommand($sql_graph)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }

        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => FALSE,
        ]);
        
        $dataProvider_graph = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData_graph,
            'pagination' => FALSE,
        ]);

        return $this->render('report4', [
                    'dataProvider' => $dataProvider,
                    'dataProvider_graph' => $dataProvider_graph,
                    'rawData' => $rawData,
                    'rawData_graph' => $rawData_graph,
                    'report_name' => $report_name,
                    'report_name_graph' => $report_name_graph,
                    'details' => $details,
        ]);
    }
    
    
    public function actionReport5($datestart, $dateend, $details) {
                // save log
        $this->SaveLog($this->dep_controller, 'report5', $this->getSession());

        $report_name = "รายงานมูลค่าการสั่งใช้ยาแพทย์แผนไทยทั้งหมดแยกตามแพทย์ผู้สั่ง";
        $report_name_graph = "รายงาน 10 อันดับมูลค่าการสั่งใช้ยาแพทย์แผนไทยแยกตามแพทย์ผู้สั่ง";      
        $sql = "SELECT
                       doc.name as doctor_name,sum(o.qty) as sum_qty,
                       sum(o.sum_price) as sum_price,
                       count(distinct(o.hn)) as count_hn,
                       count(distinct(o.vn)) as count_visit ,
                       count(o.hn) as count_useage
                 FROM opitemrece o
                 LEFT OUTER JOIN drugitems d ON d.icode = o.icode
                 LEFT OUTER JOIN doctor doc ON doc.code = o.doctor
                 WHERE o.vstdate BETWEEN $datestart and $dateend  AND d.did like '4%'
                 GROUP BY o.doctor
                 ORDER BY sum_price DESC ";
        
        $sql_graph = "SELECT
                        doc.name as doctor_name,sum(o.qty) as sum_qty,
                        sum(o.sum_price) as sum_price
                 FROM opitemrece o
                 LEFT OUTER JOIN drugitems d ON d.icode = o.icode
                 LEFT OUTER JOIN doctor doc ON doc.code = o.doctor
                 WHERE o.vstdate BETWEEN $datestart and $dateend  AND d.did like '4%'
                 GROUP BY o.doctor
                 ORDER BY sum_price DESC
                 LIMIT 10 ";
        
        try {
            $rawData = \yii::$app->db->createCommand($sql)->queryAll();
            $rawData_graph = \yii::$app->db->createCommand($sql_graph)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }

        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => FALSE,
        ]);
        
        $dataProvider_graph = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData_graph,
            'pagination' => FALSE,
        ]);

        return $this->render('report5', [
                    'dataProvider' => $dataProvider,
                    'dataProvider_graph' => $dataProvider_graph,
                    'rawData' => $rawData,
                    'rawData_graph' => $rawData_graph,
                    'report_name' => $report_name,
                    'report_name_graph' => $report_name_graph,
                    'details' => $details,
        ]);
    }
    
    
    public function actionReport6($datestart, $dateend, $details) {
                // save log
        $this->SaveLog($this->dep_controller, 'report6', $this->getSession());

        $report_name = "รายงานมูลค่าการสั่งใช้ยาแพทย์แผนไทยทั้งหมดแยกตามสิทธิ์การรักษา";
        $report_name_graph = "รายงาน 10 อันดับมูลค่าการสั่งใช้ยาแพทย์แผนไทยแยกตามสิทธิ์การรักษา ";      
        $sql = "SELECT
                     o.pttype,pt.name as pt_type_name,
                     sum(o.qty) as sum_qty,
                     sum(o.sum_price) as sum_price  ,
                     count(distinct(o.hn)) as count_hn,
                     count(o.hn) as count_useage
                 FROM opitemrece o
                 LEFT OUTER JOIN drugitems d ON d.icode = o.icode
                 LEFT OUTER JOIN pttype pt ON pt.pttype = o.pttype
                 WHERE o.vstdate BETWEEN $datestart and $dateend  AND d.did like '4%'
                 GROUP BY o.pttype 
                 ORDER BY sum_price DESC ";
        
        $sql_graph = "SELECT
                     o.pttype,pt.name as pt_type_name,
                     sum(o.qty) as sum_qty,
                     sum(o.sum_price) as sum_price 
                 FROM opitemrece o
                 LEFT OUTER JOIN drugitems d ON d.icode = o.icode
                 LEFT OUTER JOIN pttype pt ON pt.pttype = o.pttype
                 WHERE o.vstdate BETWEEN $datestart and $dateend  AND d.did like '4%'
                 GROUP BY o.pttype 
                 ORDER BY sum_price DESC 
                 LIMIT 10 ";
        
        try {
            $rawData = \yii::$app->db->createCommand($sql)->queryAll();
            $rawData_graph = \yii::$app->db->createCommand($sql_graph)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }

        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => FALSE,
        ]);
        
        $dataProvider_graph = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData_graph,
            'pagination' => FALSE,
        ]);
    
        return $this->render('report6', [
                    'dataProvider' => $dataProvider,
                    'dataProvider_graph' => $dataProvider_graph,
                    'rawData' => $rawData,
                    'rawData_graph' => $rawData_graph,
                    'report_name' => $report_name,
                    'report_name_graph' => $report_name_graph,
                    'details' => $details,
        ]); 
    }

    
      public function actionReport7($datestart, $dateend, $details) {
                // save log
        $this->SaveLog($this->dep_controller, 'report7', $this->getSession());

        $report_name = "รายงานการให้บริการ(หัตถการที่กำหนด)ตามเจ้าหน้าที่ให้บริการ";
        $sql = "SELECT
                    hs.health_med_service_id,hs.hn,
                    concat(pt.pname,pt.fname,'  ',pt.lname) as pt_name,
                    hs.an,
                    concat(DAY(hs.service_date),'/',MONTH(hs.service_date),'/',(YEAR(hs.service_date)+543)) as vstdate ,
                    ho.health_med_operation_item_id ,
                    hi.health_med_operation_item_name ,
                    ho.health_med_provider_id,
                    hp.health_med_provider_full_name as dorcor_name ,
                    ns.billcode,
                    v.pttype,
                    p.name as pttype_name,
                    '' as input1,
                    '' as input2,
                    '' as input3,
                    '' as input4
              FROM 
                health_med_service hs

              LEFT OUTER JOIN patient pt ON pt.hn = hs.hn
              LEFT OUTER JOIN health_med_service_operation ho ON ho.health_med_service_id = hs.health_med_service_id
              LEFT OUTER JOIN health_med_operation_item hi ON  hi.health_med_operation_item_id = ho.health_med_operation_item_id
              lEFT OUTER JOIN health_med_provider hp ON hp.health_med_provider_id = ho.health_med_provider_id
              LEFT OUTER JOIN nondrugitems ns ON ns.icode = ho.service_icode
              LEFT OUTER JOIN vn_stat v ON v.vn = hs.vn
              LEFT OUTER JOIN pttype p ON p.pttype = v.pttype
              
              WHERE
                hs.service_date between $datestart and $dateend AND
                ho.health_med_operation_item_id in (7,104,107,72,95,68,71,72,73,76) AND
                ho.service_qty >= 1

              ORDER BY
                    ho.health_med_provider_id,
                    v.vstdate, ho.health_med_operation_item_id ";
              
        
        try {
            $rawData = \yii::$app->db->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }

        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => FALSE,
        ]);

        return $this->render('report7', [
                    'dataProvider' => $dataProvider,
                    'rawData' => $rawData,
                    'report_name' => $report_name,
                    'details' => $details,
        ]);
    }
    
    
     public function actionReport8($datestart, $dateend, $details) {
        // save log
        $this->SaveLog($this->dep_controller, 'report8', $this->getSession());

        $report_name = "รายงานการลง Diag U778 ในผู้ป่วยที่ไม่ใช่สิทธิ์ UC";
        $sql = "SELECT           
                v.vn,v.hn,concat(p.pname,p.fname,'  ',p.lname) as pt_name,
                v.pdx,dx0,v.dx1,v.dx2,v.dx3,v.dx4,v.dx5,v.pttype, pp.name as pttype_name,v.vstdate

                FROM vn_stat v
                
                left outer join patient p on p.hn = v.hn
                left outer join pttype pp on pp.pttype = p.pttype

                WHERE

                 (

                  v.pdx = 'u778' or
                  v.dx0 = 'u778' or
                  v.dx1 = 'u778' or
                  v.dx2 = 'u778' or
                  v.dx3 = 'u778' or
                  v.dx4 = 'u778' or
                  v.dx5 = 'u778'

                  )

                AND v.vstdate between $datestart and $dateend
                AND v.pttype in (11,14,31) ";

        try {
            $rawData = \yii::$app->db->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }

        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => FALSE,
        ]);
        
  
        return $this->render('report8', [
                    'dataProvider' => $dataProvider,
                    'rawData' => $rawData,
                    'report_name' => $report_name,
                    'details' => $details,
        ]); 
        
        
        
    
        
    }
    
    
    public function actionReport9($datestart, $dateend, $details) {
        // save log
        $this->SaveLog($this->dep_controller, 'report9', $this->getSession());

        $report_name = "รายงานการสั่งใช้ยามะระขี้นก";
        $sql = "SELECT
                o.hn,o.vn,concat(p.pname,p.fname,'  ',p.lname) as pt_name,
                o.icode,dr.name as drug_name,o.qty,
                if(o.drugusage !='',du.shortlist,concat(s.name1,'  ',s.name2,'  ',s.name3)) as shortlist,
                concat(DAY(o.vstdate),'/',MONTH(o.vstdate),'/',(YEAR(o.vstdate)+543)) as vstdate, 
                o.doctor,d.name as doctor_name ,
                GROUP_CONCAT(DISTINCT concat('[ ',lh.order_date,'=', lo.lab_order_result, ' ]') SEPARATOR ', ')  as lab_Glucose_FBS ,
                GROUP_CONCAT(DISTINCT concat('[ ',lh2.order_date,'=', lo2.lab_order_result, ' ]') SEPARATOR ', ')  as lab_HbA1C


          FROM opitemrece  o
          LEFT OUTER JOIN patient p ON p.hn = o.hn
          LEFT OUTER JOIN doctor d  ON d.code = o.doctor

          LEFT OUTER JOIN (select lab_order_number,hn,order_date from lab_head where order_date between '2017-10-01' AND CURDATE()  AND confirm_report='Y' order by order_date desc)  as lh ON lh.hn = o.hn
          LEFT OUTER JOIN lab_order lo ON lo.lab_order_number = lh.lab_order_number  and lo.lab_items_code = '3001'
          LEFT OUTER JOIN lab_items li ON li.lab_items_code = lo.lab_items_code

          LEFT OUTER JOIN (select lab_order_number,hn,order_date from lab_head where order_date between '2017-10-01' AND CURDATE() AND confirm_report='Y' order by order_date desc)  as  lh2 ON lh2.hn = o.hn
          LEFT OUTER JOIN lab_order lo2 ON lo2.lab_order_number = lh2.lab_order_number  and lo2.lab_items_code = '48'
          LEFT OUTER JOIN lab_items li2 ON li2.lab_items_code = lo2.lab_items_code

          LEFT OUTER JOIN drugitems dr ON dr.icode = o.icode
          LEFT OUTER JOIN drugusage du ON du.drugusage = o.drugusage
          LEFT OUTER JOIN sp_use s     ON s.sp_use = o.sp_use



          WHERE
               o.vstdate BETWEEN $datestart and $dateend  AND
               o.icode = '1560012'

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
        
  
        return $this->render('report9', [
                    'dataProvider' => $dataProvider,
                    'rawData' => $rawData,
                    'report_name' => $report_name,
                    'details' => $details,
        ]); 
        
        
        
    
        
    }
    
    
    

}
