@extends('admin.layouts.app')
@section('title', $title.' - '. appName())
@section('content')
<input type="hidden" id="page_url" value="{{ route('assets.index') }}">
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card mb-4">
            <div class="row ">
                <div class="col-md-6">
                    <div class="card-header">
                        <h4 class="fw-bold mb-0"><span class="text-muted fw-light">Home /</span> {{ $title }}</h4>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex justify-content-end align-item-center mt-4">
                        <div class="dt-buttons btn-group flex-wrap">
                            <a data-toggle="tooltip" data-placement="top" title="Show All Records" href="{{ route('assets.index') }}" class="btn btn-success btn-primary mx-3">
                                <span>
                                    <i class="ti ti-eye me-0 me-sm-1 ti-xs"></i>
                                    <span class="d-none d-sm-inline-block">View All Records</span>
                                </span>
                            </a>
                        </div>
                        <div class="dt-buttons btn-group flex-wrap">
                            <button data-toggle="tooltip" data-placement="top" title="Refresh " type="button" class="btn btn-secondary add-new btn-primary mx-3" id="refresh-btn" data-url="{{ route('assets.index') }}">
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

        <div class="card">
            <div class="card-datatable">
                <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                    <div class="container">
                        <table class="dt-row-grouping table dataTable dtr-column border-top table-border data_table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Category</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Available Quantity</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Actions</th>
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
<div class="modal fade" id="details-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered1 modal-simple modal-add-new-cc">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-4">
                    <h3 class="mb-2" id="modal-label"></h3>
                </div>

                <div class="col-12">
                    <span id="show-content"></span>
                </div>

                <div class="col-12 mt-3 text-end" style="text-align: center;">
                    <button type="reset" class="btn btn-label-primary btn-reset" data-bs-dismiss="modal" aria-label="Close">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
    $(document).on("click", "#refresh-btn", function() {
        loadPageData()
    });

    $(document).ready(function() {
        loadPageData()
    });
    //Open modal to add more quantity
    $(document).on('click', '.add-more-btn', function() {
        var url = $(this).attr('data-url');
        var modal_label = $(this).attr('title');
        $('#modal-label').html(modal_label);
        $("#create-form").attr("action", url);
        $("#create-form").attr("data-method", 'POST');
        var edit_url = $(this).attr('data-edit-url');
        $.ajax({
            url: edit_url,
            method: 'GET',
            beforeSend: function() {
                // var spinner = '<div class="col-md-12" style="width: 50px !important;height: 50px !important;color: #e30b5c;font-weight: bold;font-size: 21px;margin-top: 20px;margin-bottom: 20px;"></span></div>';
                // $('#edit-content').html(spinner)
                $('#edit-content').empty()
            },
            success: function(response) {
                $('#edit-content').html(response);
                $('.select2').select2({
                    dropdownParent: $('#offcanvasAddAnnouncement')
                });
            }
        });
    });

    $(document).on("click", ".restore-btn", function() {

        var id = $(this).attr("data-id");
        var route = $(this).attr("data-route");
        console.log(route)
        $.ajax({
            url: route,
            method: 'GET',
            data: {
                id: id,
            },

            success: function(response) {
                var table = $('.data_table').DataTable();
                toastr.success('You have restored asset successfully.');
                table.ajax.reload(null, false)
            }
        });
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
                    data: 'category',
                    name: 'category'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'quantity',
                    name: 'quantity'
                },
                {
                    data: 'date',
                    name: 'date'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ]
        });
    }
</script>

<script>

</script>
@endpush