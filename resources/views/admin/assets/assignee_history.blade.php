@extends('admin.layouts.app')
@section('title', $title.' - '. appName())
@push("styles")
<style>
    label {
        font-size: 16px;
        font-weight: bold;
        margin-right: 5px;
    }

    .modal-body {
        overflow-x: auto;
    }
</style>
@endpush
@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card mb-4">
            <div class="card-header">
                <div class="row ">
                    <div class="col-md-6">
                        <h4 class="fw-bold mb-0"><span class="text-muted fw-light">Assets /</span> {{ $title }}</h4>
                    </div>
                    <div class="col-md-6" style="text-align: right;">
                        <a data-toggle="tooltip" data-placement="top" title="View All Assets" href="{{ route('assets.index') }}" class="btn btn-success btn-primary mx-3">
                            <span>
                                <i class="ti ti-list me-0 me-sm-1 ti-xs"></i>
                                <span class="d-none d-sm-inline-block">View Assets</span>
                            </span>
                        </a>
                        <a href="javascript:;" data-toggle="tooltip" data-placement="top" title="View All Assets" id="refresh-btn" class="btn btn-success btn-primary mx-3">
                            <span>
                                <i class="ti ti-refresh ti-sm"></i>
                                <span class="d-none d-sm-inline-block">Refresh Records</span>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- details -->
        <section id="multiple-column-form">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                        </div>
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <label for="">ID:</label>
                                    <span>{{$show->id ?? ''}} </span>
                                </div>
                                <div class="col-md-6">
                                    <label for="">Asset:</label>
                                    <span>{{$show->asset->name ?? ''}} </span>
                                </div>
                            </div>
                            <div class="row mt-4 align-items-center">
                                <div class="col-md-6">
                                    <label for="" class="mb-2">Created By:</label>
                                    {!! !empty($show->asset->createdBy) ? userWithHtml($show->asset->createdBy) : "-" !!}
                                </div>
                                <div class="col-md-6">
                                    <label for="">Created At:</label>
                                    <span class="d-block">{{!empty($show->created_at) ? formatDateTime($show->created_at) : "-"}} </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- details -->

        <br>

        <!-- asset childs -->
        <div class="card ">
            <div class="card-datatable">
                <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                    <div class="container">
                        <table class="dt-row-grouping table dataTable dtr-column border-top table-border logs_data_table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Assignee</th>
                                    <th scope="col" style="width: 238px;">Assign by</th>
                                    <th scope="col">From</th>
                                    <th scope="col">To</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Damage</th>
                                    <th scope="col">Last Remarks</th>
                                    <th scope="col">Last Updated</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody id="body"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- asset childs -->

    </div>
</div>
<!-- asset time line modal -->
<div class="modal fade" id="assetTimeline" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered1 modal-simple modal-add-new-cc">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-4">
                    <h3 class="mb-2" id="modal-label">Timeline</h3>
                </div>
            </div>
            <div class="modal-body" id="timeline-content">

            </div>
            <div class="modal-footer">
                <div class="col-12 mt-3 action-btn" style="text-align: center;">
                    <div class="demo-inline-spacing sub-btn">
                        <button type="reset" class="btn btn-label-secondary btn-reset" data-bs-dismiss="modal" aria-label="Close">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- asset time line modal -->


<input type="hidden" name="" id="pageUrl" value="{{route('assets.assetAssigneeLogsList' , $show->id)}}">
@endsection
@push("js")

<script>
    $(document).on("click", "#refresh-btn", function() {
        loadPageData()
    });

    $(document).ready(function() {
        loadPageData()
    });


    // timeline button
    $(document).on("click", ".asset-user-timeline", function() {
        var route = $(this).attr("data-route");

        $.ajax({
            url: route,
            type: 'GET',
            beforeSend: function() {
                $("#timeline-content").empty()
            },
            success: function(res) {
                if (res == false) {
                    showError("Timeline not found!");
                } else {
                    $("#assetTimeline").modal("show");
                    $("#timeline-content").html(res)
                }
            }
        });
    })


    function loadPageData() {
        var table = $('.logs_data_table').DataTable();
        if ($.fn.DataTable.isDataTable('.logs_data_table')) {
            table.destroy();
        }
        var page_url = $("#pageUrl").val();
        var table = $('.logs_data_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: page_url,
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    // orderable: false,
                    searchable: false
                },
                {
                    data: 'user',
                    name: 'user',
                    orderable: false,
                    searchable: false
                },

                {
                    data: 'assignBy',
                    name: 'assignBy',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'effective_date',
                    name: 'effective_date',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'end_date',
                    name: 'end_date',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'status',
                    name: 'status',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'is_damage',
                    name: 'is_damage',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'remarks',
                    name: 'remarks',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'last_updated',
                    name: 'last_updated',
                    orderable: false,
                    searchable: false
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
@endpush