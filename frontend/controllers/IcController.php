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
                    v.pttype,pp.name as pttype_name,
                    v.pdx,
                    if(v.dx0 is not null, v.dx0, '') as dx0,
                    if(v.dx1 is not null, v.dx1, '') as dx1,
                    if(v.dx2 is not null, v.dx2, '') as dx2,
                    if(v.dx3 is not null, v.dx3, '') as dx3,
                    if(v.dx4 is not null, v.dx4, '') as dx4,
                    if(v.dx5 is not null, v.dx5, '') as dx5,
                    if(o.bw is not null, o.bw, '') as weight, 
                    if(o.height is not null, o.height, '') as height, 
                    if(o.bps is not null,o.bps,'') as bps , 
                    if(o.bpd is not null,o.bpd,'') as bpd ,
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
                    
                    (select if(lo15.lab_order_result is not null, 'มีตรวจ UA', '')  from lab_order lo15 where lo15.lab_order_number = lh.lab_order_number and lo15.lab_items_code = 3035  group by lo15.lab_order_number )  as  UA,
                    
                    (select  if(x.xray_items_code is not null, 'มี CXR PA', '') from xray_report  x where x.vn = v.vn and x.xray_items_code = 11  group by x.vn )  as  xray_cxr,

                    if(o.pmh is not null, o.pmh, '') as pmh ,
                    (select 'มีตรวจ EKG' from opitemrece op where op.icode = 3001141 and op.vn = v.vn)  as EKG

                    
                FROM vn_stat v
                left outer join patient p on p.hn = v.hn
                left outer join pttype pp on pp.pttype = v.pttype
                left outer join opdscreen o on o.vn = v.vn
                left outer join lab_head lh on lh.vn = v.vn
                left outer join lab_order lo on lo.lab_order_number = lh.lab_order_number
                left outer join opitemrece op on op.vn = v.vn

                WHERE 
                     v.vstdate between $datestart and $dateend  and 
                         
                    (
                        v.pdx = 'z000' 
                     )
                     
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
                             '4605903','5903779','4403536','6001579'
                             

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
    
    
    
    
    
    public function actionReport2($datestart, $dateend, $details) {
        // save log
        $this->SaveLog($this->dep_controller, 'report2', $this->getSession());

        $report_name = "รายงานการฉีด HB VACCINE(Heberbiovac HB )และผลตรวจแลป Anti-HBs เจ้าหน้าที่โรงพยาบาลละแม";

        $sql = "SELECT
                    concat(p.pname,p.fname) as fname, p.lname as lname,
                    p.hn,
                   (select o1.vstdate from opitemrece o1 where o1.hn= p.hn and o1.icode in ('1460182','1460181') and o1.vstdate between $datestart and $dateend order by o1.vstdate limit 0,1 ) as hb1 ,
                   (select o2.vstdate from opitemrece o2 where o2.hn= p.hn and o2.icode  = '1460182' and o2.vstdate between $datestart and $dateend order by o2.vstdate limit 1,1 ) as hb2 ,
                   (select o3.vstdate from opitemrece o3 where o3.hn= p.hn and o3.icode = '1460182' and o3.vstdate between $datestart and $dateend order by o3.vstdate limit 2,1 ) as hb3 ,
                   (select v1.pttype from vn_stat v1 where v1.hn= p.hn and v1.vstdate between $datestart and $dateend order by v1.vstdate desc limit 0,1 ) as pttype ,
                   (
                        select 
                            pp.name 
                        from vn_stat v2 
                        left outer join pttype pp on pp.pttype = v2.pttype
                        where v2.hn = p.hn and v2.vstdate between $datestart and $dateend 
                        order by v2.vstdate desc limit 0,1 
                    ) as pttype_name ,

                   (
                           select
                              lh.order_date
                           from lab_head lh
                           left outer join lab_order lo on lo.lab_order_number = lh.lab_order_number
                           where lo.lab_items_code = 2176  and lh.order_date between $datestart and $dateend and lh.hn = p.hn
                           order by lh.order_date desc limit 0,1

                   ) as lab_order_date,

                      (
                           select
                              lo.lab_order_result
                           from lab_head lh
                           left outer join lab_order lo on lo.lab_order_number = lh.lab_order_number
                           where lo.lab_items_code = 2176  and lh.order_date between $datestart and $dateend and lh.hn = p.hn
                           order by lh.order_date desc limit 0,1

                   ) as lab_order_result

             FROM patient p

             WHERE  p.hn in ('4501661','5500252','5701788','5302679','5503924',
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
                             '4605903','5903779','4403536','6001579'
                             

                )

             GROUP BY p.hn
             ORDER BY p.hn ";
                         

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

        $report_name = "รายงานตรวจสอบ แลป Culture คนไข้ IPD";

        $sql = "SELECT
                    a.hn,
                    a.an,
                    concat(pt.pname,pt.fname) as fname,
                    pt.lname,
                    a.age_y,
                    a.regdate,
                    a.pttype,
                    pp.name as pttype_name,
                    a.pdx,
                    a.dx0,a.dx1,a.dx2,a.dx3,a.dx4,a.dx5,
                    if(lhe.lab_items_code = '3166', lhe.order_date,'') as hemo_cs,
                    if(lhe.lab_items_code = '3259', lhe.order_date,'') as pus_cs,
                    if(lhe.lab_items_code = '3252', lhe.order_date,'') as sputum_cs,
                    if(lhe.lab_items_code = '3213', lhe.order_date,'') as stool_cs,
                    if(lhe.lab_items_code = '3250', lhe.order_date,'') as urine_cs,
                    a.dchdate,
                    a.admdate

              FROM an_stat a
              left outer join patient pt on pt.hn = a.hn
              left outer join pttype pp on pp.pttype = a.pttype

              left outer join (

                   select lh.vn,lh.order_date,lo.lab_order_number, lo.lab_items_code,lo.lab_order_result
                   from lab_head lh
                   left outer join lab_order lo on lo.lab_order_number = lh.lab_order_number
                   where lh.order_date between  $datestart and $dateend  and lo.lab_items_code in ('3166','3259','3252','3213','3250')

              ) lhe on lhe.vn = a.an


              WHERE a.dchdate between $datestart and $dateend   
              GROUP BY a.an ORDER BY a.regdate ";
                         

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
        $this->SaveLog($this->dep_controller, 'report5', $this->getSession());

        $report_name = "รายงานผลการใช้ยาปฏิชีวนะ ในผู้ป่วยติดเชื้อดื้อยา";

        $sql = " select
                o.hn,o.an,concat(p.pname,p.fname, '  ',p.lname) as pt_name,a.age_y,
                p.addrpart,p.moopart,
                th.full_name,
                o.vstdate,o.rxdate,
                a.regdate,a.admdate,a.dchdate,
                a.pdx ,a.dx0,a.dx1,a.dx2,a.dx3,a.dx4,a.dx5,
                d.name as drug_name,
                if(lhe.lab_items_code = '3166', lhe.order_date,'') as hemo_cs,
                if(lhe.lab_items_code = '3259', lhe.order_date,'') as pus_cs,
                if(lhe.lab_items_code = '3250', lhe.order_date,'') as urine_cs,
                if(lhe.lab_items_code = '3252', lhe.order_date,'') as sputum_cs,
                if(lhe.lab_items_code = '3213', lhe.order_date,'') as stool_cs

                    
                from opitemrece  o
                left outer join an_stat a on a.an = o.an
                left outer join patient p on p.hn = o.hn
                left outer join thaiaddress th on th.addressid = concat(p.chwpart,p.amppart,p.tmbpart)
                left outer join drugitems d on d.icode = o.icode
              
              left outer join (
                   select lh.vn,lh.order_date,lo.lab_order_number, lo.lab_items_code,lo.lab_order_result
                   from lab_head lh
                   left outer join lab_order lo on lo.lab_order_number = lh.lab_order_number
                   where lh.order_date between  $datestart and $dateend  and lo.lab_items_code in ('3166','3259','3250','3252','3213')
              ) lhe on lhe.vn = a.an
              
                where o.icode in ('1560011','1580019','1590011','1560013')
                and a.dchdate between $datestart and $dateend 
                order by  o.hn ,o.vstdate ";
        
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

    
    
    public function actionReport5($datestart, $dateend, $details) {
        // save log
        $this->SaveLog($this->dep_controller, 'report5', $this->getSession());

        $report_name = "รายงานประวัติการมารับบริการของเจ้าหน้าที่โรงพยาบาล";

        $sql = "SELECT
                    concat(p.pname,p.fname) as fname, p.lname as lname, p.hn,
                    timestampdiff(year,p.birthday,v.vstdate)  as age_y ,
                    concat(DAY(v.vstdate),'/',MONTH(v.vstdate),'/',(YEAR(v.vstdate)+543)) as vstdate,
                    v.pttype,pp.name as pttype_name,
                    v.pdx,
                    if(v.dx0 is not null, v.dx0, '') as dx0,
                    if(v.dx1 is not null, v.dx1, '') as dx1,
                    if(v.dx2 is not null, v.dx2, '') as dx2,
                    if(v.dx3 is not null, v.dx3, '') as dx3,
                    if(v.dx4 is not null, v.dx4, '') as dx4,
                    if(v.dx5 is not null, v.dx5, '') as dx5
                    
                    
                FROM vn_stat v
                left outer join patient p on p.hn = v.hn
                left outer join pttype pp on pp.pttype = v.pttype
                left outer join opdscreen o on o.vn = v.vn
                left outer join lab_head lh on lh.vn = v.vn
                left outer join lab_order lo on lo.lab_order_number = lh.lab_order_number
                left outer join opitemrece op on op.vn = v.vn

                WHERE 
                     v.vstdate between $datestart and $dateend  
                         
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
                             '4605903','5903779','4403536','6001579'
                             

                )
                AND 
                (
                    ( 
                      v.pdx between 'A150' and 'A199' or
                      v.dx0 between 'A150' and 'A199' or
                      v.dx1 between 'A150' and 'A199' or
                      v.dx2 between 'A150' and 'A199' or
                      v.dx3 between 'A150' and 'A199' or
                      v.dx4 between 'A150' and 'A199' or
                      v.dx5 between 'A150' and 'A199'
                     )
                     OR
                     ( 
                      v.pdx between 'B010' and 'B019' or
                      v.dx0 between 'B010' and 'B019' or
                      v.dx1 between 'B010' and 'B019' or
                      v.dx2 between 'B010' and 'B019' or
                      v.dx3 between 'B010' and 'B019' or
                      v.dx4 between 'B010' and 'B019' or
                      v.dx5 between 'B010' and 'B019'
                     )
                      OR
                     ( 
                      v.pdx between 'B050' and 'B059' or
                      v.dx0 between 'B050' and 'B059' or
                      v.dx1 between 'B050' and 'B059' or
                      v.dx2 between 'B050' and 'B059' or
                      v.dx3 between 'B050' and 'B059' or
                      v.dx4 between 'B050' and 'B059' or
                      v.dx5 between 'B050' and 'B059'
                     )
                     OR
                     ( 
                      v.pdx between 'J00' and 'J069' or
                      v.dx0 between 'J00' and 'J069' or
                      v.dx1 between 'J00' and 'J069' or
                      v.dx2 between 'J00' and 'J069' or
                      v.dx3 between 'J00' and 'J069' or
                      v.dx4 between 'J00' and 'J069' or
                      v.dx5 between 'J00' and 'J069'
                     )
                       OR
                     ( 
                      v.pdx between 'H100' and 'H109' or
                      v.dx0 between 'H100' and 'H109' or
                      v.dx1 between 'H100' and 'H109' or
                      v.dx2 between 'H100' and 'H109' or
                      v.dx3 between 'H100' and 'H109' or
                      v.dx4 between 'H100' and 'H109' or
                      v.dx5 between 'H100' and 'H109'
                     )
                      OR
                     ( 
                      v.pdx between 'B180' and 'B1819' or
                      v.dx0 between 'B180' and 'B1819' or
                      v.dx1 between 'B180' and 'B1819' or
                      v.dx2 between 'B180' and 'B1819' or
                      v.dx3 between 'B180' and 'B1819' or
                      v.dx4 between 'B180' and 'B1819' or
                      v.dx5 between 'B180' and 'B1819'
                     )
                      OR
                     ( 
                      v.pdx in ('B171','B182') or
                      v.dx0 in ('B171','B182') or
                      v.dx1 in ('B171','B182') or
                      v.dx2 in ('B171','B182') or
                      v.dx3 in ('B171','B182') or
                      v.dx4 in ('B171','B182') or
                      v.dx5 in ('B171','B182')
                     )  
                     OR
                     ( 
                      v.pdx between 'A000' and 'A099' or
                      v.dx0 between 'A000' and 'A099' or
                      v.dx1 between 'A000' and 'A099' or
                      v.dx2 between 'A000' and 'A099' or
                      v.dx3 between 'A000' and 'A099' or
                      v.dx4 between 'A000' and 'A099' or
                      v.dx5 between 'A000' and 'A099'
                     )
                       OR
                     ( 
                      v.pdx between 'B060' and 'B069' or
                      v.dx0 between 'B060' and 'B069' or
                      v.dx1 between 'B060' and 'B069' or
                      v.dx2 between 'B060' and 'B069' or
                      v.dx3 between 'B060' and 'B069' or
                      v.dx4 between 'B060' and 'B069' or
                      v.dx5 between 'B060' and 'B069'
                     )
                      OR
                     ( 
                      v.pdx between 'B000' and 'B009' or
                      v.dx0 between 'B000' and 'B009' or
                      v.dx1 between 'B000' and 'B009' or
                      v.dx2 between 'B000' and 'B009' or
                      v.dx3 between 'B000' and 'B009' or
                      v.dx4 between 'B000' and 'B009' or
                      v.dx5 between 'B000' and 'B009'
                     ) 
                     OR
                     ( 
                      v.pdx between 'B020' and 'B029' or
                      v.dx0 between 'B020' and 'B029' or
                      v.dx1 between 'B020' and 'B029' or
                      v.dx2 between 'B020' and 'B029' or
                      v.dx3 between 'B020' and 'B029' or
                      v.dx4 between 'B020' and 'B029' or
                      v.dx5 between 'B020' and 'B029'
                     )
                     
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

        return $this->render('report5', [
                    'dataProvider' => $dataProvider,
                    'rawData' => $rawData,
                    'report_name' => $report_name,
                    'details' => $details,
        ]);
    }
    
 

}
