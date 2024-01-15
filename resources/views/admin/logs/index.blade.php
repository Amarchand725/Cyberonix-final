@extends('admin.layouts.app')
@section('title', $title . ' -  ' . appName())

@section('content')
    <input type="hidden" id="page_url" value="{{ route(Route::currentRouteName()) }}">

    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="card">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card-header">
                            <h4 class="fw-bold mb-0"><span class="text-muted fw-light">Home /</span> {{ $title }}</h4>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-end align-item-center mt-4">
                            <div class="col-md-6">
                                <div class="d-flex justify-content-end align-item-center">
                                    <div class="dt-buttons btn-group flex-wrap">
                                        <button data-toggle="tooltip" data-placement="top" title="Refresh " type="button"
                                            class="btn btn-secondary add-new btn-primary me-3" id="refresh-btn"
                                            data-url="{{ route('logs.index') }}">
                                            <span>
                                                <i class="ti ti-refresh ti-sm"></i>
                                                <span class="d-none d-sm-inline-block">Refresh Records</span>
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row p-3">
                    <div class="col-md-4 mb-3">
                        <label for="">Model Name</label>
                        <select name="model_name" id="model_name" class="select2 form-select model_name">
                            <option value="all">All</option>
                            @if (!empty(getLogModel()) && count(getLogModel()) > 0)
                                @foreach (getLogModel() as $model)
                                    <option value="{{ getModel($model->model_name) ?? '' }}">{{ getModel($model->model_name, true) ?? '' }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="">type</label>
                        <select name="log_type" id="log_type" class="select2 form-select log_type">
                            <option value="all">All</option>
                            @if (!empty(getLogType()) && count(getLogType()) > 0)
                                @foreach (getLogType() as $type)
                                    <option value="{{ $type->id ?? '' }}">{{ $type->name ?? '' }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
            </div>

            <!-- Logs List Table -->
            <div class="card mt-4">
                <div class="card-datatable table-responsive">
                    <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                        <div class="container">
                            <table
                                class="dt-row-grouping table dataTable dtr-column border-top table-border data_table table-responsive">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">User</th>
                                        {{--  <th scope="col">Model</th>  --}}
                                        <th scope="col">Data</th>
                                        <th scope="col">Type</th>
                                        <th scope="col">Event</th>
                                        <th scope="col">Remarks</th>
                                        <th scope="col">IP</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="body"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- view documents modal --}}
    @include('admin.profile.documents.partials.viewModal')
    {{-- view documents modal --}}
@endsection
@push('js')
    <script>
        $(document).on("change", ".model_name", function() {
            var id = $(this).val();
            loadPageData()
        });
        $(document).on("change", ".log_type", function() {
            var id = $(this).val();
            loadPageData()
        });

        //datatable
        $(document).on("click", "#refresh-btn", function() {
            loadPageData()
        });

        $(document).ready(function() {
            loadPageData()
        });

        function loadPageData() {
            var table = $('.data_table').DataTable();
            if ($.fn.DataTable.isDataTable('.data_table')) {
                table.destroy();
            }

            var page_url = $('#page_url').val();
            var table = $('.data_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: page_url + "?loaddata=yes",
                    type: "GET",
                    data: function(d) {
                        d.model_name = $('.model_name').val();
                        d.log_type = $('.log_type').val();
                        d.search = $('input[type="search"]').val();
                    },
                    error: function(xhr, error, code) {
                        console.log(xhr);
                        console.log(error);
                        console.log(code);
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'user',
                        name: 'user'
                    },
                   // {
                   //    data: 'model',
                   //     name: 'model'
                   // },
                    {
                        data: 'data',
                        name: 'data'
                    },
                    {
                        data: 'type',
                        name: 'type'
                    },
                    {
                        data: 'event_id',
                        name: 'event_id'
                    },
                    {
                        data: 'remarks',
                        name: 'remarks'
                    },
                    {
                        data: 'ip',
                        name: 'ip'
                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    }
                ]
            });
        }
        //datatable
    </script>
@endpush
