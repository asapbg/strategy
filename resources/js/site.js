var canAjax = true;
$(function() {
    //===============================
    // START MyModal
    // Create modal and show it with option for load body from url or pass direct content
    // available params:
    // title, body (content), destroyListener (boolean : do destroy modal on close), bodyLoadUrl (url for loading body content)
    //===============================

    function MyModal(obj){
        var _myModal = Object.create(MyModal.prototype)
        _myModal.id = (new Date()).getTime();
        _myModal.dismissible = typeof obj.dismissible != 'undefined' ? obj.dismissible : true;
        _myModal.title = typeof obj.title != 'undefined' ? obj.title : '';
        _myModal.body = typeof obj.body != 'undefined' ? obj.body : '';
        _myModal.footer = typeof obj.footer != 'undefined' ? obj.footer : '';
        _myModal.bodyLoadUrl = typeof obj.bodyLoadUrl != 'undefined' ? obj.bodyLoadUrl : null;
        _myModal.destroyListener = typeof obj.destroyListener != 'undefined' ? obj.destroyListener : false;
        _myModal.customClass = typeof obj.customClass != 'undefined' ? obj.customClass : '';
        _myModal.modalObj = _myModal.init(_myModal);
        if( _myModal.destroyListener ) {
            _myModal.setDestroyListener(_myModal);
        }
        if( _myModal.bodyLoadUrl ) {
            _myModal.loadModalBody(_myModal)
        } else {
            _myModal.showModal(_myModal);
        }
        return _myModal;
    }

    function MyModal(obj){
        var _myModal = Object.create(MyModal.prototype)
        _myModal.id = (new Date()).getTime();
        _myModal.title = typeof obj.title != 'undefined' ? obj.title : '';
        _myModal.dismissible = typeof obj.dismissible != 'undefined' ? obj.dismissible : true;
        _myModal.body = typeof obj.body != 'undefined' ? obj.body : '';
        _myModal.footer = typeof obj.footer != 'undefined' ? obj.footer : '';
        _myModal.bodyLoadUrl = typeof obj.bodyLoadUrl != 'undefined' ? obj.bodyLoadUrl : null;
        _myModal.destroyListener = typeof obj.destroyListener != 'undefined' ? obj.destroyListener : false;
        _myModal.customClass = typeof obj.customClass != 'undefined' ? obj.customClass : '';
        _myModal.modalObj = _myModal.init(_myModal);
        if( _myModal.destroyListener ) {
            _myModal.setDestroyListener(_myModal);
        }
        if( _myModal.bodyLoadUrl ) {
            _myModal.loadModalBody(_myModal)
        } else {
            _myModal.showModal(_myModal);
        }
        return _myModal;
    }

    MyModal.prototype.init = function (_myModal) {
        let modalHtml = '<div id="' + _myModal.id + '" class="modal fade myModal '+ _myModal.customClass +'" role="dialog" style="display: none">\n' +
            '  <div class="modal-dialog">\n' +
            '    <!-- Modal content-->\n' +
            '    <div class="modal-content">\n' +
            '      <div class="modal-header">\n' +
            '        <h4 class="modal-title">' + _myModal.title + '</h4>\n' +
            (_myModal.dismissible ? '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>\n' : '') +
            '      </div>\n' +
            '      <div class="modal-body" id="' + _myModal.id + '-body' + '">\n' + _myModal.body +
            '      </div>\n' +
            (_myModal.footer ? '<div class="modal-footer">'+ _myModal.footer +'</div>' : '') +
            '    </div>\n' +
            '  </div>\n' +
            '</div>';
        document.body.insertAdjacentHTML('beforeend', modalHtml);
        return  new bootstrap.Modal(document.getElementById(_myModal.id), {
            keyboard: false,
            backdrop: 'static'
        })
    }

    MyModal.prototype.showModal = function (_myModal){
        _myModal.modalObj.show();
    }

    MyModal.prototype.setDestroyListener = function (_myModal){
        $('#' + _myModal.id).on('hidden.bs.modal', function(){
            _myModal.modalObj.dispose();
            $('#' + _myModal.id).remove();
        });
    }

    MyModal.prototype.loadModalBody = function (_myModal) {
        $('#' + _myModal.id + '-body').load(_myModal.bodyLoadUrl, function (){
            _myModal.showModal(_myModal);
        });
    }


    $(document).ready(function() {
        let hash = location.hash.replace(/^#/, '');  // ^ means starting, meaning only match the first hash
        if (hash) {
            $('#'+hash+'-tab').trigger('click');
        }

        $('.nav-tabs a').on('shown.bs.tab', function (e) {
            window.location.hash = e.target.hash;
        });

        if( $('.preview-file-modal').length ) {
            $('.preview-file-modal').on('click', function(){
                let cancelBtnTxt = GlobalLang == 'bg' ? 'Откажи' : 'Cancel';
                let titleTxt = GlobalLang == 'bg' ? 'Преглед на файл' : 'File preview';
                if( canAjax ) {
                    canAjax = false;
                    new MyModal({
                        title: titleTxt,
                        footer: '<button class="btn btn-sm btn-secondary closeModal ms-3" data-dismiss="modal" aria-label="'+ cancelBtnTxt +'">'+ cancelBtnTxt +'</button>',
                        bodyLoadUrl: $(this).data('url'),
                        customClass: 'file-preview'
                    });
                    canAjax = true;
                }

            });
        }

        $.datepicker.setDefaults( $.datepicker.regional[typeof GlobalLang != 'undefined' ? GlobalLang : ''] );
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
                language: typeof GlobalLang != 'undefined' ? GlobalLang : 'en',
                format: 'dd.mm.yyyy',
                todayHighlight: true,
                orientation: "bottom left",
                autoclose: true,
                weekStart: 1,
                changeMonth: true,
                changeYear: true,
            });
        }

        var tabEl = $('button[data-bs-toggle="tab"]');
        tabEl.on('shown.bs.tab', function (event) {
            event.target // newly activated tab
            event.relatedTarget // previous active tab
        })

        if($('.summernote').length) {
            $('.summernote').summernote({
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol','paragraph']],
                    ['view', ['fullscreen']],
                    // ['insert', ['link']]
                ]
            });
        }

    });
});
