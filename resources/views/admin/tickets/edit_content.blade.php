<input type="hidden" id="id" name="id" value="{{ $model->id }}">
<div class="row">
    <div class="col-12">
        <label class="form-label" for="ticket_category_id">Category <span class="text-danger">*</span></label>
        <select name="ticket_category_id" id="ticket_category_id" class="form-control">
            <option value="" selected>Select ticket category</option>
            @foreach ($data['ticket_categories'] as $category)
                <option value="{{ $category->id }}" {{ $model->ticket_category_id==$category->id?'selected':'' }} data-category_name="{{ $category->name }}">{{ $category->name }}</option>
            @endforeach
        </select>
        <div class="fv-plugins-message-container invalid-feedback"></div>
        <span id="ticket_category_id_error" class="text-danger error"></span>
    </div>
    <div class="col-12" id="reason-block" @if(empty($model->reason_id)) style="display: none" @endif>
        <label class="form-label" for="reason_id">Reason</label>
        <select name="reason_id" id="reason_id" class="form-control">
            <option value="" selected>Select ticket reason</option>
            @foreach ($data['reasons'] as $reason)
                <option value="{{ $reason->id }}" {{ $model->reason_id==$reason->id?'selected':'' }}>{{ $reason->name }}</option>
            @endforeach
        </select>
        <div class="fv-plugins-message-container invalid-feedback"></div>
        <span id="reason_id_error" class="text-danger error"></span>
    </div>
</div>
<div class="row mt-2">
    <div class="col-md-12">
        <label class="form-label" for="subject">Subject <span class="text-danger">*</span></label>
        <input type="text" id="subject" name="subject" value="{{ $model->subject }}" class="form-control" placeholder="Enter subject" />
        <div class="fv-plugins-message-container invalid-feedback"></div>
        <span id="subject_error" class="text-danger error"></span>
    </div>
</div>

<div class="row mt-2">
    <div class="col-md-12">
        <label class="form-label" for="attachment">Attachment <small>(If Any)</small></label>
        <input type="file" id="attachment" name="attachment" class="form-control" />
        <div class="fv-plugins-message-container invalid-feedback"></div>
        <span id="attachment_error" class="text-danger error"></span>

        @if(!empty($model->attachment))
            <span id='attachment-file'>
                <a href="{{ asset('public/admin/assets/ticket_attachments') }}/{{ $model->attachment }}" download="{{ $model->attachment }}">Download Attachment</a>
            </span>
        @endif
    </div>
</div>

<div class="col-12 col-md-12 mt-3">
    <label class="form-label" for="note">Note <span class="text-danger">*</span></label>
    <textarea class="form-control" rows="5" name="note" id="note" placeholder="Enter note here...">{!! $model->note !!}</textarea>
    <div class="fv-plugins-message-container invalid-feedback"></div>
    <span id="note_error" class="text-danger error"></span>
</div>
