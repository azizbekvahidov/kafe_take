<? $expense = new Expense(); $allSalary = 0; $waiterCnt = 0; $paidDebtSum = 0; $debtSum = 0; $costSumm = 0;
$waiterProc = Yii::app()->config->get("waiterSalary");
?>
<style>
    th,td{

        padding-left: 3px;
        border: 1px solid #000;
        text-align: left;
        border-collapse: collapse;
        font-size: 10px;
    }
    th:last-child,td:last-child{
        text-align: right;
    }
    table{
        width:100%;
        border-collapse: collapse;
    }
    h2{
        padding: 0;
        margin: 0;
    }
</style>
<div class="col-lg-12">
    <h2>Прибыль</h2>
    <table class="" id="dataTable">
        <thead>
            <tr>
                <th>Тип прибыли</th>
                <th>Сумма</th>
            </tr>
        </thead>
        <tbody>
        <tr>
            <td>Наличные</td>
            <th><?=number_format($sum - $terminal,0,',',' ')?></th>
        </tr>
        <tr>
            <td>Терминал</td>
            <th><?=number_format($terminal,0,',',' ')?></th>
        </tr>
        <tr>
            <td>Аванс</td>
            <th><?=number_format($avans,0,',',' ')?></th>
        </tr>
        <tr>
            <td>Итог</td>
            <th><?=number_format($sum + $avans,0,',',' ')?></th>
        </tr>
        </tbody>
    </table>
    <h2>Расходы</h2>
    <table class="" >
        <tr>
            <th colspan="">Расход</th>
            <td colspan="">Сумма</td>
        </tr>
        <? foreach ($cost as $val){?>
            <tr>
                <td colspan=""><?=$val["comment"]?></td>
                <td colspan=""><?=number_format($val["summ"]*(-1),0,',',' ')?> </td>

            </tr>
            <?$costSumm = $costSumm + $val["summ"];}?>

        <tr>
            <th colspan="">Итого</th>
            <th colspan=""><?=number_format($costSum,0,',',' ')?></th>
        </tr>
    </table>
    <h2>Долги</h2>
    <table class="" >
        <tr>
            <th colspan="">Комментарий</th>
            <td colspan="">Сумма</td>
        </tr>
        <? foreach ($debt as $val){?>
            <tr>
                <td colspan=""><?=$val["comment"]?></td>
                <td colspan=""><?=number_format($val["expSum"]-$val["debtPayed"],0,',',' ')?> </td>
            </tr>
            <?$debtSum = $debtSum + $val["expSum"]-$val["debtPayed"];}?>
        <tr>
            <th colspan="">Итого</th>
            <th colspan=""><?=number_format($debtSum,0,',',' ')?></th>
        </tr>
    </table>
    <h2>Оплаченные долги</h2>
    <table class="table table-bordered" >
        <tr>
            <th colspan="">Комментарий</th>
            <th colspan="">дата</th>
            <td colspan="">Сумма</td>
        </tr>
        <? foreach ($paidDebt as $val){?>
            <tr>
                <td colspan=""><?=$val["comment"]?></td>
                <td><?=date("d.m.Y",strtotime($val["d_date"]))?></td>
                <td colspan=""><?=number_format($val["expSum"]-$val["debtPayed"],0,',',' ')?> </td>
            </tr>
            <?$paidDebtSum = $paidDebtSum + $val["expSum"]-$val["debtPayed"];}?>
        <tr>
            <th colspan="2">Итого</th>
            <th colspan=""><?=number_format($paidDebtSum,0,',',' ')?></th>
        </tr>
    </table>
</div>
<?
?>
<div class="col-lg-12">
    <h2>Прибыль отделов</h2>
    <table class="" id="depSum">
        <thead>
        <tr>
            <th>Отдел</th>
            <th>Сумма</th>
        </tr>
        </thead>
        <tbody>

        <? $allDepSum = 0;
        foreach($department as $val){ $depSum = $expense->getDepIncome($val["department_id"],$to,$to);?>
            <tr>
                <td><?=$val["name"]?></td>
                <td><?=number_format($depSum,0,',',' ')?></td>
            </tr>
            <? $allDepSum = $allDepSum + $depSum;}?>
        <tr>
            <th>Всего</th>
            <th><?=number_format($allDepSum,0,',',' ')?></th>
        </tr>
        </tbody>

    </table>
</div>