<form action="{{ route('profile.updateDocuments', $model->id) }}" method="POST" enctype="multipart/form-data" id="updateDocumentsForm">
    @csrf
    <input type="hidden" id="id" name="document_id" value="{{ $model->id }}">
    <div class="row">
        @if (isset($model->hasAttachments) && !empty($model->hasAttachments) && sizeof($model->hasAttachments) > 0)
        @foreach ($model->hasAttachments as $attachment)
        <span class="document_data" data-id="id-{{ $attachment->id }}">
            <div class="row mt-2 w-full border-top align-items-center py-2 position-relative document_row_{{ $attachment->id }}">
                
                <div class="col-10 mt-3">
                    <input type="hidden" name="attachment_id[]" value="{{ $attachment->id }}">
                    <input type="text" id="title" name="titles[]" class="form-control edit_titles edit_titles_value_{{ $attachment->id }}" data-count="{{ $attachment->id }}" value="{{ $attachment->title }}" placeholder="Enter Title" />
                    <div class="fv-plugins-message-container invalid-feedback"></div>
                    <span id="edit_titles_error_{{ $attachment->id }}" class="text-danger error"></span>
                </div>
                <div class="col-2 text-center">
                        <button type="button" class="btn btn-label-primary btn-sm delete-document-btn mt-3" data-id="{{ $attachment->id }}" data-route="{{ route('profile.deleteDocuments', $attachment->id) }}" style="margin-left:2px"><i class="fa fa-close icon-close"></i></button>
                </div>
                <div class="col-lg-10 mt-3">
                    <input type="hidden" name="attachment_count[]" value="{{ $attachment->id }}">
                    <input type="file" id="attachments" name="attachments[]" accept="image/*,.doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document , .pdf, .txt" value="{{ asset('public/admin/assets/document_attachments/' . $attachment->attachment ?? '') }}" data-val="{{ $attachment->id }}" class="form-control  input-file attachments attachments_{{ $attachment->id }}" />
                    <div class="fv-plugins-message-container invalid-feedback"></div>
                    <span style="font-size: 10px">( Optional )</span>
                    <span id="attachments_error_{{ $attachment->id }}" class="text-danger error"></span>
                </div>
                <div class="col-lg-2 mt-3">
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
    </div>
</form>