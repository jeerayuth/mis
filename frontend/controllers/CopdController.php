<?php

namespace frontend\controllers;

use Yii;
use frontend\components\CommonController;

class CopdController extends CommonController {
    public $dep_controller = 'copd';

    public function actionReport1($uclinic) {       
        // save log
        $this->SaveLog($this->dep_controller, 'report1', $this->getSession());
        
        if ($uclinic != "") { // เริ่มต้นตรวจสอบประเภทคนไข้ในคลินิก
            // ตัวแปร $get_type เอาไว้ตรวจสอบว่าเป็นคนไข้ dm หรือ dm with ht
            // ตัวแปร $report_name เอาไว้ไปแสดงชื่อรายงานในหน้า view
            if ($uclinic == 1) {
                $cryteria = '';
                $report_name = 'รายงานสรุปคนไข้ทะเบียน COPD ทั้งหมด(รวมที่เป็น DM,HT)';
            } else if ($uclinic == 2) {
                $cryteria = " AND cm.hn  not in(select hn from clinicmember where clinic=(select sys_value from sys_var where sys_name='dm_clinic_code'))
                              AND cm.hn  not  in(select hn from clinicmember where clinic=(select sys_value from sys_var where sys_name='ht_clinic_code')) ";                                 
                $report_name = 'รายงานสรุปคนไข้ทะเบียน COPD อย่างเดียว(ไม่รวมที่เป็น DM,HT)';
                
            } else if ($uclinic == 3) {
                   $cryteria = " AND cm.hn  in(select hn from clinicmember where clinic=(select sys_value from sys_var where sys_name='dm_clinic_code'))
                                 AND cm.hn  not  in(select hn from clinicmember where clinic=(select sys_value from sys_var where sys_name='ht_clinic_code')) ";                 
                $report_name = 'รายงานสรุปคนไข้ทะเบียน COPD WITH DM (WITH DM, NO HT)';
            } else if ($uclinic == 4) {
                   $cryteria = " AND cm.hn  not in(select hn from clinicmember where clinic=(select sys_value from sys_var where sys_name='dm_clinic_code'))
                                 AND cm.hn   in(select hn from clinicmember where clinic=(select sys_value from sys_var where sys_name='ht_clinic_code')) ";                 
                $report_name = 'รายงานสรุปคนไข้ทะเบียน COPD WITH HT (WITH HT, NO DM)';
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
                        cm.clinic = '005'

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

    /* รายงานสรุปทะเบียนถุงลมโป่งพองแบบ(แสดงรายชื่อคนไข้) */

    public function actionReport2($addressid , $uclinic) {
            
        // save log
        $this->SaveLog($this->dep_controller, 'report2', $this->getSession());
        
         if ($uclinic != "") { // เริ่มต้นตรวจสอบประเภทคนไข้ในคลินิก
            // ตัวแปร $get_type เอาไว้ตรวจสอบว่าเป็นคนไข้ dm หรือ dm with ht
            // ตัวแปร $report_name เอาไว้ไปแสดงชื่อรายงานในหน้า view
            if ($uclinic == 1) {
                $cryteria = '';
                $report_name = 'รายงานสรุปคนไข้ทะเบียน COPD ทั้งหมด(รวมที่เป็น DM,HT)';
            } else if ($uclinic == 2) {
                $cryteria = " AND cm.hn  not in(select hn from clinicmember where clinic=(select sys_value from sys_var where sys_name='dm_clinic_code'))
                              AND cm.hn  not  in(select hn from clinicmember where clinic=(select sys_value from sys_var where sys_name='ht_clinic_code')) ";                                 
                $report_name = 'รายงานสรุปคนไข้ทะเบียน COPD อย่างเดียว(ไม่รวมที่เป็น DM,HT)';
                
            } else if ($uclinic == 3) {
                   $cryteria = " AND cm.hn  in(select hn from clinicmember where clinic=(select sys_value from sys_var where sys_name='dm_clinic_code'))
                                 AND cm.hn  not  in(select hn from clinicmember where clinic=(select sys_value from sys_var where sys_name='ht_clinic_code')) ";                 
                $report_name = 'รายงานสรุปคนไข้ทะเบียน COPD WITH DM (WITH DM, NO HT)';
            } else if ($uclinic == 4) {
                   $cryteria = " AND cm.hn  not in(select hn from clinicmember where clinic=(select sys_value from sys_var where sys_name='dm_clinic_code'))
                                 AND cm.hn   in(select hn from clinicmember where clinic=(select sys_value from sys_var where sys_name='ht_clinic_code')) ";                 
                $report_name = 'รายงานสรุปคนไข้ทะเบียน COPD WITH HT (WITH HT, NO DM)';
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
                    cm.clinic = '005'

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


        $report_name = "รายงานจำนวนคนไข้คลินิกถุงลมโป่งพอง ได้รับการคัดกรองการสูบบุหรี่-ดื่มสุรา";

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
	cm.clinic = '005'
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


        $report_name = "รายงานจำนวนคนไข้คลินิกถุงลมโป่งพอง ได้รับบริการที่ห้องฉุกเฉิน";

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
and c.clinic = '005' /* and c.clinic_member_status_id=1 */
and v.pdx between 'J440' and 'J449'

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


        $report_name = "รายงานจำนวนคนไข้คลินิกถุงลมโป่งพอง ได้รับการ Admit";

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
and o.an  != ''  and c.clinic='005'  /* and c.clinic_member_status_id='1' */
and a.pdx between 'j440'  and 'j449'
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

        
        $report_name = "รายงานจำนวนคนไข้คลินิกถุงลมโป่งพอง Re-visit ภายใน 48 ชั่วโมง ที่ OPD";

        $sql = "select
v.lastvisit_hour,v.vn,v.hn,v.vstdate,v.age_y,
concat(p.pname,p.fname,'  ',p.lname) as pt_name,s.name ,
v.pdx,v.dx0 ,v.dx1,v.dx2,v.dx3,v.dx4,v.dx5,
 v.moopart,t.full_name as address

from vn_stat  v

left outer join clinicmember c on c.hn = v.hn
left outer join patient p on p.hn = v.hn
left outer join spclty s on s.spclty = v.spclty
left OUTER join thaiaddress t on t.addressid=v.aid
left outer join sex se on se.code = p.sex

where c.clinic = '005'  /* and c.clinic_member_status_id='1' */  and v.old_diagnosis = 'Y'
and v.lastvisit_hour <= 48
and v.vstdate between $datestart and $dateend
and  v.vn  not in (select e.vn from er_regist e )
and v.pdx between 'j440'  and 'j449'
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


        $report_name = "รายงานจำนวนคนไข้คลินิกถุงลมโป่งพอง Re-visit ภายใน 48 ชั่วโมง ที่ ER";

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


where c.clinic = '005'  /*and c.clinic_member_status_id='1'*/  
and v.lastvisit_hour <= 48 and v.old_diagnosis = 'Y'
and v.vstdate between $datestart and $dateend
and v.pdx between 'j440'  and 'j449'

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
    
    
    
    
    
    public function actionReport8($datestart, $dateend, $details, $item = null) {
                        // save log
            $this->SaveLog($this->dep_controller, 'report8', $this->getSession());
     
            $report_name = 'รายงานจำนวนคนไข้คลินิกถุงลมโป่งพอง แยกกลุ่มผู้ป่วยตาม GOLD 11';
      
            $sql = "SELECT  
                            n.hn,concat(p.pname,p.fname,'  ',p.lname) as pt_name, 
                            n.plain_text,n.note_datetime,n.note_staff
                    FROM ptnote n
                    LEFT OUTER JOIN patient p ON p.hn = n.hn
                    WHERE
                    n.note_datetime between $datestart and $dateend

                    and n.hn in

                    (
                        select c.hn from clinicmember c
                        left outer join clinic_member_status cl on cl.clinic_member_status_id = c.clinic_member_status_id
                        where c.hn = n.hn and c.clinic = '005'  /* and cl.provis_typedis = '03' */

                    )  and n.plain_text like  '%$item%'

                    GROUP BY n.hn
                    ORDER BY n.note_datetime ";


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
        
        
        
        public function actionReport9($uclinic,$datestart, $dateend, $details) {
                        // save log
            $this->SaveLog($this->dep_controller, 'report9', $this->getSession());
      
            
  if ($uclinic != "") { 
      
            if ($uclinic == 1) {
                $join_opd = ' ';
                $join_ipd = ' ';
                $criteria = ' ';
                $report_name = 'รายงานจำนวนคนไข้ที่มีรหัสวินิจฉัย j440-j449 ทั้งโรงพยาบาล(คลินิก COPD+ คนไข้ทั่วไป) (รายคน)';
            } else if ($uclinic == 2) {
                $join_opd = ' left outer join clinicmember c on c.hn = v.hn ';
                $join_ipd = ' left outer join clinicmember c on c.hn = v.hn ';
                $criteria = ' and c.clinic = "005" ';
                $report_name = 'รายงานจำนวนคนไข้ที่มีรหัสวินิจฉัย j440-j449 ทั้งโรงพยาบาล(เฉพาะคนไข้ในคลินิก COPD) (รายคน)';
            }
            
      
            $sql = "SELECT
                            a.hn,a.pt_name
                    FROM
                     (
                            select distinct(v.hn) ,concat(p.pname,p.fname,'  ',p.lname) as pt_name
                                from vn_stat v
                                left outer join patient p on p.hn = v.hn
                                $join_opd
                                where v.vstdate between $datestart and $dateend   and
                                (
                                      v.pdx between 'j440' and 'j449' or
                                      v.dx0 between 'j440' and 'j449' or
                                      v.dx1 between 'j440' and 'j449' or
                                      v.dx2 between 'j440' and 'j449' or
                                      v.dx3 between 'j440' and 'j449' or
                                      v.dx4 between 'j440' and 'j449' or
                                      v.dx5 between 'j440' and 'j449'
                                ) $criteria
                        union  all
                                select distinct(v.hn) ,concat(p.pname,p.fname,'  ',p.lname) as pt_name
                                from an_stat v
                                left outer join patient p on p.hn = v.hn
                                $join_ipd
                                where v.dchdate between $datestart and $dateend  and

                                (
                                      v.pdx between 'j440' and 'j449' or
                                      v.dx0 between 'j440' and 'j449' or
                                      v.dx1 between 'j440' and 'j449' or
                                      v.dx2 between 'j440' and 'j449' or
                                      v.dx3 between 'j440' and 'j449' or
                                      v.dx4 between 'j440' and 'j449' or
                                      v.dx5 between 'j440' and 'j449'
                                ) $criteria

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
           
   }
             
        } // จบ function
        
        
        
        public function actionReport10($datestart, $dateend, $details) {
                        // save log
            $this->SaveLog($this->dep_controller, 'report10', $this->getSession());
     
            $report_name = 'รายงานจำนวนคนไข้ re-admit ด้วยรหัสวินิจฉัย j440-j449';
      
            $sql = "select
                    q3.hn,concat(patient.pname,patient.fname,'  ',patient.lname) as ptname,
                    patient.birthday,
                    timestampdiff(year,patient.birthday,q3.regdate_AN_New) as age_y,
                    q3.AN_new ,q3.regdate_AN_New ,q3.dcdate_AN_New ,q3.AN_Old as AN_old
                   ,q3.regdate_AN_Old ,q3.dcdate_AN_Old ,q3.icd10_1,q3.ReAdmitDate

               from patient inner join

                    (select q1.hn ,q1.an as AN_new ,q1.regdate as regdate_AN_New,q1.dchdate as dcdate_AN_New,q2.an as AN_old ,q2.regdate as regdate_AN_Old 
                    ,q2.dchdate as dcdate_AN_Old,q1.icd10 as icd10_1,TIMESTAMPDIFF(day,substring(q2.dchdate,1,10),substring(q1.regdate,1,10)) as ReAdmitDate

                   from (select ipt.hn ,ipt.an ,ipt.regdate,ipt.dchdate,iptdiag.icd10 ,iptdiag.diagtype from

                    ipt  inner join iptdiag on ipt.an = iptdiag.an where ipt.hn != ' ' and iptdiag.diagtype = '1') as q1

                    inner join 

                   (select ipt1.hn ,ipt1.an ,ipt1.regdate,ipt1.dchdate,iptdiag1.icd10 ,iptdiag1.diagtype from ipt as ipt1 
                   inner join iptdiag as iptdiag1 on ipt1.an = iptdiag1.an where ipt1.hn != ' ' and iptdiag1.diagtype ='1' ) as q2
                    where q1.hn = q2.hn and q1.an <> q2.an and q1.icd10 = q2.icd10 and

                   TIMESTAMPDIFF(day,substring(q2.dchdate,1,10),substring(q1.regdate,1,10)) > 0 and 
                   TIMESTAMPDIFF(day,substring(q2.dchdate,1,10),substring(q1.regdate,1,10)) <= 28 and

                   q1.regdate between $datestart and $dateend  ) as q3  on q3.hn = patient.hn   AND q3.icd10_1 between 'j440' and 'j449' ";
                    

                   

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
    


        
        public function actionReport11($uclinic,$datestart, $dateend, $details) {
                // save log
        $this->SaveLog($this->dep_controller, 'report11', $this->getSession());
  
        if ($uclinic != "") { 

                if ($uclinic == 1) {
                    $join_opd = ' ';
                    $criteria = ' ';
                    $report_name = 'รายงานจำนวนคนไข้ ที่มีรหัสวินิจฉัย j440-j449  Re-visit ภายใน 48 ชั่วโมง (คลินิก COPD+คนไข้ทั่วไป) (รายคน)';
                } else if ($uclinic == 2) {
                    $join_opd = ' left outer join clinicmember c on c.hn = v.hn ';
                    $criteria = ' and c.clinic = "005" ';
                    $report_name = 'รายงานจำนวนคนไข้ ที่มีรหัสวินิจฉัย j440-j449  Re-visit ภายใน 48 ชั่วโมง (เฉพาะคนไข้ในคลินิก COPD) (รายคน)';
                }


            $sql = "SELECT
                        v.vn,v.hn,v.vstdate,v.age_y,
                        concat(p.pname,p.fname,'  ',p.lname) as pt_name,s.name ,
                        v.pdx,v.dx0 ,v.dx1,v.dx2,v.dx3,v.dx4,v.dx5,
                        v.moopart,t.full_name as address,
                        v.lastvisit_hour
                    FROM vn_stat  v
                    left outer join patient p on p.hn = v.hn
                    left outer join spclty s on s.spclty = v.spclty
                    left OUTER join thaiaddress t on t.addressid=v.aid
                    left outer join sex se on se.code = p.sex
                    $join_opd
                    WHERE                
                        v.old_diagnosis = 'Y' and v.lastvisit_hour <= 48
                        and v.vstdate between $datestart and $dateend
                        and v.pdx between 'j440'  and 'j449'
                        $criteria
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


            return $this->render('report11', [
                        'dataProvider' => $dataProvider,
                        'report_name' => $report_name,
                        'details' => $details,
            ]);
        }
    } // จบ function
    

    
    
           
        public function actionReport12($uclinic,$datestart, $dateend, $details) {
                        // save log
            $this->SaveLog($this->dep_controller, 'report12', $this->getSession());
     
            $report_name = 'รายงานจำนวนคนไข้ผู้ป่วยนอก(OPD+ER) ที่มีรหัสวินิจฉัย j441 และมีอายุมากกว่า 15 ปี ';
                                   
            if ($uclinic != "") { 

                      if ($uclinic == 1) {
                          $join_opd = ' ';
                          $criteria = ' ';
                          $report_name = 'รายงานจำนวนคนไข้ผู้ป่วยนอก(OPD+ER) ที่มีรหัสวินิจฉัย j441 และมีอายุมากกว่า 15 ปี  (คลินิก COPD+ คนไข้ทั่วไป) (รายคน)';
                      } else if ($uclinic == 2) {
                          $join_opd = ' left outer join clinicmember c on c.hn = v.hn ';
                          $criteria = ' and c.clinic = "005" ';
                          $report_name = 'รายงานจำนวนคนไข้ผู้ป่วยนอก(OPD+ER) ที่มีรหัสวินิจฉัย j441 และมีอายุมากกว่า 15 ปี  (เฉพาะคนไข้ในคลินิก COPD) (รายคน)';
                      }


                      $sql = "
                              SELECT
                                     v.hn ,concat(p.pname,p.fname,'  ',p.lname) as pt_name,
                                     v.age_y
                               FROM vn_stat v
                               LEFT OUTER JOIN patient p on p.hn = v.hn
                               $join_opd
                               WHERE 
                                  v.vstdate between $datestart and $dateend  
                                  and v.pdx = 'j441' and v.age_y > 15  $criteria
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


                     return $this->render('report12', [
                                  'dataProvider' => $dataProvider,
                                  'report_name' => $report_name,
                                  'details' => $details,

                           ]); 

                      }
        } // จบ function
        
    
         public function actionReport13($datestart, $dateend, $details) {
                        // save log
            $this->SaveLog($this->dep_controller, 'report13', $this->getSession());
     
            $report_name = 'รายงานจำนวนครั้งคนไข้ในเขตอำเภอละแม รับบริการที่(OPD+ER+IPD) ที่มีรหัสวินิจฉัย j440 ถึง j441  ' ;
                                   
            $sql = "SELECT
                        v.vn as visit_number,v.hn,concat(p.pname,p.fname,'   ',p.lname) as pt_name,
                        v.vstdate as vstdate,v.pdx, p.addrpart,p.moopart, t.full_name
                    FROM vn_stat  v
                    left outer join patient p on p.hn = v.hn
                    left outer join thaiaddress t on t.addressid = concat(p.chwpart,p.amppart,p.tmbpart)
                    WHERE 
                        v.vstdate between $datestart and $dateend   and v.pdx between 'j440' and 'j441'
                        and concat(p.chwpart,p.amppart) = '8605'

                 UNION ALL

                    SELECT
                        a.an as visit_number,a.hn,concat(p.pname,p.fname,'   ',p.lname) as pt_name,
                        a.dchdate as vstdate,a.pdx, p.addrpart,p.moopart, t.full_name
                    FROM an_stat  a
                    left outer join patient p on p.hn = a.hn
                    left outer join thaiaddress t on t.addressid = concat(p.chwpart,p.amppart,p.tmbpart)
                    WHERE 
                        a.dchdate between $datestart and $dateend    and a.pdx between 'j440' and 'j441'
                        and concat(p.chwpart,p.amppart) = '8605' ";

                             
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
