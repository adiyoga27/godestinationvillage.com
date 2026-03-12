<script>
    $(document).ready(function() {
        var baseURL                         = '{{ url("") }}';
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
        
        var modalEl                         = $('.editModal');
        var modalTitleEl                    = '.js-modal-title';
        var modalHistoryEl                  = '.js-modal-history';

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
                    historyRow = desc === 'created' ? data.properties.attributes[column] : data.properties.old[column];
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

        // generate datatable event
        function generateDatatableEvent(datatableEl, tableURLLocal, columnsLocal) {
            $(datatableEl).on({
                mouseenter: function() {
                    popSelf            = this;
                    
                    generatePopover(
                        popSelf,
                        tableURLLocal,
                        columnsLocal,
                    );
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
                    
                    generateModal(
                        modalSelf,
                        tableURLLocal,
                        columnsLocal,
                    );
                }
            }, 'tr > td');
        }


        // generate popover
        function generatePopover(popSelfLocal, tableURLLocal, columnsLocal) {
            popId              = $(popSelfLocal).closest('tr').attr('id');
            popColumn          = columnsLocal[$(popSelfLocal).index()].column;
            popTitle           = columnsLocal[$(popSelfLocal).index()].title;
            popType            = columnsLocal[$(popSelfLocal).index()].modalType;

            // init popover
            if(popType !== '') {
                $(popSelfLocal).attr('title', popTitle);
                $(popSelfLocal).popover({
                    content: '<div class="popover-loading">@lang('common.loading')</div>',
                    trigger: 'hover',
                    placement: 'top',
                    container: 'body',
                    html: true,
                });
                $(popSelfLocal).popover('show');

                // set delay popover
                popSetTimeoutConst = setTimeout(function() {
                    // call api
                    $.ajax({
                        type: "POST",
                        url: baseURL + tableURLLocal,
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
                                    $(popSelfLocal).attr(
                                        'data-content',
                                        '<div class="popover-title--history">@lang('common.created_by') ' + logData.causer.name + ' <br/>' + logData.created_at + '</div>'
                                    );
                                }else{
                                    $(popSelfLocal).attr(
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
                                $(popSelfLocal).popover('show');
                            } else {
                                $(popSelfLocal).popover('hide');
                            }
                        },
                        error: function(err){
                            $(popSelfLocal).popover('hide');
                            console.log(err);
                        }
                    });
                }, delay);
            }
        }

        // gemerate modal
        function generateModal(modalSelfLocal, tableURLLocal, columnsLocal) {
            modalId              = $(modalSelfLocal).closest('tr').attr('id');
            modalColumn          = columnsLocal[$(modalSelfLocal).index()].column;
            modalTitle           = columnsLocal[$(modalSelfLocal).index()].title;
            modalType            = columnsLocal[$(modalSelfLocal).index()].modalType;
            modalEl              = $('.editModal.fade[data-type="'+ modalType +'"]');

            // init modal
            if( modalType !== '' ) {
                // clear popover
                clearTimeout(popSetTimeoutConst);
                $(popSelf).popover('hide');

                // set title
                modalEl.find(modalHistoryEl).html('');
                modalEl.find(modalTitleEl).html('@lang('common.title_history') ' + modalTitle);
                
                console.log('preparing...');

                // call API
                $.ajax({
                    type: "POST",
                    url: baseURL + tableURLLocal,
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

                        if(res.data.length > 0) {
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

        // datatable quotation hotel
        generateDatatableEvent(
            '#quotation_hotel_table',
            '/api/v1/quotation-hotel-history',
            [
                {
                    'column'        : 'action',
                    'title'         : 'Action',
                    'modalType'     : ''
                },
                {
                    'column'        : 'hotel_name',
                    'title'         : '@lang('quotation.lbl_hotel_name')',
                    'modalType'     : 'text'
                },
                {
                    'column'        : 'hotel_room_name',
                    'title'         : '@lang('quotation.lbl_hotel_room_name')',
                    'modalType'     : 'text'
                },
                {
                    'column'        : 'base_price',
                    'title'         : '@lang('quotation.lbl_base_price')',
                    'modalType'     : 'currency-escape'
                },
                {
                    'column'        : 'benefit',
                    'title'         : '@lang('quotation.lbl_benefit')',
                    'modalType'     : 'text'
                },
                {
                    'column'        : 'contract_name',
                    'title'         : '@lang('quotation.lbl_contract_name')',
                    'modalType'     : 'text'
                },
                {
                    'column'        : 'contract_name_name',
                    'title'         : '@lang('quotation.lbl_surcharge_name')',
                    'modalType'     : 'text'
                },
                {
                    'column'        : 'surcharge',
                    'title'         : '@lang('quotation.lbl_surcharge')',
                    'modalType'     : 'currency-escape'
                },
                {
                    'column'        : 'discount',
                    'title'         : '@lang('quotation.lbl_discount')',
                    'modalType'     : 'currency-escape'
                },
                {
                    'column'        : 'pax',
                    'title'         : '@lang('quotation.lbl_pax')',
                    'modalType'     : 'text'
                },
                {
                    'column'        : 'price',
                    'title'         : '@lang('quotation.lbl_price')',
                    'modalType'     : 'currency-escape'
                },
                {
                    'column'        : 'created_at',
                    'title'         : 'Created At',
                    'modalType'     : ''
                }
            ]
        );
        
        // datatable quotation tour
        generateDatatableEvent(
            '#quotation_tour_table',
            '/api/v1/quotation-tour-history',
            [
                {
                    'column'        : 'action',
                    'title'         : 'Action',
                    'modalType'     : ''
                },
                {
                    'column'        : 'place_name',
                    'title'         : '@lang('quotation.lbl_place_name')',
                    'modalType'     : 'text'
                },
                {
                    'column'        : 'package_name',
                    'title'         : '@lang('quotation.lbl_package_name')',
                    'modalType'     : 'text'
                },
                {
                    'column'        : 'base_price',
                    'title'         : '@lang('quotation.lbl_base_price')',
                    'modalType'     : 'currency-escape'
                },
                {
                    'column'        : 'package_detail',
                    'title'         : '@lang('quotation.lbl_package_detail')',
                    'modalType'     : 'text'
                },
                {
                    'column'        : 'package_term_condition',
                    'title'         : '@lang('quotation.lbl_package_term_condition')',
                    'modalType'     : 'text'
                },
                {
                    'column'        : 'contract_type',
                    'title'         : '@lang('quotation.lbl_contract_type')',
                    'modalType'     : 'text'
                },
                {
                    'column'        : 'contract_name',
                    'title'         : '@lang('quotation.lbl_contract_name')',
                    'modalType'     : 'text'
                },
                {
                    'column'        : 'contract_entrance_fee',
                    'title'         : '@lang('quotation.lbl_contract_entrance_fee')',
                    'modalType'     : 'currency-escape'
                },
                {
                    'column'        : 'contract_start_date',
                    'title'         : '@lang('quotation.lbl_contract_start_date')',
                    'modalType'     : 'chaindate'
                },
                {
                    'column'        : 'contract_end_date',
                    'title'         : '@lang('quotation.lbl_contract_end_date')',
                    'modalType'     : 'chaindate'
                },
                {
                    'column'        : 'surcharge_name',
                    'title'         : '@lang('quotation.lbl_surcharge_name')',
                    'modalType'     : 'text'
                },
                {
                    'column'        : 'surcharge',
                    'title'         : '@lang('quotation.lbl_surcharge')',
                    'modalType'     : 'currency-escape'
                },
                {
                    'column'        : 'discount',
                    'title'         : '@lang('quotation.lbl_discount')',
                    'modalType'     : 'currency-escape'
                },
                {
                    'column'        : 'surcharge_start_date',
                    'title'         : '@lang('quotation.lbl_surcharge_start_date')',
                    'modalType'     : 'chaindate'
                },
                {
                    'column'        : 'surcharge_end_date',
                    'title'         : '@lang('quotation.lbl_surcharge_end_date')',
                    'modalType'     : 'chaindate'
                },
                {
                    'column'        : 'pax',
                    'title'         : '@lang('quotation.lbl_pax')',
                    'modalType'     : 'text'
                },
                {
                    'column'        : 'price',
                    'title'         : '@lang('quotation.lbl_price')',
                    'modalType'     : 'currency-escape'
                },
                {
                    'column'        : 'day',
                    'title'         : '@lang('quotation.lbl_itinerary_days')',
                    'modalType'     : 'text'
                },
                {
                    'column'        : 'is_eating_place',
                    'title'         : '@lang('quotation.lbl_is_eating_place')',
                    'modalType'     : 'text'
                },
                {
                    'column'        : 'created_at',
                    'title'         : 'Created At',
                    'modalType'     : ''
                }
            ]
        );
        
        // datatable quotation transport
        generateDatatableEvent(
            '#quotation_transport_table',
            '/api/v1/quotation-transport-history',
            [
                {
                    'column'        : 'action',
                    'title'         : 'Action',
                    'modalType'     : ''
                },
                {
                    'column'        : 'category',
                    'title'         : '@lang('quotation.lbl_category')',
                    'modalType'     : 'text'
                },
                {
                    'column'        : 'transfer',
                    'title'         : '@lang('quotation.lbl_transfer')',
                    'modalType'     : 'text'
                },
                {
                    'column'        : 'package_name',
                    'title'         : '@lang('quotation.lbl_package_name')',
                    'modalType'     : 'text'
                },
                {
                    'column'        : 'base_price',
                    'title'         : '@lang('quotation.lbl_base_price')',
                    'modalType'     : 'currency-escape'
                },
                {
                    'column'        : 'base_price_unit',
                    'title'         : '@lang('quotation.lbl_base_price_unit')',
                    'modalType'     : 'currency-escape'
                },
                {
                    'column'        : 'total_passenger',
                    'title'         : '@lang('quotation.lbl_total_passenger')',
                    'modalType'     : 'text'
                },
                {
                    'column'        : 'surcharge_name',
                    'title'         : '@lang('quotation.lbl_surcharge_name')',
                    'modalType'     : 'text'
                },
                {
                    'column'        : 'surcharge',
                    'title'         : '@lang('quotation.lbl_surcharge')',
                    'modalType'     : 'currency-escape'
                },
                {
                    'column'        : 'surcharge_start_date',
                    'title'         : '@lang('quotation.lbl_surcharge_start_date')',
                    'modalType'     : 'chaindate'
                },
                {
                    'column'        : 'surcharge_end_date',
                    'title'         : '@lang('quotation.lbl_surcharge_end_date')',
                    'modalType'     : 'chaindate'
                },
                {
                    'column'        : 'pax',
                    'title'         : '@lang('quotation.lbl_pax')',
                    'modalType'     : 'text'
                },
                {
                    'column'        : 'price',
                    'title'         : '@lang('quotation.lbl_price')',
                    'modalType'     : 'currency-escape'
                },
                {
                    'column'        : 'created_at',
                    'title'         : 'Created At',
                    'modalType'     : ''
                }
            ]
        );
    })
</script>