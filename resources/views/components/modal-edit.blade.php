<div class="modal fade editModal"
    data-type="{{ $modalType }}"
    data-index="{{ empty($modalIndex) ? '' : $modalIndex }}"
    data-form="{{ empty($editForm) ? '' : $editForm }}"
    style="display: none;">

    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-close">
                    <img class="icon" src="{{ asset($public.'icons/fail.svg') }}" data-dismiss="modal" aria-label="Close">
                </div>
                <h4 class="modal-title js-modal-title"></h4>
            </div>
            <div class="modal-body radius--bottom">
                @if( empty($editForm) ? true : $editForm != 'hidden' )
                    <div class="modal-label">
                        <span class="js-modal-column-label"></span>

                        {{-- Text input --}}
                        @if($modalType == 'text' || $modalType == 'text-escape')
                            <input class="form-control js-edit-column-text-val" type="text" name="" required="required" />
                        @endif
                        {{-- ./End text input --}}

                        {{-- Textarea input --}}
                        @if($modalType == 'textarea' || $modalType == 'textarea-escape')
                            <textarea class="form-control js-edit-column-text-val" name="" rows="4" required="required"></textarea>
                        @endif
                        {{-- ./End textarea input --}}

                        {{-- Textarea TinyMCE input --}}
                        @if($modalType == 'textarea-mce')
                            <textarea id="js-edit-column-textarea-mce-val" class="form-control js-edit-column-text-val js-edit-column-textarea-mce-val" name="" rows="4" required="required"></textarea>
                        @endif
                        {{-- ./End textarea input --}}
                        
                        {{-- Number input --}}
                        @if($modalType == 'number' || $modalType == 'currency-escape')
                            <input class="form-control js-edit-column-text-val" type="number" name="" required="required" />
                        @endif
                        {{-- ./End Number input --}}

                        {{-- Dropdown input --}}
                        @if($modalType == 'dropdown' || $modalType == 'chaindropdown')
                            <div class="form-group">
                                {!! Form::select($dropdownName, $dropdownQuery, null, ['class'=>'form-control select2 js-'.$dropdownName, 'data-history'=>$modalHistory]) !!}
                            </div>
                        @endif

                        {{-- Dropdown multiselect input --}}
                        @if($modalType == 'dropdown-multiple')
                            <div class="form-group">
                                {!! Form::select($dropdownName.'[]', $dropdownQuery, null, ['class'=>'form-control select2 js-'.$dropdownName, 'data-history'=>$modalHistory, 'multiple'=>'multiple']) !!}
                            </div>
                        @endif
                        {{-- ./End date dropdown input --}}

                        {{-- Date input --}}
                        @if($modalType == 'date')
                            <input type="text" class="form-control pointer js-date" readonly>
                        @endif
                        {{-- ./End date input --}}

                        {{-- Date range input --}}
                        @if($modalType == 'chaindate')
                            <div class="input-group input-daterange">
                                <input type="text" class="form-control pointer js-chaindate js-date_start" readonly>
                                <div class="input-group-addon">to</div>
                                <input type="text" class="form-control pointer js-chaindate js-date_end" readonly>
                            </div>
                        @endif
                        {{-- ./End date range input --}}
                    </div>
                    
                    <div class="modal-label">
                        <span>@lang('common.lbl_reason')</span>
                        <textarea class="form-control js-modal-reason-val" name="reason" rows="4" required="required"></textarea><br>
                    </div>
                    
                    <button type="button" class="btn btn-primary box-shadow right-align js-modal-save-btn">@lang('common.btn_save')</button><br><br>
                @endif
                
                <div class="modal-label">@lang('common.history')</div>
                <div class="history js-modal-history">
                    {{-- loop here --}}
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>