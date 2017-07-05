<?php

namespace frontend\controllers;

use Yii;
use frontend\components\CommonController;

class IcController extends CommonController {

    public $dep_controller = 'ic';

    public function actionReport1($datestart, $dateend, $details) {
        // save log
        $this->SaveLog($this->dep_controller, 'report1', $this->getSession());

        $report_name = "รายงานตรวจสุขภาพเจ้าหน้าที่โรงพยาบาลละแม";

        $sql = "SELECT
                    concat(p.pname,p.fname) as fname, p.lname as lname, p.hn,
                    timestampdiff(year,p.birthday,v.vstdate)  as age_y , v.vstdate,
                    v.pdx,
                    if(o.bw is not null, o.bw, '') as weight, 
                    if(o.height is not null, o.height, '') as height, 
                    if(concat(o.bps,' / ', o.bpd) is not null,concat(o.bps,' / ', o.bpd),'') as bp , 
                    if(o.bmi is not null, o.bmi, '') as bmi, 
                    if(o.waist is not null, o.waist, 0.000) as waist,
                    
                    (select lo1.lab_order_result  from lab_order lo1 where lo1.lab_order_number = lh.lab_order_number and lo1.lab_items_code = 2098     group by lo1.lab_order_number )  as CBC,
                    (select lo2.lab_order_result  from lab_order lo2 where lo2.lab_order_number = lh.lab_order_number and lo2.lab_items_code = 69       group by lo2.lab_order_number )  as  BUN,
                    (select lo3.lab_order_result  from lab_order lo3 where lo3.lab_order_number = lh.lab_order_number and lo3.lab_items_code = 3003     group by lo3.lab_order_number )  as  Creatinine,
                    (select lo4.lab_order_result  from lab_order lo4 where lo4.lab_order_number = lh.lab_order_number and lo4.lab_items_code = 3001     group by lo4.lab_order_number )  as  Glucose_FPG,
                    (select lo5.lab_order_result  from lab_order lo5 where lo5.lab_order_number = lh.lab_order_number and lo5.lab_items_code = 3014     group by lo5.lab_order_number )  as  SGOT_AST,
                    (select lo6.lab_order_result  from lab_order lo6 where lo6.lab_order_number = lh.lab_order_number and lo6.lab_items_code = 3015     group by lo6.lab_order_number )  as  SGPT_ALT,
                    (select lo7.lab_order_result  from lab_order lo7 where lo7.lab_order_number = lh.lab_order_number and lo7.lab_items_code = 3016     group by lo7.lab_order_number )  as  ALP,
                    (select lo8.lab_order_result  from lab_order lo8 where lo8.lab_order_number = lh.lab_order_number and lo8.lab_items_code = 3005     group by lo8.lab_order_number )  as  Cholesterol,
                    (select lo9.lab_order_result  from lab_order lo9 where lo9.lab_order_number = lh.lab_order_number and lo9.lab_items_code = 3007     group by lo9.lab_order_number )  as  HDL_C,
                    (select lo10.lab_order_result  from lab_order lo10 where lo10.lab_order_number = lh.lab_order_number and lo10.lab_items_code = 3008  group by lo10.lab_order_number )  as  LDL_C,
                    (select lo11.lab_order_result  from lab_order lo11 where lo11.lab_order_number = lh.lab_order_number and lo11.lab_items_code = 3006  group by lo11.lab_order_number )  as  Triglyceride,
                    (select lo12.lab_order_result  from lab_order lo12 where lo12.lab_order_number = lh.lab_order_number and lo12.lab_items_code = 48    group by lo12.lab_order_number )  as  HbA1C,
                    (select lo13.lab_order_result  from lab_order lo13 where lo13.lab_order_number = lh.lab_order_number and lo13.lab_items_code = 2176  group by lo13.lab_order_number )  as  Anti_HBs,
                    (select lo14.lab_order_result  from lab_order lo14 where lo14.lab_order_number = lh.lab_order_number and lo14.lab_items_code = 2174  group by lo14.lab_order_number )  as  HBsAg,


                    if(o.pmh is not null, o.pmh, '') as pmh ,
                    if(op.icode = 3001141,'มีตรวจ EKG','')  as EKG
                    
                FROM vn_stat v
                left outer join patient p on p.hn = v.hn
                left outer join opdscreen o on o.vn = v.vn
                left outer join lab_head lh on lh.vn = v.vn
                left outer join lab_order lo on lo.lab_order_number = lh.lab_order_number
                left outer join opitemrece op on op.vn = v.vn

                WHERE 
                     v.vstdate between $datestart and $dateend  and v.pdx = 'z000' 
                     
                and p.hn in ('4501661','5500252','5701788','5302679','5503924',
                             '5201958','5301599','4801532','5401806','5500068',
                             '5902133','5902323','5902025','5902019','4702928',
                             '4410623','4700504','4400765','4402710','4405177',
                             '4600046','4706079','4807976','4400350','4405347',
                             '4400525','4400748','5003731','4407741','4800313',
                             '4900574','4405769','4705745','4702092','4405283',
                             
                             '4600119','4807494','4901678','4504082','4903681',
                             '4900625','4403526','4500492','4800283','4401358',
                             '4708050','4509002','4407985','4600941','4404900',
                             '4401076','4400610','4400488','4408750','4400923',
                             '4404488','4401371',
                             
                             '4400915','4705318','4700946','4401948','4400832',
                             '4703808','4400513','5901945','4505377','4601594',
                             '4900672','5700203','5800611','4401055','4900448',
                             '4401685','4401351','4400422','4410886','4404574',
                             '4400625','4404291','4707053','4400245',
                             
                             '5300143','4605430','4906012','4412878','4805845',
                             '4504085','4405225','4411606','4405925','5501683',
                             '5602487','5400312','5503444 ','5404482','5302377',
                             '5202159','4505145','4902140','5805426','4905854',
                             '5902406','4404004',
                             
                             '4400866','4401363','4411337','4501714','5600142',
                             '5601740','5701253','5701307','5701494','5703871',
                             '4500732','5803220','5004287','5801707','4400023',
                             '5404784',
                             
                             '4707680','4602424','5002128','4400537','4504929',
                             '4606911','4407608','4806443','4402827','4704584',
                             '4401258','4401770','5103473','4800330','4406578',
                             '4407643','4702497','4600280','4801684','4400448',
                             '4404518','4700068',
                             
                             '5102697','5205141','5301662','4405925','4504760',
                             '4409134','5102704','4905065','4603853','5202473',
                             '4605903','5903779','4403536'
                             

                )
                
                GROUP BY v.vn
                ORDER BY v.hn,v.vstdate ";
                         

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
