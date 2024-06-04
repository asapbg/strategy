var canAjax = true;

const SUBSCRIBED = 1;
const UNSUBSCRIBED = 0;

toastr.options = {
    "closeButton": true,
    "debug": false,
    "newestOnTop": false,
    "progressBar": false,
    "positionClass": "toast-top-right",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "1000",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
}

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
        (_myModal.dismissible ? '<button type="button" class="btn btn-close close" data-dismiss="modal" aria-label="Close"></button>\n' : '') +
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

function ShowLoadingSpinner() {
    $("#ajax_loader_backgr").show();
    $("#ajax_loader").show();
    setTimeout(() => {
        HideLoadingSpinner();
    }, 2500);
}

function HideLoadingSpinner() {
    $("#ajax_loader_backgr").hide();
    $("#ajax_loader").hide();
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
        allowClear: true,
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

// ===================
// !!! DO NOT CHANGE
// START LISTS AJAX
// ===================


function initInputs()
{
    $('.select2').select2(select2Options);
    $('.datepicker, .datepicker-btn').datepicker('destroy');
    $('.datepicker, .datepicker-btn').datepicker({
        language: typeof GlobalLang != 'undefined' ? GlobalLang : 'en',
        format: 'dd.mm.yyyy',
        todayHighlight: true,
        // orientation: "auto",
        autoclose: true,
        weekStart: 1,
        changeMonth: true,
        changeYear: true,
    }).on("show", function(e) {
        let top = $("#header-wrapper").height() + parseInt($(".datepicker").css("top"));
        $(".datepicker").css("top", top);
    });

    $('.pick-institution').on('click', function (){
        let subjectModal = new MyModal({
            title: $(this).data('title'),
            destroyListener: true,
            bodyLoadUrl: $(this).data('url'),
            customClass: 'no-footer'
        });
        // console.log($('#select-institution'));
// console.log(subjectModal.id);
//         $(document).on('click', '#select-institution', function (){
//             console.log('ok');
//         });
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

// START control connected filters
function institutionControl(){
    let level = $('#level');
    let levelVals = level.val();
    if($('#importers').length){
        $('#importers option').each(function (){
            let opt = $(this);
            if(typeof opt.data('level') == 'undefined'){
                $(this).attr('disabled', false);
            } else{
                if(levelVals.indexOf(opt.data('level').toString()) != -1){
                    $(this).attr('disabled', false);
                } else{
                    $(this).attr('disabled', true);
                }
            }
        });
    }
}

function categoriesControl(){
    let level = $('#level');
    let levelVals = level.val();

    if($('#fieldOfActions').length && typeof levelVals != 'undefined'){
        let fieldOfActionsConnected = $('#fieldOfActions');
        if(levelVals.length == 0 || (levelVals.indexOf(centralLevel) != -1 || levelVals.indexOf(centralOtherLevel) != -1)){
            fieldOfActionsConnected.parent().parent().parent().removeClass('d-none');
        } else{
            fieldOfActionsConnected.parent().parent().parent().addClass('d-none');
            fieldOfActionsConnected.val('');
        }
    }

    if($('#areas').length){
        let areasConnected = $('#areas');
        // console.log(level.val(), areaLevel, levelVals.indexOf(areaLevel), levelVals.indexOf(areaLevel) != -1 || !levelVals.length);
        if(levelVals.indexOf(areaLevel) != -1 || levelVals.length == 0){
            areasConnected.parent().parent().parent().removeClass('d-none');
        } else{
            areasConnected.parent().parent().parent().addClass('d-none');
            areasConnected.val('');
        }
    }

    if($('#municipalities').length){
        let municipalitiesConnected = $('#municipalities');
        // console.log(level.val(), municipalityLevel, levelVals.indexOf(municipalityLevel), levelVals.indexOf(municipalityLevel) != -1 || !levelVals.length);
        if(levelVals.indexOf(municipalityLevel) != -1 || levelVals.length == 0){
            municipalitiesConnected.parent().parent().parent().removeClass('d-none');
        } else{
            municipalitiesConnected.parent().parent().parent().addClass('d-none');
            municipalitiesConnected.val('');
        }
    }

    if($('#acceptActInstitution').length){
        $('#acceptActInstitution').val(null).trigger("change");
        if(levelVals.length == 0){
            $('#acceptActInstitution option').each(function (){
                $(this).attr('disabled', false);
            });
        } else{
            $('#acceptActInstitution option').each(function (){
                if(levelVals.indexOf($(this).data('level').toString()) != -1){
                    $(this).attr('disabled', false);
                } else{
                    $(this).attr('disabled', true);
                }
            });
        }
    }

    if($('#actTypes').length){
        $('#actTypes').val(null).trigger("change");
        if(levelVals.length == 0){
            $('#actTypes option').each(function (){
                $(this).attr('disabled', false);
            });
        } else{
            $('#actTypes option').each(function (){
                if(levelVals.indexOf($(this).data('level').toString()) != -1){
                    $(this).attr('disabled', false);
                } else{
                    $(this).attr('disabled', true);
                }
            });
        }
    }
}

$(document).on('change', '#level', function (){
    categoriesControl();
    institutionControl();
});
// END control connected filters

function ajaxList(domElement) {
    $(document).on('change', domElement + ' #groupByAjax', function (){
        $($(this).data('container')).load($(this).find(':selected').data('url'), function (){
            ShowLoadingSpinner();
            //$('.select2').select2(select2Options);
            initInputs();
            ajaxList($(this).data('container'));
            categoriesControl();
            institutionControl();
        });
    });
    $(document).on('change', domElement + ' #list-paginate', function (){
        $($(this).data('container')).load($(this).find(':selected').data('url'), function (){
            ShowLoadingSpinner();
            //$('.select2').select2(select2Options);
            initInputs();
            ajaxList($(this).data('container'));
            categoriesControl();
            institutionControl();
        });
    });
    $(document).on('click', domElement + ' .ajaxSort', function (e){
        e.preventDefault();
        $($(this).data('container')).load($(this).data('url'), function (){
            ShowLoadingSpinner();
            initInputs();
            ajaxList($(this).data('container'));
            categoriesControl();
            institutionControl();
        });
    });
    $(document).on('click', domElement + ' .ajaxSearch', function (e){
        e.preventDefault();
        let data = $(this).closest('form').serializeArray();
        let dataObj = {};
        let oldParams = $.map($(this).data('params'), function(value, index) {
            return [[index,value]];
        });

        $(data).each(function(i, field){
            let multiValue = field.name.split('[');
            if(typeof multiValue[0] != "undefined") {
                dataObj[field.name] = typeof dataObj[field.name] == 'undefined' ? field.value : dataObj[field.name] + ',' + field.value;
            } else {
                dataObj[field.name] = field.value;
            }
        });
        let url = $(this).data('url');
        $($(this).data('container')).load(url + '?' + jQuery.param( dataObj ), function (){
            ShowLoadingSpinner();
            initInputs();
            categoriesControl();
            institutionControl();
            ajaxList($(this).data('container'));
        });
    });
}

function clearSearchForm() {
    $("#results-per-page").prop("selectedIndex", 0);
    $("#search-form").find("input, textarea").not("#amount").not('#model_type').val("");
    $("#search-form").find('select option[value=""]').not("#results-per-page").prop('selected', true);
    $("#search-form").find('.select2').val('clear').trigger('change');
}

function isEmpty(arg){
    return (
        arg == null || // Check for null or undefined
        arg.length === 0 || // Check for empty String (Bonus check for empty Array)
        (typeof arg === 'object' && Object.keys(arg).length === 0) // Check for empty Object or Array
    );
}

function showModalAlert(message,title = false) {
    if (title) {
        $("#modal-alert .modal-title").html(title);
    }
    $("#modal-alert .modal-body").html(message);
    $("#modal-alert").modal('show');
}

//ajaxList();
// ===================
// !!! DO NOT CHANGE
// END LISTS AJAX
// ===================

$(document).ready(function () {
    $(document).on('keypress', function (e){
        if(e.which == 13) {
            if($('.ajaxSearch').length){
                e.preventDefault();
                $('.filter-results').parent().find('.ajaxSearch').trigger('click');
            }
        }
    });


    let hash = location.hash.replace(/^#/, '');  // ^ means starting, meaning only match the first hash
    if (hash) {
        console.log('#' + hash + '-tab');
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
                    footer: '<button class="btn btn-sm btn-danger closeModal ms-3" data-dismiss="modal" aria-label="' + cancelBtnTxt + '">' + cancelBtnTxt + '</button>',
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
    // if ($('.datepicker').length) {
        $('.datepicker, .datepicker-btn').datepicker({
            language: typeof GlobalLang != 'undefined' ? GlobalLang : 'en',
            format: 'dd.mm.yyyy',
            todayHighlight: true,
            // orientation: "auto",
            autoclose: true,
            weekStart: 1,
            changeMonth: true,
            changeYear: true,
        }).on("show", function(e) {
            let top = $("#header-wrapper").height() + parseInt($(".datepicker").css("top"));
            $(".datepicker").css("top", top);
        });

        // $('.datepicker-btn').datepicker({
        //     language: typeof GlobalLang != 'undefined' ? GlobalLang : 'en',
        //     format: 'dd.mm.yyyy',
        //     todayHighlight: true,
        //     orientation: "auto",
        //     autoclose: true,
        //     weekStart: 1,
        //     changeMonth: true,
        //     changeYear: true,
        //     showOnFocus: "false"
        // });
    // }

    // $(document).on('focusout', '.datepicker-btn', function() {
    //     $(this).datepicker('update', $(this).val());
    // });
    // $(document).on('click', '.datepicker-addon', function (){
    //     $(this).parent().find('.datepicker-btn').datepicker('show');
    // });

    var tabEl = $('button[data-bs-toggle="tab"]');
    tabEl.on('shown.bs.tab', function (event) {
        event.target // newly activated tab
        event.relatedTarget // previous active tab
    })

    $(document).tooltip({
        selector: '[data-bs-toggle="tooltip"]'
    });
    // $('[data-bs-toggle="tooltip"]').tooltip();

    if ($('.summernote').length) {
        $('.summernote').summernote({
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['view', ['fullscreen']],
                ['insert', ['link']]
            ],
            dialogsInBody: true,
            lang: typeof GlobalLang != 'undefined' ? GlobalLang + '-' + GlobalLang.toUpperCase() : 'en-US',
        });
    }

    $(document).on('click', '.share-link', function (){
        if(typeof $(this).data('link') != 'undefined'){
            window.navigator.clipboard.writeText($(this).data('link'));
            toastr.success('Връзката е копирана');
        }
    });

    $('#global_search_btn').on('click', function (){
        let lForm = $(this).closest('form');
        $('#global_search_error').html('').addClass('d-none');
        if($('#global_search').val().length > 3){
            $(lForm).submit();
        } else{
            $('#global_search_error').html('Въведете поне три символа').removeClass('d-none');
        }
    });

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

    if($('.li-law').length){
        $('.li-law').on('change', function (){
            let selectedLaw = parseInt($(this).val());
            let activePc = $(this).data('activepc');

            $('#active_consultation_info').addClass('d-none');
            $('#active_consultation_info #consultations').html('');
            $('#institutions_section').removeClass('d-none');
            $('#new_li_submit').removeClass('d-none');

            let foundPc = false;
            if(selectedLaw > 0 && typeof activePc != 'undefined' && typeof activePc[selectedLaw] != 'undefined'){
                foundPc = true;
                $('#new_li_submit').addClass('d-none');
                //deselect and hide institution section
                $('#institutions_section select option').each(function (){
                    $(this).attr('disabled', true);
                });
                $('#institutions_section').addClass('d-none');

                //show active consultation info
                let consultationsHtml = '';
                $.each(activePc[selectedLaw], function(index,value) {
                    consultationsHtml += '<a class="w-100 d-block" href="'+ value.url +'"><i class="fa-solid fa-arrow-right-from-bracket me-2 main-color" title="'+ value.name +'"></i>'+ value.name +'</a>';
                });
                $('#active_consultation_info #consultations').html(consultationsHtml)
                $('#active_consultation_info').removeClass('d-none');
            }

            if(!foundPc){
                $('#institutions_section select option').each(function (){
                    let laws = $(this).data('laws');
                    if(selectedLaw > 0 && (typeof laws == 'undefined' || laws.indexOf(selectedLaw) != -1)) {
                        $(this).attr('disabled', false);
                    } else{
                        $(this).attr('disabled', true);
                    }
                });
            }
        });

        $('#institutions_section select').on('change', function (){
            let lValues = $('#institutions_section select').val();
            if(lValues.indexOf('0') != -1 && lValues.length > 1){
                $('#institutions_section select').val('0');
                $('#institutions_section select').trigger('change');
            }
        });
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

    function subscribeWithoutName(btn){
        let channel = $(btn).data('channel');
        let model = $("#subscribe_model").val();
        let model_id = typeof $("#subscribe_model_id") != 'undefined' ? $("#subscribe_model_id").val() : null;
        let model_filter = typeof btn.data('filter') != 'undefined' ? JSON.stringify(btn.data('filter')) : null;
        let route_name = $("#subscribe_route_name").val();
        let text = $("#unsubscribe_text").val();

        $.ajax({
            type: 'GET',
            url: "/subscribe",
            data: {
                channel: channel,
                model: model,
                model_id: model_id,
                model_filter: model_filter,
                route_name: route_name,
                is_subscribed: SUBSCRIBED
            },
            success: function (res) {
                if (res.success) {
                    $(btn).find('span').html(text);
                    $(btn).removeClass('subscribe').addClass('unsubscribe');
                    toastr.success(res.message);
                } else {
                    showModalAlert(res.message);
                }
            },
            error: function () {
                toastr.error('Възникна грешка, моля опитайте отново по-късно');
            }
        });
    }
    // Subscribe
    $(document).on('click', '.edit_subscribe', function (e) {
        e.preventDefault();
        let btn = $(this);
        let titleTxt = GlobalLang == 'bg' ? 'Редакция на абонамент' : 'Edit subscription';
        let cancelBtnTxt = GlobalLang == 'bg' ? 'Откажи' : 'Cancel';
        let labelTxt = GlobalLang == 'bg' ? 'Заглавие' : 'Title';
        let ContinueTxt = GlobalLang == 'bg' ? 'Продължи' : 'Continue';

        let editModal = new MyModal({
            title: titleTxt,
            destroyListener: true,
            body: '<input type="hidden" id="si" value="'+ btn.data('objid') +'"><label>'+ labelTxt +':<span class="required">*</span></label><input class="form-control form-control-sm" type="text" id="sn" value="'+ btn.data('objname') +'"><p class="text-danger w-100 d-block" id="serror"></p>',
            footer: '<button type="button" id="store-subscription" class="btn btn-sm btn-success ms-3">' + ContinueTxt + '</button>' +
                '<button class="btn btn-sm btn-danger closeModal ms-3" data-dismiss="modal" aria-label="' + cancelBtnTxt + '">' + cancelBtnTxt + '</button>',
        });

        $(document).on('click', '#store-subscription', function (){
            $('#serror').html('');
            if($("#sn").val() == ''){
                $('#serror').html(GlobalLang == 'bg' ? 'Въведете заглавие' : 'Enter title');
            } else{
                $('#subscription_id').val($("#si").val());
                $('#subscription_name').val($("#sn").val());
                console.log($("#si").val(), $("#sn").val());
                console.log($("#subscription_id").val(), $("#subscription_name").val());
                $('#edit-subscription-name').submit();
            }
        });
    });

    $(document).on('click', 'button.subscribe', function (e) {
        e.preventDefault();

        if(typeof $('#subscribe_model_id') != 'undefined' && parseInt($('#subscribe_model_id').val()) > 0){
            subscribeWithoutName($(this));
        } else{
            let btn = $(this);
            let channel = $(btn).data('channel');
            let model = $("#subscribe_model").val();
            let model_id = typeof $("#subscribe_model_id") != 'undefined' ? $("#subscribe_model_id").val() : null;
            let model_filter = typeof btn.data('filter') != 'undefined' ? JSON.stringify(btn.data('filter')) : null;
            let route_name = $("#subscribe_route_name").val();
            let cancelBtnTxt = GlobalLang == 'bg' ? 'Откажи' : 'Cancel';
            let ContinueTxt = GlobalLang == 'bg' ? 'Продължи' : 'Continue';


            let subscribeModal = new MyModal({
                title: btn.data('subscribetitle'),
                destroyListener: true,
                bodyLoadUrl: btn.data('url') + '?channel=' + channel + '&model=' + model + '&model_id=' + model_id + '&model_filter=' + model_filter + '&route_name=' + route_name,
                footer: '<button type="button" id="confirm-subscription" class="btn btn-sm btn-success ms-3">' + ContinueTxt + '</button>' +
                    '<button class="btn btn-sm btn-danger closeModal ms-3" data-dismiss="modal" aria-label="' + cancelBtnTxt + '">' + cancelBtnTxt + '</button>',
            });

            $(document).on('click', '#confirm-subscription', function (){
                let channel = $('#'+ subscribeModal.id +' #channel').val();
                let model = $('#'+ subscribeModal.id +' #model').val();
                let model_id = $('#'+ subscribeModal.id +' #model_id').val() == 'undefined' ? null : $('#'+ subscribeModal.id +' #model_id').val();
                let model_filter = $('#'+ subscribeModal.id +' #model_filter').val();
                let route_name = $('#'+ subscribeModal.id +' #route_name').val();
                let subscribe_title = $('#'+ subscribeModal.id +' #subscribe_name').val();
                let text = $("#unsubscribe_text").val();
                $.ajax({
                    type: 'GET',
                    url: "/subscribe",
                    data: {
                        channel: channel,
                        model: model,
                        model_id: model_id,
                        model_filter: model_filter,
                        route_name: route_name,
                        is_subscribed: SUBSCRIBED,
                        subscribe_title: subscribe_title
                    },
                    success: function (res) {
                        if (res.success) {
                            subscribeModal.modalObj.hide();
                            $(btn).find('span').html(text);
                            $(btn).removeClass('subscribe').addClass('unsubscribe');
                            toastr.success(res.message);
                        } else {
                            subscribeModal.modalObj.hide();
                            showModalAlert(res.message);
                        }
                    },
                    error: function () {
                        subscribeModal.modalObj.hide();
                        toastr.error('Възникна грешка, моля опитайте отново по-късно');
                    }
                });
            });
        }
    });

    // $(document).on('click', 'button.subscribe', function (e) {
    //     e.preventDefault();
    //     let btn = $(this);
    //     let channel = $(btn).data('channel');
    //     let model = $("#subscribe_model").val();
    //     let model_id = typeof $("#subscribe_model_id") != 'undefined' ? $("#subscribe_model_id").val() : null;
    //     let model_filter = typeof btn.data('filter') != 'undefined' ? btn.data('filter') : null;
    //     let route_name = $("#subscribe_route_name").val();
    //     let text = $("#unsubscribe_text").val();
    //
    //     $.ajax({
    //         type: 'GET',
    //         url: "/subscribe",
    //         data: {
    //             channel: channel,
    //             model: model,
    //             model_id: model_id,
    //             model_filter: model_filter,
    //             route_name: route_name,
    //             is_subscribed: SUBSCRIBED
    //         },
    //         success: function (res) {
    //             if (res.success) {
    //                 HideLoadingSpinner();
    //                 $(btn).find('span').html(text);
    //                 $(btn).removeClass('subscribe').addClass('unsubscribe');
    //                 toastr.success(res.message);
    //             } else {
    //                 showModalAlert(res.message);
    //             }
    //         },
    //         error: function () {
    //             toastr.error('Възникна грешка, моля опитайте отново по-късно');
    //         }
    //     });
    // });

    // Unsubscribe
    $(document).on('click', 'button.unsubscribe', function (e) {
        e.preventDefault();
        let btn = $(this);
        let channel = $(btn).data('channel');
        let model = $("#subscribe_model").val();
        let model_id = typeof $("#subscribe_model_id") != 'undefined' ? $("#subscribe_model_id").val() : null;
        let model_filter = typeof btn.data('filter') != 'undefined' ? JSON.stringify(btn.data('filter')) : null;
        let route_name = $("#subscribe_route_name").val();
        let text = $("#subscribe_text").val();

        $.ajax({
            type: 'GET',
            url: "/subscribe",
            data: {
                channel: channel,
                model: model,
                model_id: model_id,
                model_filter: model_filter,
                route_name: route_name,
                is_subscribed: UNSUBSCRIBED
            },
            success: function (res) {
                if (res.success) {
                    HideLoadingSpinner();
                    $(btn).find('span').html(text);
                    $(btn).removeClass('unsubscribe').addClass('subscribe');
                    toastr.success(res.message);
                } else {
                    showModalAlert(res.message);
                }
            },
            error: function () {
                toastr.error('Възникна грешка, моля опитайте отново по-късно');
            }
        });
    });

    // Ajax search pagination
    $(document).on('click', '#ajax-pagination .pagination li', function (e) {
        if ($(this).hasClass('disabled')) {
            return;
        }
        ShowLoadingSpinner();
        e.preventDefault();

        let anchor = $(this).find('a');
        let page = $(anchor).html();
        let rel = $(anchor).attr('rel');
        if (!isEmpty(rel)) {
            page = (rel == "prev")
                ? parseInt($("#search-form .current_page").val()) - 1
                : parseInt($("#search-form .current_page").val()) + 1
        }
        $("#search-form .current_page").val(page);
        $("#searchBtn").trigger('click');
    });

    $(document).on('click', '#search-form .sort_search', function (e) {
        ShowLoadingSpinner();
        e.preventDefault();

        let sort = ($(".sort").val() == "ASC") ? "DESC" : "ASC";
        $(".sort").val(sort);
        $(".order_by").attr('disabled', true);
        $(this).find('.order_by').attr('disabled', false);
        $("#searchBtn").trigger('click');
    });

    $(document).on('click', '.filter_link', function (e) {
        ShowLoadingSpinner();
        e.preventDefault();

        $(".current_page").val(1);
        let filter_id = $(this).data('id');
        let filter = $(this).data('filter');
        let filter_value = $(this).html();
        let element = document.getElementById(filter);
        if (element.tagName == 'SELECT') {
            $("#"+filter+" option[value='"+filter_id+"']").prop('selected', true).change();
        } else {
            $("#"+filter).val($.trim(filter_value));
        }
        $("#searchBtn").trigger('click');
    });

    $(document).on('change', '#results-per-page', function (e) {
        $(".current_page").val(1);
        $("#searchBtn").trigger('click');
    });

    $("#search-form .search-btn").click(function (e) {
        ShowLoadingSpinner();
        e.preventDefault();

        if ($(this).hasClass('clear')) {
            clearSearchForm();
        }
        let model_type = $("#model_type").val();
        $.ajax({
            type: 'GET',
            url: $("#search-form").attr('action'),
            data: $("#search-form").serialize(),
            success: function (res) {
                $("#"+model_type+"-results").html(res);
                HideLoadingSpinner();
                $([document.documentElement, document.body]).animate({
                    scrollTop: $("#search-form").offset().top
                }, 20);
            },
            error: function () {
                // $periodUl.find('li').remove();
            }
        });
    })

    // Home page ajax search
    $(".ajax_search_form .search-btn").click(function (e) {
        ShowLoadingSpinner();
        e.preventDefault();

        let id = $(this).data('id');
        $form = $("#"+id+"_form");

        $.ajax({
            type: 'GET',
            url: $form.attr('action'),
            data: $form.serialize(),
            success: function (res) {
                $("#"+id+"_results").html(res);
                $("#"+id+"_total").html($("#"+id+"-total-data").data('total'));
                HideLoadingSpinner();
                $([document.documentElement, document.body]).animate({
                    scrollTop: $form.offset().top
                }, 20);
            },
            error: function () {
                // $periodUl.find('li').remove();
            }
        });
    })

    // Home page ajax search pagination
    $(document).on('click', '.ajax_pagination .pagination li', function (e) {
        if ($(this).hasClass('disabled')) {
            return;
        }
        ShowLoadingSpinner();
        e.preventDefault();

        $p_div = $(this).closest('.ajax_pagination');
        let id = $p_div.data('id');
        $form = $("#"+id+"_form");

        let anchor = $(this).find('a');
        let page = $(anchor).html();
        let rel = $(anchor).attr('rel');
        $c_page = $form.find('.current_page');
        if (!isEmpty(rel)) {
            page = (rel == "prev")
                ? parseInt($c_page.val()) - 1
                : parseInt($c_page.val()) + 1
        }
        $c_page.val(page);
        $form.find('.search-btn').trigger('click');
    });

});
