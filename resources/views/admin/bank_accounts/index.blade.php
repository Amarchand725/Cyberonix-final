@extends('admin.layouts.app')
@section('title', $title.' - ' . appName())

@section('content')
<input type="hidden" id="page_url" value="{{ route('bank_accounts.index') }}">
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card mb-4">
            <div class="row">
                <div class="col-md-4">
                    <div class="card-header">
                        <h4 class="fw-bold mb-0"><span class="text-muted fw-light">Home /</span> {{ $title }}</h4>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="d-flex justify-content-end align-item-center mt-4">
                        <div class="dt-buttons flex-wrap">
                            @if(count($model) > 0)
                            @can('export_bank_account-create')
                            <a data-toggle="tooltip" data-placement="top" title="Export Bank Accounts" href="{{ route('bank_accounts.export.pdf') }}" class="btn btn-label-primary me-3">
                                <span>
                                    <i class="fa fa-file-pdf me-0 me-sm-1 ti-xs"></i>
                                    <span class="d-none d-sm-inline-block">Export as PDF </span>
                                </span>
                            </a>
                            <a data-toggle="tooltip" data-placement="top" title="Export Bank Accounts" href="{{ route('bank_accounts.export.excel') }}" class="btn btn-label-success me-3">
                                <span>
                                    <i class="fa fa-file-excel me-0 me-sm-1 ti-xs"></i>
                                    <span class="d-none d-sm-inline-block">Export as Excel </span>
                                </span>
                            </a>
                            @endcan
                            @endif
                            @can("bank_accounts-import")
                            <a data-toggle="tooltip" data-placement="top" title="Import Bank Accounts" href="javascript:;" class="btn btn-label-success me-3 import_bank_accounts">
                                <span>
                                    <i class="fa fa-file-excel me-0 me-sm-1 ti-xs"></i>
                                    <span class="d-none d-sm-inline-block">Import CSV</span>
                                </span>
                            </a>
                            @endcan
                            @can('bank_accounts-create')
                            <a data-toggle="tooltip" data-placement="top" title="Export Bank Accounts" href="{{ route('bank_accounts.create') }}" class="btn btn-primary me-3">
                                <span>
                                    <i class="fa fa-plus me-0 me-sm-1 ti-xs"></i>
                                    <span class="d-none d-sm-inline-block">Add New </span>
                                </span>
                            </a>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Users List Table -->
        <div class="card">
            <div class="card-datatable table-responsive">
                <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                    <div class="container">
                        <table class="dt-row-grouping table dataTable dtr-column table table-border border-top data_table table-responsive">
                            <thead>
                                <tr>
                                    <th>S.No#</th>
                                    <th>Employee</th>
                                    <th style="width:25%;">Bank</th>
                                    <th>Title</th>
                                    <th>Account</th>
                                    <th aria-label="Role: activate to sort column ascending">Created At</th>
                                    <th>Status</th>
                                    <th>Upload From Excel</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="body"></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- View Bank Details Modal -->
            <div class="modal fade" id="dept-details-modal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered1 modal-simple modal-add-new-cc">
                    <div class="modal-content p-3 p-md-5">
                        <div class="modal-body p-0">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            <div class="text-center mb-4">
                                <h3 class="mb-2" id="modal-label"></h3>
                            </div>

                            <div class="col-12">
                                <span id="show-content"></span>
                            </div>

                            <div class="col-12 mt-3 text-end">
                                <button type="reset" class="btn btn-label-primary btn-reset" data-bs-dismiss="modal" aria-label="Close">
                                    Close
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- View Bank Details Modal -->
        </div>
    </div>
</div>



<!-- import-bank-modals -->
<div class="modal fade" id="import-bank-modals" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered1 modal-simple modal-add-new-cc">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <h3 class="mb-2" id="modal-label">Import CSV</h3>
            </div>
            <form id="importBankAccounts" method="post" enctype="multipart/form-data" class="row gy-1 gx-2">
                @csrf
                <div class="modal-body">
                    <div class="col-12 col-md-12">
                        <label class="form-label" for="files">Files (CSV)</label>
                        <input type="file" id="file" name="file" class="form-control file_input" accept=".csv" />
                        <span class="text-danger file_input_error"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-12 mt-3" style="text-align: center;">
                        <button type="submit" class="btn btn-label-success  submitBtn" aria-label="Close">
                            <span class="spinner-border  submitBtnSpinner  d-none me-1" role="status" aria-hidden="true"></span>
                            Import
                        </button>
                        <button type="reset" class="btn btn-label-primary btn-reset" data-bs-dismiss="modal" aria-label="Close">
                            Cancel
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- import-bank-modals -->


@endsection
@push('js')
<script>
    $(document).ready(function() {
        loadPageData()
    });
    //datatable
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
                    data: 'user_id',
                    name: 'user_id'
                },
                {
                    data: 'bank_name',
                    name: 'bank_name'
                },
                {
                    data: 'title',
                    name: 'title'
                },
                {
                    data: 'account',
                    name: 'account'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'upload_from_excel',
                    name: 'upload_from_excel'
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
    $(document).on("click", ".import_bank_accounts", function() {
        $("#import-bank-modals").modal("show")
    });



    $(document).ready(function() {

        $('.submitBtn').click(function() {
            $(".file_input_error").html("")
            // Get the selected file
            var files = $('#file')[0].files;

            if (files.length > 0) {
                var fd = new FormData();

                // Append data
                fd.append('file', files[0]);
                fd.append('_token', "{{csrf_token()}}");

                // AJAX request
                $.ajax({
                    url: "{{ route('bank_accounts.import') }}",
                    method: 'post',
                    data: fd,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    beforeSend: function() {
                        if ($(".submitBtnSpinner").hasClass('d-none')) {
                            $(".submitBtnSpinner").removeClass('d-none')
                        }
                        $(".submitBtn").prop('disabled', true);
                    },
                    success: function(res) {
                        loadPageData()
                        $(".file_input").val("")
                        showSuccess(res.message)
                        $("#import-bank-modals").modal("hide")
                        $(".submitBtn").prop('disabled', false);

                        if (!$(".submitBtnSpinner").hasClass('d-none')) {
                            $(".submitBtnSpinner").addClass('d-none')
                        }
                    },
                    error: function(xhr, status, error) {
                        $(".file_input_error").html(error)
                        $(".submitBtn").prop('disabled', false);
                        if (!$(".submitBtnSpinner").hasClass('d-none')) {
                            $(".submitBtnSpinner").addClass('d-none')
                        }
                    }
                });
            } else {
                $(".file_input_error").html("Please select a file")

            }

        });
    });
</script>
@endpush
