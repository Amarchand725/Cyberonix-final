@extends('admin.layouts.app')
@section('title', $title.' - ' . appName() )

@section('content')
<input type="hidden" id="page_url" value="{{ route('letter_templates.index') }}">
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card mb-4">
            <div class="row">
                <div class="col-md-6">
                    <div class="card-header">
                        <h4 class="fw-bold mb-0"><span class="text-muted fw-light">Home /</span> {{ $title }}</h4>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="dt-buttons btn-group flex-wrap float-end mt-4">
                        <a data-toggle="tooltip" data-placement="top" title="All Trashed Records" href="{{ route('letter_templates.trashed') }}" class="btn btn-label-primary me-3">
                            <span>
                                <i class="ti ti-trash me-0 me-sm-1 ti-xs"></i>
                                <span class="d-none d-sm-inline-block">All Trashed Records ( <span id="trash-record-count">{{ $onlySoftDeleted }}</span> )</span>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Users List Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-3">Search Filter</h5>
                <div class="row">
                    <div class="col-lg-6 col-md-12">
                        <div class="col-md-6">
                            <div>
                                <input type="search" class="form-control" id="search" name="search" placeholder="Search ">
                                <input type="hidden" id="status" value="All" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 text-end">
                        <button data-toggle="tooltip" data-placement="top" title="Add Letter Template" type="button" class="btn btn-secondary add-new btn-primary mx-3" id="add-btn" data-url="{{ route('letter_templates.store') }}" tabindex="0" aria-controls="DataTables_Table_0" type="button" data-bs-toggle="modal" data-bs-target="#offcanvasAddAnnouncement">
                            <span>
                                <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>
                                <span class="d-none d-sm-inline-block">Add New Template</span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-datatable table-responsive">
                <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                    <div class="container">
                        <table class="dt-row-grouping table dataTable dtr-column border-top table-border">
                            <thead>
                                <tr>
                                    <th scope="col">S.No#</th>
                                    <th scope="col">Title</th>
                                    <th scope="col">Created At</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="body">
                                @foreach ($models as $key=>$model)
                                <tr class="odd" id="id-{{ $model->id }}">
                                    <td tabindex="0">{{ $models->firstItem()+$key }}.</td>
                                    <td>
                                        <span class="text-truncate d-flex align-items-center text-primary fw-semibold">
                                            {{ $model->title??'-' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-truncate d-flex align-items-center text-primary">
                                            {{ date('d M Y', strtotime($model->created_at)) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($model->status)
                                        <span class="badge bg-label-success" text-capitalized="">Active</span>
                                        @else
                                        <span class="badge bg-label-danger" text-capitalized="">De-Active</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <a href="javascript:;" data-toggle="tooltip" data-placement="top" title="Show Letter Template" data-show-url="{{ route('letter_templates.show', $model->id) }}" class="btn btn-icon btn-label-info waves-effect me-2 show" type="button" tabindex="0" aria-controls="DataTables_Table_0" type="button" data-bs-toggle="modal" data-bs-target="#view-template-modal" fdprocessedid="i1qq7b">
                                                <i class="ti ti-eye ti-sm"></i>
                                            </a>
                                            <a href="javascript:;" data-toggle="tooltip" data-placement="top" title="Edit Letter Template" data-edit-url="{{ route('letter_templates.edit', $model->id) }}" data-url="{{ route('letter_templates.update', $model->id) }}" class="btn btn-icon btn-label-warning waves-effect me-2 edit-btn" type="button" tabindex="0" aria-controls="DataTables_Table_0" type="button" data-bs-toggle="modal" data-bs-target="#offcanvasAddAnnouncement" fdprocessedid="i1qq7b">
                                                <i class="ti ti-edit ti-sm"></i>
                                            </a>
                                            <a href="javascript:;" class="delete btn btn-icon btn-label-primary waves-effect" data-slug="{{ $model->id }}" data-del-url="{{ route('letter_templates.destroy', $model->id) }}">
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
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Employment Status Modal -->
<div class="modal fade" id="offcanvasAddAnnouncement" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered1 modal-simple modal-add-new-cc">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-4">
                    <h3 class="mb-2" id="modal-label"></h3>
                </div>
                <form id="create-form" class="row g-3" data-method="" data-modal-id="offcanvasAddAnnouncement">
                    @csrf

                    <span id="edit-content">
                        <div class="col-12 col-md-12">
                            <label class="form-label" for="title">Title <span class="text-danger">*</span></label>
                            <input type="text" id="title" name="title" class="form-control" placeholder="Enter title" />
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                            <span id="title_error" class="text-danger error"></span>
                        </div>

                        <div class="col-12 col-md-12 mt-3">
                            <label class="form-label" for="description">Template <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="description" id="description" placeholder="Enter template">{{ old('template') }}</textarea>

                            <div class="fv-plugins-message-container invalid-feedback"></div>
                            <span id="description_error" class="text-danger error"></span>
                        </div>
                    </span>

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary me-sm-3 me-1 submitBtn">Submit</button>
                        <button type="reset" class="btn btn-label-secondary btn-reset" data-bs-dismiss="modal" aria-label="Close">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--/ Edit Employment Status Modal -->

<!-- Show Template -->
<div class="modal fade" id="view-template-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered1 modal-simple modal-add-new-cc">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="mb-4">
                    <h3 class="mb-2" id="modal-label"></h3>
                </div>
                <span id="show-content"></span>
            </div>
        </div>
    </div>
</div>
<!-- Show Template -->
@endsection
@push('js')
@endpush