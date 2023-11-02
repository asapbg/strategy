$(function() {
    $(document).ready(function() {
        $.datepicker.regional = {
            bg: {
                days: ["Неделя", "Понеделник", "Вторник", "Сряда", "Четвъртък", "Петък", "Събота", "Неделя"],
                daysShort: ["Нед", "Пон", "Вт", "Ср", "Чет", "Пет", "Съб", "Нед"],
                daysMin: ["Н", "П", "В", "С", "Ч", "П", "С", "н"],
                months: ["Януари", "Февруари", "Март", "Април", "Май", "Юни", "Юли", "Август", "Септември", "Октомври", "Ноември", "Декември"],
                monthsShort: ["Ян", "Фев", "Мар", "Апр", "Май", "Юн", "Юл", "Авг", "Сеп", "Окт", "Ное", "Дек"],
                today: "Днес",
                clear: "Изчисти"
            },

            en: {
                days: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"],
                daysShort: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
                daysMin: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa", "Su"],
                months: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
                monthsShort: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                today: "Today",
                clear: "Clear"
            }

        };
        $.datepicker.setDefaults($.datepicker.regional['bg']);

        if ($('.datepicker').length) {
            $('.datepicker').datepicker({
                language: 'bg',
                format: 'dd.mm.yyyy',
                todayHighlight: true,
                orientation: "bottom left",
                autoclose: true,
                weekStart: 1,
                changeMonth: true,
                changeYear: true,
            });
        }
    });
});
