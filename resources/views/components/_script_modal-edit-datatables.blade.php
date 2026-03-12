<script>
    $(document).ready(function() {
        var baseURL                         = '{{ url("") }}';
        var tableURL                        = '{{ $historyURL }}';
        var postColumnURL                   = '{{ $postColumnURL }}';
        var columns                         = @json($columns);
        var datatableEl                     = $('#dataTableBuilder');
        var delay                           = 1000;

        // popover
        var popSelf                         = '';
        var popId                           = 0;
        var popColumn                       = '';
        var popTitle                        = '';
        var popType                         = '';
        var popSetTimeoutConst;
        
        // history data
        var historyRow                      = '';
        var reasonRow                       = '';

        // modal
        var modalSelf                       = '';
        var modalId                         = 0;
        var modalColumn                     = '';
        var modalTitle                      = '';
        var modalType                       = '';
        var modalIndex                      = '';
        
        var modalEl                         = $('.editModal[data-type="text"]');
        var modalTitleEl                    = '.js-modal-title';
        var modalColumnLabelEl              = '.js-modal-column-label';
        var modalHistoryEl                  = '.js-modal-history';
        var modalReasonEl                   = '.js-modal-reason-val';
        var modalColumnTextEl               = '.js-edit-column-text-val';

        // form data
        var editColumnVal                   = '';
        var editReasonVal                   = '';
        var putData;

        var dropdownHistoryColumn           = '';
        var dropdownHistoryColumnChild      = '';
        var dateStartEl                     = '.js-date_start';
        var dateEndEl                       = '.js-date_end';

        // object finder
        function findByMatchingProperties(set, properties) {
            return set.filter(function (entry) {
                return Object.keys(properties).every(function (key) {
                    return entry[key] === properties[key];
                });
            });
        }
        
        // init mce if type is textarea-mce
        if(findByMatchingProperties(columns, { modalType: 'textarea-mce' }).length > 0) {
            tinymce.init({ 
                selector: '.js-edit-column-textarea-mce-val',
                menubar: false,
                plugins: [
                    'advlist autolink lists charmap print preview textcolor',
                    'searchreplace visualblocks code fullscreen',
                    'table contextmenu paste code help wordcount'
                ],
                toolbar: 'undo redo |  formatselect | bold italic backcolor  | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
                content_css: [
                    '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
                    '//www.tinymce.com/css/codepen.min.css']
            });
        }

        // create row log for modal
        function createRowLog(data, desc, column, modalEl) {
            reasonRow = desc === 'created' ? 'Created' : data.properties.reason;

            switch (modalType) {
                case 'text':
                case 'text-escape':
                case 'textarea-escape':
                case 'textarea':
                case 'number':
                    historyRow = desc === 'created' ? data.properties.attributes[column] : data.properties.old[column];
                    break;
                case 'currency-escape':
                    historyRow = desc === 'created' ? data.properties.attributes[column] : data.properties.old[column];
                    historyRow = 'IDR ' + historyRow.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,")
                    break;
                case 'dropdown':
                case 'chaindropdown':
                    historyRow = desc === 'created' ? data.properties.attributes[dropdownHistoryColumn + dropdownHistoryColumnChild] : data.properties.old[dropdownHistoryColumn + dropdownHistoryColumnChild];
                    break;
                case 'date':
                case 'chaindate':
                    historyRow = desc === 'created' ? data.properties.attributes[column] : data.properties.old[column];
                    historyRow = historyRow === null ? '' : moment(historyRow).format('DD-MM-YYYY')
                    break;
                default:
                    break;
            }

            return modalEl.find(modalHistoryEl).append('\
                    <div class="row history__row">\
                        <div class="col-md-5 history__col">\
                            <div class="history__date">\
                                '+ moment(data.created_at).format('DD-MM-YYYY HH:mm') +'\
                            </div>\
                            <div class="history__causer">\
                                by ' + data.causer.name + '\
                            </div>\
                        </div>\
                        <div class="col-md-7 history__col">\
                            <div class="history__reason">'+ reasonRow +'</div>\
                            <div class="history__edited-data">\
                                <img class="icon" src="{{ asset($public.'icons/history.svg') }}">\
                                '+ historyRow +'\
                            </div>\
                        </div>\
                    </div>');
        }

        // datatable events
        datatableEl.on({
            mouseenter: function() {
                popSelf            = this;
                popId              = $(popSelf).closest('tr').attr('id');
                popColumn          = columns[$(popSelf).index()].column;
                popTitle           = columns[$(popSelf).index()].title;
                popType            = columns[$(popSelf).index()].modalType;
                
                // init popover
                if(popType !== '') {
                    $(popSelf).attr('title', popTitle);
                    $(popSelf).popover({
                        content: '<div class="popover-loading">@lang('common.loading')</div>',
                        trigger: 'hover',
                        placement: 'top',
                        container: 'body',
                        html: true,
                    });
                    $(popSelf).popover('show');

                    // set delay popover
                    popSetTimeoutConst = setTimeout(function() {
                        // call api
                        $.ajax({
                            type: "POST",
                            url: baseURL + tableURL,
                            dataType: "JSON",
                            data: {
                                id: popId,
                                attribute: popColumn,
                                limit: 1,
                            },
                            success: function(res){
                                if(res.data.length > 0) {
                                    var logData = res.data[0];
                                        
                                    // set content
                                    if(res.data[0].description === 'created') {
                                        $(popSelf).attr(
                                            'data-content',
                                            '<div class="popover-title--history">@lang('common.created_by') ' + logData.causer.name + ' <br/>' + logData.created_at + '</div>'
                                        );
                                    }else{
                                        $(popSelf).attr(
                                            'data-content',
                                            '<div class="popover-title--history">@lang('common.edited_by') ' + logData.causer.name + ' <br/>' + logData.created_at + '</div>\
                                            <div class="popover-content--history">\
                                                <div class="popover-arrow">\
                                                    &#9658;\
                                                </div>\
                                                <div class="popover-reason">\
                                                    @lang('common.lbl_reason') : ' + logData.properties.reason + '\
                                                </div>\
                                            </div>'
                                        );
                                    }
                                    $(popSelf).popover('show');
                                } else {
                                    $(popSelf).popover('hide');
                                }
                            },
                            error: function(err){
                                $(popSelf).popover('hide');
                                console.log(err);
                            }
                        });
                    }, delay);
                }
            },
            mouseleave: function() {
                // clear popover
                clearTimeout(popSetTimeoutConst);
                $(this).removeAttr('data-content');
                $(this).removeAttr('title');
                $(popSelf).popover('hide');
            },
            click: function() {
                modalSelf            = this;
                modalId              = $(modalSelf).closest('tr').attr('id');
                modalColumn          = columns[$(modalSelf).index()].column;
                modalTitle           = columns[$(modalSelf).index()].title;
                modalType            = columns[$(modalSelf).index()].modalType;

                modalIndex           = $('.editModal.fade[data-type="'+ modalType +'"]').attr('data-index');
                if (modalIndex === '') {
                    modalEl              = $('.editModal.fade[data-type="'+ modalType +'"]');
                }else{
                    modalEl              = $('.editModal.fade[data-type="'+ modalType +'"][data-index="'+ modalColumn +'"]');
                }

                // init modal
                if( modalType !== '' ) {
                    // clear popover
                    clearTimeout(popSetTimeoutConst);
                    $(popSelf).popover('hide');

                    // clear form and set title
                    modalEl.find(modalColumnTextEl).val('');
                    modalEl.find(modalReasonEl).val('');
                    modalEl.find(modalHistoryEl).html('');
                    modalEl.find(modalColumnLabelEl).html(modalTitle);
                    if(modalEl.attr('data-form') === 'hidden') {
                        modalEl.find(modalTitleEl).html('@lang('common.title_history') ' + modalTitle);
                    }else{
                        modalEl.find(modalTitleEl).html('@lang('common.title_edit') ' + modalTitle);
                    }
                    
                    console.log('preparing...');

                    // call API
                    $.ajax({
                        type: "POST",
                        url: baseURL + tableURL,
                        dataType: "JSON",
                        data: {
                            id: modalId,
                            attribute: modalColumn,
                            limit: 10,
                        },
                        success: function(res){
                            console.log(res);

                            // init modal
                            modalEl.modal('show');

                            // FIXME: add condition for other input method
                            // set current value on input form
                            switch (modalType) {
                                case 'text':
                                case 'textarea':
                                case 'number':
                                    modalEl.find(modalColumnTextEl).val($(modalSelf).html());
                                    break;
                                case 'textarea-mce':
                                    tinymce.get('js-edit-column-textarea-mce-val').setContent( $(modalSelf).find('.js-hidden-value').html() );
                                    console.log($(modalSelf).html());
                                break;
                                case 'text-escape':
                                case 'textarea-escape':
                                case 'currency-escape':
                                    modalEl.find(modalColumnTextEl).val($(modalSelf).find('.js-hidden-value').html());
                                    break;
                                case 'dropdown':
                                case 'dropdown-multiple':
                                case 'chaindropdown':
                                    // for creating log history
                                    dropdownHistoryColumn = modalColumn.substr(0, modalColumn.indexOf('_id')) === '' ?
                                        modalColumn
                                        : modalColumn.substr(0, modalColumn.indexOf('_id')) ;
                                    dropdownHistoryColumnChild = modalEl.find('.js-' + dropdownHistoryColumn).attr('data-history');
                                    console.log({dropdownHistoryColumn});
                                    console.log({dropdownHistoryColumnChild});
                                    
                                    break;
                                case 'date':
                                    modalEl.find('.js-date').datepicker({
                                        autoclose: 'true',
                                        format: 'dd-mm-yyyy',
                                    })
                                    modalEl.find('.js-date').datepicker('update', $(modalSelf).html());
                                    break;
                                case 'chaindate':
                                    // set default
                                    modalEl.find('.js-chaindate').removeClass('bg-white');
                                    modalEl.find('.js-chaindate').val('');
                                    modalEl.find(dateStartEl).datepicker({
                                        autoclose: 'true',
                                        format: 'dd-mm-yyyy',
                                    })
                                    modalEl.find(dateEndEl).datepicker({
                                        autoclose: 'true',
                                        format: 'dd-mm-yyyy',
                                    })

                                    if(modalColumn === 'date_start' || modalColumn === 'start_date' || modalColumn === 'period_in') {
                                        modalEl.find(dateStartEl).prop('disabled', false);
                                        modalEl.find(dateEndEl).prop('disabled', true);
                                        modalEl.find(dateStartEl).addClass('bg-white');

                                        modalEl.find(dateStartEl).datepicker('update', $(modalSelf).html());
                                        modalEl.find(dateEndEl).datepicker('update', $(modalSelf).next().html());
                                    }else if(modalColumn === 'date_end' || modalColumn === 'end_date' || modalColumn === 'period_out') {
                                        modalEl.find(dateStartEl).prop('disabled', true);
                                        modalEl.find(dateEndEl).prop('disabled', false);
                                        modalEl.find(dateEndEl).addClass('bg-white');

                                        modalEl.find(dateStartEl).datepicker('update', $(modalSelf).prev().html());
                                        modalEl.find(dateEndEl).datepicker('update', $(modalSelf).html());
                                    }                                        
                                    break;
                                default:
                                    break;
                            }

                            if(res.data.length > 0) {
                                if(modalType === 'dropdown' || modalType === 'dropdown-mutiple') {
                                    modalEl.find('.js-' + dropdownHistoryColumn).val(res.data[0].properties.attributes[modalColumn]).trigger('change');
                                }else if(modalType == 'chaindropdown') {
                                    var chainId = $(modalSelf).prev().html();
                                    console.log({chainId});
                                    
                                    {{-- FIXME: make dinamyc URL --}}
                                    $.ajax({
                                        type: 'GET',
                                        url: baseURL + '/api/v1/hotel/' + chainId + '/hotel_room'
                                    }).then(function (data) {
                                        modalEl.find('.js-' + dropdownHistoryColumn).empty().trigger('change');

                                        $.each(data.data.hotel_rooms, function(key, value){
                                            var option = new Option(value.room_type, value.id, false, false);
                                            modalEl.find('.js-' + dropdownHistoryColumn).append(option).trigger('change');
                                            modalEl.find('.js-' + dropdownHistoryColumn).val(res.data[0].properties.attributes[modalColumn]).trigger('change');
                                        })
                                    });
                                }

                                // generate history
                                for(var i = 0; i < res.data.length; i++) {
                                    createRowLog(res.data[i], res.data[i].description, modalColumn, modalEl);
                                }
                            }else{
                                modalEl.find(modalHistoryEl).append('<center>No History Found</center>');
                            }
                        },
                        error: function(err){
                            console.log(err);
                        }
                    });
                }
            }
        }, 'tr > td');

        // submit edited data
        $('.js-modal-save-btn').on('click', function(){
            console.log('sending...');
            // FIXME: add condition for other input method
            switch (modalType) {
                case 'text':
                case 'text-escape':
                case 'textarea-escape':
                case 'number':
                case 'currency-escape':
                case 'textarea':
                    editColumnVal = modalEl.find(modalColumnTextEl).val();
                    break;
                case 'textarea-mce':
                    editColumnVal = tinymce.get('js-edit-column-textarea-mce-val').getContent()
                    break;
                case 'dropdown':
                case 'chaindropdown':
                    editColumnVal = modalEl.find('.js-' + dropdownHistoryColumn).val();
                    break;
                case 'dropdown-multiple':
                    editColumnVal = modalEl.find('.js-' + dropdownHistoryColumn).val();
                    modalColumn = modalColumn + '[]';
                    break;
                case 'date':
                    editColumnVal = modalEl.find('.js-date').val();
                    break;
                case 'chaindate':
                    var dateEnggaKonsisten;
                    if( modalColumn === 'period_in' || modalColumn === 'start_date'  || modalColumn === 'date_start' ) {
                        dateEnggaKonsisten = 'date_start';
                    }else if( modalColumn === 'period_out' || modalColumn === 'end_date' || modalColumn === 'date_end' ) {
                        dateEnggaKonsisten = 'date_end';
                    }
                    editColumnVal = modalEl.find('.js-' + dateEnggaKonsisten ).val();
                    break;
                default:
                    break;
            }
            
            editReasonVal = modalEl.find(modalReasonEl).val();

            putData = {
                [modalColumn]: editColumnVal,
                'user_id': {{ Auth::user()->id }},
                'reason': editReasonVal
            }

            if(putData[modalColumn] !== '' && putData[modalColumn].length > 0 && putData.reason !=='') {                
                // post data to API
                $.ajax({
                    type: "PUT",
                    url: baseURL + postColumnURL + modalId,
                    dataType: "JSON",
                    data: putData,
                    success: function(res){
                        modalEl.modal('hide');
                        $($.fn.dataTable.tables(true)).DataTable().ajax.reload();

                        // SEMENTARA
                        alert('@lang('common.reason_success')');
                    },
                    error: function(err){
                        console.log(err);
                        modalEl.modal('show');
                    }
                });
            }else{
                alert('@lang('common.reason_err')!');
            }
        })
        console.log(columns);
    })
</script>