<?php
/* @var $this yii\web\View */
$this->title = $dep_name;
$this->params['breadcrumbs'][] = ['label' => 'กลุ่มรายงานตามหน่วยงาน', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>






<!-- start project list -->
<table class="table table-striped projects">
    <thead>
        <tr>
            <th style="width: 1%">ลำดับ</th>
            <th style="width: 85%">ชื่อรายงาน</th>

            <th style="width: 10%"></th>
        </tr>
    </thead>
    <tbody>
        <?php $count = 0; ?>
        <?php foreach ($models as $item) { ?>

            <tr>
                <td><?= $count = $count + 1; ?></td>
                <td>
                    <a><?= $item['name']; ?></a>
                    <br />
                    <small>ปรับปรุงล่าสุด <?php echo $item['created']; ?></small>
                </td>

                <td>
                    <a href="index.php?r=report/form&controller=<?php echo $item['controller'];?>&form_id=<?php echo $item['form']; ?>&pointer=<?php echo $item['pointer']; ?>&report_name=<?php echo $item['name']; ?>&details=<?php echo $item['details']; ?>" type="button" class="btn btn-primary btn-xs" data-toggle="modal" ><i class="fa fa-search"></i> ดูรายงาน </a>   
                </td>
            </tr>

        <?php } ?>

    </tbody>
</table>
<!-- end project list -->

