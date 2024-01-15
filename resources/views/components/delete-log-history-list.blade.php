@extends('admin.layouts.app')
@section('title', $title.' - '. appName())
@section('content')
<input type="hidden" id="page_url" value="{{ route('logs.showJsonData', [request()->model,request()->id,isset(request()->title) && !empty(request()->title) ? Str::slug(request()->title) : '']) }}">
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card mb-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="card-header">
                        <h4 class="fw-bold mb-0"><span class="text-muted fw-light">Home /</span> {{ $title }}</h4>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex justify-content-end align-item-center">
                        <div class="dt-buttons btn-group flex-wrap">
                            <button data-toggle="tooltip" data-placement="top" title="Refresh " type="button" class="btn btn-secondary add-new btn-primary me-3" id="refresh-btn" data-url="{{ route('logs.showJsonData', [request()->model,request()->id,isset(request()->title) && !empty(request()->title) ? Str::slug(request()->title) : '']) }}">
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

        <!-- Delete & Restore Logs List Table -->
        <div class="card">
            <div class="card-datatable">
                <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                    <div class="container">
                        <table class="dt-row-grouping table dataTable dtr-column border-top table-border data_table table-responsive table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">User</th>
                                    {{--  <th scope="col">Model</th>  --}}
                                    <th scope="col">Type</th>
                                    <th scope="col">Remarks</th>
                                    <th scope="col">IP</th>
                                    <th scope="col">Action Date</th>
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


@endsection
@push('js')
<script type="text/javascript">
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
            ajax: page_url + "?loaddata=yes",
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
               //    name: 'model'
               // },
                {
                    data: 'type',
                    name: 'type'
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
                    data: 'action date',
                    name: 'action date'
                }
            ]
        });
    }
</script>
@endpush
