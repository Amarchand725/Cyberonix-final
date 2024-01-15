<form action="{{ route('documents.update_document') }}" method="POST" enctype="multipart/form-data" id="updateDocumentsFormHr">
    @csrf
    <input type="hidden" id="id" name="document_id" value="{{ $model->id }}">
    <div class="row mt-2">
        <div class="col-md-12">
            <label class="form-label" for="employee">Employees <span class="text-danger">*</span></label>

            <select id="selectEmployee" name="employee" class="form-select select2" required>
                <option value="" selected>Select Status</option>
                @if (isset($employees))
                @foreach ($employees as $employee)
                @php
                $designation = '';
                if (!empty($employee->jobHistory->designation->title)) {
                $designation = '( ' . $employee->jobHistory->designation->title . ' )';
                }
                @endphp
                <option value="{{ $employee->slug }}" {{ $model->user_id == $employee->id ? 'selected' : '' }}>
                    {{ $employee->first_name }} {{ $employee->last_name }} {{ $designation }}
                </option>
                @endforeach
                @endif
            </select>
            <div class="fv-plugins-message-container invalid-feedback"></div>
            <span id="employee_error" class="text-danger error"></span>
        </div>
    </div>
    <div class="row mt-2">
        <div class="row mt-3 border-top py-3">
            <span id="add-more-data">
                @if (isset($model->hasAttachments) && !empty($model->hasAttachments) && sizeof($model->hasAttachments) > 0)
                @foreach ($model->hasAttachments as $index => $attachment)
                <span class="document_data" data-id="id-{{ $attachment->id }}">
                    <div class="row mt-1 w-full  py-2 position-relative document_row_{{ $attachment->id }} align-items-center">

                        <div class="col-md-10 mt-3">
                            <label for="">Title</label>
                            <input type="text" id="title" name="titles[]" class="form-control edit_titles edit_titles_value_{{ $attachment->id }}" data-count="{{ $attachment->id }}" value="{{ $attachment->title }}" placeholder="Enter Title" />
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                            <span id="edit_titles_error_{{ $attachment->id }}" class="text-danger error"></span>
                        </div>
                        <div class="col-2 text-center">
                            <button type="button" class="btn btn-label-primary btn-sm delete-document-btn mt-4" data-id="{{ $attachment->id }}" data-route="{{ route('documents.deleteDocumentsHr', $attachment->id) }}" style="margin-left:2px"><i class="fa fa-close icon-close"></i></button>
                        </div>
                        <div class="col-md-10 mt-3">
                            <label for="">Attachment</label>
                            <input type="hidden" name="attachment_count[]" value="{{ $attachment->id }}">
                            <input type="file" id="attachments" name="attachments[]" accept="image/*,.doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document , .pdf, .txt" value="{{ asset('public/admin/assets/document_attachments/' . $attachment->attachment ?? '') }}" data-val="{{ $attachment->id }}" class="form-control  input-file attachments attachments_{{ $attachment->id }}" />
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                            <span style="font-size: 10px">( Optional )</span>
                            <span id="attachments_error_{{ $attachment->id }}" class="text-danger error"></span>
                        </div>
                        <div class="col-md-2 mt-3">
                            <div class="preview-container-{{ $attachment->id }} text-center">
                                @if (isset($attachment->attachment) && !empty($attachment->attachment))
                                @if (checkFileType($attachment->attachment) == 'xls')
                                <a href="{{ asset('public/admin/assets/document_attachments/' . $attachment->attachment ?? '') }}" target="_blank">
                                    <img src="{{ asset('public/admin/assets/img/xls.png') }}" style="width:50px" alt="">
                                </a>
                                @elseif(checkFileType($attachment->attachment) == 'word')
                                <a href="{{ asset('public/admin/assets/document_attachments/' . $attachment->attachment ?? '') }}" target="_blank">
                                    <img src="{{ asset('public/admin/assets/img/doc.png') }}" style="width:50px" alt="">
                                </a>
                                @elseif(checkFileType($attachment->attachment) == 'pdf')
                                <a href="{{ asset('public/admin/assets/document_attachments/' . $attachment->attachment ?? '') }}" target="_blank">
                                    <img src="{{ asset('public/admin/assets/img/pdf.png') }}" style="width:50px" alt="">
                                </a>
                                @elseif(checkFileType($attachment->attachment) == 'image')
                                    <img src="{{ asset('public/admin/assets/document_attachments/' . $attachment->attachment ?? '') }}" style="width:50px" alt="">
                                @else
                                <a href="{{ asset('public/admin/assets/document_attachments/' . $attachment->attachment ?? '') }}" target="_blank">
                                    <img src="{{ asset('public/admin/assets/img/fileicon.png') }}" style="width:50px" alt="">
                                </a>
                                @endif
                                @endif
        
                            </div>
                        </div>
                    </div>
                </span>
                @endforeach
                @endif
            </span>
        </div>
    </div>
    <div class="col-12 mt-3 action-btn" style="text-align: right">
        <div class="demo-inline-spacing sub-btn">
            <button type="submit" class="btn btn-primary me-sm-3 me-1 btn_submit" id="updateDocumentsBtn"> <span class="spinner-border me-1 d-none" role="status" aria-hidden="true"></span> Update</button>
            <button type="reset" class="btn btn-label-secondary btn-reset" data-bs-dismiss="modal" aria-label="Close">
                Cancel
            </button>
        </div>
        <div class="demo-inline-spacing loading-btn" style="display: none;">
            <button class="btn btn-primary waves-effect waves-light" type="button" disabled="">
                <span class="spinner-border me-1" role="status" aria-hidden="true"></span>
                Loading...
            </button>
            <button type="reset" class="btn btn-label-secondary btn-reset" data-bs-dismiss="modal" aria-label="Close">
                Cancel
            </button>
        </div>
    </div>

</form>