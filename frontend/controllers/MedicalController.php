<?php

namespace frontend\controllers;

class MedicalController extends \yii\web\Controller {
    /*  รายงานสรุปคนไข้ OPD แยกรายเดือน */

    public function actionReport1($datestart, $dateend, $details) {

        $report_name = "รายงานสรุปคนไข้ OPD แยกรายเดือน";

        $sql = "SELECT

CONCAT(MONTH(v.vstdate),'-',YEAR(v.vstdate))  as vstmonth ,
COUNT(v.vn) as count_vn_opd  ,
COUNT(distinct(v.hn)) as count_hn_opd ,

(
      SELECT
            count(distinct(s.hn)) as count_hn_new_year
      FROM vn_stat s
      WHERE
           s.count_in_year = 0  and   (CONCAT(YEAR(s.vstdate),'-',MONTH(s.vstdate)) = CONCAT(YEAR(v.vstdate),'-',MONTH(v.vstdate)))
)     AS count_in_new_year,


SUM(v.income) as Income

FROM vn_stat v
WHERE  v.vstdate BETWEEN $datestart AND $dateend
GROUP BY  CONCAT(YEAR(v.vstdate),'-',MONTH(v.vstdate))
ORDER BY  v.vstdate ";


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

        $report_name = "รายงานสรุปคนไข้ IPD (IPD+LR) แยกรายเดือน";

        $sql = "SELECT

CONCAT(MONTH(a.dchdate),'-',YEAR(a.dchdate))  as group_date ,
COUNT(a.an) as count_an_ipd  ,
COUNT(distinct(a.hn)) as count_hn_ipd ,
SUM(a.admdate) as wunnon,
SUM(a.income) as income,

(
      SELECT
            count(distinct(s.hn)) as count_hn_new_year
      FROM an_stat s
      WHERE
           s.count_in_year = 0 and   (CONCAT(YEAR(s.dchdate),'-',MONTH(s.dchdate)) = CONCAT(YEAR(a.dchdate),'-',MONTH(a.dchdate)))

)     AS count_in_year

FROM an_stat a

WHERE  a.dchdate BETWEEN $datestart AND $dateend

group by  CONCAT(YEAR(a.dchdate),'-',MONTH(a.dchdate))

order by a.dchdate ";


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

        $report_name = "รายงานทะเบียนผู้เสียชีวิต";

        $sql = "SELECT

pt.hn,pt.cid,concat(pt.pname,pt.fname,'  ',pt.lname) as ptname  ,
pt.birthday,pt.deathday,  death_diag_1 as death_diag_a , death_diag_2 as death_diag_b,death_diag_3 as death_diag_c,death_diag_4 as death_diag_d

from death d  

left outer join patient pt on pt.hn = d.hn
left outer join icd101 i1 on i1.code=pt.death_diag  

where d.death_date between $datestart and $dateend ";


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

        $report_name = "รายงานตรวจสอบ RW ที่มีค่าว่าง (Null)";

        $sql = "select v.vn,v.hn,v.vstdate,o.rw,concat(p.pname,p.fname,'  ',p.lname) as pt_name

from vn_stat   v

left outer join ovst_drgs o on o.vn = v.vn
left outer join patient p on p.hn = v.hn

where v.vstdate between $datestart  and $dateend and o.rw is null

order by v.vstdate ";


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

        $report_name = "รายงานตรวจสอบเวชระเบียนผู้ป่วยนอก";

        $sql = "SELECT

v.vstdate,

(
  select count(n.vn) from vn_stat n where n.vstdate = v.vstdate
) as count_all_visit ,

(
  select count(s.vn) from vn_stat s

  left outer join vn_opd_complete o on o.vn = s.vn

  where s.vstdate = v.vstdate AND

  s.vn  in (select vn from vn_opd_complete where complete = 'Y')
)  as count_folder_complete  ,


(
   select count(distinct(s.vn)) from vn_stat s
  left outer join vn_opd_complete o on o.vn = s.vn
  left outer join ksklog k on k.detail = s.vn
  where s.vstdate = v.vstdate  AND  Time(k.logtime) >= '07:00:00' AND Time(k.logtime) <= '15:30:00'  AND k.loginname = 'ศุภนารี' AND s.vn  in (select vn from vn_opd_complete where complete = 'Y')
) as count_in_time ,


(
   SELECT count(distinct(s.vn))  FROM vn_stat s

  left outer join vn_opd_complete o on o.vn = s.vn

  left outer join ksklog k on k.detail = s.vn

  WHERE s.vstdate =  v.vstdate   AND  Time(k.logtime) >= '07:00:00' AND   
  
  Time(k.logtime) <= '15:30:00'  AND k.loginname = 'ศุภนารี'
  
  AND s.vstdate != DATE(k.logtime)  AND  s.vn = k.detail

  AND s.vn  in (select vn from vn_opd_complete where complete = 'Y')

) as difference_date_receive, 

(
     SELECT count(distinct(s.vn))  FROM vn_stat  s

    LEFT OUTER JOIN ovst_drgs o ON o.vn = s.vn

    WHERE s.vstdate = v.vstdate  AND o.rw = 0

)  as count_rw_o, 

(
     SELECT count(distinct(s.vn))  FROM vn_stat  s

    WHERE s.vstdate = v.vstdate  AND s.pdx=''

)  as count_pdx_empty

FROM vn_stat v
WHERE v.vstdate between $datestart and $dateend
GROUP BY v.vstdate
ORDER BY v.vstdate ";


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
    
    public function actionReport6($details) {

        $report_name = "รายงานตรวจสอบคนไข้นอกเขต รพ.ละแม แต่ลง Typearea เป็นคนในเขต";

        $sql = "SELECT p.hn,concat(p.pname,p.fname,'   ',p.lname) as pt_name,
            concat(p.addrpart,' ม.',p.moopart,' ', t.full_name) as full_address,p.type_area
        FROM patient    p
        LEFT OUTER JOIN thaiaddress t on t.addressid = concat(p.chwpart,p.amppart,p.tmbpart)
        WHERE concat(p.chwpart,p.amppart,p.tmbpart) = 860501
        and p.moopart not in (1,2,3,4,5,6,7,9,10,12,14)  and p.type_area = 1";


        try {
            $rawData = \yii::$app->db->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }

        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => FALSE,
        ]);

        return $this->render('report6', [
                    'dataProvider' => $dataProvider,
                    'rawData' => $rawData,
                    'report_name' => $report_name,
                    'details' => $details,
        ]);
    }

}
