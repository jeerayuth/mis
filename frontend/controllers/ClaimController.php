<?php

namespace frontend\controllers;

use Yii;
use frontend\components\CommonController;

class ClaimController extends CommonController {
    public $dep_controller = 'claim';

    public function actionReport1($datestart, $dateend, $details) {
        // save log
        $this->SaveLog($this->dep_controller, 'report1', $this->getSession());

        $report_name = "รายงานจำนวนคนไข้จ่ายค่าธรรมเนียม 30 บาท(เฉพาะสิทธิ์ 89,52,56)";
        $sql = "SELECT
            v.vn,v.hn,v.cid,
            concat(p.pname,p.fname,'  ',p.lname) as patient_name ,
            v.pttype,pp.name as pttype_name,
            concat(DAY(v.vstdate),'/',MONTH(v.vstdate),'/',(YEAR(v.vstdate)+543)) as vstdate  
        FROM vn_stat v	
        LEFT OUTER JOIN patient p ON p.hn = v.hn
        LEFT OUTER JOIN pttype pp ON pp.pttype = v.pttype
        WHERE 
            v.pttype in ('89','52','56') 
        AND 
            v.vstdate between $datestart and $dateend 
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
        // save log
        $this->SaveLog($this->dep_controller, 'report2', $this->getSession());

        $report_name = "จำนวนคนไข้ตามสิทธิ์ UC ทั้งหมด";
        $sql = "SELECT
	v.vn,v.hn,v.cid,
	concat(p.pname,p.fname,'  ',p.lname) as patient_name ,
	v.pttype,v.pcode,concat(DAY(v.vstdate),'/',MONTH(v.vstdate),'/',(YEAR(v.vstdate)+543)) as vstdate,pp.name as pttype_name   
        FROM 
            vn_stat v
        LEFT OUTER JOIN patient p ON p.hn = v.hn
        LEFT OUTER JOIN pttype pp ON pp.pttype = v.pttype
        WHERE 
            v.pcode NOT IN('A1','A2','A7','A8','A9','AL') 
        AND 
            v.vstdate BETWEEN $datestart and $dateend ";

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
    
    public function actionReport3($datestart, $dateend, $details) {
        // save log
        $this->SaveLog($this->dep_controller, 'report3', $this->getSession());

        $report_name = "รายงานพม่า (ที่มีรหัสวินิจฉัยหลักเป็น Z008,Z000)";
        $sql = "SELECT  v.vn,v.hn,concat(DAY(v.vstdate),'/',MONTH(v.vstdate),'/',(YEAR(v.vstdate)+543)) as vstdate ,v.hospmain ,p.cid,p.pname,concat(p.fname,'  ',p.lname) as pt_name,p.birthday ,p.nationality ,
        n.name as nation_name ,s.name as sex_name,p.informaddr  ,p.informname,p.informname ,p.hometel,
		dr.name as doc_name, st.service5 as startexam, 'แรงงาน' as type_name

        FROM vn_stat v
        left outer join ovst ov on ov.vn = v.vn
        left outer join service_time st on st.vn = v.vn
        left outer join doctor dr on dr.code=ov.doctor
        left outer join patient p on p.hn = v.hn
        left outer join nationality n on n.nationality = p.nationality
        left outer join sex s on s.code = p.sex
        WHERE   v.vstdate between $datestart and $dateend
        and v.pdx in ('Z008','Z000') and p.nationality!=99 and v.pttype in ('42', '43', '44','45') 
        ORDER BY v.vstdate,st.service5 ";

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
                    'report_name' => $report_name,
                    'details' => $details,
        ]);
    }
    
    
    
    public function actionReport4($datestart, $dateend, $details) {
        // save log
        $this->SaveLog($this->dep_controller, 'report4', $this->getSession());

        $report_name = "รายงานประกันสังคมผู้ป่วยนอก";
        $sql = "SELECT concat(DAY(v.vstdate),'/',MONTH(v.vstdate),'/',(YEAR(v.vstdate)+543)) as vst_date,v.hn,v.vn,concat(p.pname,p.fname,'  ',p.lname) as patient_name
                ,v.cid,s.name as sex,v.age_y,
                
                v.pdx as pdx,
                 concat(
                           if(v.dx0 is not null,concat(v.dx0,'   '),' '),
                           if(v.dx1 is not null,concat(v.dx1,'   '),' '),
                           if(v.dx2 is not null,concat(v.dx2,'   '),' '),
                           if(v.dx3 is not null,concat(v.dx3,'   '),' '),
                           if(v.dx4 is not null,concat(v.dx4,'   '),' '),
                           if(v.dx5 is not null,concat(v.dx5,'   '),' ')
                           )  as second_diag,
                     concat(
                            if(v.op0 is not null, concat(v.op0,'   '),' '),
                            if(v.op1 is not null, concat(v.op1,'   '),' '),
                            if(v.op2 is not null, concat(v.op2,'   '),' '),
                            if(v.op3 is not null, concat(v.op3,'   '),' '),
                            if(v.op4 is not null, concat(v.op4,'   '),' '),
                            if(v.op5 is not null, concat(v.op5,'   '),' ')
                            ) as icd9,
                

                dr.name as doc_name,dr.licenseno,v.income,v.paid_money,remain_money,uc_money,item_money,
                ov.vsttime as vst_time,v.inc12 as v_drug,v.inc04 as v_xray,v.inc01 as v_lab,
                (v.inc06+v.inc07+v.inc13) as v_icd9,
                (v.inc05+v.inc09+v.inc02+v.inc03+v.inc08+v.inc11+v.inc14+v.inc15+v.inc16+v.inc17) as v_other
            FROM vn_stat v
            left outer join patient p on p.hn=v.hn 
            left outer join ovst ov on ov.vn=v.vn 
            left outer join icd101 ic on ic.code=v.pdx
            left outer join doctor dr on dr.code=ov.doctor
            left outer join sex s on s.code=v.sex 
            where  v.pcode='A7' and v.vstdate between $datestart and $dateend  and pdx not like 'z35%' and pdx not like 'z36%'
            and pdx  not in('z32','z320','z321','z33','z34','z340','z348','z349') and pdx <>'' and pdx not like '%xx%' and pdx is not null
            and v.hn not in (select distinct(d.hn) from dtmain d where d.vn = v.vn) 
            GROUP BY v.vn 
            ORDER BY v.vstdate,v.hn ";

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
                    'report_name' => $report_name,
                    'details' => $details,
        ]);
    }
    
    
      public function actionReport5($datestart, $dateend, $details) {
          // save log
        $this->SaveLog($this->dep_controller, 'report5', $this->getSession());

        $report_name = "รายงานประกันสังคมผู้ป่วยใน";
        $sql = " SELECT 
                    p.cid,a.an,concat(DAY(a.regdate),'/',MONTH(a.regdate),'/',(YEAR(a.regdate)+543)) as regdate,
                    concat(DAY(a.dchdate),'/',MONTH(a.dchdate),'/',(YEAR(a.dchdate)+543)) as dchdate,
                    a.hn,a.vn,concat(DAY(a.regdate),'/',MONTH(a.regdate),'/',(YEAR(a.regdate)+543)) as ovst_date,
                    concat(p.pname,p.fname,'  ',p.lname) as patient_name, 
                    s.name as sex,a.age_y,
                    
                    a.pdx as pdx,
                    
                    concat(
                           if(a.dx0 is not null,concat(a.dx0,'   '),' '),
                           if(a.dx1 is not null,concat(a.dx1,'   '),' '),
                           if(a.dx2 is not null,concat(a.dx2,'   '),' '),
                           if(a.dx3 is not null,concat(a.dx3,'   '),' '),
                           if(a.dx4 is not null,concat(a.dx4,'   '),' '),
                           if(a.dx5 is not null,concat(a.dx5,'   '),' ')
                           )  as second_diag,
                     concat(
                            if(a.op0 is not null, concat(a.op0,'   '),' '),
                            if(a.op1 is not null, concat(a.op1,'   '),' '),
                            if(a.op2 is not null, concat(a.op2,'   '),' '),
                            if(a.op3 is not null, concat(a.op3,'   '),' '),
                            if(a.op4 is not null, concat(a.op4,'   '),' '),
                            if(a.op5 is not null, concat(a.op5,'   '),' ')
                            ) as icd9,
                    
                    
                    dr.name as doc_name,dr.licenseno,a.item_money, i.rw 
                FROM an_stat a 
                    left outer join patient p on p.hn=a.hn 
                    left outer join ovst ov on ov.vn=a.vn
                    left outer join doctor dr on dr.code=ov.doctor 
                    left outer join sex s on s.code=a.sex 
                    left outer join ipt i on i.an = a.an  

                WHERE  a.pcode='A7' and a.regdate between $datestart and $dateend and a.pdx not between 'k000' and  'k089' and pdx !='o800' and pdx!= 'z370' and pdx not like 'z35%' and pdx not like 'z36%' 
                and pdx  not in('z32','z320','z321','z33','z34','z340','z348','z349')   and pdx not like '%xx%' 
                GROUP BY a.vn order by a.regdate,a.hn  ";

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
                    'report_name' => $report_name,
                    'details' => $details,
        ]);
    }
    
    
     public function actionReport6($datestart, $dateend, $details) {
         // save log
        $this->SaveLog($this->dep_controller, 'report6', $this->getSession());

        $report_name = "ประกันสังคมผู้ป่วยนอก สุราษฎร์ธานี";
        $sql = "SELECT concat(DAY(v.vstdate),'/',MONTH(v.vstdate),'/',(YEAR(v.vstdate)+543)) as vst_date,v.hn,v.vn,concat(p.pname,p.fname,'  ',p.lname) as patient_name
                ,v.cid,s.name as sex,v.age_y,
                
                v.pdx as pdx,
                concat(
                           if(v.dx0 is not null,concat(v.dx0,'   '),' '),
                           if(v.dx1 is not null,concat(v.dx1,'   '),' '),
                           if(v.dx2 is not null,concat(v.dx2,'   '),' '),
                           if(v.dx3 is not null,concat(v.dx3,'   '),' '),
                           if(v.dx4 is not null,concat(v.dx4,'   '),' '),
                           if(v.dx5 is not null,concat(v.dx5,'   '),' ')
                           )  as second_diag,
                     concat(
                            if(v.op0 is not null, concat(v.op0,'   '),' '),
                            if(v.op1 is not null, concat(v.op1,'   '),' '),
                            if(v.op2 is not null, concat(v.op2,'   '),' '),
                            if(v.op3 is not null, concat(v.op3,'   '),' '),
                            if(v.op4 is not null, concat(v.op4,'   '),' '),
                            if(v.op5 is not null, concat(v.op5,'   '),' ')
                            ) as icd9,
                
                dr.name as doc_name,ov.vsttime as vst_time,v.income,v.paid_money,remain_money,uc_money,item_money,
                v.inc12 as v_drug,v.inc04 as v_xray,v.inc01 as v_lab,(v.inc06+v.inc07+v.inc13) as v_icd9 ,(v.inc05+v.inc09+v.inc02+v.inc03+v.inc08+v.inc11+v.inc14+v.inc15+v.inc16+v.inc17) as v_other 
            FROM vn_stat v 
                left outer join patient p on p.hn=v.hn 
                left outer join ovst ov on ov.vn=v.vn 
                left outer join icd101 ic on ic.code=v.pdx 
                left outer join doctor dr on dr.code=ov.doctor 
                left outer join sex s on s.code=v.sex 
            WHERE  v.pttype='32' and v.vstdate between $datestart and $dateend  and pdx not like 'z35%' and pdx not like 'z36%'
                and pdx  not in('z32','z320','z321','z33','z34','z340','z348','z349') and pdx <>'' and pdx not like '%xx%' and pdx is not null

            and v.hn not in (select distinct(d.hn) from dtmain d where d.vn = v.vn and icd !='Z012')
            GROUP BY v.vn order by v.vstdate,v.hn  ";

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
                    'report_name' => $report_name,
                    'details' => $details,
        ]);
    }
    
    
    
     public function actionReport7($datestart, $dateend, $details) {
         // save log
        $this->SaveLog($this->dep_controller, 'report7', $this->getSession());

        $report_name = "ประกันสังคมชุมพร เรื้อรัง";
        $sql = "SELECT
            concat(pt.pname,pt.fname,'   ',pt.lname) as ptname,pt.cid,
            pt.hn,concat(DAY(ov.vstdate),'/',MONTH(ov.vstdate),'/',(YEAR(ov.vstdate)+543)) as vst_date,  
            ov.pdx,ov.dx0,ov.dx1,ov.dx2,ov.dx3,ov.dx4,ov.dx5,       
            ov.inc_drug,ov.inc03,  
            ov.inc04,ov.dx_doctor, d.licenseno, d.name as doctor_name , ov.income,
            ov.inc12,
            (ov.inc02+ov.inc03+ov.inc04+ov.inc05+ov.inc06+
            ov.inc07+ov.inc08+ov.inc09+ov.inc10+ov.inc11+
            ov.inc13+ov.inc14+ov.inc15+ov.inc16) as other_income ,
            ov.inc01, ov.income

        FROM vn_stat ov ,patient pt ,ovst ovst, doctor d
        WHERE  
            ov.vn = ovst.vn and pt.hn=ov.hn AND 
            ov.vstdate BETWEEN $datestart AND $dateend AND
            dx_doctor = d.code AND
            ov.pttype='31' AND
                    ((ov.pdx like 'b2%')
                 or (ov.dx0 like 'b2%') 
                 or (ov.dx1 like 'b2%') 
                 or (ov.dx2 like 'b2%') 
                 or (ov.dx3 like 'b2%') 
                 or (ov.dx4 like 'b2%') 
                 or (ov.dx5 like 'b2%') 


                 or (ov.pdx like 'e1%') 
                 or (ov.dx0 like 'e1%') 
                 or (ov.dx1 like 'e1%') 
                 or (ov.dx2 like 'e1%') 
                 or (ov.dx3 like 'e1%') 
                 or (ov.dx4 like 'e1%') 
                 or (ov.dx5 like 'e1%') 
                 or (ov.pdx like 'e78%')
                 or (ov.dx0 like 'e78%')
                 or (ov.dx1 like 'e78%')
                 or (ov.dx2 like 'e78%')
                 or (ov.dx3 like 'e78%')
                 or (ov.dx4 like 'e78%')
                 or (ov.dx5 like 'e78%')
                  

                 /* เพิ่มเติมเมื่อวันที่ 2017-10-17 */
                 or (ov.pdx between 'e000' and 'e079')
                 or (ov.dx0 between 'e000' and 'e079')
                 or (ov.dx1 between 'e000' and 'e079')
                 or (ov.dx2 between 'e000' and 'e079')
                 or (ov.dx3 between 'e000' and 'e079')
                 or (ov.dx4 between 'e000' and 'e079')
                 or (ov.dx5 between 'e000' and 'e079')
                 /* end */

                 or (ov.dx0 like 'e05%')
                 or (ov.dx1 like 'e05%')
                 or (ov.dx2 like 'e05%')
                 or (ov.dx3 like 'e05%')
                 or (ov.dx4 like 'e05%')
                 or (ov.dx5 like 'e05%')
                 or (ov.pdx like 'i1%') 
                 or (ov.dx0 like 'i1%') 
                 or (ov.dx1 like 'i1%') 
                 or (ov.dx2 like 'i1%') 
                 or (ov.dx3 like 'i1%') 
                 or (ov.dx4 like 'i1%') 
                 or (ov.dx5 like 'i1%')

                 or (ov.pdx like 'i64')
                 or (ov.dx0 like 'i64')
                 or (ov.dx1 like 'i64')
                 or (ov.dx2 like 'i64')
                 or (ov.dx3 like 'i64')
                 or (ov.dx4 like 'i64')
                 or (ov.dx5 like 'i64')

                 or (ov.pdx like 'i698')
                 or (ov.dx0 like 'i698')
                 or (ov.dx1 like 'i698')
                 or (ov.dx2 like 'i698')
                 or (ov.dx3 like 'i698')
                 or (ov.dx4 like 'i698')
                 or (ov.dx5 like 'i698')

                 or (ov.pdx like 'n18%') 
                 or (ov.dx0 like 'n18%') 
                 or (ov.dx1 like 'n18%') 
                 or (ov.dx2 like 'n18%') 
                 or (ov.dx3 like 'n18%') 
                 or (ov.dx4 like 'n18%') 
                 or (ov.dx5 like 'n18%') 
                 or (ov.pdx like 'c0%') 
                 or (ov.dx0 like 'c0%') 
                 or (ov.dx1 like 'c0%') 
                 or (ov.dx2 like 'c0%') 
                 or (ov.dx3 like 'c0%') 
                 or (ov.dx4 like 'c0%') 
                 or (ov.dx5 like 'c0%') 
                 or (ov.pdx like 'c1%') 
                 or (ov.dx0 like 'c1%') 
                 or (ov.dx1 like 'c1%') 
                 or (ov.dx2 like 'c1%') 
                 or (ov.dx3 like 'c1%') 
                 or (ov.dx4 like 'c1%') 
                 or (ov.dx5 like 'c1%') 
                 or (ov.pdx like 'c2%') 
                 or (ov.dx0 like 'c2%') 
                 or (ov.dx1 like 'c2%') 
                 or (ov.dx2 like 'c2%') 
                 or (ov.dx3 like 'c2%') 
                 or (ov.dx4 like 'c2%') 
                 or (ov.dx5 like 'c2%') 
                 or (ov.pdx like 'c3%') 
                 or (ov.dx0 like 'c3%') 
                 or (ov.dx1 like 'c3%') 
                 or (ov.dx2 like 'c3%') 
                 or (ov.dx3 like 'c3%') 
                 or (ov.dx4 like 'c3%') 
                 or (ov.dx5 like 'c3%') 
                 or (ov.pdx like 'c4%') 
                 or (ov.dx0 like 'c4%') 
                 or (ov.dx1 like 'c4%') 
                 or (ov.dx2 like 'c4%') 
                 or (ov.dx3 like 'c4%') 
                 or (ov.dx4 like 'c4%') 
                 or (ov.dx5 like 'c4%') 
                 or (ov.pdx like 'c5%') 
                 or (ov.dx0 like 'c5%') 
                 or (ov.dx1 like 'c5%') 
                 or (ov.dx2 like 'c5%') 
                 or (ov.dx3 like 'c5%') 
                 or (ov.dx4 like 'c5%') 
                 or (ov.dx5 like 'c5%') 
                 or (ov.pdx like 'c6%') 
                 or (ov.dx0 like 'c6%') 
                 or (ov.dx1 like 'c6%') 
                 or (ov.dx2 like 'c6%') 
                 or (ov.dx3 like 'c6%') 
                 or (ov.dx4 like 'c6%') 
                 or (ov.dx5 like 'c6%') 
                 or (ov.pdx like 'c7%') 
                 or (ov.dx0 like 'c7%') 
                 or (ov.dx1 like 'c7%') 
                 or (ov.dx2 like 'c7%') 
                 or (ov.dx3 like 'c7%') 
                 or (ov.dx4 like 'c7%') 
                 or (ov.dx5 like 'c7%') 
                 or (ov.pdx like 'c8%') 
                 or (ov.dx0 like 'c8%') 
                 or (ov.dx1 like 'c8%') 
                 or (ov.dx2 like 'c8%') 
                 or (ov.dx3 like 'c8%') 
                 or (ov.dx4 like 'c8%') 
                 or (ov.dx5 like 'c8%') 
                 or (ov.pdx like 'c9%') 
                 or (ov.dx0 like 'c9%') 
                 or (ov.dx1 like 'c9%') 
                 or (ov.dx2 like 'c9%') 
                 or (ov.dx3 like 'c9%') 
                 or (ov.dx4 like 'c9%') 
                 or (ov.dx5 like 'c9%'))
                ORDER BY ov.vstdate ";

 

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
                    'report_name' => $report_name,
                    'details' => $details,
        ]);
    }

    
    
     public function actionReport8($datestart, $dateend, $details) {
         // save log
        $this->SaveLog($this->dep_controller, 'report8', $this->getSession());

        $report_name = "ประกันสังคมทันตกรรม";
        $sql = "SELECT 
                    concat(DAY(v.vstdate),'/',MONTH(v.vstdate),'/',(YEAR(v.vstdate)+543)) as vst_date,
                    v.hn,v.vn,concat(p.pname,p.fname,'  ',p.lname) as patient_name
                    ,v.cid,s.name as sex,v.age_y,v.pdx as pdx,
                    concat(
                           if(v.dx0 is not null,concat(v.dx0,'   '),' '),
                           if(v.dx1 is not null,concat(v.dx1,'   '),' '),
                           if(v.dx2 is not null,concat(v.dx2,'   '),' '),
                           if(v.dx3 is not null,concat(v.dx3,'   '),' '),
                           if(v.dx4 is not null,concat(v.dx4,'   '),' '),
                           if(v.dx5 is not null,concat(v.dx5,'   '),' ')
                           )  as second_diag,
                    concat(
                            if(v.op0 is not null, concat(v.op0,'   '),' '),
                            if(v.op1 is not null, concat(v.op1,'   '),' '),
                            if(v.op2 is not null, concat(v.op2,'   '),' '),
                            if(v.op3 is not null, concat(v.op3,'   '),' '),
                            if(v.op4 is not null, concat(v.op4,'   '),' '),
                            if(v.op5 is not null, concat(v.op5,'   '),' ')
                            ) as icd9,
                            dr.name as doc_name,dr.licenseno,v.income,v.paid_money,remain_money,uc_money,item_money,
                            ov.vsttime as vst_time,v.inc12 as v_drug,v.inc04 as v_xray,v.inc01 as v_lab,
                            (v.inc06+v.inc07+v.inc13) as v_icd9,
                            (v.inc05+v.inc09+v.inc02+v.inc03+v.inc08+v.inc11+v.inc14+v.inc15+v.inc16+v.inc17) as v_other
                    FROM vn_stat v
                    left outer join patient p on p.hn=v.hn 
                    left outer join ovst ov on ov.vn=v.vn 
                    left outer join icd101 ic on ic.code=v.pdx
                    left outer join doctor dr on dr.code=ov.doctor
                    left outer join sex s on s.code=v.sex

                    WHERE  
                        v.pttype='34' and v.vstdate between $datestart and $dateend
                    GROUP BY v.vn 
                    ORDER BY v.vstdate,v.hn ";

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
                    'report_name' => $report_name,
                    'details' => $details,
        ]);
     }
     
     
      public function actionReport9($datestart, $dateend, $details) {
          // save log
        $this->SaveLog($this->dep_controller, 'report9', $this->getSession());

        $report_name = "ประกันสังคม ANC ผู้ป่วยนอก";
        $sql = "SELECT 
                    p.cid,v.hn,v.vn,concat(DAY(v.vstdate),'/',MONTH(v.vstdate),'/',(YEAR(v.vstdate)+543)) as ovst_date,concat(p.pname,p.fname,'  ',p.lname) as patient_name
                    ,s.name as sex,v.age_y,          
                    v.pdx as pdx,              
                    concat(
                           if(v.dx0 is not null,concat(v.dx0,'   '),' '),
                           if(v.dx1 is not null,concat(v.dx1,'   '),' '),
                           if(v.dx2 is not null,concat(v.dx2,'   '),' '),
                           if(v.dx3 is not null,concat(v.dx3,'   '),' '),
                           if(v.dx4 is not null,concat(v.dx4,'   '),' '),
                           if(v.dx5 is not null,concat(v.dx5,'   '),' ')
                           )  as second_diag,
                     concat(
                            if(v.op0 is not null, concat(v.op0,'   '),' '),
                            if(v.op1 is not null, concat(v.op1,'   '),' '),
                            if(v.op2 is not null, concat(v.op2,'   '),' '),
                            if(v.op3 is not null, concat(v.op3,'   '),' '),
                            if(v.op4 is not null, concat(v.op4,'   '),' '),
                            if(v.op5 is not null, concat(v.op5,'   '),' ')
                            ) as icd9,
                    
                    dr.name as doc_name,dr.licenseno,        
                    if(v.item_money>700,700,v.item_money) as item_money
                    

                FROM vn_stat v 
                    left outer join patient p on p.hn=v.hn 
                    left outer join ovst ov on ov.vn=v.vn 
                    left outer join doctor dr on dr.code=ov.doctor
                    left outer join sex s on s.code=v.sex 
                WHERE  
                    v.pcode='A7' and v.vstdate between $datestart and $dateend  and pdx not like 'k%'
                    and (pdx like 'z35%' or pdx like 'z36%' 
                    or pdx  in('z32','z320','z321','z33','z34','z340','z348','z349'))   
                    and p.pname not like '%?.?%'  and pdx <>'' and pdx not like '%xx%' and pdx is not null 
                GROUP BY v.vn order by v.vstdate,v.hn ";

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
                    'report_name' => $report_name,
                    'details' => $details,
        ]);
     }
     
     
     public function actionReport10($datestart, $dateend, $details) {
         // save log
        $this->SaveLog($this->dep_controller, 'report10', $this->getSession());

        $report_name = "สิทธิ์บัตรประกันสุขภาพถ้วนหน้า(UC) มีท,ไม่มีท เขตรอยต่อ(รวมผลวินิจฉัย E100 - E119 และ I10)";
        $sql = "SELECT 
                    concat(DAY(v.vstdate),'/',MONTH(v.vstdate),'/',(YEAR(v.vstdate)+543)) as vst_date,v.hn,v.vn,concat(p.pname,p.fname,'  ',p.lname) as patient_name
                    ,v.cid,s.name as sex,v.age_y,
                     
                    concat(
                           if(v.pdx is not null,concat(v.pdx,'   '),' '),
                           if(v.dx0 is not null,concat(v.dx0,'   '),' '),
                           if(v.dx1 is not null,concat(v.dx1,'   '),' '),
                           if(v.dx2 is not null,concat(v.dx2,'   '),' '),
                           if(v.dx3 is not null,concat(v.dx3,'   '),' '),
                           if(v.dx4 is not null,concat(v.dx4,'   '),' '),
                           if(v.dx5 is not null,concat(v.dx5,'   '),' ')
                           )  as diag,
                     concat(
                            if(v.op0 is not null, concat(v.op0,'   '),' '),
                            if(v.op1 is not null, concat(v.op1,'   '),' '),
                            if(v.op2 is not null, concat(v.op2,'   '),' '),
                            if(v.op3 is not null, concat(v.op3,'   '),' '),
                            if(v.op4 is not null, concat(v.op4,'   '),' '),
                            if(v.op5 is not null, concat(v.op5,'   '),' ')
                            ) as icd9,
                    
                    dr.name as doc_name,dr.licenseno,v.income,v.paid_money,remain_money,uc_money,item_money
                FROM vn_stat v
                    left outer join patient p on p.hn=v.hn 
                    left outer join ovst ov on ov.vn=v.vn 
                    left outer join icd101 ic on ic.code=v.pdx 
                    left outer join doctor dr on dr.code=ov.doctor 
                    left outer join sex s on s.code=v.sex 
                WHERE
                    v.pcode='UC' and v.vstdate between $datestart and $dateend
                    and v.pttype in('52','54')
                    and pdx !=''  and pdx is not null
                    group by v.vn order by v.vstdate,v.hn ";

        try {
            $rawData = \yii::$app->db->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }

        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => FALSE,
        ]);

        return $this->render('report10', [
                    'dataProvider' => $dataProvider,
                    'report_name' => $report_name,
                    'details' => $details,
        ]);
     }
     
     
      public function actionReport11($datestart, $dateend, $details) {
          // save log
        $this->SaveLog($this->dep_controller, 'report11', $this->getSession());

        $report_name = "รายงานสิทธิ์บัตรประกันสุขภาพถ้วนหน้า(UC) มีท,ไม่มีท เขตรอยต่อ (ผลวินิจฉัย E100 - E119,I10)";
        $sql = "select 
                    concat(DAY(v.vstdate),'/',MONTH(v.vstdate),'/',(YEAR(v.vstdate)+543)) as vst_date,v.hn,v.vn,concat(p.pname,p.fname,'  ',p.lname) as patient_name
                    ,v.cid,s.name as sex,v.age_y,               
                    concat(
                           if(v.pdx is not null,concat(v.pdx,'   '),' '),
                           if(v.dx0 is not null,concat(v.dx0,'   '),' '),
                           if(v.dx1 is not null,concat(v.dx1,'   '),' '),
                           if(v.dx2 is not null,concat(v.dx2,'   '),' '),
                           if(v.dx3 is not null,concat(v.dx3,'   '),' '),
                           if(v.dx4 is not null,concat(v.dx4,'   '),' '),
                           if(v.dx5 is not null,concat(v.dx5,'   '),' ')
                           )  as diag,
                     concat(
                            if(v.op0 is not null, concat(v.op0,'   '),' '),
                            if(v.op1 is not null, concat(v.op1,'   '),' '),
                            if(v.op2 is not null, concat(v.op2,'   '),' '),
                            if(v.op3 is not null, concat(v.op3,'   '),' '),
                            if(v.op4 is not null, concat(v.op4,'   '),' '),
                            if(v.op5 is not null, concat(v.op5,'   '),' ')
                            ) as icd9,
                
                dr.name as doc_name,dr.licenseno,v.income,v.paid_money,remain_money,uc_money,item_money 
                

                FROM vn_stat v 
                    left outer join patient p on p.hn=v.hn 
                    left outer join ovst ov on ov.vn=v.vn 
                    left outer join icd101 ic on ic.code=v.pdx 
                    left outer join doctor dr on dr.code=ov.doctor 
                    left outer join sex s on s.code=v.sex 
                WHERE  v.pcode='UC' and v.vstdate between $datestart and $dateend
                    and v.pttype in('52','54')
                    and((pdx between 'E100' and 'E119' )or(pdx='I10')
                    or ( dx0 between 'E100' and 'E119' )or(dx0='I10')
                    or ( dx1 between 'E100' and 'E119' )or(dx1='I10')
                    or ( dx2 between 'E100' and 'E119' )or(dx2='I10')
                    or ( dx3 between 'E100' and 'E119' )or(dx3='I10')
                    or ( dx4 between 'E100' and 'E119' )or(dx4='I10')
                    or ( dx5 between 'E100' and 'E119' )or(dx5='I10'))
                    and pdx !=''  and pdx is not null
                GROUP BY v.vn order by v.vstdate,v.hn ";

        try {
            $rawData = \yii::$app->db->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }

        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => FALSE,
        ]);

        return $this->render('report11', [
                    'dataProvider' => $dataProvider,
                    'report_name' => $report_name,
                    'details' => $details,
        ]);
     }
     
      public function actionReport12($datestart, $dateend, $details) {
          // save log
        $this->SaveLog($this->dep_controller, 'report12', $this->getSession());

        $report_name = "รายงานคนไข้ที่ใช้สิทธิ์โครงการจ่ายตรง (ผู้ป่วยนอก)";
        $sql = "
                SELECT 
                    concat(DAY(v.vstdate),'/',MONTH(v.vstdate),'/',(YEAR(v.vstdate)+543)) as vst_date,v.hn,v.vn,concat(p.pname,p.fname,'  ',p.lname) as patient_name
                    ,v.cid,s.name as sex,v.age_y,
                    v.pdx as pdx,
                    v.pttype,
                    concat(
                           if(v.dx0 is not null,concat(v.dx0,'   '),' '),
                           if(v.dx1 is not null,concat(v.dx1,'   '),' '),
                           if(v.dx2 is not null,concat(v.dx2,'   '),' '),
                           if(v.dx3 is not null,concat(v.dx3,'   '),' '),
                           if(v.dx4 is not null,concat(v.dx4,'   '),' '),
                           if(v.dx5 is not null,concat(v.dx5,'   '),' ')
                           )  as second_diag,
                     concat(
                            if(v.op0 is not null, concat(v.op0,'   '),' '),
                            if(v.op1 is not null, concat(v.op1,'   '),' '),
                            if(v.op2 is not null, concat(v.op2,'   '),' '),
                            if(v.op3 is not null, concat(v.op3,'   '),' '),
                            if(v.op4 is not null, concat(v.op4,'   '),' '),
                            if(v.op5 is not null, concat(v.op5,'   '),' ')
                            ) as icd9,
                    
                    dr.name as doc_name,dr.licenseno,v.income,v.paid_money,remain_money,uc_money,item_money
                FROM vn_stat v
                    left outer join patient p on p.hn=v.hn
                    left outer join ovst ov on ov.vn=v.vn
                    left outer join icd101 ic on ic.code=v.pdx
                    left outer join doctor dr on dr.code=ov.doctor
                    left outer join sex s on s.code=v.sex
                WHERE
                    v.pcode='A2' and v.vstdate between $datestart and $dateend
                    and v.pttype in('11','12')
                GROUP BY v.vn 
                ORDER BY v.vstdate,v.hn ";

        try {
            $rawData = \yii::$app->db->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }

        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => FALSE,
        ]);

        return $this->render('report12', [
                    'dataProvider' => $dataProvider,
                    'report_name' => $report_name,
                    'details' => $details,
        ]);
     }
     
     
      public function actionReport13($datestart, $dateend, $details) {
          // save log
        $this->SaveLog($this->dep_controller, 'report13', $this->getSession());

        $report_name = "รายงานคนไข้ที่ใช้สิทธิ์ อปท.เข้าโครงการจ่ายตรง (ผู้ป่วยนอก)";
        $sql = "SELECT 
                    concat(DAY(v.vstdate),'/',MONTH(v.vstdate),'/',(YEAR(v.vstdate)+543)) as vst_date,v.hn,v.vn,concat(p.pname,p.fname,'  ',p.lname) as patient_name
                    ,v.cid,s.name as sex,v.age_y,
                    v.pdx as pdx,                  
                    concat(
                           if(v.dx0 is not null,concat(v.dx0,'   '),' '),
                           if(v.dx1 is not null,concat(v.dx1,'   '),' '),
                           if(v.dx2 is not null,concat(v.dx2,'   '),' '),
                           if(v.dx3 is not null,concat(v.dx3,'   '),' '),
                           if(v.dx4 is not null,concat(v.dx4,'   '),' '),
                           if(v.dx5 is not null,concat(v.dx5,'   '),' ')
                           )  as second_diag,
                     concat(
                            if(v.op0 is not null, concat(v.op0,'   '),' '),
                            if(v.op1 is not null, concat(v.op1,'   '),' '),
                            if(v.op2 is not null, concat(v.op2,'   '),' '),
                            if(v.op3 is not null, concat(v.op3,'   '),' '),
                            if(v.op4 is not null, concat(v.op4,'   '),' '),
                            if(v.op5 is not null, concat(v.op5,'   '),' ')
                            ) as icd9,
                    
                    dr.name as doc_name,dr.licenseno,
                    v.income,v.paid_money,remain_money,uc_money,item_money
                FROM vn_stat v
                    left outer join patient p on p.hn=v.hn
                    left outer join ovst ov on ov.vn=v.vn
                    left outer join icd101 ic on ic.code=v.pdx
                    left outer join doctor dr on dr.code=ov.doctor
                    left outer join sex s on s.code=v.sex
                WHERE  
                    v.pcode = 'A2' and v.vstdate between $datestart and $dateend
                    and v.pttype in('14')
                GROUP BY v.vn                   
                ORDER BY v.vstdate,v.hn ";
                
        try {
            $rawData = \yii::$app->db->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }

        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => FALSE,
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

        $report_name = "รายงาน คนไข้ ที่ใช้สิทธิ์ โครงการจ่ายตรง (ผู้ป่วยใน)";
        $sql = "SELECT
                    p.cid,a.an,a.hn,a.vn,concat(DAY(a.dchdate),'/',MONTH(a.dchdate),'/',(YEAR(a.dchdate)+543)) as ovst_date,
                    concat(p.pname,p.fname,'  ',p.lname) as patient_name
                    ,s.name as sex,a.age_y,
                    
                    a.pdx as pdx,
                    
                    concat(
                           if(a.dx0 is not null,concat(a.dx0,'   '),' '),
                           if(a.dx1 is not null,concat(a.dx1,'   '),' '),
                           if(a.dx2 is not null,concat(a.dx2,'   '),' '),
                           if(a.dx3 is not null,concat(a.dx3,'   '),' '),
                           if(a.dx4 is not null,concat(a.dx4,'   '),' '),
                           if(a.dx5 is not null,concat(a.dx5,'   '),' ')
                           )  as second_diag,
                     concat(
                            if(a.op0 is not null, concat(a.op0,'   '),' '),
                            if(a.op1 is not null, concat(a.op1,'   '),' '),
                            if(a.op2 is not null, concat(a.op2,'   '),' '),
                            if(a.op3 is not null, concat(a.op3,'   '),' '),
                            if(a.op4 is not null, concat(a.op4,'   '),' '),
                            if(a.op5 is not null, concat(a.op5,'   '),' ')
                            ) as icd9,
                    
                    dr.name as doc_name,dr.licenseno,a.item_money
                FROM an_stat a
                    left outer join patient p on p.hn=a.hn
                    left outer join ovst ov on ov.vn=a.vn
                    left outer join doctor dr on dr.code=ov.doctor
                    left outer join sex s on s.code=a.sex
                WHERE a.dchdate between $datestart and $dateend
                    and pdx!='' and pdx is not null
                    and a.pttype in('11')
                GROUP BY a.vn 
                ORDER BY a.dchdate,a.hn ";

        try {
            $rawData = \yii::$app->db->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }

        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => FALSE,
        ]);

        return $this->render('report14', [
                    'dataProvider' => $dataProvider,
                    'report_name' => $report_name,
                    'details' => $details,
        ]);
     }
     
     
     
        public function actionReport15($datestart, $dateend, $details) {
            // save log
        $this->SaveLog($this->dep_controller, 'report15', $this->getSession());

        $report_name = "E-Claim ผู้ป่วยนอก สิทธิ์ 56(UC นอกเขตต่างจังหวัดไม่มีท),57(UC ท นอกเขตต่างจังหวัดมี ท)";
        $sql = "SELECT
                    concat(DAY(v.vstdate),'/',MONTH(v.vstdate),'/',(YEAR(v.vstdate)+543)) as vst_date,v.hn,v.vn,concat(p.pname,p.fname,'  ',p.lname) as patient_name
                    ,v.cid,s.name as sex,v.age_y,
                    
                    v.pdx as pdx,
                    
                    concat(
                           if(v.dx0 is not null,concat(v.dx0,'   '),' '),
                           if(v.dx1 is not null,concat(v.dx1,'   '),' '),
                           if(v.dx2 is not null,concat(v.dx2,'   '),' '),
                           if(v.dx3 is not null,concat(v.dx3,'   '),' '),
                           if(v.dx4 is not null,concat(v.dx4,'   '),' '),
                           if(v.dx5 is not null,concat(v.dx5,'   '),' ')
                           )  as second_diag,
                     concat(
                            if(v.op0 is not null, concat(v.op0,'   '),' '),
                            if(v.op1 is not null, concat(v.op1,'   '),' '),
                            if(v.op2 is not null, concat(v.op2,'   '),' '),
                            if(v.op3 is not null, concat(v.op3,'   '),' '),
                            if(v.op4 is not null, concat(v.op4,'   '),' '),
                            if(v.op5 is not null, concat(v.op5,'   '),' ')
                            ) as icd9,
                            
                    dr.name as doc_name,
                    dr.licenseno,v.income,v.paid_money,remain_money,uc_money,item_money
                FROM vn_stat v
                    left outer join patient p on p.hn=v.hn
                    left outer join ovst ov on ov.vn=v.vn
                    left outer join icd101 ic on ic.code=v.pdx
                    left outer join doctor dr on dr.code=ov.doctor
                    left outer join sex s on s.code=v.sex
                WHERE  
                    v.vstdate between $datestart and $dateend
                    and v.pttype in('56','57')
                GROUP BY v.vn 
                ORDER BY v.vstdate,v.hn ";

        try {
            $rawData = \yii::$app->db->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }

        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => FALSE,
        ]);

        return $this->render('report15', [
                    'dataProvider' => $dataProvider,
                    'report_name' => $report_name,
                    'details' => $details,
        ]);
     }
     
     
     
      public function actionReport16($datestart, $dateend, $details) {
          // save log
        $this->SaveLog($this->dep_controller, 'report16', $this->getSession());

        $report_name = "รายงานตรวจสอบข้อมูลประกันสังคมผู้ป่วยนอก (ที่ยังไม่ลง diag หลัก)";
        $sql = "SELECT
                    concat(DAY(v.vstdate),'/',MONTH(v.vstdate),'/',(YEAR(v.vstdate)+543)) as vst_date,v.hn,v.vn,concat(p.pname,p.fname,'  ',p.lname) as patient_name
                    ,v.cid,s.name as sex,v.age_y,'' as icd10,
                    concat(v.op0,'  ',v.op1) as icd9,dr.name as doc_name,dr.licenseno,v.income,v.paid_money,remain_money,uc_money,item_money
                FROM vn_stat v
                    left outer join patient p on p.hn=v.hn
                    left outer join ovst ov on ov.vn=v.vn
                    left outer join icd101 ic on ic.code=v.pdx
                    left outer join doctor dr on dr.code=ov.doctor
                    left outer join sex s on s.code=v.sex
                WHERE  
                    v.pcode='A7' and v.vstdate between $datestart and $dateend
                    and (pdx =''  or pdx is null)
                    and v.hn not in (select distinct(d.hn) from dtmain d where d.vn = v.vn)
                GROUP BY v.vn 
                ORDER BY v.vstdate,v.hn";

        try {
            $rawData = \yii::$app->db->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }

        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => FALSE,
        ]);

        return $this->render('report16', [
                    'dataProvider' => $dataProvider,
                    'report_name' => $report_name,
                    'details' => $details,
        ]);
     }
     
     
     
     public function actionReport17($datestart, $dateend, $details) {
         // save log
        $this->SaveLog($this->dep_controller, 'report17', $this->getSession());

        $report_name = "รายงานตรวจสอบสิทธิ์บัตรประกันสุขภาพถ้วนหน้า(UC) มีท,ไม่มีท เขตรอยต่อ (ที่ยังไม่ได้ลง diag)";
        $sql = "
                select
                concat(DAY(v.vstdate),'/',MONTH(v.vstdate),'/',(YEAR(v.vstdate)+543)) as vst_date,
                v.hn,v.vn,concat(p.pname,p.fname,'  ',p.lname) as patient_name,
                v.cid,s.name as sex,v.age_y,
                
                v.pdx as pdx,
                
                 concat(
                           if(v.dx0 is not null,concat(v.dx0,'   '),' '),
                           if(v.dx1 is not null,concat(v.dx1,'   '),' '),
                           if(v.dx2 is not null,concat(v.dx2,'   '),' '),
                           if(v.dx3 is not null,concat(v.dx3,'   '),' '),
                           if(v.dx4 is not null,concat(v.dx4,'   '),' '),
                           if(v.dx5 is not null,concat(v.dx5,'   '),' ')
                           )  as second_diag,
                     concat(
                            if(v.op0 is not null, concat(v.op0,'   '),' '),
                            if(v.op1 is not null, concat(v.op1,'   '),' '),
                            if(v.op2 is not null, concat(v.op2,'   '),' '),
                            if(v.op3 is not null, concat(v.op3,'   '),' '),
                            if(v.op4 is not null, concat(v.op4,'   '),' '),
                            if(v.op5 is not null, concat(v.op5,'   '),' ')
                            ) as icd9,
                

                dr.name as doc_name,dr.licenseno,v.income,v.paid_money,remain_money,uc_money,item_money

                from vn_stat v
                left outer join patient p on p.hn=v.hn
                left outer join ovst ov on ov.vn=v.vn
                left outer join icd101 ic on ic.code=v.pdx
                left outer join doctor dr on dr.code=ov.doctor
                left outer join sex s on s.code=v.sex
                where  v.pcode='UC' and v.vstdate between $datestart and $dateend and v.pttype in('52','54')
                and (pdx =''  or pdx is null)
                group by v.vn order by v.vstdate,v.hn";

        try {
            $rawData = \yii::$app->db->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }

        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => FALSE,
        ]);

        return $this->render('report17', [
                    'dataProvider' => $dataProvider,
                    'report_name' => $report_name,
                    'details' => $details,
        ]);
     }
     
     
     public function actionReport18($details) {
         // save log
        $this->SaveLog($this->dep_controller, 'report18', $this->getSession());

        $report_name = "รายงานคนไข้ที่มีการบันทึก Typearea เป็น Type3 ในฝั่งข้อมูลประชากร(Person)";
        $sql = "select 
                    p.cid,p.hn,concat(p.pname,p.fname,p.lname) as pt_name,
                    p.addrpart,p.moopart, t.full_name,p.hometel,person.house_regist_type_id,h.house_regist_type_name,
                    (
                        select count(distinct(o.vn)) from ovst o where o.hn = p.hn  and o.vstdate between '2015-10-01' and '2016-09-30'
                    ) as count_vn_59,
                    (
                        select count(distinct(o.vn)) from ovst o where o.hn = p.hn  and o.vstdate between '2014-10-01' and '2015-09-30'
                    ) as count_vn_58

                    from person

                    left outer join patient p on p.cid = person.cid
                    left outer join house_regist_type h on h.house_regist_type_id = person.house_regist_type_id
                    left outer join thaiaddress t on t.addressid = concat(p.chwpart,p.amppart,p.tmbpart)

                    where person.house_regist_type_id = '3'  and person.cid != '' and p.cid != ''   and t.full_name != ''

                    order by p.chwpart,p.amppart,p.tmbpart,p.moopart ";



        try {
            $rawData = \yii::$app->db->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }

        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => FALSE,
        ]);

        return $this->render('report18', [
                    'dataProvider' => $dataProvider,
                    'report_name' => $report_name,
                    'details' => $details,
        ]);
     }
     
     public function actionReport19($datestart, $dateend, $details) {
         // save log
        $this->SaveLog($this->dep_controller, 'report19', $this->getSession());

        $report_name = "รายงานผู้ป่วย OPD ในเขตตำบลละแม (หมู่1-7,9,10,12,14) มีรหัสวินิจฉัยหลัก เป็น z515";
        $sql = " 
                SELECT
                       concat(DAY(v.vstdate),'/',MONTH(v.vstdate),'/',(YEAR(v.vstdate)+543)) as vstdate_thai,
                       v.hn,concat(pt.pname,pt.fname,'  ',pt.lname) as pt_name,
                       v.pdx,v.dx0,v.dx1,v.dx2,v.dx3,v.dx4,v.dx5,
                       v.moopart,th.full_name,v.income
                FROM vn_stat v
                LEFT OUTER JOIN patient pt on pt.hn = v.hn
                LEFT OUTER JOIN thaiaddress th on th.addressid = v.aid
                WHERE
                      v.vstdate BETWEEN $datestart AND $dateend AND v.pdx = 'z515' AND
                      v.aid = '860501' AND v.moopart IN (1,2,3,4,5,6,7,9,10,12,14)
                ORDER BY v.aid,v.moopart  ";
                               

        try {
            $rawData = \yii::$app->db->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }

        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => FALSE,
        ]);

        return $this->render('report19', [
                    'dataProvider' => $dataProvider,
                    'report_name' => $report_name,
                    'details' => $details,
        ]);
     }
     
      public function actionReport20($datestart, $dateend, $details) {
         // save log
        $this->SaveLog($this->dep_controller, 'report20', $this->getSession());

        $report_name = "รายงานสรุปยอดผู้มารับบริการ OPD แยกรายวันตามสิทธิ์การรักษา";
        
        /* แบบเดิม วิธีการสรุปข้อมูลเป็นดังนี้ เช่น ผู้ใช้เลือกวันที่ที่ต้องการดูรายงานเป็นวันที่ 15 ม.ค.60 ระบบจะดึงข้อมูล ของวันที่ 14 ม.ค. 60 ระหว่างเวลา 16:01:00 น.  ถึง 23:59:59 น. มารวมกันวันที่ 15 ม.ค. 60 ระหว่างเวลา 00:00:00  ถึง 16:00:59 */
        $sql = "SELECT
                    v.pttype , p.name as pttype_name,count(distinct(v.vn)) as count_vn  ,
                    sum(income) as sum_income,
                    sum(v.uc_money) as sum_uc_money
                FROM vn_stat  v
                left outer join pttype p on p.pttype = v.pttype
                left outer join service_time s on s.vn = v.vn
                WHERE v.vstdate between $datestart and $dateend
                /*
                  (
                    (v.vstdate = date_sub($datestart,interval 1 day) and s.service3 between '16:01:00' and '23:59:59')
                      or
                     (v.vstdate = $datestart and s.service3 between '00:00:00' and '16:00:59')
                   ) */
                    GROUP BY v.pttype ";

                                                       
        try {
            $rawData = \yii::$app->db->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }

        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => FALSE,
        ]);
        
      
        return $this->render('report20', [
                    'dataProvider' => $dataProvider,
                    'rawData' => $rawData,
                    'date_start' => $datestart,
                    'date_end' => $dateend,
                    'report_name' => $report_name,
                    'details' => $details,
        ]);
      
        
        
     }
     
     
     
      public function actionReport21($pttype,$date_start,$date_end) {
            // save log
        $this->SaveLog($this->dep_controller, 'report21', $this->getSession());

        $report_name = "รายงานยอดผู้มารับบริการ OPD แยกรายวันตามสิทธิ์การรักษา";
        
        /* แบบเดิม วิธีการสรุปข้อมูลเป็นดังนี้ เช่น ผู้ใช้เลือกวันที่ที่ต้องการดูรายงานเป็นวันที่ 15 ม.ค.60 ระบบจะดึงข้อมูล ของวันที่ 14 ม.ค. 60 ระหว่างเวลา 16:01:00 น.  ถึง 23:59:59 น. มารวมกันวันที่ 15 ม.ค. 60 ระหว่างเวลา 00:00:00  ถึง 16:00:59 */
        $sql = "SELECT
                        v.vn,concat(DAY(v.vstdate),'/',MONTH(v.vstdate),'/',(YEAR(v.vstdate)+543)) as vstdate ,
                        v.hn,concat(pt.pname,pt.fname,'  ',pt.lname) as pt_name,
                        v.pttype,t.name as pttype_name,
                        v.income,r.total_amount,
                        if(v.paid_money is not null,v.paid_money,'-') as net_total,
                         v.uc_money,
                         ks.department as department_name
                        
                  FROM vn_stat v
                  left outer join  rcpt_print r on r.vn = v.vn
                  left outer join  pttype t on t.pttype=v.pttype
                  left outer join patient pt on pt.hn = v.hn
                  left outer join ovst ov on ov.vn=v.vn 
                  left outer join kskdepartment ks on ks.depcode = ov.main_dep
                  
                  WHERE
                       v.vstdate between $date_start and $date_end   and v.pttype = $pttype
                  GROUP BY v.vn
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

        return $this->render('report21', [
                    'dataProvider' => $dataProvider,
                    'rawData' => $rawData,
                    'date_start' => $date_start,
                    'date_end' => $date_end,
                    'report_name' => $report_name,

        ]);
      
          
      }
      
      
      public function actionReport22($date_start, $date_end) {
            // save log
        $this->SaveLog($this->dep_controller, 'report22', $this->getSession());

        $report_name = "รายงานยอดผู้มารับบริการ OPD แยกรายวัน ระหว่างวันที่ $date_start ถึงวันที่ $date_end";
        
        $sql = "SELECT
                        v.vn,concat(DAY(v.vstdate),'/',MONTH(v.vstdate),'/',(YEAR(v.vstdate)+543)) as vstdate ,
                        v.hn,concat(pt.pname,pt.fname,'  ',pt.lname) as pt_name,
                        v.pttype,t.name as pttype_name,
                        v.income,r.total_amount,
                        if(v.paid_money,v.paid_money,'-') as net_total,
                        v.uc_money,
                        ks.department as department_name
                        
                  FROM vn_stat v
                  left outer join  rcpt_print r on r.vn = v.vn
                  left outer join  pttype t on t.pttype=v.pttype
                  left outer join patient pt on pt.hn = v.hn
                  left outer join ovst ov on ov.vn=v.vn 
                  left outer join kskdepartment ks on ks.depcode=ov.main_dep
                  
                  WHERE
                       v.vstdate between $date_start  and $date_end
                  GROUP BY v.vn
                  ORDER BY v.pttype, v.vstdate ";

                                                       
        try {
            $rawData = \yii::$app->db->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }

        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => FALSE,
        ]);

        return $this->render('report22', [
                    'dataProvider' => $dataProvider,
                    'rawData' => $rawData,
                    'date_start' => $date_start,
                    'report_name' => $report_name,

        ]);
      
          
      }
     
     
      
      public function actionReport23($datestart, $dateend, $details) {
        // save log
        $this->SaveLog($this->dep_controller, 'report23', $this->getSession());

        $report_name = "รายงานผู้ป่วยที่จำหน่ายแล้ว";
        
        $sql = "SELECT
                    a.an,a.hn,concat(p.pname,p.fname,'  ',p.lname) as pt_name,a.pttype,
                    a.regdate,a.dchdate,a.pdx,
                    if(a.dx0 is not null,a.dx0,' ') as dx0,
                    if(a.dx1 is not null,a.dx1,' ') as dx1,
                    if(a.dx2 is not null,a.dx2,' ') as dx2,
                    if(a.dx3 is not null,a.dx3,' ') as dx3,
                    if(a.dx4 is not null,a.dx4,' ') as dx4,
                    if(a.dx5 is not null,a.dx5,' ') as dx5,
                    a.income,a.rw    
                    
                FROM an_stat  a 
                left outer join patient p on p.hn=a.hn 
                WHERE 
                    a.dchdate BETWEEN $datestart AND $dateend
                ORDER BY a.an ";
                
        try {
            $rawData = \yii::$app->db->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }

        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => FALSE,
        ]);

        return $this->render('report23', [
                    'dataProvider' => $dataProvider,
                    'report_name' => $report_name,
                    'details' => $details,
        ]);
    }
    
     
     
    
     public function actionReport24($datestart, $dateend, $details) {
         // save log
        $this->SaveLog($this->dep_controller, 'report24', $this->getSession());

        $report_name = "รายงานสรุปยอดผู้มารับบริการ IPD แยกรายวันตามสิทธิ์การรักษา";
        
        /* แบบเดิม วิธีการสรุปข้อมูลเป็นดังนี้ เช่น ผู้ใช้เลือกวันที่ที่ต้องการดูรายงานเป็นวันที่ 15 ม.ค.60 ระบบจะดึงข้อมูล ของวันที่ 14 ม.ค. 60 ระหว่างเวลา 16:01:00 น.  ถึง 23:59:59 น. มารวมกันวันที่ 15 ม.ค. 60 ระหว่างเวลา 00:00:00  ถึง 16:00:59 */
        
        $sql = "SELECT
                    a.pttype , p.name as pttype_name,count(distinct(a.an)) as count_an  ,
                    sum(a.income) as sum_income,
                    sum(a.uc_money) as sum_uc_money
                FROM an_stat  a
                left outer join pttype p on p.pttype = a.pttype
                WHERE a.dchdate BETWEEN $datestart AND $dateend
                GROUP BY a.pttype ";
        
                                                              
        try {
            $rawData = \yii::$app->db->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }

        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => FALSE,
        ]);
        
      
        return $this->render('report24', [
                    'dataProvider' => $dataProvider,
                    'rawData' => $rawData,
                    'date_start' => $datestart,
                    'date_end' => $dateend,
                    'report_name' => $report_name,
                    'details' => $details,
        ]);
      
              
     }
     
     
     
     
     
     public function actionReport25($pttype,$date_start, $date_end) {
         // save log
        $this->SaveLog($this->dep_controller, 'report25', $this->getSession());

        $report_name = "รายงานสรุปยอดผู้มารับบริการ IPD แยกรายวันตามสิทธิ์การรักษา ที่จำหน่าย ระหว่างวันที่ $date_start ถึงวันที่ $date_end";
   
        $sql = "SELECT
                    w.name as ward_name, a.an, p.hn, 
                    CONCAT(p.pname, p.fname,' ',p.lname) AS pt_name,
                    CONCAT(o.hospmain,' ', h.hosptype, h.name) AS hosp_name,
                    a.regdate,a.dchdate,
                    a.pttype,
                    pp.name as pttype_name,
                    a.income, a.uc_money,
                    if(a.paid_money is not null,a.paid_money,'-') as net_total
                FROM an_stat  a
                LEFT OUTER JOIN patient p on p.hn = a.hn
                LEFT OUTER JOIN ward w on w.ward = a.ward
                LEFT OUTER JOIN ovst o on o.an = a.an
                LEFT OUTER JOIN hospcode h on h.hospcode = o.hospmain
                LEFT OUTER JOIN pttype pp on pp.pttype = a.pttype
                WHERE 
                    a.dchdate BETWEEN $date_start AND $date_end 
                    and a.pttype = $pttype
                GROUP BY a.an ";

                                                       
        try {
            $rawData = \yii::$app->db->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }

        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => FALSE,
        ]);
        
      
        return $this->render('report25', [
                    'dataProvider' => $dataProvider,
                    'rawData' => $rawData,
                    'date_start' => $date_start,
                    'date_end' => $date_end,
                    'report_name' => $report_name,

        ]);
      
 
     }
     
     
       public function actionReport26($date_start, $date_end) {
         // save log
        $this->SaveLog($this->dep_controller, 'report26', $this->getSession());

        $report_name = "รายงานสรุปยอดผู้มารับบริการ IPD แยกรายวันตามสิทธิ์การรักษา ที่จำหน่าย ระหว่างวันที่ $date_start ถึงวันที่ $date_end";
   
        $sql = "SELECT
                    w.name as ward_name, a.an, p.hn, 
                    CONCAT(p.pname, p.fname,' ',p.lname) AS pt_name,
                    CONCAT(o.hospmain,' ', h.hosptype, h.name) AS hosp_name,
                    a.regdate,a.dchdate,
                    a.pttype,
                    pp.name as pttype_name,
                    a.income, a.uc_money,
                    if(a.paid_money is not null,a.paid_money,'-') as net_total
                FROM an_stat  a
                LEFT OUTER JOIN patient p on p.hn = a.hn
                LEFT OUTER JOIN ward w on w.ward = a.ward
                LEFT OUTER JOIN ovst o on o.an = a.an
                LEFT OUTER JOIN hospcode h on h.hospcode = o.hospmain
                LEFT OUTER JOIN pttype pp on pp.pttype = a.pttype
                WHERE 
                    a.dchdate BETWEEN $date_start AND $date_end
                GROUP BY a.an ";

                                                       
        try {
            $rawData = \yii::$app->db->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }

        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => FALSE,
        ]);
        
      
        return $this->render('report26', [
                    'dataProvider' => $dataProvider,
                    'rawData' => $rawData,
                    'date_start' => $date_start,
                    'date_end' => $date_end,
                    'report_name' => $report_name,

        ]);
      
 
     }
     
     
       public function actionReport27($datestart, $dateend,$details) {
         // save log
        $this->SaveLog($this->dep_controller, 'report27', $this->getSession());

        $report_name = "สรุปรายงานลูกหนี้ค่ารักษา OPD/IPD ระหว่างวันที่ $datestart ถึงวันที่ $dateend";
   
        
        $sql = "SELECT
                   'ลูกหนี้ค่ารักษา-กองทุนทันตกรรม' as pttype_name,
                    '1102050194.305' as q1,
                    '4301020106.311' as q2,
                    if(sum(v.income) is not null,concat(sum(v.income),'   '),' ') as sum_income ,
                    if(sum(v.uc_money) is not null,concat(sum(v.uc_money),'   '),' ') as sum_uc_money ,
                    count(distinct(v.vn)) as count_visit,
                    '34' as ptt_code,
                    'opd' as ptt_type,
                    '99999' as cup_status
                FROM vn_stat  v

                WHERE v.vstdate between $datestart and $dateend AND v.pttype in (34)

                UNION ALL



                SELECT
                                   'ลูกหนี้ค่ารักษา-เบิกต้นสังกัด-IPD' as pttype_name,
                                    '1102050194.111' as q1,
                                   '4301020104.105' as q2,
                                    if(sum(a.income) is not null,concat(sum(a.income),'   '),' ') as sum_income ,
                                    if(sum(a.uc_money) is not null,concat(sum(a.uc_money),'   '),' ') as sum_uc_money ,
                                   count(distinct(a.an)) as count_visit,
                                   '13' as ptt_code,
                                   'ipd' as ptt_type,
                                   '99999' as cup_status
                               FROM an_stat  a
                               WHERE a.dchdate between $datestart and $dateend AND a.pttype in (13)


                UNION ALL


                SELECT
                                  'ลูกหนี้ค่ารักษา-บุคคลปัญหาสิทธิ์ฯ-OPD' as pttype_name,
                                   '1102050194.701' as q1,
                                   '4301020106.709' as q2,
                                    if(sum(v.income) is not null,concat(sum(v.income),'   '),' ') as sum_income ,
                                    if(sum(v.uc_money) is not null,concat(sum(v.uc_money),'   '),' ') as sum_uc_money ,
                                   count(distinct(v.vn)) as count_visit,
                                   '99' as ptt_code,
                                   'opd' as ptt_type,
                                   '99999' as cup_status
                               FROM vn_stat  v

                               WHERE v.vstdate between $datestart and $dateend AND v.pttype in (99)


                UNION ALL


                 SELECT
                                   'ลูกหนี้ค่ารักษา-บุคคลปัญหาสิทธิ์ฯ-IPD' as pttype_name,
                                     '1102050194.704' as q1,
                                   '4301020106.71' as q2,
                                    if(sum(a.income) is not null,concat(sum(a.income),'   '),' ') as sum_income ,
                                    if(sum(a.uc_money) is not null,concat(sum(a.uc_money),'   '),' ') as sum_uc_money ,
                                   count(distinct(a.an)) as count_visit,
                                    '99' as ptt_code,
                                    'ipd' as ptt_type,
                                    '99999' as cup_status
                               FROM an_stat  a
                               WHERE a.dchdate between $datestart and $dateend AND a.pttype in (99)



                 UNION ALL


                 SELECT
                                  'ลูกหนี้ค่ารักษา OPD-UC ใน CUP (เฉพาะ 11381)' as pttype_name,
                                    '1102050194.201' as q1,
                                   '4301020105.201' as q2,
                                    if(sum(v.income) is not null,concat(sum(v.income),'   '),' ') as sum_income ,
                                    if(sum(v.uc_money) is not null,concat(sum(v.uc_money),'   '),' ') as sum_uc_money ,
                                   count(distinct(v.vn)) as count_visit,
                                   '60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,78,80,81,82,83,84,85,86,87,88,89,90,91,92,93,94,95,96,97,98' as ptt_code,
                                    'opd' as ptt_type,
                                    '11381' as cup_status
                               FROM vn_stat  v

                               WHERE v.vstdate between $datestart and $dateend
                               AND v.pttype in (60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,78,80,81,82,83,84,85,86,87,88,89,90,91,92,93,94,95,96,97,98)
                               AND v.hospmain = '11381'


                 UNION ALL


                 SELECT
                                   'ลูกหนี้ค่ารักษา IPD' as pttype_name,
                                     '1102050194.202' as q1,
                                   '4301020105.202' as q2,
                                    if(sum(a.income) is not null,concat(sum(a.income),'   '),' ') as sum_income ,
                                    if(sum(a.uc_money) is not null,concat(sum(a.uc_money),'   '),' ') as sum_uc_money ,
                                   count(distinct(a.an)) as count_visit,
                                     '52,54,56,57,60,61,62,63,64,65,66,67,68,69,70,72,74,75,76,77,78,80,81,82,83,84,85,86,87,88,89,90,91,92,93,94,95,96,97,98' as ptt_code,
                                     'ipd' as ptt_type,
                                     '99999' as cup_status
                               FROM an_stat  a
                               WHERE a.dchdate between $datestart and $dateend
                               AND a.pttype in (52,54,56,57,60,61,62,63,64,65,66,67,68,69,70,72,74,75,76,77,78,80,81,82,83,84,85,86,87,88,89,90,91,92,93,94,95,96,97,98)


                 UNION ALL


                  SELECT
                                  'ลูกหนี้ค่ารักษา OPD-UC นอก CUP (ในจังหวัด)ไม่ใช่ 11381' as pttype_name,
                                    '1102050194.204' as q1,
                                   '4301020105.203' as q2,
                                    if(sum(v.income) is not null,concat(sum(v.income),'   '),' ') as sum_income ,
                                    if(sum(v.uc_money) is not null,concat(sum(v.uc_money),'   '),' ') as sum_uc_money ,
                                   count(distinct(v.vn)) as count_visit,
                                   '60,61,62,63,64,65,66,67,68,69,70,72,74,75,76,77,78,80,81,82,83,84,85,86,87,88,89,90,91,92,93,94,95,96,97,98' as ptt_code,
                                   'opd' as ptt_type,
                                   '11381_n' as cup_status
                               FROM vn_stat  v

                               WHERE v.vstdate between $datestart and $dateend
                               AND v.pttype in (60,61,62,63,64,65,66,67,68,69,70,72,74,75,76,77,78,80,81,82,83,84,85,86,87,88,89,90,91,92,93,94,95,96,97,98)
                               AND v.hospmain != '11381'


                  UNION ALL


                  SELECT
                                  'ลูกหนี้ค่ารักษา OPD-UC นอก CUP (ต่างจังหวัด)' as pttype_name,
                                    '1102050194.205' as q1,
                                   '4301020105.205' as q2,
                                    if(sum(v.income) is not null,concat(sum(v.income),'   '),' ') as sum_income ,
                                    if(sum(v.uc_money) is not null,concat(sum(v.uc_money),'   '),' ') as sum_uc_money ,
                                   count(distinct(v.vn)) as count_visit,
                                   '52,54,56,57' as ptt_code,
                                    'opd' as ptt_type,
                                    '99999' as cup_status
                               FROM vn_stat  v

                               WHERE v.vstdate between $datestart and $dateend
                               AND v.pttype in (52,54,56,57)


                   UNION ALL


                  SELECT
                                  'ลูกหนี้ค่ารักษา UC - P&P Expressed demand( OPD)' as pttype_name,
                                    '1102050194.203' as q1,
                                   '4301020105.241' as q2,
                                    if(sum(v.income) is not null,concat(sum(v.income),'   '),' ') as sum_income ,
                                    if(sum(v.uc_money) is not null,concat(sum(v.uc_money),'   '),' ') as sum_uc_money ,
                                   count(distinct(v.vn)) as count_visit,
                                   '71,73' as ptt_code,
                                    'opd' as ptt_type,
                                    '99999' as cup_status
                               FROM vn_stat  v

                               WHERE v.vstdate between $datestart and $dateend
                               AND v.pttype in (71,73)


                  UNION ALL

                      /* no map code */
                  SELECT
                                  'ลูกหนี้ค่ารักษา UC OPD-AE' as pttype_name,
                                    '1102050194.207' as q1,
                                   '4301020105.244' as q2,
                                    if(sum(v.income) is not null,concat(sum(v.income),'   '),' ') as sum_income ,
                                    if(sum(v.uc_money) is not null,concat(sum(v.uc_money),'   '),' ') as sum_uc_money ,
                                    'no code map' as count_visit,
                                    '99999' as ptt_code,
                                     'opd' as ptt_type,
                                     '99999' as cup_status
                                  /* count(distinct(v.vn)) as count_visit  */
                               FROM vn_stat  v

                               WHERE v.vstdate between $datestart and $dateend
                               AND v.pttype in (99999)


                UNION ALL

                           /* no map code */
                 SELECT
                                   'ลูกหนี้ค่ารักษา UC IPD-AE' as pttype_name,
                                     '1102050194.208' as q1,
                                   '4301020105.245' as q2,
                                    if(sum(a.income) is not null,concat(sum(a.income),'   '),' ') as sum_income ,
                                    if(sum(a.uc_money) is not null,concat(sum(a.uc_money),'   '),' ') as sum_uc_money ,
                                    'no code map' as count_visit,
                                    '99999' as ptt_code,
                                     'ipd' as ptt_type,
                                     '99999' as cup_status
                                  /* count(distinct(a.an)) as count_visit  */
                               FROM an_stat  a
                               WHERE a.dchdate between $datestart and $dateend
                               AND a.pttype in (99999)



                 UNION ALL

                      /* no map code */
                  SELECT
                                  'ลูกหนี้ค่ารักษา UC -OPD-HC' as pttype_name,
                                    '1102050194.209' as q1,
                                   '4301020105.246' as q2,
                                    if(sum(v.income) is not null,concat(sum(v.income),'   '),' ') as sum_income ,
                                    if(sum(v.uc_money) is not null,concat(sum(v.uc_money),'   '),' ') as sum_uc_money ,
                                    'no code map' as count_visit,
                                    '99999' as ptt_code,
                                     'opd' as ptt_type,
                                     '99999' as cup_status
                                   /* count(distinct(v.vn)) as count_visit */
                               FROM vn_stat  v

                               WHERE v.vstdate between $datestart and $dateend
                               AND v.pttype in (99999)



                 UNION ALL

                  SELECT
                                  'ลูกหนี้ค่ารักษาประกันสังคม OPD-เครือข่าย' as pttype_name,
                                    '1102050194.301' as q1,
                                   '4301020106.305' as q2,
                                    if(sum(v.income) is not null,concat(sum(v.income),'   '),' ') as sum_income ,
                                    if(sum(v.uc_money) is not null,concat(sum(v.uc_money),'   '),' ') as sum_uc_money ,
                                   count(distinct(v.vn)) as count_visit,
                                   '31' as ptt_code,
                                    'opd' as ptt_type,
                                    '99999' as cup_status
                               FROM vn_stat  v

                               WHERE v.vstdate between $datestart and $dateend
                               AND v.pttype in (31)




                  UNION ALL

                 SELECT
                                   'ลูกหนี้ค่ารักษาประกันสังคม IPD-เครือข่าย' as pttype_name,
                                     '1102050194.302' as q1,
                                   '4301020106.306' as q2,
                                    if(sum(a.income) is not null,concat(sum(a.income),'   '),' ') as sum_income ,
                                    if(sum(a.uc_money) is not null,concat(sum(a.uc_money),'   '),' ') as sum_uc_money ,
                                   count(distinct(a.an)) as count_visit,
                                   '31' as ptt_code,
                                    'ipd' as ptt_type,
                                    '99999' as cup_status
                               FROM an_stat  a
                               WHERE a.dchdate between $datestart and $dateend
                               AND a.pttype in (31)



                UNION ALL

                  SELECT
                                  'ลูกหนี้ค่ารักษาประกันสังคม OPD-นอกเครือข่าย' as pttype_name,
                                    '1102050194.303' as q1,
                                   '4301020106.307' as q2,
                                    if(sum(v.income) is not null,concat(sum(v.income),'   '),' ') as sum_income ,
                                    if(sum(v.uc_money) is not null,concat(sum(v.uc_money),'   '),' ') as sum_uc_money ,
                                   count(distinct(v.vn)) as count_visit,
                                   '32' as ptt_code,
                                    'opd' as ptt_type,
                                    '99999' as cup_status
                               FROM vn_stat  v

                               WHERE v.vstdate between $datestart and $dateend
                               AND v.pttype in (32)


                  UNION ALL


                 SELECT
                                   'ลูกหนี้ค่ารักษาประกันสังคม IPD-นอกเครือข่าย' as pttype_name,
                                     '1102050194.304' as q1,
                                     '4301020106.308' as q2,
                                    if(sum(a.income) is not null,concat(sum(a.income),'   '),' ') as sum_income ,
                                    if(sum(a.uc_money) is not null,concat(sum(a.uc_money),'   '),' ') as sum_uc_money ,
                                   count(distinct(a.an)) as count_visit,
                                   '32,33' as ptt_code,
                                    'ipd' as ptt_type,
                                    '99999' as cup_status
                               FROM an_stat  a
                               WHERE a.dchdate between $datestart and $dateend
                               AND a.pttype in (32,33)



                UNION ALL


                 SELECT
                                   'ลูกหนี้ค่ารักษาประกันสังคม 72 ชั่วโมงแรก (IPD)' as pttype_name,
                                     '1102050194.306' as q1,
                                     '4301020106.312' as q2,
                                    if(sum(a.income) is not null,concat(sum(a.income),'   '),' ') as sum_income ,
                                    if(sum(a.uc_money) is not null,concat(sum(a.uc_money),'   '),' ') as sum_uc_money ,
                                   count(distinct(a.an)) as count_visit,
                                   '35' as ptt_code,
                                    'ipd' as ptt_type,
                                    '99999' as cup_status
                               FROM an_stat  a
                               WHERE a.dchdate between $datestart and $dateend
                               AND a.pttype in (35)



                  UNION ALL


                  SELECT
                                  'ลูกหนี้ค่ารักษา-เบิกจ่ายตรงกรมบัญชีกลาง OPD' as pttype_name,
                                    '1102050194.401' as q1,
                                   '4301020104.401' as q2,
                                    if(sum(v.income) is not null,concat(sum(v.income),'   '),' ') as sum_income ,
                                    if(sum(v.uc_money) is not null,concat(sum(v.uc_money),'   '),' ') as sum_uc_money ,
                                   count(distinct(v.vn)) as count_visit,
                                   '11' as ptt_code,
                                    'opd' as ptt_type,
                                    '99999' as cup_status
                               FROM vn_stat  v

                               WHERE v.vstdate between $datestart and $dateend
                               AND v.pttype in (11)


                   UNION ALL


                 SELECT
                                   'ลูกหนี้ค่ารักษา-เบิกจ่ายตรงกรมบัญชีกลาง IPD' as pttype_name,
                                     '1102050194.402' as q1,
                                     '4301020104.402' as q2,
                                    if(sum(a.income) is not null,concat(sum(a.income),'   '),' ') as sum_income ,
                                    if(sum(a.uc_money) is not null,concat(sum(a.uc_money),'   '),' ') as sum_uc_money ,
                                   count(distinct(a.an)) as count_visit,
                                   '11,12' as ptt_code,
                                    'ipd' as ptt_type,
                                    '99999' as cup_status
                               FROM an_stat  a
                               WHERE a.dchdate between $datestart and $dateend
                               AND a.pttype in (11,12)



                 UNION ALL


                  SELECT
                                  'ลูกหนี้ค่ารักษา-แรงงานต่างด้าว OPD' as pttype_name,
                                    '1102050194.501' as q1,
                                   '4301020106.503' as q2,
                                    if(sum(v.income) is not null,concat(sum(v.income),'   '),' ') as sum_income ,
                                    if(sum(v.uc_money) is not null,concat(sum(v.uc_money),'   '),' ') as sum_uc_money ,
                                   count(distinct(v.vn)) as count_visit,
                                   '42,44' as ptt_code,
                                    'opd' as ptt_type,
                                    '99999' as cup_status
                               FROM vn_stat  v

                               WHERE v.vstdate between $datestart and $dateend
                               AND v.pttype in (42,44)


                UNION ALL


                 SELECT
                                   'ลูกหนี้ค่ารักษา-แรงงานต่างด้าว IPD' as pttype_name,
                                     '1102050194.502' as q1,
                                     '4301020106.504' as q2,
                                    if(sum(a.income) is not null,concat(sum(a.income),'   '),' ') as sum_income ,
                                    if(sum(a.uc_money) is not null,concat(sum(a.uc_money),'   '),' ') as sum_uc_money ,
                                   count(distinct(a.an)) as count_visit,
                                   '42,44' as ptt_code,
                                    'ipd' as ptt_type,
                                    '99999' as cup_status
                               FROM an_stat  a
                               WHERE a.dchdate between $datestart and $dateend
                               AND a.pttype in (42,44)


                UNION ALL


                  SELECT
                                  'ลูกหนี้ค่ารักษา-เบิกจ่ายตรง อปท. OPD' as pttype_name,
                                    '1105050194.801' as q1,
                                   '4301020104.801' as q2,
                                    if(sum(v.income) is not null,concat(sum(v.income),'   '),' ') as sum_income ,
                                    if(sum(v.uc_money) is not null,concat(sum(v.uc_money),'   '),' ') as sum_uc_money ,
                                   count(distinct(v.vn)) as count_visit,
                                   '14' as ptt_code,
                                    'opd' as ptt_type,
                                    '99999' as cup_status
                               FROM vn_stat  v

                               WHERE v.vstdate between $datestart and $dateend
                               AND v.pttype in (14)


               UNION ALL


                 SELECT
                                   'ลูกหนี้ค่ารักษา-เบิกจ่ายตรง อปท. IPD' as pttype_name,
                                     '1102050194.802' as q1,
                                     '4301020104.802' as q2,
                                    if(sum(a.income) is not null,concat(sum(a.income),'   '),' ') as sum_income ,
                                    if(sum(a.uc_money) is not null,concat(sum(a.uc_money),'   '),' ') as sum_uc_money ,
                                   count(distinct(a.an)) as count_visit,
                                   '14' as ptt_code,
                                    'ipd' as ptt_type,
                                    '99999' as cup_status
                               FROM an_stat  a
                               WHERE a.dchdate between $datestart and $dateend
                               AND a.pttype in (14)


                UNION ALL


                  SELECT
                                  'ลูกหนี้ค่ารักษา-ชำระเงิน OPD' as pttype_name,
                                    '1102050194.112' as q1,
                                   '4301020104.106' as q2,
                                    if(sum(v.income) is not null,concat(sum(v.income),'   '),' ') as sum_income ,
                                    if(sum(v.uc_money) is not null,concat(sum(v.uc_money),'   '),' ') as sum_uc_money ,
                                   count(distinct(v.vn)) as count_visit,
                                   '01,10,12,13,16,33,37,39,43,45,56,57' as ptt_code,
                                    'opd' as ptt_type,
                                    '99999' as cup_status
                               FROM vn_stat  v

                               WHERE v.vstdate between $datestart and $dateend
                               AND v.pttype in (01,10,12,13,16,33,37,39,43,45,56,57)


               UNION ALL


                 SELECT
                                   'ลูกหนี้ค่ารักษา-ชำระเงิน IPD' as pttype_name,
                                     '1102050194.113' as q1,
                                     '4301020104.107' as q2,
                                    if(sum(a.income) is not null,concat(sum(a.income),'   '),' ') as sum_income ,
                                    if(sum(a.uc_money) is not null,concat(sum(a.uc_money),'   '),' ') as sum_uc_money ,
                                   count(distinct(a.an)) as count_visit,
                                   '01,10,43,39,45,47' as ptt_code,
                                    'ipd' as ptt_type,
                                    '99999' as cup_status
                               FROM an_stat  a
                               WHERE a.dchdate between $datestart and $dateend
                               AND a.pttype in (01,10,43,39,45,47)


                  UNION ALL


                  SELECT
                                  'ลูกหนี้ค่ารักษา-พรบ. OPD' as pttype_name,
                                    '1102050194.601' as q1,
                                   '4301020104.602' as q2,
                                    if(sum(v.income) is not null,concat(sum(v.income),'   '),' ') as sum_income ,
                                    if(sum(v.uc_money) is not null,concat(sum(v.uc_money),'   '),' ') as sum_uc_money ,
                                   count(distinct(v.vn)) as count_visit,
                                   '36,38' as ptt_code,
                                    'opd' as ptt_type,
                                    '99999' as cup_status
                               FROM vn_stat  v

                               WHERE v.vstdate between $datestart and $dateend
                               AND v.pttype in (36,38)


               UNION ALL


                 SELECT
                                   'ลูกหนี้ค่ารักษา-พรบ. IPD' as pttype_name,
                                     '1102050194.602' as q1,
                                     '4301020104.603' as q2,
                                    if(sum(a.income) is not null,concat(sum(a.income),'   '),' ') as sum_income ,
                                    if(sum(a.uc_money) is not null,concat(sum(a.uc_money),'   '),' ') as sum_uc_money ,
                                   count(distinct(a.an)) as count_visit,
                                   '36,38,37' as ptt_code,
                                    'ipd' as ptt_type,
                                    '99999' as cup_status
                               FROM an_stat  a
                               WHERE a.dchdate between $datestart and $dateend
                               AND a.pttype in (36,38,37) ";
                
                                                     
        try {
            $rawData = \yii::$app->db->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }

        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => FALSE,
        ]);
        
      
        return $this->render('report27', [
                    'dataProvider' => $dataProvider,
                    'rawData' => $rawData,
                    'date_start' => $datestart,
                    'date_end' => $dateend,
                    'report_name' => $report_name,
                    'details' => $details,
               

        ]); 
      
 
     }
     
     
     
      public function actionReport28($head,$ptt_code,$ptt_type,$date_start, $date_end,$cup_status) {
         // save log
        $this->SaveLog($this->dep_controller, 'report28', $this->getSession());

        $report_name = "มารับบริการ $ptt_type  ระหว่างวันที่ $date_start ถึงวันที่ $date_end";
        $view = '';
        $cup = '';
        
        if($cup_status == '11381') {
            $cup =  "AND v.hospmain = '11381' ";
        } else if ($cup_status == '11381_n') {
             $cup = "AND v.hospmain != '11381' ";
        } else {
            $cup = '';
        }
        
        if($ptt_type == 'opd') {
            $sql = "SELECT
                        v.vn as vn,concat(DAY(v.vstdate),'/',MONTH(v.vstdate),'/',(YEAR(v.vstdate)+543)) as vstdate ,
                        v.hn,concat(pt.pname,pt.fname,'  ',pt.lname) as pt_name,
                        v.pttype,t.name as pttype_name,
                        v.income,r.total_amount,
                        v.hospmain,
                      /*  if(v.paid_money is not null,v.paid_money,'-') as net_total, */
                         v.rcpt_money as net_total,
                         v.uc_money,
                         ks.department as department_name,
                         pt.cid,
                         v.age_y,
                         
                        v.pdx as pdx,
                        concat(
                                  if(v.dx0 is not null,concat(v.dx0,'   '),' '),
                                  if(v.dx1 is not null,concat(v.dx1,'   '),' '),
                                  if(v.dx2 is not null,concat(v.dx2,'   '),' '),
                                  if(v.dx3 is not null,concat(v.dx3,'   '),' '),
                                  if(v.dx4 is not null,concat(v.dx4,'   '),' '),
                                  if(v.dx5 is not null,concat(v.dx5,'   '),' ')
                                  )  as second_diag ,
                                  
                        concat(
                            if(v.op0 is not null, concat(v.op0,'   '),' '),
                            if(v.op1 is not null, concat(v.op1,'   '),' '),
                            if(v.op2 is not null, concat(v.op2,'   '),' '),
                            if(v.op3 is not null, concat(v.op3,'   '),' '),
                            if(v.op4 is not null, concat(v.op4,'   '),' '),
                            if(v.op5 is not null, concat(v.op5,'   '),' ')
                            ) as icd9,
                            
                            v.inc01,v.inc02,v.inc03,v.inc04,v.inc05,
                            v.inc06,v.inc07,v.inc08,v.inc09,v.inc10,
                            v.inc11,v.inc12,v.inc13,v.inc14,v.inc15,
                            v.inc16,v.inc17

                            
                            
                        
                  FROM vn_stat v
                  left outer join  rcpt_print r on r.vn = v.vn
                  left outer join  pttype t on t.pttype=v.pttype
                  left outer join patient pt on pt.hn = v.hn
                  left outer join ovst ov on ov.vn=v.vn 
                  left outer join kskdepartment ks on ks.depcode = ov.main_dep
                  
                  WHERE
                       v.vstdate between $date_start and $date_end
                       and v.pttype in ($ptt_code)
                          $cup
                  GROUP BY v.vn
                  ORDER BY v.vn ";
            
            $view = 'report28';
                  
            
        } else if ($ptt_type == 'ipd') {
            $sql = "SELECT
                    w.name as ward_name, a.an, p.hn, 
                    CONCAT(p.pname, p.fname,' ',p.lname) AS pt_name,
                    CONCAT(o.hospmain,' ', h.hosptype, h.name) AS hosp_name,
                    concat(DAY(a.regdate),'/',MONTH(a.regdate),'/',(YEAR(a.regdate)+543)) as regdate ,
                    concat(DAY(a.dchdate),'/',MONTH(a.dchdate),'/',(YEAR(a.dchdate)+543)) as dchdate ,
                    a.pttype,
                   /* a.hospmain, */
                    pp.name as pttype_name,
                    a.income, a.uc_money,
                    /* if(a.paid_money is not null,a.paid_money,'-') as net_total, */
                    a.rcpt_money as net_total,
                    a.pdx as pdx,
                    p.cid,
                    a.age_y,
                        concat(
                                  if(a.dx0 is not null,concat(a.dx0,'   '),' '),
                                  if(a.dx1 is not null,concat(a.dx1,'   '),' '),
                                  if(a.dx2 is not null,concat(a.dx2,'   '),' '),
                                  if(a.dx3 is not null,concat(a.dx3,'   '),' '),
                                  if(a.dx4 is not null,concat(a.dx4,'   '),' '),
                                  if(a.dx5 is not null,concat(a.dx5,'   '),' ')
                                  )  as second_diag  ,
                                  
                        concat(
                                 if(a.op0 is not null, concat(a.op0,'   '),' '),
                                 if(a.op1 is not null, concat(a.op1,'   '),' '),
                                 if(a.op2 is not null, concat(a.op2,'   '),' '),
                                 if(a.op3 is not null, concat(a.op3,'   '),' '),
                                 if(a.op4 is not null, concat(a.op4,'   '),' '),
                                 if(a.op5 is not null, concat(a.op5,'   '),' '),
                                 if(a.op6 is not null, concat(a.op6,'   '),' ')
                                 ) as icd9, 
                                 
                                a.inc01,a.inc02,a.inc03,a.inc04,a.inc05,
                                a.inc06,a.inc07,a.inc08,a.inc09,a.inc10,
                                a.inc11,a.inc12,a.inc13,a.inc14,a.inc15,
                                a.inc16,a.inc17
                            
                                  
                FROM an_stat  a
                LEFT OUTER JOIN patient p on p.hn = a.hn
                LEFT OUTER JOIN ward w on w.ward = a.ward
                LEFT OUTER JOIN ovst o on o.an = a.an
                LEFT OUTER JOIN hospcode h on h.hospcode = o.hospmain
                LEFT OUTER JOIN pttype pp on pp.pttype = a.pttype
                WHERE 
                    a.dchdate BETWEEN $date_start AND $date_end 
                    and a.pttype in ($ptt_code)
                GROUP BY a.an 
                ORDER BY a.an ";
                    
            
            $view = 'report29';
        }
        
                               
        try {
            $rawData = \yii::$app->db->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }

        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => FALSE,
        ]);
        
      
        return $this->render($view, [
                    'dataProvider' => $dataProvider,
                    'rawData' => $rawData,
                    'date_start' => $date_start,
                    'date_end' => $date_end,
                    'report_name' => $report_name,
                    'head' => $head

        ]);
            
     }
     
     
      public function actionReport30($datestart, $dateend, $details) {
        // save log
        $this->SaveLog($this->dep_controller, 'report30', $this->getSession());

        $report_name = "รายงานตรวจสอบการบันทึกเลข Approve Code";
        $sql = "SELECT

            pt.cid,v.hn,concat(pt.pname,pt.fname,'  ',pt.lname) as pt_name,
            concat(DAY(v.vstdate),'/',MONTH(v.vstdate),'/',(YEAR(v.vstdate)+543)) as vstdate,
            v.pdx,v.dx0,v.dx1,v.dx2,v.dx3,v.dx4,v.dx5,v.pttype,p.name as pttype_name,v.income ,
            /*r.debt_date */ 
            if(sum(r.total_amount) is not null,sum(r.total_amount),' ') as total_amount,
             if(r.sss_approval_code is not null,r.sss_approval_code,' ') as approve_code,
            if(er.vn is not null,er.vn,' ')  as er_visit

        FROM vn_stat v
        LEFT OUTER JOIN rcpt_debt r ON r.vn = v.vn
        LEFT OUTER JOIN er_regist er ON er.vn = v.vn
        LEFT OUTER JOIN patient pt ON pt.hn = v.hn
        LEFT OUTER JOIN pttype p ON  p.pttype = v.pttype

        WHERE
             v.vstdate BETWEEN $datestart AND $dateend 

        AND
             v.pttype  IN  (12,11)

             GROUP BY v.vn
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

        return $this->render('report30', [
                    'dataProvider' => $dataProvider,
                    'report_name' => $report_name,
                    'details' => $details,
        ]);
    }
    
    
     
      /*  
     
      public function actionReport30($date_start, $date_end) {
         // save log
        $this->SaveLog($this->dep_controller, 'report30', $this->getSession());

        $report_name = "รายงานจำนวนคนไข้ที่ใช้สิทธิ์ 37(พรบ.30000 จ่ายเงินไม่มีสิทธิเบิกคืนจาก รพ.) ระหว่างวันที่ $date_start ถึงวันที่ $date_end";
   
        $sql = "SELECT
                    'ผู้ป่วยนอก' as name,
                    count(distinct(hn)) as count_hn,count(distinct(vn)) as count_visit
                    from vn_stat   where pttype = '37'     
                    and vstdate between $date_start AND $date_end 
                
                UNION ALL  
                
                SELECT
                    'ผู้ป่วยใน' as name,
                    count(distinct(hn)) as count_hn,count(distinct(an)) as count_visit
                    from an_stat   where pttype = '37'     
                    and dchdate between $date_start AND $date_end  ";
             
        echo $sql;  

                                                    
        try {
            $rawData = \yii::$app->db->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }

        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => FALSE,
        ]);
        
      
        return $this->render('report30', [
                    'dataProvider' => $dataProvider,
                    'rawData' => $rawData,
                    'date_start' => $date_start,
                    'date_end' => $date_end,
                    'report_name' => $report_name,

        ]); 
      
 
     } */
     
     
     
}
