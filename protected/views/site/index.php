

<div id="createDiv">
    <? $menu = new Menu(); ?>
    <ul class="nav nav-tabs" role="tablist" style="margin-bottom: 0;border-bottom: 2px solid #000;">
        <li role="presentation" class="active success"><a href="#" aria-controls="#" role="tab" data-toggle="tab">&nbsp;</a></li>
    </ul>
    <div class="clearfix"></div>
    <nav id="navsMenu">
        <ul  class="nav nav-pills">

            <li role="presentation">
                                <?=CHtml::dropDownList('menuDrop','',$menu->getMenuList())?></li>
            <li role="presentation" ><a class="btn btn-info" href="#" id="addBtn">+</a></li>
            <li role="presentation" class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#" style="font-size: x-small" role="button" aria-haspopup="true" aria-expanded="false">
                    <?=$change["name"]."<br>".$change["start_time"]?> <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    <li role="presentation" ><a href="site/avans" id="close">Авансы</a></li>
                    <li role="presentation" ><a href="#" data-href="/expense/printReport" id="printReport">Печать</a></li>
                    <li role="presentation" ><a href="site/changelogout" id="close">Закрыть смену</a></li>
                </ul>
            </li>
        </ul>
    </nav>
<!--    <button type="button" class="btn" id="addBtn">+</button>-->
<!--    <a type="button" class="btn btn-danger" id="addBtn">Закрыть кассу</a>-->

    <input id="tempPrice" type="text" class="hidden" />
    <div >
        <div class="navbar-default sidebar" style="margin-top: 0;" role="navigation; overflow-x: scroll;">
            <div class="sidebar-nav tab-box">
                <ul class="nav nav-pills nav-stacked tab-nav" id="menuList">
                    <? foreach($menuModel as $key => $value){
                        $subMenu = Dishtype::model()->findAll('t.parent = :parent',array(':parent'=>$value->type_id))
                        ?>
                        <li id="<?=$value->type_id?>">
                            <a href="javascript:;" class="types"><?=$value->name?><span style="float: right;" class="fa fa-angle-right"></span></a>
                            <ul><? foreach($subMenu as $val){?>
                                    <li class="liStyle" id="<?=$val->type_id?>">
                                        <a href="javascript:;" class="types"><?=$val->name?><span style="float: right;" class="fa fa-angle-right"></span></a>
                                    </li>
                                <? }?>
                            </ul>
                        </li>
                    <? }?>

                </ul>

            </div>
            <!-- /.sidebar-collapse -->
        </div>
        <div id="page-wrappers">
            <div class="tab-panels" id="data">

            </div>
        </div>
        <div class="navbar-default right-sidebar" style="right: 0; top: 0;">
            <div style="position: relative">
                <div id="debtComment" class="hideBlock" >
                    <div class="form-group">
                        <textarea name="" id="textDebtComment" class="form-control" rows="5" placeholder="Комментарий"></textarea>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" id="textDebtPaid" placeholder="Оплаченная часть долга">
                    </div>
                    <div class="form-group">
                        <button type="button" id="addDebt" class="btn btn-danger">Закрыть в долг</button>
                    </div>
                </div>
                <div id="termSum" class="hideBlock" >
                    <div class="form-group">
                        <input name="" id="terminalSum" class="form-control" rows="5" placeholder="Сумма" />
                    </div>
                    <div class="form-group">
                        <button type="button" id="closeTerm" class="btn btn-danger">Закрыть терминалом</button>
                    </div>
                </div>
                <div id="avansSum" class="hideBlock" >
                    <div class="form-group">
                        <textarea name="" id="textAvansComment" class="form-control" rows="5" placeholder="Комментарий"></textarea>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" id="textAvansPaid" placeholder="Сумма аванса">
                    </div>
                    <div class="form-group">
                        <button type="button" id="closeAvans" class="btn btn-danger">Закрыть авансом</button>
                    </div>
                </div>
                <div class="tab-content">
                </div>
            </div>
        </div>
    </div>


    <script>
        var globExpId = 0;
        var cntObj;
        var cntVal;
        var isDelivery = false;
        var commentedElement = "";
        // $(".btnPrint").printPage();
        // $(".expCheck").printPage();

        function addTab(id = 0){
            if(id == 0) {
                $.ajax({
                    type: "POST",
                    url: "<?php echo Yii::app()->createUrl('expense/createExp'); ?>",
                    success: function (data) {
                        $(".nav-tabs li").removeClass("active");
                        $(".tab-content div").removeClass("active");
                        var expId = data;
                        var strTab = '';
                        globExpId = expId;
                        var expIdTxt = '#' + expId;
                        strTab = '<li role="presentation" class="active changed"><a href="' + expIdTxt + '" aria-controls="' + expId + '" role="tab" data-toggle="tab">' + expId + '</a></li>';
                        getOrder(expId);

                        $(".nav-tabs").append(strTab);
                    }

                });
            }
            else{
                $(".nav-tabs li").removeClass("active");
                var expId = id;
                var strTab = '';
                globExpId = expId;
                var expIdTxt = '#' + expId;
                strTab = '<li role="presentation" class="active changed"><a href="' + expIdTxt + '" aria-controls="' + expId + '" role="tab" data-toggle="tab">' + expId + '</a></li>';
                getOrder(expId);

                $(".nav-tabs").append(strTab);
            }
        }

        function getOrder(expId){
            $.ajax({
                type: "POST",
                data: 'id='+expId,
                url: "<?php echo Yii::app()->createUrl('expense/orders'); ?>",
                success: function(data) {
                    var strTabContent = '';
                    $(".tab-content div").removeClass("active");
                    strTabContent = '<div role="tabpanel" class="tab-pane active" id="'+expId+'">' +
                        '<form class="expense-form">' +
                            '<div style="height: 76vh; overflow-y: scroll; ">' +
                                '<table class="table table-bordered table-fixed dataTable" >' +
                                    data+
                                '</table>' +
                            '</div>'+
                            '<table style="position: fixed; bottom: 0;">'+
                                '<tr>'+
                                    '<td>'+
                                        '<div class="form-group submitDiv col-xs-12 ">'+
                                            '<button class="btn btn-success submitBtn" type="button">Добавить</button>' +
                                            '<button class="btn btn-info delivery" type="button">Доставка <i class="glyphicon glyphicon-remove"></i></button>' +
                                            '<a href="javascript:;" data-href="expense/printExpCheck?exp='+ expId+'" class="btn btn-default btnPrint pull-right">' +
                                                '<i class="glyphicon glyphicon-print"></i>  Печать ' +
                                            '</a>'+
                                        '</div>' +
                                        '<div class="form-group col-xs-12">' +
                                            '<label class="checkbox-inline">' +
                                            '   <input class="checkDebt" type="checkbox"> Долг' +
                                            '</label>' +
                                            '<label class="checkbox-inline">' +
                                            '   <input class="checkTerm" type="checkbox"> Терминал' +
                                            '</label>' +
                                            '<label class="checkbox-inline">' +
                                            '   <input class="checkAvans" type="checkbox"> Аванс' +
                                            '</label>' +
                                            '<button class="btn btn-danger pull-right" type="button" id="closeExp"  >Закрыть</button>' +
                                        '</div>'+
                                        '<div class="form-group">' +
                                            '<div class="col-xs-5">' +
                                                '<input type="text" class="telNumber form-control">' +
                                            '</div>' +
                                            '<div class="col-xs-2">' +
                                                '<input type="text" class="time  form-control">' +
                                            '</div>'+
                                        '</div>'+
                                    '</td>'+
                                '</tr>'+
                            '</table>' +
                        '</form>' +
                        '</div>';
                    $(".tab-content").append(strTabContent);
                    getDeliveryData(expId);
                    $(".btnPrint").printPage();
                }
            });
        }

        function getDeliveryData(expense_id){
            $.ajax({
                type: "POST",
                data: "expId="+expense_id,
                url: "<?php echo Yii::app()->createUrl('expense/getDeliveryData'); ?>",
                success: function(data){
                    data = JSON.parse(data);
                    if(data.delivery) {
                        data = data.delivery;
                        $("#deliveryAddress").val(data.address);
                        $("#deliveryPhone").val(data.phone);
                        $(".tab-content .active .telNumber").val(data.phone);
                        $(".tab-content .active .time").val(data.delivery_time);
                        $("#deliveryTime").val(data.delivery_time);
                        $("#deliveryComment").val(data.comment);
                        $("#deliveryPrice").val(data.price);
                        isDelivery = true;
                        $(".delivery i").removeClass("glyphicon-remove");
                        $(".delivery i").addClass("glyphicon-check");
                    }
                    else{
                        $("#deliveryAddress").val("");
                        $("#deliveryPhone").val("");
                        $("#deliveryTime").val("");
                        $(".tab-content .active .telNumber").val("");
                        $(".tab-content .active .time").val("");
                        $("#deliveryComment").val("");
                        $("#deliveryPrice").val("");
                        isDelivery = false;
                        $(".delivery i").removeClass("glyphicon-check");
                        $(".delivery i").addClass("glyphicon-remove");
                        $(".tab-content .active .telNumber").val(data.expense.phone);
                        $(".tab-content .active .time").val(data.expense.ready_time);
                    }

                }
            });
            console.log(isDelivery);
        }
        $("#addBtn").click(function () {
            addTab();

        });
        $(document).on('click','#addDebt',function () {
            var expSum = $(".tab-content .active .dataTable .summ").text();
            var discount = $(".tab-content .active .dataTable .discount").val();
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('expense/closeExp'); ?>",
                data: "paid=debt&id="+globExpId+"&sum="+expSum+"&check=0&text="+$("#textDebtComment").val()+"&discount="+discount+"&paidDebt="+$("#textDebtPaid").val(),
                success: function(){
                    $(".nav-tabs li.active").remove();
                    $("#"+globExpId).remove();
                    closeExp();
                }
            });
        });
        $(document).on("click",".delivery", function () {
            $("#contractorModal").modal("show");
        });

        $(document).on('click','#closeTerm',function () {
            var expSum = $(".tab-content .active .dataTable .summ").text();
            var discount = $(".tab-content .active .dataTable .discount").val();
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('expense/closeExp'); ?>",
                data: "paid=term&id="+globExpId+"&sum="+expSum+"&check=0&text="+$("#terminalSum").val()+"&discount="+discount,
                success: function(){
                    $(".nav-tabs li.active").remove();
                    $("#"+globExpId).remove();
                    closeExp();

                }
            });
        });

        $(document).on('click','#closeAvans',function () {


            let formData = $("#deliveryForm").serialize();
            var data = $(".tab-content .active .expense-form").serialize();
            var expSum = $(".tab-content .active .dataTable .summ").text();
            let telNumber = "";
            let prepareTime = "";
            if(!isDelivery){
                telNumber = $(".tab-content .active .telNumber").val();
                prepareTime = $(".tab-content .active .time").val();
            }
            var expSum = $(".tab-content .active .dataTable .summ").text();
            var discount = $(".tab-content .active .dataTable .discount").val();
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('expense/closeAvans'); ?>",
                data: data+"&table=0&employee_id="+<?=Yii::app()->user->getId()?>+"&expenseId="+ globExpId+"&peoples=0"+"&expSum="+expSum+"&check=0"+"&banket=0&delivery="+formData+"&phone="+telNumber+"&ready_time="+prepareTime + "paid=avans&sum="+expSum+"&check=0&text="+$("#textAvansComment").val()+"&discount="+discount+"&paidAvans="+$("#textAvansPaid").val(),
                success: function(){
                    $(".nav-tabs li.active").remove();
                    $("#"+globExpId).remove();
                    closeExp();
                }
            });
        });

        $(document).on('click','#closeExp',function () {
            var expSum = $(".tab-content .active .dataTable .summ").text();
            var discount = $(".tab-content .active .dataTable .discount").val();
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('expense/closeExp'); ?>",
                data: "paid=cash&id="+globExpId+"&sum="+expSum+"&check=0&discount="+discount,
                success: function(){
                    $(".nav-tabs li.active").remove();
                    $("#"+globExpId).remove();
                    closeExp();
                }
            });
        });

        function closeExp(){
            $("#debtComment").slideUp("slow");
            $("#textDebtComment").val("");
            $("#termSum").slideUp("slow");
            $("#avansSum").slideUp("slow");
            $("#terminalSum").val("");
            globExpId = 0;
            $(".nav-tabs li:first-child").addClass("active");
        }

        $(document).on('click','.nav-tabs li', function () {
            console.log("push navbar li")
            var id = $('.tab-content .active').attr('id');
            globExpId = id;
            getDeliveryData(id);
            $("#debtComment").slideUp("slow");
            $("#textDebtComment").val("");
            $(".checkDebt").prop( "checked", false );
            $("#termSum").slideUp("slow");
            $("#terminalSum").val("");
            $(".checkTerm").prop( "checked", false );
            $(".checkAvans").prop( "checked", false );
        });

        $(document).on('click','.checkDebt', function () {
            if($(this).is(":checked")) {
                $("#debtComment").slideDown("slow");
                $("#avansSum").slideUp("slow");
                $("#termSum").slideUp("slow");
                $(".checkTerm").prop( "checked", false );
                $(".checkAvans").prop( "checked", false );
                $("#closeExp").addClass("hidden");
            }
            else{
                $("#debtComment").slideUp("slow");
                $("#closeExp").removeClass("hidden");
            }
        });
        $(document).on('click','.checkTerm', function () {
            if($(this).is(":checked")) {
                $("#termSum").slideDown("slow");
                $("#avansSum").slideUp("slow");
                $("#debtComment").slideUp("slow");
                $(".checkDebt").prop( "checked", false );
                $(".checkAvans").prop( "checked", false );
                $("#closeExp").addClass("hidden");
            }
            else{
                $("#termSum").slideUp("slow");
                $("#closeExp").removeClass("hidden");
            }
        });

        $(document).on('click','.checkAvans', function () {
            if($(this).is(":checked")) {
                $("#avansSum").slideDown("slow");
                $("#termSum").slideUp("slow");
                $("#debtComment").slideUp("slow");
                $(".checkDebt").prop( "checked", false );
                $(".checkTerm").prop( "checked", false );
                $("#closeExp").addClass("hidden");
            }
            else{
                $("#avansSum").slideUp("slow");
                $("#closeExp").removeClass("hidden");
            }
        });

        $(document).on("click", '.types' ,function(){
            var thisId = $(this).parent().attr('id');
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('expense/lists'); ?>",
                data: "id="+thisId,
                success: function(data){
                    $('#data').html(data);
                }
            });
        });

        $(document).on('click','.plus', function () {
            $(".nav-tabs li.active").removeClass("changed");
            $(".nav-tabs li.active").addClass("change");
            var identifies = $(this).children('span').text();
            var thisId = $(this).attr('id');

            if($('.tab-content .active .order tr.'+thisId).exists()){
                var types = str_split(thisId,1);
                var count = $('.tab-content .active .order tr.'+thisId).children("td.cnt").children('input').val();
                count = parseFloat(count)+1;
                $('.tab-content .active .order tr.'+thisId).children("td:first-child").children('input').val(thisId);
                $('.tab-content .active .order tr.'+thisId).children("td.cnt").children('input').val(count);
                $('.tab-content .active .order tr.'+thisId).children("td.cnt").children('span').text(count);
            }
            else{
                var types = str_split(thisId,1);
                $('.tab-content .active .order').append("<tr class="+thisId+">\
                                <td class='removed  '>\
                                    <i class='glyphicon glyphicon-remove'></i>\
                                    <input style='display:none' name='id[]' value='"+thisId+"' />\
                                </td>\
                                <td class='dish'> <input style='display:none' type='text' name='comment[]'>"+identifies+"</td>\
                                <td>"+$(this).children('div').text()+"</td>\
                                <td class='cnt'>\
                                    <input name='count[]' style='display:none' value='1' />\
                                    <a type='button' class='pluss btn hide'>\
										<input name='' style='display:none' value='0'>\
                                        <i class='fa fa-plus'></i>\
                                    </a>\
                                    <span>" +1+"</span>\
                                    <a type='button' class='minus btn hide'>\
                                        <i class='fa fa-minus'></i>\
                                    </a>\
                                </td>\
                            </tr>");
            }
            getSum();
        });

        $(document).on("click",".removed", function () {
            $(".nav-tabs li.active").removeClass("changed");
            $(".nav-tabs li.active").addClass("change");
            var id = $(this).parent().attr('class');
            $(this).parent().remove();
            // removeFromOrder(id,0);

            getSum();
        });

        $(document).on("click",'.tab-content .active .submitBtn', function(){

            let formData = $("#deliveryForm").serialize();
            var data = $(".tab-content .active .expense-form").serialize();
            var expSum = $(".tab-content .active .dataTable .summ").text();
            let telNumber = "";
            let prepareTime = "";
            console.log(formData);
            if(!isDelivery){
                telNumber = $(".tab-content .active .telNumber").val();
                prepareTime = $(".tab-content .active .time").val();
            }
            var res = $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('expense/create'); ?>",
                data: data+"&table=0&employee_id="+<?=Yii::app()->user->getId()?>+"&expenseId="+ globExpId+"&peoples=0"+"&expSum="+expSum+"&check=0"+"&banket=0&delivery="+formData+"&phone="+telNumber+"&ready_time="+prepareTime,
                success: function(){
                    $(".nav-tabs li.active").removeClass("change");
                    $(".nav-tabs li.active").addClass("changed");
                },
                error: function(){
                    alert("что то пошло не так! попробуйте еще раз")
                }
            });
            console.log(res);
            $('.tab-content .active .dataTable order').children('tr').remove();
            //$('#Expense_debt').removeAttr('checked');
            //$("#Expense_comment").val('');
            getSum();
        });

        $(document).on('keyup',".discount", function (e) {
            if (e.keyCode == 13) {
                $.ajax({
                    type: "POST",
                    url: "<?php echo Yii::app()->createUrl('expense/setDiscount'); ?>",
                    data: 'id='+globExpId+"&val="+$(this).val(),
                    success: function(data){
                    }
                });
            }
        });

        $(document).on("click", ".cnt", function() {
            var count = $(this).children('input').val();
            cntObj = $(this);
            cntVal = count;
            var thisClass = $(this).parent().parent().attr('class');
            $("#myModal").modal();
            return false;
        });



        function printCheck(expSum){
            var data = $("#orderForm").serialize();
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('orders/printCheck'); ?>",
                data: data + "&expSum=" + $("#summ").text() + "&expId="+$("#expId").val(),
                success: function (data) {
                    getSum();
                }
            });
        }

        $(document).on("keyup","#customValue", function(e){
            if (e.keyCode == 13) {
                console.log($(this).val());
                cntObj.children("span").text($(this).val());
                cntObj.children("input").val($(this).val());
                getSum();
                $(this).val("");
                $("#myModal").modal("hide");
            }
        })

        $.fn.cntChange = function () {
            $(this).on('click',function() {
                var id = $(this).attr("id");
                $(".nav-tabs li.active").removeClass("changed");
                $(".nav-tabs li.active").addClass("change");
                switch (id){
                    case "plusOne":
                        var changeCnt = parseFloat(parseFloat(cntObj.children("span").text()) + 0.1).toFixed(1);
                        cntObj.children("span").text(changeCnt);
                        cntObj.children("input").val(changeCnt);
                        break;
                    case "plusHalf":
                        var changeCnt = parseFloat(parseFloat(cntObj.children("span").text()) + 0.5).toFixed(1);
                        cntObj.children("span").text(changeCnt);
                        cntObj.children("input").val(changeCnt);
                        break;

                    case "minusHalf":
                        var changeCnt = parseFloat(parseFloat(cntObj.children("span").text()) - 0.5).toFixed(1);
                        if($("#action").val() == "update"){

                            cntObj.children("span").text(changeCnt);
                            cntObj.children("input").val(changeCnt);
                        }
                        else if($("#action").val() == "create"){
                            cntObj.children("span").text(changeCnt);
                            cntObj.children("input").val(changeCnt);
                        }
                        break;
                    case "minusOne":
                        var changeCnt = parseFloat(parseFloat(cntObj.children("span").text()) - 0.1).toFixed(1);
                        if($("#action").val() == "update"){

                            cntObj.children("span").text(changeCnt);
                            cntObj.children("input").val(changeCnt);
                        }
                        else if($("#action").val() == "create"){
                            cntObj.children("span").text(changeCnt);
                            cntObj.children("input").val(changeCnt);
                        }
                        break;

                }
                getSum();
            });
            return this;
        };

        function str_split ( str, len ) {

            str = str.split('_');
            if ( !len ) { return str; }

            var r = [];
            for( var k=0;k<(str.length/len); k++ ) {
                r[k] = '';
            }
            for( var k in str ) {
                r[ Math.floor(k/len) ] += str[k];
            }
            return r;
        };

        function getSum(){
            var summ = 0;
            $('.tab-content .active .dataTable tbody tr').each(function(indx){
                var temp = parseFloat($(this).children('td:nth-child(4)').text())*parseInt($(this).children('td:nth-child(3)').text());
                summ += temp
                //sum += $(this).children('td:nth-child(3)').text();
            });
            summ = parseInt(summ/100) * 100;
            $('.tab-content .active .dataTable .summ').text(summ);
        }


        jQuery.fn.exists = function() {
            return $(this).length;
        }

        $(document).ready(function(){
            $("#menuDrop").chosen({
                width: "100%",
                no_results_text: "Ничего не найдено"
            }).change(function(){
                $(".nav-tabs li.active").removeClass("changed");
                $(".nav-tabs li.active").addClass("change");
                var texts = $(this).find('option:selected').text();
                var thisId = $(this).val();
                console.log(texts,thisId);
                var temps = str_split(texts,1);
                if($('.tab-content .active .order tr.'+thisId).exists()){
                    var types = str_split(thisId,1);
                    var count = $('.tab-content .active .order tr.'+thisId).children("td.cnt").children('input').val();
                    count = parseFloat(count)+1;
                    $('.tab-content .active .order tr.'+thisId).children("td:first-child").children('input').val(thisId);
                    $('.tab-content .active .order tr.'+thisId).children("td.cnt").children('input').val(count);
                    $('.tab-content .active .order tr.'+thisId).children("td.cnt").children('span').text(count);
                }
                else{
                    var types = str_split(thisId,1);
                    $('.tab-content .active .order').append("<tr class="+thisId+">\
                                <td class='removed  '>\
                                    <i class='glyphicon glyphicon-remove'></i>\
                                    <input style='display:none' name='id[]' value='"+thisId+"' />\
                                </td>\
                                <td class='dish'> <input style='display:none' type='text' name='comment[]'>"+temps[0]+"</td>\
                                <td>"+temps[1]+"</td>\
                                <td class='cnt'>\
                                    <input name='count[]' style='display:none' value='1' />\
                                    <a type='button' class='pluss btn hide'>\
										<input name='' style='display:none' value='0'>\
                                        <i class='fa fa-plus'></i>\
                                    </a>\
                                    <span>" +1+"</span>\
                                    <a type='button' class='minus btn hide'>\
                                        <i class='fa fa-minus'></i>\
                                    </a>\
                                </td>\
                            </tr>");
                }

                getSum();
            });
            $(".btnPrint").printPage();
            $(".expCheck").printPage();
            $(".cntPlus").cntChange();
            $("#printReport").printPage();
        });

        $(document).on("click","#deliveryOk", function () {
            let formData = $("#deliveryForm").serialize();
            $.ajax({
                type: "POST",
                data: formData+"&expId="+globExpId,
                url: "<?php echo Yii::app()->createUrl('expense/createDelivery'); ?>",
                success: function(){
                    isDelivery = true;
                    $(".delivery i").removeClass("glyphicon-remove");
                    $(".delivery i").addClass("glyphicon-check");
                    $("#contractorModal").modal("hide");
                }
            })


        });
        $(document).on("click","#deliveryCencel", function () {
            isDelivery = false;
            $(".delivery i").removeClass("glyphicon-check");
            $(".delivery i").addClass("glyphicon-remove");
            $.ajax({
                type: "POST",
                data: "expId="+globExpId,
                url: "<?php echo Yii::app()->createUrl('expense/cencelDelivery'); ?>",
                success: function(){
                    isDelivery = false;
                    $(".delivery i").removeClass("glyphicon-check");
                    $(".delivery i").addClass("glyphicon-remove");
                    $("#contractorModal").modal("hide");
                    $("#deliveryAddress").val("");
                    $("#deliveryPhone").val("");
                    $("#deliveryTime").val("");
                    $("#deliveryComment").val("");
                    $("#deliveryPrice").val("");
                }
            })
        });

        $(document).on("click", ".dish", function () {
            $("#comment").modal("show");
            commentedElement = $(this).parent().attr('class');
        });

        $(document).on('click',"#saveComment", function () {
            let text = $("#commentText").val();
            $("."+commentedElement+" .dish>input").val(text);
            $("#commentText").val("");
            $("#comment").modal("hide");
        });

        $(document).on('hide.bs.modal','#comment', function () {
            console.log("close modal");
            $('#commentText').val("");
        });

        $(document).on('show.bs.modal','#comment', function () {
            console.log("close modal");
            $('#commentText').focus();
        });
    </script>

    <?
    foreach ($expModel as $item) {?>
        <script>
            addTab(<?=$item["expense_id"]?>);
        </script>
    <?}
    ?>
    <!--/*****      --------------Modal windows------------     ******/-->

</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body" id="myModalBody">
                <div class="row">
                    <div class="col-xs-3 col-md-2">
                        <a href="#" class="thumbnail cntPlus" id="plusOne">
                            <img src="/images/dish_bg.jpg" alt="...">
                            <h1 class="texts">+0.1</h1>
                        </a>
                    </div>
                    <div class="col-xs-3 col-md-2">
                        <a href="#" class="thumbnail cntPlus" id="plusHalf">
                            <img src="/images/dish_bg.jpg" alt="...">
                            <h1 class="texts">+0.5</h1>
                        </a>
                    </div>
                    <div class="col-xs-3 col-md-2" >
                        <a href="#" class="thumbnail cntPlus" id="minusHalf">
                            <img src="/images/dish_bg.jpg" alt="...">
                            <h1 class="texts">-0.5</h1>
                        </a>
                    </div>
                    <div class="col-xs-3 col-md-2" >
                        <a href="#" class="thumbnail cntPlus" id="minusOne">
                            <img src="/images/dish_bg.jpg" alt="...">
                            <h1 class="texts">-0.1</h1>
                        </a>
                    </div>
                </div>
                <div class="col-xs-5 col-md-5">
                    <input type="number" class="form-control" id="customValue">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" id="modalOk" data-dismiss="modal">Ok</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="contractorModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body" id="contractorModalBody">
                <form id="deliveryForm">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <div class="col-sm-4">
                                <input type="text" placeholder="Телефон" name="deliveryPhone" class="form-control" id="deliveryPhone">
                            </div>
                            <div class="col-sm-4">
                                <input type="text" placeholder="Сумма доставки" name="deliveryPrice" class="form-control" id="deliveryPrice">
                            </div>
                            <div class="input-group  col-sm-3">
                                <input type="text" placeholder="Время доставки" name="deliveryTime" class="form-control" id="deliveryTime">
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                            <div class="col-sm-6">
                                <textarea name="deliveryAddress" class="form-control" id="deliveryAddress"placeholder="Адрес" cols="30" rows="1"></textarea>
                            </div>
                            <div class="col-sm-6">
                                <textarea name="deliveryComment" class="form-control" id="deliveryComment" placeholder="Примечание" cols="30" rows="1"></textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" id="deliveryCencel" >Отмена</button>
                <button type="button" class="btn btn-default" id="deliveryOk" >Ok</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="comment" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <form action="" id="costsForm">
                    <div class="form-group">
                        <input type="text" id="commentText" placeholder="Комментарий к блюду" class="form-control">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                <button type="button" class="btn btn-default" id="saveComment" >Сохранить</button>
            </div>
        </div>
    </div>
</div>
