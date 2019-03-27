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

    
    
     public function actionReport3($datestart, $dateend, $details) {
                // save log
        $this->SaveLog($this->dep_controller, 'report3', $this->getSession());

        $report_name = "รายงานคนไข้คัดกรองบุหรี่";

        $sql = "SELECT
                    uh.universal_head_id,
                    concat(DAY(v.vstdate),'/',MONTH(v.vstdate),'/',(YEAR(v.vstdate)+543)) as vstdate,
                    concat(DAY(uh.entry_date),'/',MONTH(uh.entry_date),'/',(YEAR(uh.entry_date)+543)) as entry_date,  
                    uh.staff,uh.vn,uh.hn,
                    CONCAT(p.pname,p.fname,'  ',p.lname) AS pt_name,v.age_y,
                    CONCAT(p.addrpart,' หมู่.',p.moopart,' ต.',t3.name, ' อ.',t2.name,' จ.',t1.name) as address,
                    CASE  WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),2,1)  = '0' THEN 'ASTHMA'

                    WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),2,1)  = '1' THEN 'COPD'

                    WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),2,1)  = '2' THEN 'DM'

                    WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),2,1)  = '3' THEN 'DMHT'

                    WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),2,1)  = '4' THEN 'HT'

                    WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),2,1)  = '5' THEN 'MI'


                    WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),2,1)  = '6' THEN 'STROKE'


                    WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),2,1)  = '7' THEN 'NOCHRONIC'

                    WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),2,1)  = '8' THEN 'OTHERS'

                    ELSE ''  END  AS CLINIC1,




                    /* ====================== set 2 =============================*/



                    CASE  WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),4,1)  = '0' THEN 'ASTHMA'

                    WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),4,1)  = '1' THEN 'COPD'

                    WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),4,1)  = '2' THEN 'DM'

                    WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),4,1)  = '3' THEN 'DMHT'

                    WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),4,1)  = '4' THEN 'HT'

                    WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),4,1)  = '5' THEN 'MI'


                    WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),4,1)  = '6' THEN 'STROKE'


                    WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),4,1)  = '7' THEN 'NOCHRONIC'

                    WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),4,1)  = '8' THEN 'OTHERS'

                    ELSE ''  END  AS CLINIC2,




                    /* ====================== set 3  =============================*/

                    CASE  WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),6,1)  = '0' THEN 'ASTHMA'

                    WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),6,1)  = '1' THEN 'COPD'

                    WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),6,1)  = '2' THEN 'DM'

                    WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),6,1)  = '3' THEN 'DMHT'

                    WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),6,1)  = '4' THEN 'HT'

                    WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),6,1)  = '5' THEN 'MI'


                    WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),6,1)  = '6' THEN 'STROKE'


                    WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),6,1)  = '7' THEN 'NOCHRONIC'

                    WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),6,1)  = '8' THEN 'OTHERS'

                    ELSE ''  END  AS CLINIC3,



                    /* ====================== set 4 =============================*/

                     CASE  WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),8,1)  = '0' THEN 'ASTHMA'

                    WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),8,1)  = '1' THEN 'COPD'

                    WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),8,1)  = '2' THEN 'DM'

                    WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),8,1)  = '3' THEN 'DMHT'

                    WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),8,1)  = '4' THEN 'HT'

                    WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),8,1)  = '5' THEN 'MI'


                    WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),8,1)  = '6' THEN 'STROKE'


                    WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),8,1)  = '7' THEN 'NOCHRONIC'

                    WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),8,1)  = '8' THEN 'OTHERS'

                    ELSE ''  END  AS CLINIC4,



                    /* ====================== set 5=============================*/

                    CASE  WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),10,1)  = '0' THEN 'ASTHMA'

                    WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),10,1)  = '1' THEN 'COPD'

                    WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),10,1)  = '2' THEN 'DM'

                    WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),10,1)  = '3' THEN 'DMHT'

                    WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),10,1)  = '4' THEN 'HT'

                    WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),10,1)  = '5' THEN 'MI'


                    WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),10,1)  = '6' THEN 'STROKE'


                    WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),10,1)  = '7' THEN 'NOCHRONIC'

                    WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),10,1)  = '8' THEN 'OTHERS'

                    ELSE ''  END  AS CLINIC5,



                 /* ====================== set 6 =============================*/

                    CASE  WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),12,1)  = '0' THEN 'ASTHMA'

                    WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),12,1)  = '1' THEN 'COPD'

                    WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),12,1)  = '2' THEN 'DM'

                    WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),12,1)  = '3' THEN 'DMHT'

                    WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),12,1)  = '4' THEN 'HT'

                    WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),12,1)  = '5' THEN 'MI'


                    WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),12,1)  = '6' THEN 'STROKE'


                    WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),12,1)  = '7' THEN 'NOCHRONIC'

                    WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),12,1)  = '8' THEN 'OTHERS'

                    ELSE ''  END  AS CLINIC6,






                   /* ====================== set 7 =============================*/

                    CASE  WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),14,1)  = '0' THEN 'ASTHMA'

                    WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),14,1)  = '1' THEN 'COPD'

                    WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),14,1)  = '2' THEN 'DM'

                    WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),14,1)  = '3' THEN 'DMHT'

                    WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),14,1)  = '4' THEN 'HT'

                    WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),14,1)  = '5' THEN 'MI'


                    WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),14,1)  = '6' THEN 'STROKE'


                    WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),14,1)  = '7' THEN 'NOCHRONIC'

                    WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),14,1)  = '8' THEN 'OTHERS'

                    ELSE ''  END  AS CLINIC7,






                   /* ====================== set 8 =============================*/

                    CASE  WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),16,1)  = '0' THEN 'ASTHMA'

                    WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),16,1)  = '1' THEN 'COPD'

                    WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),16,1)  = '2' THEN 'DM'

                    WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),16,1)  = '3' THEN 'DMHT'

                    WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),16,1)  = '4' THEN 'HT'

                    WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),16,1)  = '5' THEN 'MI'


                    WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),16,1)  = '6' THEN 'STROKE'


                    WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),16,1)  = '7' THEN 'NOCHRONIC'

                    WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),16,1)  = '8' THEN 'OTHERS'

                    ELSE ''  END  AS CLINIC8,





                   /* ====================== set 9 =============================*/

                    CASE  WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),18,1)  = '0' THEN 'ASTHMA'

                    WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),18,1)  = '1' THEN 'COPD'

                    WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),18,1)  = '2' THEN 'DM'

                    WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),18,1)  = '3' THEN 'DMHT'

                    WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),18,1)  = '4' THEN 'HT'

                    WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),18,1)  = '5' THEN 'MI'


                    WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),18,1)  = '6' THEN 'STROKE'


                    WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),18,1)  = '7' THEN 'NOCHRONIC'

                    WHEN
                    SUBSTR((
                             select universal_item_value_text
                             from  universal_detail n where n.universal_head_id = uh.universal_head_id
                             and n.universal_item_id = '46'

                    ),18,1)  = '8' THEN 'OTHERS'

                    ELSE ''  END  AS CLINIC9,

                    /*
                  (
                    select universal_item_value_text
                    from universal_detail n where n.universal_head_id = uh.universal_head_id
                    and n.universal_item_id = '46'
                  ) as condition_0,
                  */
                  
                  if((
                    select universal_item_value_text
                    from universal_detail n where n.universal_head_id = uh.universal_head_id
                    and n.universal_item_id = '47'
                  ) is not null,  (
                    select universal_item_value_text
                    from universal_detail n where n.universal_head_id = uh.universal_head_id
                    and n.universal_item_id = '47'
                  ), '') as condition_1,

                    if((
                    select universal_item_value_text
                    from universal_detail n where n.universal_head_id = uh.universal_head_id
                    and n.universal_item_id = '48'
                  ) is not null,  (
                    select universal_item_value_text
                    from universal_detail n where n.universal_head_id = uh.universal_head_id
                    and n.universal_item_id = '48'
                  ), '') as condition_2,

                   if((
                    select universal_item_value_text
                    from universal_detail n where n.universal_head_id = uh.universal_head_id
                    and n.universal_item_id = '49'
                  ) is not null,  (
                    select universal_item_value_text
                    from universal_detail n where n.universal_head_id = uh.universal_head_id
                    and n.universal_item_id = '49'
                  ), '') as condition_3,

                   if((
                    select universal_item_value_text
                    from universal_detail n where n.universal_head_id = uh.universal_head_id
                    and n.universal_item_id = '50'
                  ) is not null,  (
                    select universal_item_value_text
                    from universal_detail n where n.universal_head_id = uh.universal_head_id
                    and n.universal_item_id = '50'
                  ), '') as condition_4,

                   if((
                    select universal_item_value_text
                    from universal_detail n where n.universal_head_id = uh.universal_head_id
                    and n.universal_item_id = '51'
                  ) is not null,  (
                    select universal_item_value_text
                    from universal_detail n where n.universal_head_id = uh.universal_head_id
                    and n.universal_item_id = '51'
                  ), '') as condition_5



                 FROM universal_head  uh
                 LEFT OUTER JOIN patient p ON p.hn = uh.hn
                 LEFT OUTER JOIN thaiaddress t1 on t1.chwpart=p.chwpart and t1.codetype='1'
                 LEFT OUTER JOIN thaiaddress t2 on t2.chwpart=p.chwpart and t2.amppart=p.amppart and t2.codetype='2'
                 LEFT OUTER JOIN thaiaddress t3 on t3.chwpart=p.chwpart and t3.amppart=p.amppart and t3.tmbpart=p.tmbpart and t3.codetype='3'
                 LEFT OUTER JOIN vn_stat v ON v.vn = uh.vn


                 WHERE
                       uh.universal_form_id = '12'

                       AND uh.entry_date BETWEEN $datestart and $dateend
                 ORDER BY uh.hn,uh.entry_date
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
       
        return $this->render('report3', [
                    'dataProvider' => $dataProvider,
                    'rawData' => $rawData,
                    'report_name' => $report_name,
                    'details' => $details,
        ]);
    }
    

    
    

}
