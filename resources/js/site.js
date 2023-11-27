var canAjax = true;

//===============================
// START MyModal
// Create modal and show it with option for load body from url or pass direct content
// available params:
// title, body (content), destroyListener (boolean : do destroy modal on close), bodyLoadUrl (url for loading body content)
//===============================

function MyModal(obj) {
    const _myModal = Object.create(MyModal.prototype)
    _myModal.id = (new Date()).getTime();
    _myModal.title = typeof obj.title != 'undefined' ? obj.title : '';
    _myModal.dismissible = typeof obj.dismissible != 'undefined' ? obj.dismissible : true;
    _myModal.body = typeof obj.body != 'undefined' ? obj.body : '';
    _myModal.footer = typeof obj.footer != 'undefined' ? obj.footer : '';
    _myModal.bodyLoadUrl = typeof obj.bodyLoadUrl != 'undefined' ? obj.bodyLoadUrl : null;
    _myModal.destroyListener = typeof obj.destroyListener != 'undefined' ? obj.destroyListener : false;
    _myModal.customClass = typeof obj.customClass != 'undefined' ? obj.customClass : '';
    _myModal.modalObj = _myModal.init(_myModal);
    if (_myModal.destroyListener) {
        _myModal.setDestroyListener(_myModal);
    }
    if (_myModal.bodyLoadUrl) {
        _myModal.loadModalBody(_myModal)
    } else {
        _myModal.showModal(_myModal);
    }
    return _myModal;
}

MyModal.prototype.init = function (_myModal) {
    let modalHtml = '<div id="' + _myModal.id + '" class="modal fade myModal ' + _myModal.customClass + '" role="dialog" style="display: none">\n' +
        '  <div class="modal-dialog">\n' +
        '    <!-- Modal content-->\n' +
        '    <div class="modal-content">\n' +
        '      <div class="modal-header">\n' +
        '        <h4 class="modal-title">' + _myModal.title + '</h4>\n' +
        (_myModal.dismissible ? '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>\n' : '') +
        '      </div>\n' +
        '      <div class="modal-body" id="' + _myModal.id + '-body' + '">\n' + _myModal.body +
        '      </div>\n' +
        (_myModal.footer ? '<div class="modal-footer">' + _myModal.footer + '</div>' : '') +
        '    </div>\n' +
        '  </div>\n' +
        '</div>';
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    return new bootstrap.Modal(document.getElementById(_myModal.id), {
        keyboard: false,
        backdrop: 'static'
    })
}

MyModal.prototype.showModal = function (_myModal) {
    _myModal.modalObj.show();
}

MyModal.prototype.setDestroyListener = function (_myModal) {
    $('#' + _myModal.id).on('hidden.bs.modal', function () {
        _myModal.modalObj.dispose();
        $('#' + _myModal.id).remove();
    });
}

MyModal.prototype.loadModalBody = function (_myModal) {
    $('#' + _myModal.id + '-body').load(_myModal.bodyLoadUrl, function () {
        _myModal.showModal(_myModal);
    });
}

//=================================
//Select2
//===============================

function select2OptionFilter(option) {
    if (typeof option.element != 'undefined' && option.element.className === 'd-none' ) {
        return false
    }
    return option.text;
}

var select2Options = {
    allowClear: true,
    placeholder: true,
    templateResult: select2OptionFilter,
    language: "bg"
};

if($('.select2').length) {
    $('.select2').select2(select2Options);
}

if($('.select2-no-clear').length) {
    $('.select2-no-clear').select2({
        allowClear: false,
        placeholder: true,
        templateResult: select2OptionFilter,
        language: "bg"
    });
}

if($('.select2-autocomplete-ajax').length) {
    $('.select2-autocomplete-ajax').each(function (){
        MyS2Ajax($(this), $(this).data('placeholders2'), $(this).data('urls2'));
    });
}

//===============================
// START Select2 Ajax Autoload
// available params:
//
//===============================

function MyS2Ajax(selectDom, selectPlaceholder, selectUrl){
    selectDom.select2({
        allowClear: false,
        templateResult: select2OptionFilter,
        language: "bg",
        placeholder: selectPlaceholder,
        ajax: {
            url: selectUrl,
            data: function (params) {
                console.log('enters');
                if($(this).data('types2ajax') == 'pris_doc') {
                    var query = {
                        actType: $('#legal_act_type_filter').val(),
                        search: params.term
                    }
                }else if($(this).data('types2ajax') == 'lp_record') {
                    var query = {
                        programId: $('#legislative_program_id').val(),
                        search: params.term
                    }
                }else if($(this).data('types2ajax') == 'op_record') {
                    var query = {
                        programId: $('#operational_program_id').val(),
                        search: params.term
                    }
                }else if($(this).data('types2ajax') == 'pc') {
                    var query = {
                        connections: typeof $(this).data('connections') != 'undefined' ? $(this).data('connections') : null,
                        exclude: $(this).data('current'),
                        search: params.term
                    }
                } else {
                    var query = {
                        search: params.term
                    }
                }
                return query;
            },
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results:  $.map(data, function (item) {
                        return {
                            text: item.name,
                            id: item.id
                        }
                    })
                };
            },
            cache: true
        }
    });
}


$(document).ready(function () {
    let hash = location.hash.replace(/^#/, '');  // ^ means starting, meaning only match the first hash
    if (hash) {
        $('#' + hash + '-tab').trigger('click');
    }

    $('.nav-tabs a').on('shown.bs.tab', function (e) {
        window.location.hash = e.target.hash;
    });

    if ($('.preview-file-modal').length) {
        $('.preview-file-modal').on('click', function () {
            let cancelBtnTxt = GlobalLang == 'bg' ? 'Откажи' : 'Cancel';
            let titleTxt = GlobalLang == 'bg' ? 'Преглед на файл' : 'File preview';
            if (canAjax) {
                canAjax = false;
                new MyModal({
                    title: titleTxt,
                    footer: '<button class="btn btn-sm btn-secondary closeModal ms-3" data-dismiss="modal" aria-label="' + cancelBtnTxt + '">' + cancelBtnTxt + '</button>',
                    bodyLoadUrl: $(this).data('url'),
                    customClass: 'file-preview'
                });
                canAjax = true;
            }

        });
    }

    $.datepicker.setDefaults($.datepicker.regional[typeof GlobalLang != 'undefined' ? GlobalLang : '']);
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

    if ($('.summernote').length) {
        $('.summernote').summernote({
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['view', ['fullscreen']],
                ['insert', ['link']]
            ]
        });
    }

    if ($('.limit-length').length) {
        $('.limit-length').each(function (index, el) {
            let text = $(this).text();
            if (text.length > 1000) {
                $(this).html(text.substring(0, 1000) + ' <span class="show-more btn btn-primary px-2 py-0 ms-2">...</span>');
            }
            //full-length
        });
        $(document).on('click', '.show-more', function () {
            $(this).parent().addClass('d-none');
            $(this).parent().parent().find('.full-length').removeClass('d-none');
        });
    }

    if ($('.js-toggle-delete-resource-modal').length) {
        $('.js-toggle-delete-resource-modal').on('click', function(e) {
            e.preventDefault();

            // If delete url specify in del.btn use that url
            if($(this).data('resource-delete-url')) {
                $( $(this).data('target')).find('form').attr('action', $(this).data('resource-delete-url'));
            }

            $($(this).data('target')).find('span.resource-name').html($(this).data('resource-name'));
            $($(this).data('target')).find('#resource_id').attr('value', $(this).data('resource-id'));

            $($(this).data('target')).modal('toggle');
        })
    }

    //======================================
    // START PDOI SUBJECTS SELECT FROM MODAL
    // use <select name="subjects[]" id="subjects" class="select2">
    // and button with class 'pick-subject' and data-url attribute to call modal with the tree
    //you can add to url get parameters to set tree as selectable or just view : select=1
    // and if you need more than one subject to be selected use parameter 'multiple=1'
    //======================================
    if( $('.pick-institution').length ) {
        $('.pick-institution').on('click', function (){
            let subjectModal = new MyModal({
                title: $(this).data('title'),
                destroyListener: true,
                bodyLoadUrl: $(this).data('url'),
                customClass: 'no-footer'
            });

            $(document).on('click', '#select-institution', function (){
                let subjectsFormSelect = $('#' + $(this).data('dom'));
                let checked = $('#'+ subjectModal.id +' input[name="institutions-item"]:checked');
                if( checked.length ) {
                    if( checked.length === 1 ) {
                        subjectsFormSelect.val(checked.val());
                    } else if( checked.length > 1 ) {
                        let subjectValues = [];
                        checked.each(function(){
                            subjectValues.push($(this).val());
                        });
                        subjectsFormSelect.val(subjectValues);
                    }
                    subjectsFormSelect.trigger('change');
                }
                subjectModal.modalObj.hide();
            });
        });
    }

});
