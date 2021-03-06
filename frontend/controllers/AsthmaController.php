<?php

namespace frontend\controllers;

use Yii;
use frontend\components\CommonController;


class AsthmaController extends CommonController {
    public $dep_controller = 'asthma';

    public function actionReport1($uclinic) {
                // save log
        $this->SaveLog($this->dep_controller, 'report1', $this->getSession());
        
        if ($uclinic != "") { // เริ่มต้นตรวจสอบประเภทคนไข้ในคลินิก
            // ตัวแปร $get_type เอาไว้ตรวจสอบว่าเป็นคนไข้ dm หรือ dm with ht
            // ตัวแปร $report_name เอาไว้ไปแสดงชื่อรายงานในหน้า view
            if ($uclinic == 1) {
                $cryteria = '';
                $report_name = 'รายงานสรุปคนไข้ทะเบียน Asthma ทั้งหมด(รวมที่เป็น DM,HT)';
            } else if ($uclinic == 2) {
                $cryteria = " AND cm.hn  not in(select hn from clinicmember where clinic=(select sys_value from sys_var where sys_name='dm_clinic_code'))
                              AND cm.hn  not  in(select hn from clinicmember where clinic=(select sys_value from sys_var where sys_name='ht_clinic_code')) ";                                 
                $report_name = 'รายงานสรุปคนไข้ทะเบียน ASthma อย่างเดียว(ไม่รวมที่เป็น DM,HT)';
                
            } else if ($uclinic == 3) {
                   $cryteria = " AND cm.hn  in(select hn from clinicmember where clinic=(select sys_value from sys_var where sys_name='dm_clinic_code'))
                                 AND cm.hn  not  in(select hn from clinicmember where clinic=(select sys_value from sys_var where sys_name='ht_clinic_code')) ";                 
                $report_name = 'รายงานสรุปคนไข้ทะเบียน Asthma WITH DM (WITH DM, NO HT)';
            } else if ($uclinic == 4) {
                   $cryteria = " AND cm.hn  not in(select hn from clinicmember where clinic=(select sys_value from sys_var where sys_name='dm_clinic_code'))
                                 AND cm.hn   in(select hn from clinicmember where clinic=(select sys_value from sys_var where sys_name='ht_clinic_code')) ";                 
                $report_name = 'รายงานสรุปคนไข้ทะเบียน Asthma WITH HT (WITH HT, NO DM)';
            }
        }

        $sql = "         
                SELECT 
                th.addressid,th.name as tumbol , th.full_name as address,count(distinct(cm.hn)) as count_hn
                FROM clinicmember  cm
                LEFT OUTER JOIN clinic_member_status cs on cs.clinic_member_status_id=cm.clinic_member_status_id
                LEFT OUTER JOIN provis_typedis pd on pd.code=cs.provis_typedis
                LEFT OUTER JOIN patient pt ON pt.hn = cm.hn
                LEFT OUTER JOIN thaiaddress th ON th.addressid = concat(pt.chwpart,pt.amppart,pt.tmbpart)
                WHERE 
                    cm.clinic = '019'
                
                $cryteria    

                AND pd.code in('3','03')
                GROUP BY th.addressid 
                ORDER BY count(distinct(cm.hn)) DESC ";

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
                    'uclinic' => $uclinic,
                    'report_name' => $report_name,
        ]);
    }

    /* รายงานสรุปคนไข้ทะเบียนคลินิกหอบหืดแยกตามที่อยู่ (แสดงรายชื่อคนไข้) */
    public function actionReport2($addressid, $uclinic) {
                // save log
        $this->SaveLog($this->dep_controller, 'report2', $this->getSession());

        $report_name = "รายงานสรุปคนไข้ทะเบียนคลินิกหอบหืดแยกตามที่อยู่";
        
         if ($uclinic != "") { // เริ่มต้นตรวจสอบประเภทคนไข้ในคลินิก
            // ตัวแปร $get_type เอาไว้ตรวจสอบว่าเป็นคนไข้ dm หรือ dm with ht
            // ตัวแปร $report_name เอาไว้ไปแสดงชื่อรายงานในหน้า view
            if ($uclinic == 1) {
                $cryteria = '';
                $report_name = 'รายงานสรุปคนไข้ทะเบียน Asthma ทั้งหมด(รวมที่เป็น DM,HT)';
            } else if ($uclinic == 2) {
                $cryteria = " AND cm.hn  not in(select hn from clinicmember where clinic=(select sys_value from sys_var where sys_name='dm_clinic_code'))
                              AND cm.hn  not  in(select hn from clinicmember where clinic=(select sys_value from sys_var where sys_name='ht_clinic_code')) ";                                 
                $report_name = 'รายงานสรุปคนไข้ทะเบียน ASthma อย่างเดียว(ไม่รวมที่เป็น DM,HT)';
                
            } else if ($uclinic == 3) {
                   $cryteria = " AND cm.hn  in(select hn from clinicmember where clinic=(select sys_value from sys_var where sys_name='dm_clinic_code'))
                                 AND cm.hn  not  in(select hn from clinicmember where clinic=(select sys_value from sys_var where sys_name='ht_clinic_code')) ";                 
                $report_name = 'รายงานสรุปคนไข้ทะเบียน Asthma WITH DM (WITH DM, NO HT)';
            } else if ($uclinic == 4) {
                   $cryteria = " AND cm.hn  not in(select hn from clinicmember where clinic=(select sys_value from sys_var where sys_name='dm_clinic_code'))
                                 AND cm.hn   in(select hn from clinicmember where clinic=(select sys_value from sys_var where sys_name='ht_clinic_code')) ";                 
                $report_name = 'รายงานสรุปคนไข้ทะเบียน Asthma WITH HT (WITH HT, NO DM)';
            }
        }

        $sql = "         
                SELECT
                pt.hn as hn,concat(pt.pname,pt.fname,'  ',pt.lname) as pt_name,
                concat( timestampdiff(year,pt.birthday,now()), ' ปี') as age_y,
                pt.cid,cm.regdate,cm.begin_year,
                concat(pt.addrpart,' ม.',pt.moopart,' ',th.full_name) address,
                pt.moopart
                FROM clinicmember  cm
                LEFT OUTER JOIN clinic_member_status cs on cs.clinic_member_status_id=cm.clinic_member_status_id
                LEFT OUTER JOIN provis_typedis pd on pd.code=cs.provis_typedis
                LEFT OUTER JOIN patient pt ON pt.hn = cm.hn
                LEFT OUTER JOIN thaiaddress th ON th.addressid = concat(pt.chwpart,pt.amppart,pt.tmbpart)
                WHERE 
                     cm.clinic = '019'
                     
                $cryteria 
                        
                AND pd.code in('3','03')
                AND concat(pt.chwpart,pt.amppart,pt.tmbpart) = '$addressid'
                GROUP BY pt.hn     
                ORDER BY pt.moopart,age_y

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
                    'report_name' => $report_name,
        ]);
    }
    
    
    
    public function actionReport3($datestart, $dateend, $details) {
                // save log
        $this->SaveLog($this->dep_controller, 'report3', $this->getSession());

        $report_name = "รายงานจำนวนคนไข้คลินิกหอบหืด ได้รับการคัดกรองการสูบบุหรี่-ดื่มสุรา";

        $sql = "select os.hn, concat(pt.pname,pt.fname,'  ',pt.lname) as pt_name,v.age_y as age_y,
                s.name as sex, os.bmi, st.smoking_type_name ,dt.drinking_type_name, 
                v.moopart,t.full_name as address, os.vstdate

from opdscreen os
left outer join vn_stat v on v.vn=os.vn
left outer join smoking_type st on st.smoking_type_id=os.smoking_type_id
left outer join drinking_type dt on dt.drinking_type_id=os.drinking_type_id
left outer join patient pt on pt.hn=os.hn
left outer join clinicmember cm on cm.hn=os.hn
left outer join clinic_member_status cs on cs.clinic_member_status_id=cm.clinic_member_status_id
left outer join provis_typedis pd on pd.code=cs.provis_typedis
left OUTER join thaiaddress t on t.addressid=v.aid
left outer join sex s on s.code = pt.sex

where 
	os.vstdate between $datestart and $dateend and  os.smoking_type_id !=0 and dt.drinking_type_id !=0 
and 
	cm.clinic = '019'
group by os.hn
order by v.aid, v.moopart, os.hn, os.vstdate ";

        try {
            $rawData = \yii::$app->db->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }

        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => False,
        ]);

        return $this->render('report3', [
                    'dataProvider' => $dataProvider,
                    'report_name' => $report_name,
                    'details' => $details,
        ]);
    } // จบ function
    
    
    
     public function actionReport4($datestart, $dateend, $details) {
                         // save log
        $this->SaveLog($this->dep_controller, 'report4', $this->getSession());

        $report_name = "รายงานจำนวนคนไข้คลินิกหอบหืด ได้รับบริการที่ห้องฉุกเฉิน(นับเป็นคน)";

        $sql = "select
v.hn,concat(p.pname,p.fname,'  ',p.lname) as pt_name,
v.age_y,s.name as sex, v.pdx,v.dx0,v.dx1,v.dx2,v.dx3,v.dx4,v.dx5 ,c.clinic,v.vstdate,
v.moopart,t.full_name as address

from vn_stat  v

left outer join clinicmember c on c.hn = v.hn
left outer join clinic_member_status cs on cs.clinic_member_status_id=c.clinic_member_status_id
left outer join provis_typedis pd on pd.code=cs.provis_typedis
right outer join er_regist e on e.vn = v.vn
left outer join patient p on p.hn = v.hn
left OUTER join thaiaddress t on t.addressid=v.aid
left outer join sex s on s.code = p.sex

where 
v.vstdate between $datestart and $dateend
and c.clinic = '019' /* and c.clinic_member_status_id=1 */
and v.pdx between 'J450' and 'J46'

group by v.hn
order by v.aid, v.moopart, v.hn, v.vstdate ";

        try {
            $rawData = \yii::$app->db->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }

        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => False,
        ]);

        
        return $this->render('report4', [
                    'dataProvider' => $dataProvider,
                    'report_name' => $report_name,
                    'details' => $details,
        ]);
    } // จบ function
    
       
       public function actionReport5($datestart, $dateend, $details) {
                           // save log
        $this->SaveLog($this->dep_controller, 'report5', $this->getSession());

        $report_name = "รายงานจำนวนคนไข้คลินิกหอบหืด ได้รับการ Admit";

        $sql = "select o.hn,o.an,concat(p.pname,p.fname,'  ',p.lname) as pt_name,v.age_y,s.name as sex,
            v.age_y,s.name as sex,o.vstdate, v.moopart,t.full_name as address

        from ovst  o

        left outer join clinicmember c on c.hn = o.hn
        left outer join patient p on p.hn = o.hn
        left outer join vn_stat v on v.vn = o.vn
        left OUTER join thaiaddress t on t.addressid=v.aid
        left outer join sex s on s.code = p.sex
        left outer join an_stat a on  a.an = o.an

        where a.dchdate between $datestart and $dateend
        and o.an  != ''  and c.clinic='019' 
        and a.pdx between 'J450'  and 'J46'
        group by o.hn
        order by v.aid, v.moopart, v.hn, v.vstdate  ";

        try {
            $rawData = \yii::$app->db->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }

        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => False,
        ]);

        
        return $this->render('report5', [
                    'dataProvider' => $dataProvider,
                    'report_name' => $report_name,
                    'details' => $details,
        ]);
    } // จบ function
    
     
       public function actionReport6($datestart, $dateend, $details) {
                                      // save log
        $this->SaveLog($this->dep_controller, 'report6', $this->getSession());

        $report_name = "รายงานจำนวนคนไข้คลินิกหอบหืด Re-visit ภายใน 48 ชั่วโมง ที่ OPD";

        $sql = "select
v.hn,v.vstdate,v.age_y,
concat(p.pname,p.fname,'  ',p.lname) as pt_name ,
v.pdx,v.dx0 ,v.dx1,v.dx2,v.dx3,v.dx4,v.dx5,
 v.moopart,t.full_name as address

from vn_stat  v

left outer join clinicmember c on c.hn = v.hn
left outer join patient p on p.hn = v.hn
left outer join spclty s on s.spclty = v.spclty
left OUTER join thaiaddress t on t.addressid=v.aid
left outer join sex se on se.code = p.sex

where c.clinic = '019'  /* and c.clinic_member_status_id='1' */  and v.old_diagnosis = 'Y'
and v.lastvisit_hour <= 48
and v.vstdate between $datestart and $dateend
and  v.vn  not in (select e.vn from er_regist e )
and v.pdx between 'J450'  and 'J46'
group by v.hn
order by v.aid, v.moopart, v.hn, v.vstdate ";
        

        try {
            $rawData = \yii::$app->db->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }

        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => False,
        ]);

        
        return $this->render('report6', [
                    'dataProvider' => $dataProvider,
                    'report_name' => $report_name,
                    'details' => $details,
        ]);
    } // จบ function
    
     
    
    
       public function actionReport7($datestart, $dateend, $details) {
                                                 // save log
        $this->SaveLog($this->dep_controller, 'report7', $this->getSession());


        $report_name = "รายงานจำนวนคนไข้คลินิกหอบหืด Re-visit ภายใน 48 ชั่วโมง ที่ ER";

        $sql = "select  v.hn,v.vstdate,
                concat(p.pname,p.fname,'  ',p.lname) as pt_name,v.age_y, s.name,
                v.pdx,v.dx0 ,v.dx1,v.dx2,v.dx3,v.dx4,v.dx5,
                v.moopart,t.full_name as address


from vn_stat  v


left outer join clinicmember c on c.hn = v.hn
left outer join patient p on p.hn = v.hn
left outer join spclty s on s.spclty = v.spclty
right outer join  er_regist e on e.vn = v.vn
left OUTER join thaiaddress t on t.addressid=v.aid
left outer join sex se on se.code = p.sex

where c.clinic = '019'  /*and c.clinic_member_status_id='1'*/  
and v.lastvisit_hour <= 48 and v.old_diagnosis = 'Y'
and v.vstdate between $datestart and $dateend
and v.pdx between 'J450'  and 'J46'

group by v.hn
order by v.aid, v.moopart, v.hn, v.vstdate  ";
        

        try {
            $rawData = \yii::$app->db->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }

        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => False,
        ]);

        
        return $this->render('report7', [
                    'dataProvider' => $dataProvider,
                    'report_name' => $report_name,
                    'details' => $details,
        ]);
    } // จบ function
    

    
    
    
       public function actionReport8($datestart, $dateend, $details) {
        // save log
        $this->SaveLog($this->dep_controller, 'report8', $this->getSession());


        $report_name = "รายงานจำนวนคนไข้คลินิกหอบหืด Re-admit ภายใน 28 วัน";
               
        $sql = "select o.hn,concat(p.pname,p.fname,'  ',p.lname) as pt_name,se.name,v.age_y,
            o.vstdate ,a.pdx  , a.old_diagnosis,
            v.moopart,t.full_name as address

from ovst  o
left outer join clinicmember c on c.hn = o.hn
left outer join patient p on p.hn = o.hn
left outer join vn_stat v on v.vn = o.vn
left outer join an_stat a on a.an  = o.an
left outer join thaiaddress t on t.addressid=v.aid
left outer join sex se on se.code = p.sex

where o.vstdate between $datestart and $dateend
and o.an  != ''  and c.clinic='019' /* and c.clinic_member_status_id='1' */
and a.old_diagnosis = 'Y'
and a.pdx between 'J450'  and 'J46'
and a.lastvisit<=28

group by o.hn ";
        

        try {
            $rawData = \yii::$app->db->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }

        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => False,
        ]);

        
        return $this->render('report8', [
                    'dataProvider' => $dataProvider,
                    'report_name' => $report_name,
                    'details' => $details,
        ]);
    } // จบ function
    
    
     public function actionReport9($datestart, $dateend, $details) {
                        // save log
            $this->SaveLog($this->dep_controller, 'report9', $this->getSession());     
            $report_name = 'รายงานจำนวนคนไข้ในคลินิก Asthma ได้รับการวินิจฉัย j450-j46 ทั้งโรงพยาบาล (รายคน)';
             
            $sql = "SELECT
                            a.hn,a.pt_name
                    FROM
                     (
                            select distinct(v.hn) ,concat(p.pname,p.fname,'  ',p.lname) as pt_name
                                from vn_stat v
                                left outer join patient p on p.hn = v.hn
                                left outer join clinicmember c on c.hn = v.hn
                                where v.vstdate between $datestart and $dateend   and
                                (
                                      v.pdx between 'J450'  and 'J46' or
                                      v.dx0 between 'J450'  and 'J46' or
                                      v.dx1 between 'J450'  and 'J46' or
                                      v.dx2 between 'J450'  and 'J46' or
                                      v.dx3 between 'J450'  and 'J46' or
                                      v.dx4 between 'J450'  and 'J46' or
                                      v.dx5 between 'J450'  and 'J46'
                                ) and c.clinic = '019'
                        union  all
                                select distinct(v.hn) ,concat(p.pname,p.fname,'  ',p.lname) as pt_name
                                from an_stat v
                                left outer join patient p on p.hn = v.hn
                                left outer join clinicmember c on c.hn = v.hn 
                                where v.dchdate between $datestart and $dateend  and

                                (
                                      v.pdx between 'J450'  and 'J46' or
                                      v.dx0 between 'J450'  and 'J46' or
                                      v.dx1 between 'J450'  and 'J46' or
                                      v.dx2 between 'J450'  and 'J46' or
                                      v.dx3 between 'J450'  and 'J46' or
                                      v.dx4 between 'J450'  and 'J46' or
                                      v.dx5 between 'J450'  and 'J46'
                                )  and c.clinic = '019'

                             )   a

                           GROUP BY a.hn   ";
                         
                  
            try {
                $rawData = \yii::$app->db->createCommand($sql)->queryAll();
            } catch (\yii\db\Exception $e) {
                throw new \yii\web\ConflictHttpException('sql error');
            }

            $dataProvider = new \yii\data\ArrayDataProvider([
                'allModels' => $rawData,
                'pagination' => False,
            ]);


           return $this->render('report9', [
                        'dataProvider' => $dataProvider,
                        'report_name' => $report_name,
                        'details' => $details,
  
            ]); 
                     
        } // จบ function
        
        
        
          public function actionReport10($datestart, $dateend, $details) {
                                      // save log
            $this->SaveLog($this->dep_controller, 'report10', $this->getSession());

            $report_name = "รายงานจำนวนคนไข้ในคลินิกหอบหืด (OPD+ER) Re-visit ภายใน 48 ชั่วโมง";

            $sql = "SELECT
                        v.hn,v.vstdate,v.age_y,
                        concat(p.pname,p.fname,'  ',p.lname) as pt_name ,
                        v.pdx,v.dx0 ,v.dx1,v.dx2,v.dx3,v.dx4,v.dx5,
                        v.moopart,t.full_name as address
                    FROM vn_stat  v

                    left outer join clinicmember c on c.hn = v.hn
                    left outer join patient p on p.hn = v.hn
                    left outer join spclty s on s.spclty = v.spclty
                    left OUTER join thaiaddress t on t.addressid=v.aid
                    left outer join sex se on se.code = p.sex

                    WHERE 
                        c.clinic = '019'   
                        and v.old_diagnosis = 'Y'
                        and v.lastvisit_hour <= 48
                        and v.vstdate between $datestart and $dateend
                        and v.pdx between 'J450'  and 'J46'
                    GROUP BY v.hn
                    ORDER BY v.aid, v.moopart, v.hn, v.vstdate ";


        try {
            $rawData = \yii::$app->db->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }

        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => False,
        ]);

        
        return $this->render('report10', [
                    'dataProvider' => $dataProvider,
                    'report_name' => $report_name,
                    'details' => $details,
        ]);
    } // จบ function
    
     
    
    public function actionReport11($datestart, $dateend, $details) {
                        // save log
            $this->SaveLog($this->dep_controller, 'report11', $this->getSession());
                                       
            $report_name = "รายงานจำนวนคนไข้ในคลินิกหอบหืด ที่มีรหัสวินิจฉัย j46 (OPD+ER) (รายคน)";
 
            $sql = "SELECT
                        v.hn ,concat(p.pname,p.fname,'  ',p.lname) as pt_name,
                        v.age_y
                    FROM vn_stat v
                    LEFT OUTER JOIN patient p on p.hn = v.hn
                    LEFT OUTER JOIN clinicmember c on c.hn = v.hn       
                    WHERE 
                        v.vstdate between $datestart and $dateend  
                        and v.pdx = 'j46'   
                        and c.clinic = '019'
                    GROUP BY v.hn ";
                              


                      try {
                          $rawData = \yii::$app->db->createCommand($sql)->queryAll();
                      } catch (\yii\db\Exception $e) {
                          throw new \yii\web\ConflictHttpException('sql error');
                      }

                      $dataProvider = new \yii\data\ArrayDataProvider([
                          'allModels' => $rawData,
                          'pagination' => False,
                      ]);


                     return $this->render('report11', [
                                  'dataProvider' => $dataProvider,
                                  'report_name' => $report_name,
                                  'details' => $details,

                           ]); 

            } // จบ function
     
        
        
     public function actionReport12($uclinic, $datestart, $dateend, $details) {
        // save log
        $this->SaveLog($this->dep_controller, 'report12', $this->getSession());

        $report_name = 'คนไข้ Asthma มีการ Refer ที่ ER';

        if ($uclinic != "") {

            if ($uclinic == 1) {
                $join_opd = ' ';
                $criteria = ' ';
                $report_name = 'คนไข้ Asthma มีการ Refer ที่ ER (ยอดคนไข้ทั่วไป + คนไข้ใน clinic asthma)';
            } else if ($uclinic == 2) {
                $join_opd = ' left outer join clinicmember c on c.hn = v.hn ';
                $criteria = ' and c.clinic = "019" ';
                $report_name = 'คนไข้ Asthma มีการ Refer ที่ ER (เฉพาะคนไข้ใน clinic asthma)';
            }
                  
            $sql = "
                    SELECT
                        v.hn,CONCAT(pt.pname,pt.fname,'  ',pt.lname) as pt_name,
                        er.vn,
                        concat(DAY(ro.refer_date),'/',MONTH(ro.refer_date),'/',(YEAR(ro.refer_date)+543)) as refer_date_thai,
                        ro.refer_type,ro.refer_point,ro.department,
                        v.pdx,
                        CONCAT(
                            if(v.dx0 is not null,concat(v.dx0,'   '),' '),
                            if(v.dx1 is not null,concat(v.dx1,'   '),' '),
                            if(v.dx2 is not null,concat(v.dx2,'   '),' '),
                            if(v.dx3 is not null,concat(v.dx3,'   '),' '),
                            if(v.dx4 is not null,concat(v.dx4,'   '),' '),
                            if(v.dx5 is not null,concat(v.dx5,'   '),' ')
                        )  as second_diag,
                        ov.ovstost, os.name as ovst_name
                    FROM
                        referout ro
                    RIGHT OUTER JOIN er_regist er ON ro.vn = er.vn
                    LEFT  OUTER JOIN vn_stat v ON v.vn = ro.vn
                    LEFT OUTER JOIN ovst ov ON ov.vn = v.vn
                    LEFT OUTER JOIN ovstost os ON os.ovstost = ov.ovstost
                    LEFT OUTER JOIN patient pt ON pt.hn =v.hn
                    $join_opd
                    WHERE
                         ro.refer_date BETWEEN $datestart AND $dateend
                    AND

                         (
                               v.pdx BETWEEN 'j450'  AND  'j46' OR
                               v.dx0 BETWEEN 'j450'  AND  'j46' OR
                               v.dx1 BETWEEN 'j450'  AND  'j46' OR
                               v.dx2 BETWEEN 'j450'  AND  'j46' OR
                               v.dx3 BETWEEN 'j450'  AND  'j46' OR
                               v.dx4 BETWEEN 'j450'  AND  'j46' OR
                               v.dx5 BETWEEN 'j450'  AND  'j46'

                          )
                    $criteria

                    GROUP BY
                          ro.vn
                    ";



            try {
                $rawData = \yii::$app->db->createCommand($sql)->queryAll();
            } catch (\yii\db\Exception $e) {
                throw new \yii\web\ConflictHttpException('sql error');
            }

            $dataProvider = new \yii\data\ArrayDataProvider([
                'allModels' => $rawData,
                'pagination' => False,
            ]);


            return $this->render('report12', [
                        'dataProvider' => $dataProvider,
                        'report_name' => $report_name,
                        'details' => $details,
            ]);
        }
    }
    
        
    
    public function actionReport13($uclinic, $datestart, $dateend, $details) {
        // save log
        $this->SaveLog($this->dep_controller, 'report13', $this->getSession());

        $report_name = 'คนไข้ Asthma มีการ Admit ที่ ER';

        if ($uclinic != "") {

            if ($uclinic == 1) {
                $join_opd = ' ';
                $criteria = ' ';
                $report_name = 'คนไข้ Asthma มีการ Admit ที่ ER (ยอดคนไข้ทั่วไป + คนไข้ใน clinic asthma)';
            } else if ($uclinic == 2) {
                $join_opd = ' left outer join clinicmember c on c.hn = v.hn ';
                $criteria = ' and c.clinic = "019" ';
                $report_name = 'คนไข้ Asthma มีการ Admit ที่ ER (เฉพาะคนไข้ใน clinic asthma)';
            }
                  
            $sql = "SELECT
                        o.hn,o.an,concat(p.pname,p.fname,'  ',p.lname) as pt_name,
                        v.age_y,s.name as sex,
                        v.age_y,s.name as sex,o.vstdate,
                        concat(DAY(a.regdate),'/',MONTH(a.regdate),'/',(YEAR(a.regdate)+543)) as regdate_thai,
                        concat(DAY(a.dchdate),'/',MONTH(a.dchdate),'/',(YEAR(a.dchdate)+543)) as dchdate_thai,
                        v.moopart,t.full_name as address,
                        a.pdx,
                        CONCAT(
                            if(a.dx0 is not null,concat(a.dx0,'   '),' '),
                            if(a.dx1 is not null,concat(a.dx1,'   '),' '),
                            if(a.dx2 is not null,concat(a.dx2,'   '),' '),
                            if(a.dx3 is not null,concat(a.dx3,'   '),' '),
                            if(a.dx4 is not null,concat(a.dx4,'   '),' '),
                            if(a.dx5 is not null,concat(a.dx5,'   '),' ')
                        )  as second_diag

                  FROM ovst  o

                  LEFT OUTER JOIN patient p ON p.hn = o.hn
                  LEFT OUTER JOIN vn_stat v ON v.vn = o.vn
                  LEFT OUTER JOIN thaiaddress t ON t.addressid=v.aid
                  LEFT OUTER JOIN sex s ON s.code = p.sex
                  LEFT OUTER JOIN an_stat a ON  a.an = o.an
                  $join_opd
                      
                  WHERE
                       a.dchdate BETWEEN $datestart AND $dateend
                  AND o.an  != ''
                  AND a.pdx BETWEEN 'J450' AND 'J46'
                  AND o.vn in (select vn from er_regist)
                  $criteria
                  GROUP BY
                        o.hn
                  ORDER BY
                        v.aid, v.moopart, v.hn, v.vstdate ";                                  

            try {
                $rawData = \yii::$app->db->createCommand($sql)->queryAll();
            } catch (\yii\db\Exception $e) {
                throw new \yii\web\ConflictHttpException('sql error');
            }

            $dataProvider = new \yii\data\ArrayDataProvider([
                'allModels' => $rawData,
                'pagination' => False,
            ]);


            return $this->render('report13', [
                        'dataProvider' => $dataProvider,
                        'report_name' => $report_name,
                        'details' => $details,
            ]);
        }
    }

    
     public function actionReport14($uclinic, $datestart, $dateend, $details) {
        // save log
        $this->SaveLog($this->dep_controller, 'report14', $this->getSession());

        $report_name = 'คนไข้ Asthma ได้รับบริการที่ ER';

        if ($uclinic != "") {

            if ($uclinic == 1) {
                $join_opd = ' ';
                $criteria = ' ';
                $report_name = 'คนไข้ Asthma ได้รับบริการที่ ER (ยอดคนไข้ทั่วไป + คนไข้ใน clinic asthma) (นับเป็นคน)';
            } else if ($uclinic == 2) {
                $join_opd = ' left outer join clinicmember c on c.hn = v.hn ';
                $criteria = ' and c.clinic = "019" ';
                $report_name = 'คนไข้ Asthma ได้รับบริการที่ ER (เฉพาะคนไข้ใน clinic asthma) (รับเป็นคน)';
            }
                  
            $sql = "SELECT
                        v.hn,CONCAT(p.pname,p.fname,'  ',p.lname) as pt_name,
                        e.vn,
                        concat(DAY(v.vstdate),'/',MONTH(v.vstdate),'/',(YEAR(v.vstdate)+543)) as vstdate_thai,
                        v.pdx,
                        CONCAT(
                            if(v.dx0 is not null,concat(v.dx0,'   '),' '),
                            if(v.dx1 is not null,concat(v.dx1,'   '),' '),
                            if(v.dx2 is not null,concat(v.dx2,'   '),' '),
                            if(v.dx3 is not null,concat(v.dx3,'   '),' '),
                            if(v.dx4 is not null,concat(v.dx4,'   '),' '),
                            if(v.dx5 is not null,concat(v.dx5,'   '),' ')
                        )  as second_diag
        
                  FROM vn_stat  v
                  RIGHT OUTER JOIN  er_regist e on e.vn = v.vn
                  LEFT  OUTER JOIN  patient p on p.hn = v.hn
                  LEFT  OUTER JOIN  thaiaddress t on t.addressid=v.aid
                  LEFT  OUTER JOIN  sex s on s.code = p.sex
                  $join_opd
                      
                  WHERE
                       v.vstdate BETWEEN $datestart and $dateend

                  AND
                       v.pdx BETWEEN 'J450' AND 'J46'
                       
                  $criteria

                  GROUP BY
                        v.hn
                  ORDER BY
                        v.aid, v.moopart, v.hn, v.vstdate ";                                  

            try {
                $rawData = \yii::$app->db->createCommand($sql)->queryAll();
            } catch (\yii\db\Exception $e) {
                throw new \yii\web\ConflictHttpException('sql error');
            }

            $dataProvider = new \yii\data\ArrayDataProvider([
                'allModels' => $rawData,
                'pagination' => False,
            ]);


            return $this->render('report14', [
                        'dataProvider' => $dataProvider,
                        'report_name' => $report_name,
                        'details' => $details,
            ]);
        }
    }

    
     public function actionReport15($uclinic, $datestart, $dateend, $details) {
        // save log
        $this->SaveLog($this->dep_controller, 'report15', $this->getSession());

        $report_name = 'คนไข้ Asthma Re-Visit ภายใน 48 ชม. ที่ ER';

        if ($uclinic != "") {

            if ($uclinic == 1) {
                $join_opd = ' ';
                $criteria = ' ';
                $report_name = 'คนไข้ Asthma Re-Visit ภายใน 48 ชม. ที่ ER (ยอดคนไข้ทั่วไป + คนไข้ใน clinic asthma) (นับเป็นคน)';
            } else if ($uclinic == 2) {
                $join_opd = ' left outer join clinicmember c on c.hn = v.hn ';
                $criteria = ' and c.clinic = "019" ';
                $report_name = 'คนไข้ Asthma Re-Visit ภายใน 48 ชม. ที่ ER (เฉพาะคนไข้ใน clinic asthma) (นับเป็นคน)';
            }
                  
            $sql = "SELECT
                        v.hn,CONCAT(p.pname,p.fname,'  ',p.lname) as pt_name,
                        e.vn,
                        concat(DAY(v.vstdate),'/',MONTH(v.vstdate),'/',(YEAR(v.vstdate)+543)) as vstdate_thai,
                        v.pdx,
                        CONCAT(
                            if(v.dx0 is not null,concat(v.dx0,'   '),' '),
                            if(v.dx1 is not null,concat(v.dx1,'   '),' '),
                            if(v.dx2 is not null,concat(v.dx2,'   '),' '),
                            if(v.dx3 is not null,concat(v.dx3,'   '),' '),
                            if(v.dx4 is not null,concat(v.dx4,'   '),' '),
                            if(v.dx5 is not null,concat(v.dx5,'   '),' ')
                        )  as second_diag
                  FROM
                  vn_stat  v
                  LEFT OUTER  JOIN  patient p on p.hn = v.hn
                  LEFT OUTER  JOIN  spclty s on s.spclty = v.spclty
                  RIGHT OUTER JOIN  er_regist e on e.vn = v.vn
                  LEFT OUTER  JOIN  thaiaddress t on t.addressid=v.aid
                  LEFT OUTER  JOIN  sex se on se.code = p.sex
                  $join_opd
                  WHERE
                       v.lastvisit_hour <= 48
                  AND
                       v.old_diagnosis = 'Y'
                  AND
                       v.vstdate BETWEEN $datestart and $dateend
                  AND
                       v.pdx BETWEEN 'J450'  and 'J46'
                  $criteria
                  GROUP BY
                        v.hn "; 
                                  

            try {
                $rawData = \yii::$app->db->createCommand($sql)->queryAll();
            } catch (\yii\db\Exception $e) {
                throw new \yii\web\ConflictHttpException('sql error');
            }

            $dataProvider = new \yii\data\ArrayDataProvider([
                'allModels' => $rawData,
                'pagination' => False,
            ]);


            return $this->render('report15', [
                        'dataProvider' => $dataProvider,
                        'report_name' => $report_name,
                        'details' => $details,
            ]);
        }
    }
    
    
    public function actionReport16($uclinic, $datestart, $dateend, $details) {
        // save log
        $this->SaveLog($this->dep_controller, 'report16', $this->getSession());

        $report_name = 'คนไข้ Asthma ที่มีรหัสวินิจฉัย j46 รับบริการที่ ER';

        if ($uclinic != "") {

            if ($uclinic == 1) {
                $join_opd = ' ';
                $criteria = ' ';
                $report_name = 'คนไข้ Asthma ที่มีรหัสวินิจฉัย j46 รับบริการที่ ER (ยอดคนไข้ทั่วไป + คนไข้ใน clinic asthma) (นับเป็นคน)';
            } else if ($uclinic == 2) {
                $join_opd = ' left outer join clinicmember c on c.hn = v.hn ';
                $criteria = ' and c.clinic = "019" ';
                $report_name = 'คนไข้ Asthma ที่มีรหัสวินิจฉัย j46 รับบริการที่ ER (เฉพาะคนไข้ใน clinic asthma) (นับเป็นคน)';
            }
                  
            $sql = "SELECT
                        v.hn,CONCAT(p.pname,p.fname,'  ',p.lname) as pt_name,
                        er.vn,
                        concat(DAY(v.vstdate),'/',MONTH(v.vstdate),'/',(YEAR(v.vstdate)+543)) as vstdate_thai,
                        v.pdx,
                        CONCAT(
                            if(v.dx0 is not null,concat(v.dx0,'   '),' '),
                            if(v.dx1 is not null,concat(v.dx1,'   '),' '),
                            if(v.dx2 is not null,concat(v.dx2,'   '),' '),
                            if(v.dx3 is not null,concat(v.dx3,'   '),' '),
                            if(v.dx4 is not null,concat(v.dx4,'   '),' '),
                            if(v.dx5 is not null,concat(v.dx5,'   '),' ')
                        )  as second_diag

                  FROM vn_stat v
                  LEFT  OUTER JOIN patient p ON p.hn = v.hn
                  RIGHT OUTER JOIN er_regist er ON er.vn = v.vn
                  $join_opd
                  WHERE
                       v.vstdate BETWEEN $datestart AND $dateend
                  AND
                       v.pdx = 'j46'
                  $criteria
                  GROUP BY v.hn "; 
                  
                                  

            try {
                $rawData = \yii::$app->db->createCommand($sql)->queryAll();
            } catch (\yii\db\Exception $e) {
                throw new \yii\web\ConflictHttpException('sql error');
            }

            $dataProvider = new \yii\data\ArrayDataProvider([
                'allModels' => $rawData,
                'pagination' => False,
            ]);


            return $this->render('report16', [
                        'dataProvider' => $dataProvider,
                        'report_name' => $report_name,
                        'details' => $details,
            ]);
        }
    }

    
    
}
