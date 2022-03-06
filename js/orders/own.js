$(function() {
    $('.daterange').daterangepicker({
        opens: 'left',
        locale: {
            format: 'DD.MM.YYYY'
        }
    });
    $('.datePick').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        locale: {
            format: 'DD.MM.YYYY'
        }
    });$('.datetimePick').datetimepicker();



});
/*
window.TablesInit = function(obj) {
    table = obj.DataTable({
        fixedHeader: true,
        language: {
            decimal: "",
            emptyTable: "Нет данных в таблице",
            info: "Показать _START_ до _END_ из _TOTAL_ записей",
            infoEmpty: "Показать от 0 до 0 из 0 записей",
            infoFiltered: "(фильтровать по _MAX_)",
            infoPostFix: "",
            thousands: ",",
            lengthMenu: "Показать _MENU_ ",
            loadingRecords: "Загрузка...",
            processing: "Процесс...",
            search: "Поиск:",
            zeroRecords: "Нет соответствующих данных",
            paginate: {
                first: "Первый",
                last: "Конец",
                next: "След.",
                previous: "Пред."
            },
            aria: {
                sortAscending: ": Задать по нарастающему",
                sortDescending: ": Задать по убывающему"
            }
        },
        pageLength: "125",
        lengthMenu: [[5, 10, 50, 100, -1], [5, 10, 50, 100, "Все"]],
        processing:false,
        serverSide:false,

    });
};
$( document ).ajaxComplete(function() {
    $('#datatable').DataTable();
    console.log("good");
});*/