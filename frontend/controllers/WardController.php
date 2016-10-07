<?php

namespace frontend\controllers;

class WardController extends \yii\web\Controller {

    public function actionReport1($datestart, $dateend, $details) {
        $count_day = (strtotime($dateend) - strtotime($datestart)) / ( 60 * 60 * 24 ) +1;
  
        $report_name = " รายงานผู้ป่วยใน รง.505";
        $sql = "SELECT 'รายงานอัตราการครองเตียง(Bed Occupancy Rate)' as name,sum(admdate) * 100/(30*$count_day) as result FROM an_stat   WHERE  dchdate BETWEEN $datestart AND $dateend 
               union
                SELECT 'อัตราการใช้เตียง (Bed Turnover Rate)' as name,count(distinct(an)) / 30 as result FROM an_stat   WHERE  dchdate BETWEEN $datestart AND $dateend
               union
                SELECT 'วันนอนเฉลี่ยผู้ป่วยใน(Length of Stay)' as name,sum(admdate) / count(distinct(an)) as result FROM an_stat   WHERE  dchdate BETWEEN $datestart AND $dateend
               union
               select 'อัตราผู้ป่วยในต่อผู้ป่วยนอก' as name,count(distinct(an)) /
                        (
                            select count(distinct(vn)) from vn_stat where vstdate between $datestart AND $dateend
                        )
                as result FROM an_stat   WHERE  dchdate BETWEEN $datestart AND $dateend
               union
               
                select 'วันนอนเฉลี่ยแยกรายสิทธิ UC' as name,sum(admdate) / count(distinct(an)) as result
                 FROM an_stat   WHERE  dchdate BETWEEN $datestart AND $dateend  AND
                pttype in (89,56,57,52,54)
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

        return $this->render('report1', [
                    'dataProvider' => $dataProvider,
                    'report_name' => $report_name,
                    'details' => $details,
        ]); 
    }
    
    
     public function actionReport2($datestart, $dateend, $details) {
       
        $report_name = "คนไข้ IPD RE-ADMIT ภายใน 28 วัน ด้วยโรคเดิม";
        $sql = "select q3.hn,concat(patient.pname,patient.fname,'  ',patient.lname) as ptname,q3.AN_new ,q3.regdate_AN_New ,q3.dcdate_AN_New ,q3.AN_Old as AN_old
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

q1.regdate between $datestart AND $dateend ) as q3  on q3.hn = patient.hn ";

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
                    'details' => $details,
        ]); 
    }
    
    
     public function actionReport3($datestart, $dateend, $details) {
       
        $report_name = "รายงานอันดับโรคคนไข้ IPD RE-ADMIT ภายใน 28 วัน ด้วยโรคเดิม";
        $sql = "select q3.icd10_1,icd.name as icd_name,count(q3.icd10_1)   as count_icd

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

           q1.regdate between $datestart AND $dateend ) as q3  on q3.hn = patient.hn

           left outer join icd101 icd on icd.code = q3.icd10_1

           group by q3.icd10_1
           order by count_icd desc ";

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
