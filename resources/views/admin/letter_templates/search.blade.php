@foreach ($models as $key=>$model)
    <tr class="odd" id="id-{{ $model->id }}">
        <td tabindex="0">{{ $models->firstItem()+$key }}.</td>
        <td>
            <span class="text-truncate d-flex align-items-center text-primary fw-semibold">
                {{ $model->title??'-' }}
            </span>
        </td>
        <td>
            @if(isset($model->hasAnnouncementDepartments) && !empty($model->hasAnnouncementDepartments))
                @foreach ($model->hasAnnouncementDepartments as $announcement_department)
                    @if(isset($announcement_department->hasDepartment) && !empty($announcement_department->hasDepartment))
                        <span class="badge bg-label-info" text-capitalized="">{{ $announcement_department->hasDepartment->name }}</span>
                    @endif
                @endforeach
            @else
            -
            @endif
        </td>
        <td>
            <span class="text-truncate d-flex align-items-center text-primary">
                {{ date('d M Y', strtotime($model->start_date))??'-' }}
            </span>
        </td>
        <td>
            @if(!empty($model->end_date))
                <span class="text-primary">{{ date('d M Y', strtotime($model->end_date)) }}</span>
            @else
                -
            @endif
        </td>
        <td>{!! \Illuminate\Support\Str::limit($model->description,50)??'-' !!}</td>
        <td>
            @if($model->createdBy)
                {{ $model->createdBy->first_name }} {{ $model->createdBy->last_name }}
            @else
                -
            @endif
        </td>
        <td>
            <div class="d-flex align-items-center">
                <a href="javascript:;"
                    data-toggle="tooltip"
                    data-placement="top"
                    title="Edit Announcement"
                    data-edit-url="{{ route('announcements.edit', $model->id) }}"
                    data-url="{{ route('announcements.update', $model->id) }}"
                    class="btn btn-icon btn-label-info waves-effect me-2 edit-btn"
                    type="button"
                    tabindex="0" aria-controls="DataTables_Table_0"
                    type="button" data-bs-toggle="modal"
                    data-bs-target="#offcanvasAddAnnouncement"
                    fdprocessedid="i1qq7b">
                    <i class="ti ti-edit ti-sm"></i>
                </a>
                <a href="javascript:;" class="delete btn btn-icon btn-label-primary waves-effect" data-slug="{{ $model->id }}" data-del-url="{{ route('announcements.destroy', $model->id) }}">
                    <i class="ti ti-trash ti-sm"></i>
                </a>
            </div>
        </td>
    </tr>
@endforeach
<tr>
    <td colspan="8">
        <div class="row">
            <div class="col-sm-12 col-md-6 ps-0">
                <div class="dataTables_info" id="DataTables_Table_0_info" role="status" aria-live="polite">Showing {{$models->firstItem()}} to {{$models->lastItem()}} of {{$models->total()}} entries</div>
            </div>
            <div class="col-sm-12 col-md-6 pe-0">
                <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate">
                    {!! $models->links('pagination::bootstrap-4') !!}
                </div>
            </div>
        </div>
    </td>
</tr>

<script src="{{ asset('public/admin/assets/js/search-delete.js') }}"></script>
