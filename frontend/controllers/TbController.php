<?php

namespace frontend\controllers;

use Yii;
use frontend\components\CommonController;

class TbController extends CommonController {

    public $dep_controller = 'tb';

    public function actionReport1($datestart, $dateend, $details) {
        // save log
        $this->SaveLog($this->dep_controller, 'report1', $this->getSession());

        $report_name = "รายงานจำนวนครั้งการคัดกรองวัณโรค";

        $sql = "SELECT
                            u.universal_head_id,u.staff,u.hn,u.vn,
                            concat(DAY(u.entry_date),'/',MONTH(u.entry_date),'/',(YEAR(u.entry_date)+543)) as entry_date ,
                            (
                                   select universal_item_value_text
                                   from universal_detail n where n.universal_head_id = u.universal_head_id   and n.universal_item_id = '23'

                            ) as clinic ,
                            (
                                   select universal_item_value_text
                                   from universal_detail n where n.universal_head_id = u.universal_head_id   and n.universal_item_id = '18'

                            ) as condition_1,
                            (
                                   select universal_item_value_text
                                   from universal_detail n where n.universal_head_id = u.universal_head_id   and n.universal_item_id = '19'

                            ) as condition_2,
                            (
                                   select universal_item_value_text
                                   from universal_detail n where n.universal_head_id = u.universal_head_id   and n.universal_item_id = '20'

                            ) as condition_3,
                            (
                                   select universal_item_value_text
                                   from universal_detail n where n.universal_head_id = u.universal_head_id   and n.universal_item_id = '21'

                            ) as condition_4,
                            (
                                   select universal_item_value_text
                                   from universal_detail n where n.universal_head_id = u.universal_head_id   and n.universal_item_id = '22'

                            ) as condition_5 ,
                            
                            (
                                   select universal_item_value_text
                                   from universal_detail n where n.universal_head_id = u.universal_head_id   and n.universal_item_id = '27'

                            ) as xray_date , 
                            (
                                   select universal_item_value_text
                                   from universal_detail n where n.universal_head_id = u.universal_head_id   and n.universal_item_id = '28'

                            ) as xray_result,
                            
                            (
                                   select universal_item_value_text
                                   from universal_detail n where n.universal_head_id = u.universal_head_id   and n.universal_item_id = '29'

                            ) as lab_afb_order_1,
                            
                            (
                                   select universal_item_value_text
                                   from universal_detail n where n.universal_head_id = u.universal_head_id   and n.universal_item_id = '32'

                            ) as lab_afb_result_1,
                             (
                                   select universal_item_value_text
                                   from universal_detail n where n.universal_head_id = u.universal_head_id   and n.universal_item_id = '30'

                            ) as lab_afb_order_2,
                            
                            (
                                   select universal_item_value_text
                                   from universal_detail n where n.universal_head_id = u.universal_head_id   and n.universal_item_id = '33'

                            ) as lab_afb_result_2,
                             (
                                   select universal_item_value_text
                                   from universal_detail n where n.universal_head_id = u.universal_head_id   and n.universal_item_id = '31'

                            ) as lab_afb_order_3,
                            
                            (
                                   select universal_item_value_text
                                   from universal_detail n where n.universal_head_id = u.universal_head_id   and n.universal_item_id = '34'

                            ) as lab_afb_result_3,
                            p.addrpart,
                            p.moopart,
                            t1.name as chw_name, t2.name as amp_name,
                            t3.name as tmb_name
         

                        FROM 
                            universal_head u
                        LEFT OUTER JOIN patient p ON p.hn = u.hn
                        
                        LEFT OUTER JOIN thaiaddress t1 on t1.chwpart=p.chwpart and t1.codetype='1'                     
                        LEFT OUTER JOIN thaiaddress t2 on t2.chwpart=p.chwpart
                        and t2.amppart=p.amppart and t2.codetype='2'
                        LEFT OUTER JOIN thaiaddress t3 on t3.chwpart=p.chwpart
                        and t3.amppart=p.amppart and t3.tmbpart=p.tmbpart and t3.codetype='3'
                        

                        WHERE
                            u.universal_form_id = '8' AND 
                            u.entry_date BETWEEN $datestart and $dateend ";

        
            $sql2 = "SELECT
                            (
                                   select universal_item_value_text
                                   from universal_detail n where n.universal_head_id = u.universal_head_id   and n.universal_item_id = '23'

                            ) as clinic ,

                            count(vn) as count_vn

                             FROM
                            universal_head u
                        WHERE
                            u.universal_form_id = '8' AND 
                            u.entry_date BETWEEN $datestart and $dateend  AND

                            (
                                   select universal_item_value_text
                                   from universal_detail n where n.universal_head_id = u.universal_head_id   and n.universal_item_id = '23'

                            )  != ''


                        GROUP BY  clinic ";

        try {
            $rawData = \yii::$app->db->createCommand($sql)->queryAll();
            $rawData2 = \yii::$app->db->createCommand($sql2)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }

        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => FALSE,
        ]);
        
        $dataProvider2 = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData2,
            'pagination' => FALSE,
        ]);

        return $this->render('report1', [
                    'dataProvider' => $dataProvider,
                    'dataProvider2' => $dataProvider2,
                    'rawData' => $rawData,
                    'rawData2' => $rawData2,
                    'report_name' => $report_name,
                    'details' => $details,
        ]);
    }

}
