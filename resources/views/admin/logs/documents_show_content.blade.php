<table class="table table-bordered">
    <tr>
        <th>Employee</th>
        <td>
            <div class="d-flex justify-content-start align-items-center user-name">
                <div class="avatar-wrapper">
                    <div class="avatar avatar-sm me-3">
                        @if (!empty($model->hasEmployee->profile->profile))
                        <img src="{{ resize(asset('public/admin/assets/img/avatars').'/'.$model->hasEmployee->profile->profile, null) }}" alt="Avatar" class="rounded-circle">
                        @else
                        <img src="{{ asset('public/admin/default.png') }}" alt="Avatar" class="rounded-circle">
                        @endif
                    </div>
                </div>
                <div class="d-flex flex-column">
                    <a href="{{ route('employees.show', $model->hasEmployee->slug ?? null) }}" class="text-body text-truncate">
                        <span class="fw-semibold">{{ Str::ucfirst($model->hasEmployee->first_name ?? '') }}
                            {{ Str::ucfirst($model->hasEmployee->last_name ?? '') }}</span>
                    </a>
                    <small class="text-muted">{{ $model->hasEmployee->email ?? '-' }}</small>
                </div>
            </div>
        </td>
    </tr>
    <tr>
        <th>Department</th>
        <td>
            @if (!empty($model->hasEmployee->departmentBridge->department->name))
            {{ $model->hasEmployee->departmentBridge->department->name }}
            @else
            '-'
            @endif
        </td>
    </tr>
    <tr>
        <th>Created At</th>
        <td>{{ date('d F Y', strtotime($model->date)) }}</td>
    </tr>
</table>
<hr>
<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>#</th>
            <th>Title</th>
            <th>Attachment</th>
        </tr>
    </thead>
    <tbody>
        @if (isset($model->hasAttachmentsWithTrashed) && !empty($model->hasAttachmentsWithTrashed) && sizeof($model->hasAttachmentsWithTrashed) > 0)
        @foreach ($model->hasAttachmentsWithTrashed as $index => $attachment)
        <tr>
            <td>{{ ++$index }}</td>
            <td>{{ $attachment->title }}</td>

            <td>

                @if (checkFileType($attachment->attachment) == 'word')
                <a href="{{ asset('public/admin/assets/document_attachments/' . $attachment->attachment ?? '') }}" target="_blank">
                    <img src="{{ asset('public/admin/assets/img/doc.png') }}" style="width:50px" alt="">
                </a>
                @elseif (checkFileType($attachment->attachment) == 'pdf')
                <a href="{{ asset('public/admin/assets/document_attachments/' . $attachment->attachment ?? '') }}" target="_blank">
                    <img src="{{ asset('public/admin/assets/img/pdf.png') }}" style="width:50px" alt="">
                </a>
                @elseif (checkFileType($attachment->attachment) == 'xls')
                <a href="{{ asset('public/admin/assets/document_attachments/' . $attachment->attachment ?? '') }}" target="_blank">
                    <img src="{{ asset('public/admin/assets/img/xls.png') }}" style="width:50px" alt="">
                </a>
                @elseif (checkFileType($attachment->attachment) == 'image')
                <img src="{{ asset('public/admin/assets/document_attachments/' . $attachment->attachment ?? '') }}" style="width:50px" alt="">
                @else
                <a href="{{ asset('public/admin/assets/document_attachments/' . $attachment->attachment ?? '') }}" target="_blank">
                    <img src="{{ asset('public/admin/assets/img/fileicon.png') }}" style="width:50px" alt="">
                </a>
                @endif
            </td>



        </tr>
        @endforeach
        @endif
    </tbody>
</table>
