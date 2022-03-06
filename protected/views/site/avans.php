<style>
    .wood{
        overflow: visible!important;
    }
</style>
<div class="col-sm-12">

    <table class="table table-bordered">
        <thead>
        <tr>
            <th>#</th>
            <th>Дата</th>
            <th>Сумма</th>
            <th>Оплаченная часть</th>
            <th>Коммент</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?
        foreach ($model as $item) {?>
            <tr>
                <td><?=$item["expense_id"]?></td>
                <td><?=$item["prepCreate"]?></td>
                <td><?=$item["expSum"]?></td>
                <td><?=$item["prepaidSum"]?></td>
                <td><?=$item["comment"]?></td>
                <td>
                    <a href="javascript:;" data-id="<?=$item["expense_id"]?>" class="btn btn-default view" data-toggle="modal" data-target="#Modal" ><i class="fa fa-eye"></i></a>
                    <a href="javascript:;" data-id="<?=$item["expense_id"]?>" class="btn btn-info closed" ><i class="fa fa-close"></i></a>
                    <a href="javascript:;" data-id="<?=$item["expense_id"]?>" class="btn btn-danger delete"><i class="fa fa-trash"></i></a>

                </td>
            </tr>
        <?}
        ?>
        </tbody>
    </table>
</div>
<script>
    $(document).on("click",".closed",function(){
        var elem = $(this);
        if(confirm("Вы уверены?")) {
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('expense/closedAvans'); ?>",
                data: "id=" + $(this).attr("data-id"),
                success: function () {
                    elem.parent().parent().remove();
                }
            });
        }
    });
    $(document).on("click",".delete",function(){
        var elem = $(this);
        if(confirm("Вы уверены?")) {
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('expense/deletedAvans'); ?>",
                data: "id=" + $(this).attr("data-id"),
                success: function () {
                    elem.parent().parent().remove();
                }
            });
        }
    });

    $(document).on('click','.view',function(){
        var id =  $(this).attr("data-id");
        $.ajax({
            type: "GET",
            url: "<?php echo Yii::app()->createUrl('/expense/printExpCheck'); ?>",
            data: 'exp='+id,
            success: function(data){
                $("#ModalBody").html(data);
            }
        });
    });
</script>
<div class="modal fade bs-example-modal-sm" id="Modal"  tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body" id="ModalBody">
            </div>

        </div>
    </div>
</div>