var canAjax = true;
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

function WebSocketPrinter(options) {
    var defaults = {
        url: "ws://127.0.0.1:12212/Citizen CL-S621",
        onConnect: function () {
        },
        onDisconnect: function () {
        },
        onUpdate: function () {
        },
    };

    var settings = Object.assign({}, defaults, options);
    var websocket;
    var connected = false;

    var onMessage = function (evt) {
        settings.onUpdate(evt.data);
    };

    var onConnect = function () {
        connected = true;
        settings.onConnect();
    };

    var onDisconnect = function () {
        connected = false;
        settings.onDisconnect();
        reconnect();
    };

    var connect = function () {
        websocket = new WebSocket(settings.url);
        websocket.onopen = onConnect;
        websocket.onclose = onDisconnect;
        websocket.onmessage = onMessage;
    };

    var reconnect = function () {
        connect();
    };

    this.submit = function (data) {
        if (Array.isArray(data)) {
            data.forEach(function (element) {
                websocket.send(JSON.stringify(element));
            });
        } else {
            console.log(JSON.stringify(data));
            websocket.send(JSON.stringify(data));
        }
    };

    this.isConnected = function () {
        return connected;
    };

    connect();
}

function populateModal(data, modal) {
    //console.log(data);return false;

    Object.keys(data).forEach(function (field, index) {
        let fieldName = field + '-plch';

        let $fields = modal.find('.' + fieldName);
        if ($fields && $fields.length) {
            Object.keys($fields).forEach(function (fieldIndex) {

                if (!Number.isInteger(Number.parseInt(fieldIndex))) return;

                let singleField = $fields[fieldIndex];
                let tag = singleField.localName;
                //console.log(singleField, tag, singleField.type, data[field]);return false;
                if (tag == 'select') {
                    if (typeof data[field] === "object") {
                        (data[field]).forEach(function (option) {
                            //console.log(singleField, newOption); return false;
                            $(singleField).find('option[value=' + option.id + ']').prop('selected', true).trigger('change');
                        })
                    } else {
                        $(singleField).find('option[value=' + data[field] + ']').prop('selected', true).trigger('change');
                    }
                } else if (tag == 'input') {
                    if (singleField.type == "checkbox") {
                        $(singleField).prop('checked', data[field]);
                    } else {
                        $(singleField).val(data[field]);
                    }
                } else if (tag == 'textarea') {
                    $(singleField).val(data[field]);
                } else if (tag == 'span' || tag == 'div') {
                    $(singleField).html(data[field]);
                }

                //console.log(fieldIndex, singleField, singleField.localName);
            })

        }
    })
}

function isEmpty(arg) {
    return (
        arg == null || // Check for null or undefined
        arg.length === 0 || // Check for empty String (Bonus check for empty Array)
        (typeof arg === 'object' && Object.keys(arg).length === 0) // Check for empty Object or Array
    );
}

function ToggleBoolean(booleanType, entityId) {
    let form = "#" + booleanType + "_form_" + entityId;
    let model = $(form + " .model").val();
    let status = $(form + " .status").attr('data-status');
    showModalConfirm();
    $.ajax({
        type: 'GET',
        url: '/toggle-boolean',
        data: {entityId: entityId, model: model, booleanType: booleanType, status: status},
        success: function (res) {
            if (status == 1) {
                $(form + " .status").removeClass('bg-red').addClass('bg-green').attr('data-status', 0).html('Да');
            } else {
                $(form + " .status").removeClass('bg-green').addClass('bg-red').attr('data-status', 1).html('Не');
            }
            if (booleanType == "active") {
                $(form).closest('tr').remove();
            }
        },
        error: function () {
            // $periodUl.find('li').remove();
        }
    });
}

function TogglePermission(permission, entityId) {
    let form = "#" + permission + "_form_" + entityId;
    let model = $(form + " .model").val();
    let status = $(form + " .status").attr('data-status');
    //console.log(status);
    $.ajax({
        type: 'GET',
        url: '/toggle-permissions',
        data: {entityId: entityId, model: model, permission: permission, status: status},
        success: function (res) {
            if (status == 1) {
                $(form + " .status").removeClass('bg-red').addClass('bg-green').attr('data-status', 0).html('Да');
            } else {
                $(form + " .status").removeClass('bg-green').addClass('bg-red').attr('data-status', 1).html('Не');
            }
        },
        error: function () {
            // $periodUl.find('li').remove();
        }
    });
}

function ToggleCheckboxes(el, class_name) {
    if ($(el).hasClass('checked')) {
        $(el).removeClass('checked');
        $('.' + class_name).prop('checked', false);
    } else {
        $(el).addClass('checked');
        $('.' + class_name).prop('checked', true);
    }
}

function SelectCheckboxes(action, class_name) {
    if (action == 'uncheck') {
        $('.' + class_name).prop('checked', false);
    } else {
        $('.' + class_name).prop('checked', true);
    }
}

// Handling Cookies
function createCookie(name, value, hours) {
    var expires = "";
    if (hours) {
        var date = new Date();
        date.setTime(date.getTime() + (hours * 60 * 60 * 1000));
        expires = "; expires=" + date.toGMTString();
    }
    document.cookie = name + "=" + value + expires + "; path=/";
}

function formatBytes(bytes, decimals = 2) {
    if (!+bytes) return '0 Bytes'

    const k = 1024
    const dm = decimals < 0 ? 0 : decimals
    const sizes = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB']

    const i = Math.floor(Math.log(bytes) / Math.log(k))

    return `${parseFloat((bytes / Math.pow(k, i)).toFixed(dm))} ${sizes[i]}`
}

function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}

function eraseCookie(name) {
    createCookie(name, "", -3);
}

function showModalAlert(message, title = false) {
    if (title) {
        $("#modal-alert .modal-title").html(title);
    }
    $("#modal-alert .modal-body").html(message);
    $("#modal-alert").modal('show');
}

function showModalConfirm(url, message, title = false) {
    if (title) {
        $("#modal-confirm .modal-title").html(title);
    }
    $("#modal-confirm .modal-body p").html(message);
    $("#modal-confirm form").attr('action', url);
    $("#modal-confirm").modal('show');
}

function ConfirmToggleBoolean(booleanType, entityId, message, title = false) {
    if (title) {
        $("#modal-confirm .modal-title").html(title);
    }
    $("#modal-confirm .modal-body p").html(message);
    $("#modal-confirm button.btn-success").attr('onclick', "ToggleBoolean('" + booleanType + "','" + entityId + "')");
    $("#modal-confirm button.btn-success").attr('data-dismiss', "modal");
    $("#modal-confirm").modal('show');
}

function adminModal(modalTitle, modalBody) {
    let adminModal = $('#adminModal');
    if (typeof modalTitle != 'undefined') {
        adminModal.find('#modal-title').html(modalTitle);
    }
    if (typeof modalBody != 'undefined') {
        adminModal.find('#modal-body').html(modalBody);
    }
    adminModal.modal('show');
}

$.fn.appendAttr = function (attrName, suffix) {
    this.attr(attrName, function (i, val) {
        return val + suffix;
    });
    return this;
};

function submitNewSdChild(lForm) {
    if (canAjax) {
        canAjax = false;
        $('#main_error').html('');
        $('.ajax-error').html('');
        let formData = $(lForm).serialize();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: '/admin/ajax/strategic-documents/documents/create',
            data: formData,
            success: function (result) {
                if (typeof result.errors != 'undefined') {
                    let errors = Object.entries(result.errors);
                    for (let i = 0; i < errors.length; i++) {
                        const search_class = '.error_' + errors[i][0];
                        $($(lForm).find(search_class)[0]).html(errors[i][1][0]);
                    }
                    canAjax = true;
                } else if (typeof result.main_error != 'undefined') {
                    $($(lForm).find('#main_error')[0]).html(result.main_error);
                    canAjax = true;
                } else {
                    window.location = result.redirect_url;
                }

            },
            error: function (result) {
                canAjax = true;
            }
        });
    }
}

$(document).on("select2:open", () => {
    document.querySelector(".select2-container--open .select2-search__field").focus()
})

/**
 * This function listen for change event on specific select and control
 * which options in specified other selects (by class) will be visible
 * @param obj
 * obj.mainSelectId (string) - Dom ID for main select
 * obj.mainSelectDataType  (string) Default: int - Data type of the specified main select data attribute (int, string).
 * obj.childSelectClass  (string) - Dom class of children selects
 * obj.childSelectData  (string) - Which data attribute to get for comparing values between main and child selects
 * obj.canReset (bool) Default: false - If you need to show all children option when main select has option like 'Any'
 * obj.anyValue (string|int) - If using option 'canReset', specify main select value when this happen
 * @constructor
 */
var cainSelect = function (obj) {
    // Main select will change other by specific value
    let mainSelect = $('#' + (typeof obj.mainSelectId != 'undefined' ? obj.mainSelectId : ''));
    // We will search for this value in children data
    let valueToType = mainSelect.data(typeof obj.mainSelectDataType != 'undefined' ? obj.mainSelectDataType : 'int');
    let childSelects = $('.' + (typeof obj.childSelectClass != 'undefined' ? obj.childSelectClass : ''));
    let childValueData = typeof obj.childSelectData != 'undefined' ? obj.childSelectData : '';
    let canReset = typeof obj.canReset != 'undefined' ? obj.canReset : false;
    let resetValue = typeof obj.resetValue != 'undefined' ? obj.resetValue : '';

    mainSelect.on('change', function () {
        childSelects.find('option').each(function () {
            let isSelected = $(this).is(':selected');
            let childValue = $(this).data(childValueData);
            let parentValue = mainSelect.val();
            if (valueToType == 'int') {
                childValue = parseInt(childValue);
                parentValue = parseInt(parentValue);
            }
            if (canReset && childValue == resetValue) {
                $(this).prop('disabled', false);
            } else {
                if (childValue == parentValue) {
                    $(this).prop('disabled', false);
                } else {
                    if (isSelected) {
                        $(this).prop('selected', false);
                    }
                    $(this).prop('disabled', true);
                }
            }
        });
    });
}


// ;(function($, window, document, undefined){
// $("#days").on("change", function(){
//     var date = new Date($("#start_date").val()),
//         days = parseInt($("#days").val(), 10);
//
//     if(!isNaN(date.getTime())){
//         date.setDate(date.getDate() + days);
//
//         $("#end_date").val(date.toInputFormat());
//     } else {
//         alert("Invalid Date");
//     }
// });


//From: http://stackoverflow.com/questions/3066586/get-string-in-yyyymmdd-format-from-js-date-object
// Date.prototype.toInputFormat = function() {
//     var yyyy = this.getFullYear().toString();
//     var mm = (this.getMonth()+1).toString(); // getMonth() is zero-based
//     var dd  = this.getDate().toString();
//     return (dd[1]?dd:"0"+dd[0]) + "." + (mm[1]?mm:"0"+mm[0]) + "." + yyyy;
// };
// })(jQuery, this, document);

//Calculate date
function addSubDays(currentDate, days = 0, addDays = true, returnObj = false) {
    baseDate = currentDate.split(".");
    var newDate = new Date(baseDate[2], baseDate[1] - 1, baseDate[0]);
    if (addDays) {
        newDate.setDate(newDate.getDate() + days);
    } else {
        newDate.setDate(newDate.getDate() - days);
    }

    if (returnObj) {
        return newDate;
    } else {
        return newDate.getDate() + '.' + (newDate.getMonth() + 1) + '.' + newDate.getFullYear();
    }

}

function select2OptionFilter(option) {
    if (typeof option.element != 'undefined' && option.element.className === 'd-none') {
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

//===============================
// START MyModal
// Create modal and show it with option for load body from url or pass direct content
// available params:
// title, body (content), destroyListener (boolean : do destroy modal on close), bodyLoadUrl (url for loading body content)
//===============================

/**
 *
 * @param obj
 * @returns {*}
 * @constructor
 */
function MyModal(obj) {
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

//==========================
// End MyModal
//==========================

//===============================
// START Select2 Ajax Autoload
// available params:
//
//===============================
function MyS2Ajax(selectDom, selectPlaceholder, selectUrl) {
    selectDom.select2({
        allowClear: true,
        templateResult: select2OptionFilter,
        language: "bg",
        placeholder: selectPlaceholder,
        escapeMarkup: function (text) {
            return text;
        },
        ajax: {
            url: selectUrl,
            data: function (params) {
                if ($(this).data('types2ajax') == 'pris_doc') {
                    let legalActTypeFilterId = $("#legal_act_type_filter_new").length ? "#legal_act_type_filter_new" : "#legal_act_type_filter";
                    var query = {
                        actType: typeof $(this).data('legalacttype') != 'undefined'
                            ? parseInt($(this).data('legalacttype'))
                            : (typeof $(legalActTypeFilterId) != 'undefined' ? $(legalActTypeFilterId).val() : null),
                        consultationId: typeof $(this).data('consultationid') != 'undefined'
                            ? parseInt($(this).data('consultationid'))
                            : (typeof $('#public_consultation_id') != 'undefined' ? $('#public_consultation_id').val() : null),
                        search: params.term
                    }
                } else if ($(this).data('types2ajax') == 'lp_record') {
                    var query = {
                        programId: $('#legislative_program_id').val(),
                        search: params.term
                    }
                } else if ($(this).data('types2ajax') == 'lp_record_pc') {
                    var query = {
                        institution: typeof $(this).data('institution') != 'undefined' ? $(this).data('institution') : null,
                        programId: $('#legislative_program_id select').val(),
                        search: params.term
                    }
                } else if ($(this).data('types2ajax') == 'op_record') {
                    var query = {
                        programId: $('#operational_program_id').val(),
                        search: params.term
                    }
                } else if ($(this).data('types2ajax') == 'op_record_pc') {
                    var query = {
                        institution: typeof $(this).data('institution') != 'undefined' ? $(this).data('institution') : null,
                        programId: $('#operational_program_id select').val(),
                        search: params.term
                    }
                } else if ($(this).data('types2ajax') == 'pc') {
                    var query = {
                        connections: typeof $(this).data('connections') != 'undefined' ? $(this).data('connections') : null,
                        pris: $('#pris_act_id') != 'undefined' ? $('#pris_act_id').val() : 0,
                        exclude: $(this).data('current'),
                        search: params.term
                    }
                } else if ($(this).data('types2ajax') == 'adv_board') {
                    var query = {
                        byModerator: typeof $(this).data('bymoderator') != 'undefined' ? $(this).data('bymoderator') : false,
                        search: params.term
                    }
                } else if ($(this).data('types2ajax') == 'sd_parent_documents') {
                    var query = {
                        level: $('#strategic_document_level_id') != 'undefined' ? $('#strategic_document_level_id').val() : 0,
                        policy: $('#policy_area_id') != 'undefined' ? $('#policy_area_id').val() : null,
                        areaPolicy: $('#ekatte_area_id') != 'undefined' ? $('#ekatte_area_id').val() : null,
                        municipalityPolicy: $('#ekatte_municipality_id') != 'undefined' ? $('#ekatte_municipality_id').val() : null,
                        document: typeof $(this).data('documentid') != 'undefined' ? $(this).data('documentid') : 0,
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
                    results: $.map(data, function (item) {
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

//==========================
// End Select2 Ajax Autoload
//==========================

// ===========================
// START Custom File validation
//==========================
function myFileSize(lField) {
    let fieldErrorEl = $($(lField).closest('.sd-form-files')[0]).find('.error_' + lField.name)[0];
    $(fieldErrorEl).html('');
    $(fieldErrorEl).removeClass('is-invalid');

    if (lField.files.length) {
        if (lField.files.length && lField.files[0].size >= MaxUploadFileSize) {
            $(fieldErrorEl).html("Максималният размер на файла трябва да е по-малък от " + MaxUploadFileSize + " MB");
            $(lField).addClass('is-invalid');
            return false;
        } else {
            $(fieldErrorEl).html('');
            $(lField).removeClass('is-invalid');
        }
    }
    return true;
}

function myExtension(lField, allowed_file_extensions) {
    let fieldErrorEl = $($(lField).closest('.sd-form-files')[0]).find('.error_' + lField.name)[0];
    $(fieldErrorEl).html('');
    $(lField).removeClass('is-invalid');

    if (lField.files.length) {
        let explode = allowed_file_extensions.split(",");

        let check = false;
        if (explode.length > 0) {
            $.each(explode, function (index, value) {
                if ((lField.files[0].name).match(new RegExp(".(" + value + ")$", "i"))) {
                    check = true;
                }
            });
        }

        if (!check) {
            $(fieldErrorEl).html("Разрешените файлови формати са " + allowed_file_extensions);
            $(lField).addClass('is-invalid');
        }
        return check;
    }

    return true;
}

function removeCheckboxFromForm(form, checkboxId) {
    if (!form) {
        console.error(`Form not found.`);
        return;
    }

    const checkbox = form.querySelector(`input[name=${checkboxId}]`);
    if (checkbox) {
        checkbox.closest('.col-md-6').remove();
    }
}

/**
 * Attaches a checkbox to a specified form and handles its value on form submission.
 *
 * @param {string} form - The ID of the form to attach the checkbox to.
 * @param {string} checkboxId - The ID to assign to the new checkbox.
 * @param {string} checkboxLabel - The label text for the checkbox.
 * @param {boolean} [defaultChecked=false] - Whether the checkbox should be checked by default.
 */
function attachCheckboxToForm(form, checkboxId, checkboxLabel, defaultChecked = false) {
    if (!form) {
        console.error(`Form not found.`);
        return;
    }

    const row = document.createElement('div');
    row.className = 'row';

    const column = document.createElement('div');
    column.className = 'col-md-6 col-12';

    // Create a div wrapper for the checkbox and label
    const wrapper = document.createElement('div');
    wrapper.className = 'form-check pl-4';

    // Create the checkbox input
    const checkbox = document.createElement('input');
    checkbox.type = 'checkbox';
    checkbox.id = checkboxId;
    checkbox.name = checkboxId;
    checkbox.className = 'form-check-input';
    checkbox.checked = defaultChecked;
    checkbox.value = 1;

    // Create the label for the checkbox
    const label = document.createElement('label');
    label.htmlFor = checkboxId;
    label.className = 'form-check-label';
    label.textContent = checkboxLabel;

    // Append the checkbox and label to the wrapper
    wrapper.appendChild(checkbox);
    wrapper.appendChild(label);
    column.appendChild(wrapper);
    row.appendChild(column);

    // Append the wrapper to the form
    form.appendChild(row);
}

function fieldRequired(lField, isRequired) {
    let fieldErrorEl = $($(lField).closest('.sd-form-files')[0]).find('.error_' + lField.name)[0];
    $(fieldErrorEl).html('');
    $(lField).removeClass('is-invalid');

    if (isRequired && !$(lField).val().length) {
        $(fieldErrorEl).html("Полето е задължително");
        $(lField).addClass('is-invalid');
        return false;
    }
    return true;
}

// ===========================
// START Custom File validation
//==========================

$(document).ready(function (e) {

    let hash = location.hash.replace(/^#/, '');  // ^ means starting, meaning only match the first hash
    if (hash) {
        $('#' + hash + '-tab').trigger('click');
    }

    $('.nav-tabs a').on('shown.bs.tab', function (e) {
        window.location.hash = e.target.hash;
    });

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

    $.datepicker.setDefaults($.datepicker.regional[typeof GlobalLang != 'undefined' ? GlobalLang : '']);

    $('.table-bordered tbody tr:even').not($('.dataTable tbody tr, .table-with-toggle-content tr')).addClass('odd');

    if ($('.dataTable').length) {
        $('.dataTable').DataTable({
            "paging": false,
            "order": [1, 'asc'],
            "language": {
                url: '/js/dataTables.bulgarian.json'
            }
        });
    }

    $('.toggle').on('click', function (e) {
        e.preventDefault();
        let class_id = $(this).data('id');
        let toggle_class = $(this).data('class');
        let icon = $(this).find('i.fas');
        $(this).closest('tr').addClass('opened');
        if ($(icon).hasClass('fa-plus-circle')) {
            $(icon).removeClass('fa-plus-circle').addClass('fa-minus-circle');
        } else {
            $(this).closest('tr').removeClass('opened');
            $(icon).removeClass('fa-minus-circle').addClass('fa-plus-circle');
        }
        $('.' + toggle_class + '_' + class_id).toggle();
    });

    $('.show_confirm').on('click', function () {
        showModalConfirm($(this).data('url'), $(this).data('message'))
    });

    if ($('#must_change_password').length) {
        $('#must_change_password').click(function () {
            if ($(this).is(':checked')) {
                $(".passwords").attr('readonly', true);
            } else {
                $(".passwords").attr('readonly', false);
            }
        })
    }

    if ($('.form-group.disabled').length) {
        $('.form-group.disabled').each(function () {
            $(this).find('.btn-danger').remove();
            $(this).find('select, input').attr('disabled', true);
        })
    }

    if ($('input[type="text"]').length) {
        $('input[type="text"]').attr('autocomplete', 'off');
    }

    // if($('.summernote').length) {
    //     $('.summernote').summernote({
    //         height: 80,
    //         fontNames: ['Arial', 'Arial Black', 'Comic Sans MS', 'Courier New'],
    //         styleTags: ['p', 'h1', 'h2', 'h3', 'h4', 'h5'],
    //         toolbar: [
    //             // [groupName, [list of button]]
    //             ['style', ['style','bold', 'italic', 'underline', 'clear']],
    //             ['font', ['superscript', 'subscript']],
    //             ['fontsize', ['fontsize']],
    //             ['color', ['color']],
    //             ['para', ['ul', 'ol', 'paragraph']],
    //             ['view', ['fullscreen']],
    //             ['table', ['table']],
    //             ['insert', ['hr']]
    //         ]
    //     });
    // }

    if ($('.summernote').length) {
        $('.summernote').summernote({
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['para', ['ul', 'ol']], //,'paragraph'
                ['view', ['fullscreen']],
                ['insert', ['link']],
                //['fontsize', ['fontsize']],
            ],
            dialogsInBody: true,
            lang: typeof GlobalLang != 'undefined' ? GlobalLang + '-' + GlobalLang.toUpperCase() : 'en-US',
            callbacks: {
                onPaste: (e) => {
                    var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');

                    e.preventDefault();

                    // Firefox fix
                    setTimeout(function () {
                        document.execCommand('insertText', false, bufferText);
                    }, 10);

                }
            }
        });
    }

    if ($('.summernote-disabled').length) {
        $('.summernote-disabled').summernote({
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['para', ['ul', 'ol']], //,'paragraph'
                ['view', ['fullscreen']],
                ['insert', ['link']],
                //['fontsize', ['fontsize']],
            ],
            dialogsInBody: true,
            lang: typeof GlobalLang != 'undefined' ? GlobalLang + '-' + GlobalLang.toUpperCase() : 'en-US',
            disable: true
        });
    }

    $('.navbar .sidebar-toggle').bind('click', function () {
        let body = $("body");
        if (body.hasClass("sidebar-collapse")) {
            eraseCookie('nav');
        } else {
            createCookie('nav', 'sidebar-collapse', 2);
        }
    });

    if ($('.js-toggle-delete-resource-modal').length) {
        $('.js-toggle-delete-resource-modal').on('click', function (e) {
            e.preventDefault();

            // If delete url specify in del.btn use that url
            if ($(this).data('resource-delete-url')) {
                $($(this).data('target')).find('form').attr('action', $(this).data('resource-delete-url'));
            }

            if ($(this).data('title_singular')) {
                $('#modal_title_singular').html($(this).data('title_singular'));
                $('#modal_btn_title_singular').html($(this).data('title_singular'));
            }

            $($(this).data('target')).find('span.resource-name').html($(this).data('resource-name'));
            $($(this).data('target')).find('#resource_id').attr('value', $(this).data('resource-id'));

            $($(this).data('target')).modal('toggle');
        })
    }

    if ($('.js-toggle-restore-resource-modal').length) {
        $('.js-toggle-restore-resource-modal').on('click', function (e) {
            e.preventDefault();

            // If delete url specify in del.btn use that url
            if ($(this).data('resource-restore-url')) {
                $($(this).data('target')).find('form').attr('action', $(this).data('resource-restore-url'));
            }

            $($(this).data('target')).find('span.resource-name').html($(this).data('resource-name'));
            $($(this).data('target')).find('#resource_id').attr('value', $(this).data('resource-id'));

            $($(this).data('target')).modal('toggle');
        })
    }

    //=================================
    //Select2
    //===============================

    // function select2OptionFilter(option) {
    //     if (typeof option.element != 'undefined' && option.element.className === 'd-none' ) {
    //         return false
    //     }
    //     return option.text;
    // }
    //
    // var select2Options = {
    //     allowClear: true,
    //     placeholder: true,
    //     templateResult: select2OptionFilter,
    //     language: "bg"
    // };

    if ($('.select2').length) {
        $('.select2').select2(select2Options);
    }

    if ($('.select2-no-clear').length) {
        $('.select2-no-clear').select2({
            allowClear: false,
            placeholder: true,
            templateResult: select2OptionFilter,
            language: "bg"
        });
    }

    if ($('.select2-autocomplete-ajax').length) {
        $('.select2-autocomplete-ajax').each(function () {
            MyS2Ajax($(this), $(this).data('placeholders2'), $(this).data('urls2'));
        });
    }


    //=================================
    //Datepicker
    //===============================
    if ($('.datepicker').length) {
        $('.datepicker').datepicker({
            language: typeof GlobalLang != 'undefined' ? GlobalLang : 'en',
            format: 'dd.mm.yyyy',
            todayHighlight: true,
            orientation: "auto",
            autoclose: true,
            weekStart: 1
        });
    }

    if ($('.datepicker-from-this-year').length) {
        $('.datepicker-from-this-year').datepicker({
            language: typeof GlobalLang != 'undefined' ? GlobalLang : 'en',
            format: 'yyyy',
            viewMode: "years",
            minViewMode: "years",
            yearRange: new Date().getFullYear() + ':' + new Date().getFullYear(),
            orientation: "bottom left",
            startDate: new Date(new Date().getFullYear(), 0, 1),
            autoclose: true,
            onClose: function (dateText, inst) {
                $(this).datepicker('setDate', new Date(inst.selectedYear, 1, 1));
            }
        });
    }

    if ($('.datepicker-today').length) {
        $('.datepicker-today').datepicker({
            language: typeof GlobalLang != 'undefined' ? GlobalLang : 'en',
            format: 'dd.mm.yyyy',
            todayHighlight: true,
            orientation: "bottom left",
            autoclose: true,
            weekStart: 1,
            //TODO fix me if set next date is not recognized on initialization and filed goes empty on focus out
            //: new Date()
        });
    }

    if ($('.datepicker-tomorrow').length) {
        $('.datepicker-tomorrow').datepicker({
            language: typeof GlobalLang != 'undefined' ? GlobalLang : 'en',
            format: 'dd.mm.yyyy',
            todayHighlight: false,
            orientation: "bottom left",
            autoclose: true,
            weekStart: 1,
            //TODO fix me start form tomorrow
            startDate: '+1d'
        });
    }

    if ($('.datepicker-month').length) {
        $('.datepicker-month').datepicker({
            language: typeof GlobalLang != 'undefined' ? GlobalLang : 'en',
            format: 'mm.yyyy',
            viewMode: "months",
            minViewMode: "months",
            maxViewMode: "years",
            changeMonth: true,
            changeYear: true,
            orientation: "bottom left",
            autoclose: true,
            weekStart: 1,
            onClose: function (dateText, inst) {
                $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
            }
        }).on('show', function (e) {
            if (typeof this.firstShow == 'undefined') {
                let startDateStr = $('#' + e.target.id).data('start');
                if (typeof startDateStr != 'undefined') {
                    $(this).datepicker('setStartDate', startDateStr.toString());
                }
                let endDateStr = $('#' + e.target.id).data('end');
                if (typeof endDateStr != 'undefined') {
                    $(this).datepicker('setEndDate', endDateStr.toString());
                }
                this.firstShow = true;
            }
        });
    }

    if ($('.datepicker-year').length) {
        $('.datepicker-year').datepicker({
            language: typeof GlobalLang != 'undefined' ? GlobalLang : 'en',
            format: 'yyyy',
            viewMode: "years",
            minViewMode: "years",
            changeMonth: false,
            changeYear: true,
            orientation: "bottom left",
            autoclose: true,
            onClose: function (dateText, inst) {
                $(this).datepicker('setDate', new Date(inst.selectedYear, 1, 1));
            }
        });
    }

    if ($('.timepicker').length) {
        $('.timepicker').timepicker({
            showSeconds: false,
            showMeridian: false,
            icons: {
                up: 'fas fa-caret-up',
                down: 'fas fa-caret-down'
            }
        });
    }

    let start_date = (isEmpty($(".start_date").val())) ? moment().subtract(6, 'days').format('YYYY-MM-DD') : $(".start_date").val();
    let end_date = (isEmpty($(".end_date").val())) ? moment().format('YYYY-MM-DD') : $(".end_date").val();
    $(".start_date").val(start_date);
    $(".end_date").val(end_date);
    //console.log(start_date, end_date);

    $('.date_range').daterangepicker({
        ranges: {
            'Днес': [moment(), moment()],
            'Вчера': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Последните 7 дена': [moment().subtract(6, 'days'), moment()],
            'Последните 30 дена': [moment().subtract(29, 'days'), moment()],
            'Този месец': [moment().startOf('month'), moment().endOf('month')],
            'Миналият месец': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        startDate: start_date,
        endDate: end_date,
        alwaysShowCalendars: true,
        locale: {
            customRangeLabel: 'Персонализиран',
            applyLabel: 'Запази',
            cancelLabel: 'Откажи',
            format: 'dd.mm.yyyy'
        }
    }, function (start, end) {
        $(".start_date").val(start.format('dd.mm.yyyy'));
        $(".end_date").val(end.format('dd.mm.yyyy'));
    });

    if ($('.simple-datatable').length) {
        $('.simple-datatable').DataTable({
            paging: false,
            // searching: false
        });
    }

    // allow only latin letters, disable paste so no possible cyrillic letters
    $(".latin_letters").on("keypress", function (event) {
        var englishAlphabetAndWhiteSpace = /^[-@./#&+\w\s\\]*$/;
        var key = String.fromCharCode(event.which);
        if (event.keyCode == 8 || event.keyCode == 37 || event.keyCode == 39 || englishAlphabetAndWhiteSpace.test(key)) {
            return true;
        }
        return false;
    });
    $('.latin_letters').on("paste", function (e) {
        e.preventDefault();
    });

    $('[data-toggle="tooltip"]').tooltip();

    // $('[data-toggle="tooltip"]').tooltip();

    $(document).keyup(function (e) {
        if (e.key === "Escape") {
            $('.modal').modal('hide');
        }
    });

    $('.js-toggle-role-permission').change(function () {
        let token = $('[name="_token"]').val(),
            data = {
                'role': $(this).data('role'),
                'permission': $(this).data('permission'),
                'has': $(this).prop('checked') ? 1 : 0,
                '_token': token
            },
            url = $(this).data('url');
        $(this).prop('disabled', true);
        $.post(url, data)
            .then(res => {
                if (res.success) {
                    toastr.success('Ролята е променена', 'Правата върху ролята са успешно променени');
                } else if (res.error) {
                    toastr.error('Грешка', 'Възникна грешка, моля опитайте по-късно');
                }
                $(this).prop('disabled', false);
            }).catch(function (err) {
            console.error(err);
            toastr.error('Грешка', 'Възникна грешка, моля опитайте по-късно');
            $(this).prop('disabled', false);
        })
    })

    ClassicEditor
        .create(document.querySelector('.ckeditor'), {
            language: GlobalLang,
        })
        .catch(error => {
            //console.error(error);
        });

    // $('[data-provide="datepicker"]').datepicker({
    //     todayBtn: true,
    //     language: GlobalLang,
    //     format: 'yyyy-mm-dd',
    //     todayHighlight: true,
    //     orientation: "bottom left",
    //     autoclose: true
    // });

    //======================================
    // START PDOI SUBJECTS SELECT FROM MODAL
    // use <select name="subjects[]" id="subjects" class="select2">
    // and button with class 'pick-subject' and data-url attribute to call modal with the tree
    //you can add to url get parameters to set tree as selectable or just view : select=1
    // and if you need more than one subject to be selected use parameter 'multiple=1'
    //======================================
    if ($('.pick-institution').length) {
        $('.pick-institution').on('click', function () {
            let subjectModal = new MyModal({
                title: $(this).data('title'),
                destroyListener: true,
                bodyLoadUrl: $(this).data('url'),
                customClass: 'no-footer'
            });

            $(document).on('click', '#select-institution', function () {
                let subjectsFormSelect = $('#' + $(this).data('dom'));
                let checked = $('#' + subjectModal.id + ' input[name="institutions-item"]:checked');
                if (checked.length) {
                    if (checked.length === 1) {
                        subjectsFormSelect.val(checked.val());
                    } else if (checked.length > 1) {
                        let subjectValues = [];
                        checked.each(function () {
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

    //Approve modal
    if ($('.approveModal').length) {
        $('.approveModal').on('click', function (event) {
            let btn = $(this);
            let approveModal = new MyModal({
                title: btn.data('title'),
                destroyListener: true,
                body: '<p class="m-0">' + btn.data('question') + '</p>',
                footer: '<button class="btn btn-sm btn-danger confirmApproveModal">Да</button>' +
                    '<button class="btn btn-sm btn-danger closeModal ms-3" data-dismiss="modal" aria-label="Не">Не</button>'
            });
            $('#' + approveModal.id).on('click', '.confirmApproveModal', function () {
                $('#approveModalSubmit_' + btn.data('file'))[0].click();
            });
        });
    }

    //Hide/show comment
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

    if ($('.add_sd_document').length) {
        $('.add_sd_document').each(function (index, el) {
            $(el).on('click', function () {
                let url = $(this).data('url');
                let cancelBtnTxt = GlobalLang == 'bg' ? 'Откажи' : 'Cancel';
                let saveBtnTxt = GlobalLang == 'bg' ? 'Добави' : 'Add';
                let titleTxt = GlobalLang == 'bg' ? 'Добавяне на дъщерен документ' : 'Add document';
                new MyModal({
                    title: titleTxt,
                    footer: '<button class="btn btn-sm btn-success ms-3 me-2" type="button" onclick="submitNewSdChild($(\'#new_sd_child_form\'));">' + saveBtnTxt + '</button><button class="btn btn-sm btn-danger closeModal" data-dismiss="modal" aria-label="' + cancelBtnTxt + '">' + cancelBtnTxt + '</button>',
                    bodyLoadUrl: url,
                    customClass: 'w-70p',
                    destroyListener: true
                });
            });
        });
    }

    if ($('.edit-sd-document').length) {
        $('.edit-sd-document').on('click', function () {
            if (canAjax) {
                canAjax = false;
                let lForm = $(this).closest('form');
                let lUrl = $(lForm).data('url');
                let lMainError = $(lForm).find('.main-error')[0];
                let lMainSuccess = $(lForm).find('.main-success')[0];
                $('.main-success').html('');
                $('.main-error').html('');
                $('.ajax-error').html('');

                let formData = $(lForm).serialize();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    url: lUrl,
                    data: formData,
                    success: function (result) {
                        if (typeof result.errors != 'undefined') {
                            let errors = Object.entries(result.errors);
                            for (let i = 0; i < errors.length; i++) {
                                const search_class = '.error_' + errors[i][0];
                                let errDiv = $(lForm).find(search_class);
                                $(errDiv[0]).html(errors[i][1][0]);
                            }
                            canAjax = true;
                        } else if (typeof result.main_error != 'undefined') {
                            $(lMainError).html(result.main_error);
                            canAjax = true;
                        } else {
                            $(lMainSuccess).html(result.success_message);
                            canAjax = true;
                        }

                    },
                    error: function (result) {
                        canAjax = true;
                    }
                });
            }
        });
    }

    if ($('.included-file-form-submit').length) {
        $('.included-file-form').on('click', function () {

        });
    }

    function validateFileForm(lForm, submit = true) {
        let bgDescField = $(lForm).find('input[name="description_bg"]')[0];
        let bgDesc = $(bgDescField).val().length;
        let enDescField = $(lForm).find('input[name="description_en"]')[0];
        let enDesc = $(enDescField).val().length;
        let bgFileField = $(lForm).find('input[name="file_bg"]')[0];
        let bgFile = $(bgFileField).val().length;
        let enFileField = $(lForm).find('input[name="file_en"]')[0];
        let enFile = $(enFileField).val().length;

        let bgRequired = bgFile || (!(enDesc > 0) && !(bgDesc > 0) && !enFile);
        let bgFileRequired = bgDesc || (!(enDesc > 0) && !bgFile && !enFile);

        let enRequired = enFile;
        let enFileRequired = enDesc > 0;

        let allowed_file_extensions = $(lForm).data('extension');
        if (!fieldRequired(bgDescField, bgRequired)) {
            return false;
        } else if (!fieldRequired(enDescField, enRequired)) {
            return false;
        } else if (!fieldRequired(bgFileField, bgFileRequired)) {
            return false;
        } else if (!fieldRequired(enFileField, enFileRequired)) {
            return false;
        } else if (!myExtension(bgFileField, allowed_file_extensions)) {
            return false;
        } else if (!myExtension(enFileField, allowed_file_extensions)) {
            return false;
        } else if (!myFileSize(bgFileField)) {
            return false;
        } else if (!myFileSize(enFileField)) {
            return false;
        }

        if (submit) {
            $(lForm).submit();
        } else {
            return true;
        }

    }

    if ($('.sd-submit-files').length) {
        $('.sd-submit-files').on('click', function () {
            let lForm = $(this).closest('form')[0];
            validateFileForm(lForm);
        });
    }

    if ($('.included-file-form-submit').length) {
        $('.included-file-form-submit').on('click', function () {
            let lForm = $(this).closest('div.sd-form-files')[0];

            if (validateFileForm(lForm, false)) {
                var lData = new FormData();
                if ($($(lForm).find("input[name=description_bg]")[0]).val().length) {
                    lData.append('description_bg', $($(lForm).find("input[name=description_bg]")[0]).val());
                }
                if ($($(lForm).find("input[name=description_en]")[0]).val().length) {
                    lData.append('description_en', $($(lForm).find("input[name=description_en]")[0]).val());
                }
                if ($(lForm).find("input[name=file_bg]")[0].files.length) {
                    lData.append('file_bg', $(lForm).find("input[name=file_bg]")[0].files[0]);
                }
                if ($(lForm).find("input[name=file_en]")[0].files.length) {
                    lData.append('file_en', $(lForm).find("input[name=file_en]")[0].files[0]);
                }
                lData.append('formats', $($(lForm).find("input[name=formats]")[0]).val());

                if (canAjax) {
                    canAjax = false;
                    let lUrl = $(lForm).data('url');
                    let lMainError = $(lForm).find('.main-error')[0];
                    let lMainSuccess = $(lForm).find('.main-success')[0];
                    $(lMainError).html('');
                    $(lMainSuccess).html('');
                    $('.ajax-error').html('');

                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'POST',
                        url: lUrl,
                        data: lData,
                        contentType: false,
                        processData: false,
                        success: function (result) {
                            if (typeof result.errors != 'undefined') {
                                let errors = Object.entries(result.errors);
                                for (let i = 0; i < errors.length; i++) {
                                    const search_class = '.error_' + errors[i][0];
                                    let errDiv = $(lForm).find(search_class);
                                    $(errDiv[0]).html(errors[i][1][0]);
                                }
                                canAjax = true;
                            } else if (typeof result.main_error != 'undefined') {
                                $(lMainError).html(result.main_error);
                                canAjax = true;
                            } else {
                                window.location = result.redirect_url;
                            }

                        },
                        error: function (result) {
                            canAjax = true;
                        }
                    });
                }

            }
        });
    }


})

