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
                <div class="row align-items-center">
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
                        <a href="javascript:;" data-toggle="tooltip" data-placement="top" title="View All Assets" id="refresh-btn" class="btn btn-success btn-primary me-3">
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
                        <div class="card-body">
                            {{-- <div class="row">
                                <div class="col-md-6">
                                    <label for="">ID:</label>
                                    <span>{{$show->id ?? ''}} </span>
                                </div>
                                <div class="col-md-6">
                                    <label for="">Name:</label>
                                    <span>{{$show->name ?? ''}} </span>
                                </div>
                            </div> --}}
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <label for="" class="mb-2">Created By:</label>
                                    {!! !empty($show->createdBy) ? userWithHtml($show->createdBy) : "-" !!}
                                </div>
                                <div class="col-md-6">
                                    <label for="" class="mb-2">Created At:</label>
                                    <span class="d-block">{{formatDateTime($show->created_at)}} </span>
                                </div>
                            </div>
                            <!-- <table class="table table-responsive">
                                <tr>
                                    <td class=" border-top-0" width="150" style="font-weight:bold;">ID</td>
                                    <td class="border-top-0">{{ $show->id }}</td>
                                </tr>
                                <tr>
                                    <td style="font-weight:bold;">Name</td>
                                    <td>{{ $show->name ?? "-" }}</td>
                                </tr>
                                <tr>
                                    <td style="font-weight:bold;">Created</td>
                                    <td>{{ $show->created_at->format('F j, Y H:i A') }}</td>
                                </tr>
                            </table> -->
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- details -->



        <br>
        <!-- asset childs -->
        <div class="card">
            <div class="card-datatable">
                <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                    <div class="container">
                        <table class="dt-row-grouping table dataTable dtr-column border-top table-border data_table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">UID</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Current Assignee</th>
                                    <th scope="col">Damage Status</th>
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
        <!-- asset childs -->



    </div>
</div>
<div class="modal fade" id="offcanvasAddAnnouncement" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered1 modal-simple modal-add-new-cc">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-4">
                    <h3 class="mb-2" id="modal-label"></h3>
                </div>
                <form id="create-form" class="row g-3" data-method="" data-modal-id="offcanvasAddAnnouncement">
                    @csrf
                    <span id="edit-content">
                    </span>
                    <div class="col-12 mt-3 action-btn" style="text-align: center;">
                        <div class="demo-inline-spacing sub-btn">
                            <button type="submit" class="btn btn-primary me-sm-3 me-1 submitBtn">Submit</button>
                            <button type="reset" class="btn btn-label-secondary btn-reset" data-bs-dismiss="modal" aria-label="Close">
                                Cancel
                            </button>
                        </div>
                        <div class="demo-inline-spacing loading-btn" style="display: none;">
                            <button class="btn btn-primary waves-effect waves-light" type="button" disabled="">
                                <span class="spinner-border me-1" role="status" aria-hidden="true"></span>
                                Loading...
                            </button>
                            <button type="reset" class="btn btn-label-secondary btn-reset" data-bs-dismiss="modal" aria-label="Close">
                                Cancel
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- unassign modal -->
<div class="modal fade" id="unassignModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered1 modal-simple modal-add-new-cc">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-4">
                    <h3 class="mb-2" id="modal-label">Un Assign</h3>
                </div>
                <span id="unassign-content">
                </span>
                <div class="col-12 mt-3 action-btn" style="text-align: center;">
                    <div class="demo-inline-spacing sub-btn">
                        <button type="button" class="btn btn-primary me-sm-3 me-1 unassignSubmitBtn"> <span class="spinner-border me-1 d-none spinner" role="status" aria-hidden="true"></span> Submit</a>
                            <button type="reset" class="btn btn-label-secondary btn-reset" data-bs-dismiss="modal" aria-label="Close">
                                Cancel
                            </button>
                    </div>
                    <!-- <div class="demo-inline-spacing loading-btn" style="display: none;">
                        <button class="btn btn-primary waves-effect waves-light" type="button" disabled="">
                            <span class="spinner-border me-1" role="status" aria-hidden="true"></span>
                            Loading...
                        </button>
                        <button type="reset" class="btn btn-label-secondary btn-reset" data-bs-dismiss="modal" aria-label="Close">
                            Cancel
                        </button>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- unassign modal -->

<!-- unassign modal -->
<div class="modal fade" id="damageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered1 modal-simple modal-add-new-cc">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-4">
                    <h3 class="mb-2" id="modal-label">Mark as Damage</h3>
                </div>
                <span id="unassign-content">
                    <input type="hidden" name="" id="asset_detail_id">
                    <textarea name="" id="damage_remarks" cols="30" rows="5" class="form-control"></textarea>
                </span>
                <div class="col-12 mt-3 action-btn" style="text-align: center;">
                    <div class="demo-inline-spacing sub-btn">
                        <button type="button" class="btn btn-primary me-sm-3 me-1 marked-as-damage-submit">
                            <span class="spinner-border me-1 d-none spinner" role="status" aria-hidden="true"></span> Submit</a>
                            <button type="reset" class="btn btn-label-secondary btn-reset" data-bs-dismiss="modal" aria-label="Close">
                                Cancel
                            </button>
                    </div>
                    <!-- <div class="demo-inline-spacing loading-btn" style="display: none;">
                        <button class="btn btn-primary waves-effect waves-light" type="button" disabled="">
                            <span class="spinner-border me-1" role="status" aria-hidden="true"></span>
                            Loading...
                        </button>
                        <button type="reset" class="btn btn-label-secondary btn-reset" data-bs-dismiss="modal" aria-label="Close">
                            Cancel
                        </button>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- unassign modal -->

<!-- view assignee history modal -->
<input type="hidden" name="" id="getAssetDetail" value="{{route('assets.getChildAsset' , $show->id)}}">
@endsection
@push("js")
<script>
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
        var page_url = $("#getAssetDetail").val();
        var table = $('.data_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: page_url,
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'uid',
                    name: 'uid'
                },

                {
                    data: 'price',
                    name: 'price'
                },
                {
                    data: 'assignee',
                    name: 'assignee'
                },
                {
                    data: 'damage',
                    name: 'damage'
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

    //Open modal to assign asset to a user
    $(document).on('click', '.assign-asset', function() {
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
    //Open modal to assign asset to a user

    // unassign function
    $(document).on("click", ".un-assign", function() {

        var id = $(this).attr("data-id");
        $("#unassignModal").modal("show");
        $.ajax({
            url: "{{route('assets.unassignModal')}}",
            type: 'GET',
            data: {
                id: id,
            },
            beforeSend: function() {
                $("#unassign-content").empty()
            },
            success: function(res) {
                $("#unassign-content").html(res)
            }
        });
    })

    // unassign  submit function
    $(document).on("click", ".unassignSubmitBtn", function() {
        if ($(".spinner").hasClass("d-none")) {
            $(".spinner").removeClass("d-none")
        }
        $(".unassignSubmitBtn").attr("disabled", true)
        var id = $("#unassign_asset_id").val();
        var remarks = $("#unassign_remarks").val();
        var damage = $("#is_damage").val();
        if (!remarks) {
            var remarks = "";
        }
        $.ajax({
            url: "{{route('assets.unassignedFromEmployee')}}",
            method: "POST",
            data: {
                _token: "{{csrf_token()}}",
                id: id,
                remarks: remarks,
                damage: damage,
            },
            beforeSend: function() {
                $(this).parents('.action-btn').find('.sub-btn').hide();
                $(this).parents('.action-btn').find('.loading-btn').show();
            },
            success: function(res) {
                if (res.success == true) {
                    $("#unassignModal").modal("hide");
                    loadPageData()
                }
                toastr.success(res.message, 'Success', {
                    timeOut: 1000
                });

                if (!$(".spinner").hasClass("d-none")) {
                    $(".spinner").addClass("d-none")
                }
                $(".unassignSubmitBtn").attr("disabled", false)
            },
            error: function(xhr, status, error) {
                if (!$(".spinner").hasClass("d-none")) {
                    $(".spinner").addClass("d-none")
                }
                $(".unassignSubmitBtn").attr("disabled", false)
            }
        });

    });
    // unassign function

    // mark-as-damage
    $(document).on("click", ".mark-as-damage", function() {
        $("#asset_detail_id").val("");
        $("#damage_remarks").val("");
        $("#damageModal").modal("show")
        var assetDetailId = $(this).attr("data-asset-detail-id")
        if (assetDetailId) {
            $("#asset_detail_id").val(assetDetailId)
        }
    });
    $(document).on("click", ".marked-as-damage-submit", function() {
        var id = $("#asset_detail_id").val();
        var remarks = $("#damage_remarks").val();
        if (!id) {
            showError("Invalid Asset");
            return false;
        }
        $.ajax({
            url: "{{route('assets.markAsDamage')}}",
            method: "POST",
            data: {
                _token: "{{csrf_token()}}",
                id: id,
                remarks: remarks,
            },
            beforeSend: function() {},
            success: function(res) {
                if (res.success == true) {
                    $("#damageModal").modal("hide");
                    loadPageData()
                }
                toastr.success(res.message, 'Success', {
                    timeOut: 1000
                });
                if (!$(".spinner").hasClass("d-none")) {
                    $(".spinner").addClass("d-none")
                }
                $(".marked-as-damage-submit").attr("disabled", false)
            },
            error: function(xhr, status, error) {
                if (!$(".spinner").hasClass("d-none")) {
                    $(".spinner").addClass("d-none")
                }
                $(".marked-as-damage-submit").attr("disabled", false)
            }
        });
    });

    // mark-as-damage
</script>

@endpush