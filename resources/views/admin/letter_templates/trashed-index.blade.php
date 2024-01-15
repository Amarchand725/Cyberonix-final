@extends('admin.layouts.app')
@section('title', $title.' - ' . appName())

@section('content')
<input type="hidden" id="page_url" value="{{ route('positions.index') }}">
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card mb-4">
            <div class="row">
                <div class="col-md-6">
                    <div class="card-header">
                        <h4 class="fw-bold mb-0"><span class="text-muted fw-light">Home /</span> {{ $title }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <!-- Users List Table -->
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="card-title mb-3">Search Filter</h5>
                <div class="row">
                    <div class="col-lg-6 col-md-12">
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="search" class="form-control" id="search" name="search" placeholder="Search.." aria-controls="DataTables_Table_0">
                                <input type="hidden" class="form-control" id="status" value="All">
                                <button class="btn btn-primary waves-effect" type="button" id="button-addon2" data-form-type="action" data-dashlane-label="true"><i class="ti ti-search"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-3 mb-md-0">
                            <div class="dt-buttons btn-group flex-wrap">
                                <a data-toggle="tooltip" data-placement="top" title="Show All Records" href="{{ route('announcements.index') }}" class="btn btn-success btn-primary mx-3">
                                    <span>
                                        <i class="ti ti-eye me-0 me-sm-1 ti-xs"></i>
                                        <span class="d-none d-sm-inline-block">View All Records</span>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-datatable table-responsive mt-4">
                <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                    <!-- <div class="row me-2">
                        <div class="col-md-2">
                            <div class="me-3">
                                <div class="dataTables_length" id="DataTables_Table_0_length">
                                    <label>
                                        @if(session()->has('message'))
                                            <div class="alert alert-success" id="message-alert">
                                                {{ session()->get('message') }}
                                            </div>
                                        @endif

                                        @if(session()->has('error'))
                                            <div class="alert alert-danger" id="message-alert">
                                                {{ session()->get('error') }}
                                            </div>
                                        @endif
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div> -->
                    <table class="dt-row-grouping table dataTable dtr-column">
                        <thead>
                            <tr>
                                <th class="control sorting_disabled dtr-hidden" aria-label="Avatar">S.No#</th>
                                <th class="sorting sorting_desc" tabindex="0" aria-controls="DataTables_Table_0" aria-sort="descending">Title</th>
                                <th class="sorting sorting_desc" tabindex="0" aria-controls="DataTables_Table_0" aria-sort="descending">Department</th>
                                <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0">Start Date</th>
                                <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0">End Date</th>
                                <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0">Description</th>
                                <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0">Created By</th>
                                <th class="sorting_disabled" style="width: 135px;" aria-label="Actions">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="body">
                            @foreach ($data['models'] as $key=>$model)
                                <tr class="odd" id="id-{{ $model->id }}">
                                    <td tabindex="0">{{ $key+1 }}.</td>
                                    <td>
                                        <span class="text-truncate d-flex align-items-center text-primary">
                                            {{ $model->title??'-' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-truncate d-flex align-items-center">
                                            {{ $model->department->name??'-' }}
                                        </span>
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
                                            <a href="{{ route('announcements.restore', $model->id) }}" class="btn btn-icon btn-label-info waves-effect">
                                                <span>
                                                    <i class="ti ti-refresh ti-xs"></i>
                                                </span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="8">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-6 ps-0">
                                            <div class="dataTables_info" id="DataTables_Table_0_info" role="status" aria-live="polite">Showing 1 to {{$data['models']->count()}} of {{$data['models']->count()}} entries</div>
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
@endsection
@push('js')
    <script>
        setTimeout(function() {
            $('#message-alert').fadeOut('slow');
        }, 2000);
    </script>
@endpush
