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

    public function actionReport2($addressid, $uclinic) {

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
    }

// จบ function

    public function actionReport4($uclinic, $datestart, $dateend, $details) {
        // save log
        $this->SaveLog($this->dep_controller, 'report4', $this->getSession());

        $report_name = 'คนไข้ COPD ได้รับบริการที่ ER';

        if ($uclinic != "") {

            if ($uclinic == 1) {
                $join_opd = ' ';
                $criteria = ' ';
                $report_name = 'คนไข้ COPD ได้รับบริการที่ ER (ยอดคนไข้ทั่วไป + คนไข้ใน clinic copd) (นับเป็นคน)';
            } else if ($uclinic == 2) {
                $join_opd = ' left outer join clinicmember c on c.hn = v.hn ';
                $criteria = ' and c.clinic = "005" ';
                $report_name = 'คนไข้ COPD ได้รับบริการที่ ER (เฉพาะคนไข้ใน clinic copd) (รับเป็นคน)';
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
                       v.pdx BETWEEN 'j440' AND 'j449'
                       
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


            return $this->render('report4', [
                        'dataProvider' => $dataProvider,
                        'report_name' => $report_name,
                        'details' => $details,
            ]);
        }
    }

// จบ function

    public function actionReport5($datestart, $dateend, $details) {
        // save log
        $this->SaveLog($this->dep_controller, 'report5', $this->getSession());


        $report_name = "รายงานจำนวนคนไข้คลินิกถุงลมโป่งพอง ได้รับการ Admit (นับเป็นจำนวนคน)";

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
            and o.an  != ''  and c.clinic='005'  
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
    }

// จบ function

    public function actionReport6($datestart, $dateend, $details) {
        // save log
        $this->SaveLog($this->dep_controller, 'report6', $this->getSession());


        $report_name = "รายงานจำนวนคนไข้คลินิกถุงลมโป่งพอง Re-visit ภายใน 48 ชั่วโมง ที่ OPD (นับเป็นจำนวนคน)";

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
    }

// จบ function

    public function actionReport7($datestart, $dateend, $details) {
        // save log
        $this->SaveLog($this->dep_controller, 'report7', $this->getSession());

        $report_name = "รายงานจำนวนคนไข้คลินิกถุงลมโป่งพอง Re-visit ภายใน 48 ชั่วโมง ที่ ER (นับเป็นจำนวนคน)";

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
    }

// จบ function

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
    }

// จบ function

    public function actionReport9($uclinic, $datestart, $dateend, $details) {
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
    }

// จบ function

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
    }

// จบ function

    public function actionReport11($uclinic, $datestart, $dateend, $details) {
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
    }

// จบ function

    public function actionReport12($uclinic, $datestart, $dateend, $details) {
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
    }

// จบ function

    public function actionReport13($datestart, $dateend, $details) {
        // save log
        $this->SaveLog($this->dep_controller, 'report13', $this->getSession());

        $report_name = 'รายงานจำนวนครั้งคนไข้ในเขตอำเภอละแม รับบริการที่(OPD+ER+IPD) ที่มีรหัสวินิจฉัย j440 ถึง j441 (รายครั้ง) ';

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

    
    
    public function actionReport14($datestart, $dateend, $details) {
        // save log
        $this->SaveLog($this->dep_controller, 'report14', $this->getSession());

        $report_name = 'รายงานจำนวนครั้งคนไข้ในเขตอำเภอละแม รับบริการที่(OPD+ER+IPD) ที่มีรหัสวินิจฉัย j440 ถึง j449  ';

        $sql = "SELECT
                        v.vn as visit_number,v.hn,concat(p.pname,p.fname,'   ',p.lname) as pt_name,
                        v.vstdate as vstdate,v.pdx, p.addrpart,p.moopart, t.full_name
                    FROM vn_stat  v
                    left outer join patient p on p.hn = v.hn
                    left outer join thaiaddress t on t.addressid = concat(p.chwpart,p.amppart,p.tmbpart)
                    WHERE 
                        v.vstdate between $datestart and $dateend   and v.pdx between 'j440' and 'j449'
                        and concat(p.chwpart,p.amppart) = '8605'

                 UNION ALL

                    SELECT
                        a.an as visit_number,a.hn,concat(p.pname,p.fname,'   ',p.lname) as pt_name,
                        a.dchdate as vstdate,a.pdx, p.addrpart,p.moopart, t.full_name
                    FROM an_stat  a
                    left outer join patient p on p.hn = a.hn
                    left outer join thaiaddress t on t.addressid = concat(p.chwpart,p.amppart,p.tmbpart)
                    WHERE 
                        a.dchdate between $datestart and $dateend    and a.pdx between 'j440' and 'j449'
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


        return $this->render('report14', [
                    'dataProvider' => $dataProvider,
                    'report_name' => $report_name,
                    'details' => $details,
        ]);
    }

    
    
    //Developping Now
     public function actionReport15($datestart, $dateend, $details) {
        // save log
        $this->SaveLog($this->dep_controller, 'report15', $this->getSession());

        $report_name = 'รายงานจำนวนคนไข้ในเขตอำเภอละแม รับบริการที่(OPD+ER+IPD) ที่มีรหัสวินิจฉัย j440 ถึง j441 (รายคน) ';

        $sql = " SELECT
                       distinct v.hn,concat(p.pname,p.fname,'  ',p.lname) as pt_name ,p.addrpart,p.moopart, t.full_name
                    FROM vn_stat  v
                    left outer join patient p on p.hn = v.hn
                    left outer join thaiaddress t on t.addressid = concat(p.chwpart,p.amppart,p.tmbpart)
                    WHERE 
                        v.vstdate between $datestart and $dateend    and v.pdx between 'j440' and 'j441'
                        and concat(p.chwpart,p.amppart) = '8605'

                 UNION

                   SELECT
                        distinct a.hn,concat(p.pname,p.fname,'  ',p.lname) as pt_name  , p.addrpart,p.moopart, t.full_name
                    FROM an_stat  a
                    left outer join patient p on p.hn = a.hn
                    left outer join thaiaddress t on t.addressid = concat(p.chwpart,p.amppart,p.tmbpart)
                    WHERE 
                        a.dchdate between $datestart and $dateend   and a.pdx between 'j440' and 'j441'
                        and concat(p.chwpart,p.amppart) = '8605'  ";




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
    
    
    
    
     public function actionReport16($uclinic, $datestart, $dateend, $details) {
        // save log
        $this->SaveLog($this->dep_controller, 'report16', $this->getSession());

        $report_name = 'รายงานการใส่ Tube ในผู้ป่วย COPD ที่ ER';

        if ($uclinic != "") {

            if ($uclinic == 1) {
                $join_opd = ' ';
                $criteria = ' ';
                $report_name = 'รายงานการใส่ Tube ในผู้ป่วย COPD ที่ ER >= (ยอดคนไข้ทั่วไป + คนไข้ใน clinic copd)';
            } else if ($uclinic == 2) {
                $join_opd = ' left outer join clinicmember c on c.hn = v.hn ';
                $criteria = ' and c.clinic = "005" ';
                $report_name = 'รายงานการใส่ Tube ในผู้ป่วย COPD ที่ ER >= (เฉพาะคนไข้ใน clinic copd)';
            }
                  
            $sql = "SELECT
                        er.vn,v.hn,concat(pt.pname,pt.fname,'  ',pt.lname) as pt_name,
                        er.vstdate,
                        concat(DAY(er.vstdate),'/',MONTH(er.vstdate),'/',(YEAR(er.vstdate)+543)) as vstdate_thai,
                        op.icode,nd.name as items_name,
                        v.pdx,     
                        CONCAT(
                            if(v.dx0 is not null,concat(v.dx0,'   '),' '),
                            if(v.dx1 is not null,concat(v.dx1,'   '),' '),
                            if(v.dx2 is not null,concat(v.dx2,'   '),' '),
                            if(v.dx3 is not null,concat(v.dx3,'   '),' '),
                            if(v.dx4 is not null,concat(v.dx4,'   '),' '),
                            if(v.dx5 is not null,concat(v.dx5,'   '),' ')
                        )  as second_diag
                  FROM er_regist  er
                  LEFT OUTER JOIN opitemrece op ON op.vn = er.vn
                  LEFT OUTER JOIN nondrugitems nd ON nd.icode = op.icode
                  LEFT OUTER JOIN vn_stat v ON v.vn = er.vn
                  LEFT OUTER JOIN patient pt ON pt.hn = v.hn
                  $join_opd
                  WHERE
                       er.vstdate BETWEEN $datestart AND $dateend
                  AND
                       op.icode in ('3001595','3001596')
                  AND
                       (
                             v.pdx BETWEEN 'j440'  AND  'j449' OR
                             v.dx0 BETWEEN 'j440'  AND  'j449' OR
                             v.dx1 BETWEEN 'j440'  AND  'j449' OR
                             v.dx2 BETWEEN 'j440'  AND  'j449' OR
                             v.dx3 BETWEEN 'j440'  AND  'j449' OR
                             v.dx4 BETWEEN 'j440'  AND  'j449' OR
                             v.dx5 BETWEEN 'j440'  AND  'j449'
                        )  
                  $criteria 
                  GROUP BY er.vn ";



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
    
    
     public function actionReport17($uclinic, $datestart, $dateend, $details) {
        // save log
        $this->SaveLog($this->dep_controller, 'report17', $this->getSession());

        $report_name = 'คนไข้ COPD มีการ Refer ที่ ER';

        if ($uclinic != "") {

            if ($uclinic == 1) {
                $join_opd = ' ';
                $criteria = ' ';
                $report_name = 'คนไข้ COPD มีการ Refer ที่ ER (ยอดคนไข้ทั่วไป + คนไข้ใน clinic copd)';
            } else if ($uclinic == 2) {
                $join_opd = ' left outer join clinicmember c on c.hn = v.hn ';
                $criteria = ' and c.clinic = "005" ';
                $report_name = 'คนไข้ COPD มีการ Refer ที่ ER (เฉพาะคนไข้ใน clinic copd)';
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
                               v.pdx BETWEEN 'j440'  AND  'j449' OR
                               v.dx0 BETWEEN 'j440'  AND  'j449' OR
                               v.dx1 BETWEEN 'j440'  AND  'j449' OR
                               v.dx2 BETWEEN 'j440'  AND  'j449' OR
                               v.dx3 BETWEEN 'j440'  AND  'j449' OR
                               v.dx4 BETWEEN 'j440'  AND  'j449' OR
                               v.dx5 BETWEEN 'j440'  AND  'j449'

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


            return $this->render('report17', [
                        'dataProvider' => $dataProvider,
                        'report_name' => $report_name,
                        'details' => $details,
            ]);
        }
    }


     public function actionReport18($uclinic, $datestart, $dateend, $details) {
        // save log
        $this->SaveLog($this->dep_controller, 'report18', $this->getSession());

        $report_name = 'คนไข้ COPD มีการ Admit ที่ ER';

        if ($uclinic != "") {

            if ($uclinic == 1) {
                $join_opd = ' ';
                $criteria = ' ';
                $report_name = 'คนไข้ COPD มีการ Admit ที่ ER (ยอดคนไข้ทั่วไป + คนไข้ใน clinic copd)';
            } else if ($uclinic == 2) {
                $join_opd = ' left outer join clinicmember c on c.hn = v.hn ';
                $criteria = ' and c.clinic = "005" ';
                $report_name = 'คนไข้ COPD มีการ Admit ที่ ER (เฉพาะคนไข้ใน clinic copd)';
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
                  AND a.pdx BETWEEN 'J440' AND 'J449'
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


            return $this->render('report18', [
                        'dataProvider' => $dataProvider,
                        'report_name' => $report_name,
                        'details' => $details,
            ]);
        }
    }

    
    
    
    public function actionReport19($uclinic, $datestart, $dateend, $details) {
        // save log
        $this->SaveLog($this->dep_controller, 'report19', $this->getSession());

        $report_name = 'คนไข้ COPD Re-Visit ภายใน 48 ชม. ที่ ER';

        if ($uclinic != "") {

            if ($uclinic == 1) {
                $join_opd = ' ';
                $criteria = ' ';
                $report_name = 'คนไข้ COPD Re-Visit ภายใน 48 ชม. ที่ ER (ยอดคนไข้ทั่วไป + คนไข้ใน clinic asthma) (นับเป็นคน)';
            } else if ($uclinic == 2) {
                $join_opd = ' left outer join clinicmember c on c.hn = v.hn ';
                $criteria = ' and c.clinic = "005" ';
                $report_name = 'คนไข้ COPD Re-Visit ภายใน 48 ชม. ที่ ER (เฉพาะคนไข้ใน clinic asthma) (นับเป็นคน)';
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
                       v.pdx BETWEEN 'J440' AND 'J449'
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


            return $this->render('report19', [
                        'dataProvider' => $dataProvider,
                        'report_name' => $report_name,
                        'details' => $details,
            ]);
        }
    }
    
        
    public function actionReport20($uclinic, $datestart, $dateend, $details) {
        // save log
        $this->SaveLog($this->dep_controller, 'report20', $this->getSession());

        $report_name = 'รายงานจำนวนคนไข้ ที่มีรหัสวินิจฉัย j441 และมีอายุมากกว่า 15 ปี ';

        if ($uclinic != "") {

            if ($uclinic == 1) {
                $join_opd = ' ';
                $criteria = ' ';
                $report_name = 'รายงานจำนวนคนไข้ (เฉพาะที่ ER) ที่มีรหัสวินิจฉัย j441  และมีอายุมากกว่า 15 ปี  (คนไข้ทั่วไป + คลินิก COPD) (รายคน)';
            } else if ($uclinic == 2) {
                $join_opd = ' left outer join clinicmember c on c.hn = v.hn ';
                $criteria = ' and c.clinic = "005" ';
                $report_name = 'รายงานจำนวนคนไข้ (เฉพาะที่ ER) ที่มีรหัสวินิจฉัย j441 และมีอายุมากกว่า 15 ปี  (เฉพาะคนไข้ในคลินิก COPD) (รายคน)';
            }


            $sql = "
                              SELECT
                                     v.hn ,concat(p.pname,p.fname,'  ',p.lname) as pt_name,
                                     v.age_y
                               FROM vn_stat v
                               LEFT OUTER JOIN patient p on p.hn = v.hn
                               RIGHT OUTER JOIN  er_regist e on e.vn = v.vn
                               $join_opd
                               WHERE 
                                  v.vstdate BETWEEN $datestart AND $dateend  
                                  AND 
                                    v.pdx = 'j441'
                                  AND
                                    v.age_y > 15  
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


            return $this->render('report12', [
                        'dataProvider' => $dataProvider,
                        'report_name' => $report_name,
                        'details' => $details,
            ]);
        }
    }

// จบ function
    
    
    
}


