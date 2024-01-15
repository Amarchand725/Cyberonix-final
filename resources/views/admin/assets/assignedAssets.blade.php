@extends('admin.layouts.app')
@section('title', $title.' - '. appName())
@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card mb-4">
            <div class="row ">
                <div class="col-md-6">
                    <div class="card-header">
                        <h4 class="fw-bold mb-0"><span class="text-muted fw-light">Home /</span> {{ $title }}</h4>
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
                                    <th scope="col">Assigned By</th>
                                    <th scope="col">Assigned tO</th>
                                    <th scope="col">Assigned Date</th>
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
<input type="hidden" id="page_url" value="{{ route('assets.assignedList') }}">
@endsection
@push("js")
<script src="{{ asset('public/admin/assets/js/dashboards-analytics.js') }}" defer></script>
<script>
    var table = $('.data_table').DataTable();
    if ($.fn.DataTable.isDataTable('.data_table')) {
        table.destroy();
    }
    $(document).ready(function() {
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
                    data: 'assignBy',
                    name: 'assignBy'
                },

                {
                    data: 'assignee',
                    name: 'assignee'
                },

                {
                    data: 'date',
                    name: 'date'
                },
                {
                    data: 'action',
                    name: 'action'
                },
            ]
        });
    });
    $(document).on("click", ".un-assign", function() {
        var delete_url = $(this).attr('data-del-url');
        console.log("work");
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: delete_url,
                    type: 'GET',
                    success: function(response) {
                        if (response) {
                            toastr.success('You have deleted record successfully.');
                            var oTable = $('.data_table').dataTable();
                            oTable.fnDraw(false);
                        } else {
                            toastr.error('Sorry something went wrong.');
                        }
                    }
                });
            }
        })
    })
</script>
@endpush