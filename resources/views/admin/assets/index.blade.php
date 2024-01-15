@extends('admin.layouts.app')
@section('title', $title.' - '. appName())
@section('content')
@if(isset($trashed) && !empty($trashed) && $trashed == true)
<input type="hidden" id="page_url" value="{{ route('assets.trashed') }}">
@else
<input type="hidden" id="page_url" value="{{ route('assets.index') }}">
@endif
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card mb-4">
            <div class="card-header">

                <div class="row align-items-center">
                    <div class="col-md-4">
                        {{-- <div class="card-header"> --}}
                            <h4 class="fw-bold mb-0"><span class="text-muted fw-light">Home /</span> {{ $title }}</h4>
                        {{-- </div> --}}
                    </div>
                    <div class="col-md-8 mt-md-0 mt-3">
                        <div class="d-flex justify-content-end align-item-center">
                            @if(isset($trashed) && !empty($trashed) && $trashed == true)
                            <div class="dt-buttons btn-group flex-wrap">
                                <a data-toggle="tooltip" data-placement="top" title="Show All Records" href="{{ route('assets.index') }}" class="btn btn-success btn-primary mx-3">
                                    <span>
                                        <i class="ti ti-eye me-0 me-sm-1 ti-xs"></i>
                                        <span class="d-none d-sm-inline-block">View All Records</span>
                                    </span>
                                </a>
                            </div>
                            @else
                            <div class="dt-buttons flex-wrap">
                                <a data-toggle="tooltip" data-placement="top" title="All Trashed Records" href="{{ route('assets.trashed') }}" class="btn btn-label-danger mx-1">
                                    <span>
                                        <i class="ti ti-trash me-0 me-sm-1 ti-xs"></i>
                                        <span class="d-none d-sm-inline-block">All Trashed Records </span>
                                    </span>
                                </a>
                            </div>
                            <div class="dt-buttons btn-group flex-wrap">
                                <button data-toggle="tooltip" data-placement="top" title="Add an asset" type="button" class="btn btn-secondary add-new btn-primary mx-3" id="add-btn" data-url="{{ route('assets.store') }}" tabindex="0" aria-controls="DataTables_Table_0" type="button" data-bs-toggle="modal" data-bs-target="#offcanvasAddAnnouncement">
                                    <span>
                                        <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>
                                        <span class="d-none d-sm-inline-block">Add New</span>
                                    </span>
                                </button>
                            </div>
                            @endif
                            <div class="dt-buttons btn-group flex-wrap">
                                <button data-toggle="tooltip" data-placement="top" title="Refresh " type="button" class="btn btn-secondary add-new btn-primary me-3" id="refresh-btn" data-url="{{ route('assets.index') }}">
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
        @if(!isset($trashed) || empty($trashed) && $trashed != true)
        <div class="row">
            <!-- Donut Chart -->
            <div class="col-lg-3 col-md-3 col-12 mb-md-4 mt-md-0 mt-4">
              <div class="card">
                <div class="card-body">
                  <div id="assetsChart"></div>
                  <div id="totalSummary" data-count="{{isset($total) && count($total) > 0 ? count($total) : 0}}"></div>
                </div>
              </div>
            </div>
            <!-- /Donut Chart -->

            <!-- total -->
            {{--  <div class="col-lg-3 col-md-3 col-12 mb-md-4 mt-md-0 mt-4">
                <a href="javascript:;">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-icon">
                                <span class="badge bg-label-danger rounded-pill p-2">
                                    <i class="ti ti-credit-card ti-sm"></i>
                                </span>
                            </div>
                            <h5 class="card-title mb-0 mt-2 h6 text-truncate">Available Assets</h5>
                            <small class="text-muted">{{isset($total) && count($total) > 0 ? count($total) : 0}} Total</small>
                        </div>

                        <div id="totalSummary" data-count="{{isset($total) && count($total) > 0 ? count($total) : 0}}" data-total-summary="{{json_encode($total)}}"></div>
                    </div>
                </a>
            </div>  --}}
            <!-- total -->
            <!-- assigned -->
            <div class="col-lg-3 col-md-3 col-12 mb-md-4 mt-md-0 mt-4">
                <a href="javascript:;">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-icon">
                                <span class="badge bg-label-danger rounded-pill p-2">
                                    <i class="ti ti-credit-card ti-sm"></i>
                                </span>
                            </div>
                            <h5 class="card-title mb-0 mt-2 h6 text-truncate">Assigned Assets</h5>
                            <small class="text-muted">{{isset($assignedCount) && count($assignedCount) > 0 ? count($assignedCount) : 0}} Assigned</small>
                        </div>
                        <div id="assignedSummary" data-count="{{isset($assignedCount) && count($assignedCount) > 0 ? count($assignedCount) : 0}}" data-assigned-summary="{{ json_encode($assignedCount) }}"></div>
                    </div>
                </a>
            </div>
            <!-- assigned -->
            <!-- un assigned -->
            <div class="col-lg-3 col-md-3 col-12 mb-md-4 mt-md-0 mt-4">
                <a href="javascript:;">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-icon">
                                <span class="badge bg-label-danger rounded-pill p-2">
                                    <i class="ti ti-credit-card ti-sm"></i>
                                </span>
                            </div>
                            <h5 class="card-title mb-0 mt-2 h6 text-truncate">Un Assigned Assets</h5>
                            <small class="text-muted">{{isset($unassigned) && count($unassigned) > 0 ? count($unassigned) : 0}} Un Assigned</small>
                        </div>
                        <div id="unAssignedSummary" data-count="{{isset($unassigned) && count($unassigned) > 0 ? count($unassigned) : 0}}" data-unassigned-summary="{{ json_encode($unassigned) }}"></div>
                    </div>
                </a>
            </div>
            <!-- un assigned -->

            <!-- damage -->
            <div class="col-lg-3 col-md-3 col-12 mb-md-4 mt-md-0 mt-4">
                <a href="javascript:;">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-icon">
                                <span class="badge bg-label-danger rounded-pill p-2">
                                    <i class="ti ti-credit-card ti-sm"></i>
                                </span>
                            </div>
                            <h5 class="card-title mb-0 mt-2 h6 text-truncate">Damage Assets</h5>
                            <small class="text-muted">{{isset($damageCount) && count($damageCount) > 0 ? count($damageCount) : 0}} damage</small>
                        </div>
                        <div id="damageSummary" data-count="{{isset($damageCount) && count($damageCount) > 0 ? count($damageCount) : 0}}" data-damage-summary="{{ json_encode($damageCount) }}"></div>
                    </div>
                </a>
            </div>
            <!-- damage -->


        </div>
        @endif



        <!-- Users List Table -->
        <div class="card">
            <div class="card-datatable">
                <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                    <div class="container">
                        <table class="dt-row-grouping table dataTable dtr-column border-top table-border data_table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <!-- <th scope="col" class="w-20">UID</th> -->
                                    <th scope="col">Category</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Available Quantity</th>
                                    <th scope="col">Assigned</th>
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

<!-- Add Employment Status Modal -->
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
                        <div class="col-12 col-md-12 mb-2">
                            <label class="form-label" for="title">Category <span class="text-danger">*</span></label>
                            <select name="category_id" id="category_id" class="form-control select2">
                                <option value="">---Select---</option>
                                @if(!empty(categoryList()))
                                @foreach(categoryList() as $category)
                                <option value="{{$category->id ?? ''}}">{{$category->name ?? ''}}</option>
                                @endforeach
                                @endif
                            </select>
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                            <span id="category_id_error" class="text-danger error"></span>
                        </div>


                        <div class="col-12 col-md-12 mb-2">
                            <label class="form-label" for="title">Name <span class="text-danger">*</span></label>
                            <input type="text" id="name" name="name" class="form-control" placeholder="Enter name" />
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                            <span id="name_error" class="text-danger error"></span>
                        </div>

                        <div class="col-12 col-md-12 mb-2">
                            <label class="form-label" for="title">Quantity <span class="text-danger">*</span></label>
                            <input type="number" id="quantity" name="quantity" class="form-control" placeholder="Enter Quantity" min="1" />
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                            <span id="quantity_error" class="text-danger error"></span>
                        </div>

                        <div class="col-12 col-md-12 mb-2">
                            <label class="form-label" for="title">Price <span class="text-danger">*</span></label>
                            <input type="number" id="price" name="price" class="form-control" placeholder="Enter price" min="1" />
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                            <span id="price_error" class="text-danger error"></span>
                        </div>


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
<!--/ Edit Employment Status Modal -->

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
{{--  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>  --}}
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
                // {
                //     data: 'uid',
                //     name: 'uid'
                // },
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
                    data: 'assigned',
                    name: 'assigned'
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

    // Available Summary Chart
    // --------------------------------------------------------------------
    {{--  var total_summery = $('#totalSummary').data('total-summary');
    const totalS1 = document.querySelector('#totalSummary'),
        totalConfig = {
            chart: {
                height: 130,
                type: 'area',
                parentHeightOffset: 0,
                toolbar: {
                    show: false
                },
                sparkline: {
                    enabled: true
                }
            },
            markers: {
                colors: 'transparent',
                strokeColors: 'transparent'
            },
            grid: {
                show: false
            },
            colors: [config.colors.danger],
            fill: {
                type: 'gradient',
                gradient: {
                    shade: '',
                    shadeIntensity: 0.8,
                    opacityFrom: 0.6,
                    opacityTo: 0.1
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                width: 2,
                curve: 'smooth'
            },
            series: [{
                data: total_summery
            }],
            xaxis: {
                show: true,
                lines: {
                    show: false
                },
                labels: {
                    show: true,
                },
                stroke: {
                    width: 0
                },
                axisBorder: {
                    show: false
                }
            },
            yaxis: {
                stroke: {
                    width: 0
                },
                show: false
            },
            tooltip: {
                enabled: false
            }
        };
    if (typeof totalS1 !== undefined && totalS1 !== null) {
        const total = new ApexCharts(totalS1, totalConfig);
        total.render();
    }  --}}


    // Assigned Summary Chart
    // --------------------------------------------------------------------
    var assigned_summery = $('#assignedSummary').data('assigned-summary');
    const assignedS1 = document.querySelector('#assignedSummary'),
        assignedConfig = {
            chart: {
                height: 130,
                type: 'area',
                parentHeightOffset: 0,
                toolbar: {
                    show: false
                },
                sparkline: {
                    enabled: true
                }
            },
            markers: {
                colors: 'transparent',
                strokeColors: 'transparent'
            },
            grid: {
                show: false
            },
            colors: [config.colors.danger],
            fill: {
                type: 'gradient',
                gradient: {
                    shade: '',
                    shadeIntensity: 0.8,
                    opacityFrom: 0.6,
                    opacityTo: 0.1
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                width: 2,
                curve: 'smooth'
            },
            series: [{
                data: assigned_summery
            }],
            xaxis: {
                show: true,
                lines: {
                    show: false
                },
                labels: {
                    show: true,
                },
                stroke: {
                    width: 0
                },
                axisBorder: {
                    show: false
                }
            },
            yaxis: {
                stroke: {
                    width: 0
                },
                show: false
            },
            tooltip: {
                enabled: false
            }
        };
    if (typeof assignedS1 !== undefined && assignedS1 !== null) {
        const assigned = new ApexCharts(assignedS1, assignedConfig);
        assigned.render();
    }


    // unAssigned Summary Chart
    // --------------------------------------------------------------------
    var unassigned_summery = $('#unAssignedSummary').data('unassigned-summary');
    const unassignedS1 = document.querySelector('#unAssignedSummary'),
        unassignedConfig = {
            chart: {
                height: 130,
                type: 'area',
                parentHeightOffset: 0,
                toolbar: {
                    show: false
                },
                sparkline: {
                    enabled: true
                }
            },
            markers: {
                colors: 'transparent',
                strokeColors: 'transparent'
            },
            grid: {
                show: false
            },
            colors: [config.colors.danger],
            fill: {
                type: 'gradient',
                gradient: {
                    shade: '',
                    shadeIntensity: 0.8,
                    opacityFrom: 0.6,
                    opacityTo: 0.1
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                width: 2,
                curve: 'smooth'
            },
            series: [{
                data: unassigned_summery
            }],
            xaxis: {
                show: true,
                lines: {
                    show: false
                },
                labels: {
                    show: true,
                },
                stroke: {
                    width: 0
                },
                axisBorder: {
                    show: false
                }
            },
            yaxis: {
                stroke: {
                    width: 0
                },
                show: false
            },
            tooltip: {
                enabled: false
            }
        };
    if (typeof unassignedS1 !== undefined && unassignedS1 !== null) {
        const unAssigned = new ApexCharts(unassignedS1, unassignedConfig);
        unAssigned.render();
    }

    // Damaged Summary Chart
    // --------------------------------------------------------------------
    var damage_summery = $('#damageSummary').data('damage-summary');
    const damageS1 = document.querySelector('#damageSummary'),
        damageConfig = {
            chart: {
                height: 130,
                type: 'area',
                parentHeightOffset: 0,
                toolbar: {
                    show: false
                },
                sparkline: {
                    enabled: true
                }
            },
            markers: {
                colors: 'transparent',
                strokeColors: 'transparent'
            },
            grid: {
                show: false
            },
            colors: [config.colors.danger],
            fill: {
                type: 'gradient',
                gradient: {
                    shade: '',
                    shadeIntensity: 0.8,
                    opacityFrom: 0.6,
                    opacityTo: 0.1
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                width: 2,
                curve: 'smooth'
            },
            series: [{
                data: damage_summery
            }],
            xaxis: {
                show: true,
                lines: {
                    show: false
                },
                labels: {
                    show: true,
                },
                stroke: {
                    width: 0
                },
                axisBorder: {
                    show: false
                }
            },
            yaxis: {
                stroke: {
                    width: 0
                },
                show: false
            },
            tooltip: {
                enabled: false
            }
        };
    if (typeof damageS1 !== undefined && damageS1 !== null) {
        const damage = new ApexCharts(damageS1, damageConfig);
        damage.render();
    }

</script>

<script>

  // Assets Chart
  // --------------------------------------------------------------------
    cardColor = config.colors.cardColor;
    headingColor = config.colors.headingColor;
    labelColor = config.colors.textMuted;
    legendColor = config.colors.bodyColor;
    borderColor = config.colors.borderColor;

    var avilable_assets = $("#totalSummary").data("count");
    var assigned_assets = $("#assignedSummary").data("count");
    var unassigned_assets = $("#unAssignedSummary").data("count");
    var damage_assets = $("#damageSummary").data("count");
    var array_set = [avilable_assets, assigned_assets, unassigned_assets, damage_assets];

    const assetsChartEl = document.querySelector('#assetsChart'),
    assetsChartConfig = {
        chart: {
        height: 255,
        type: 'donut'
        },
        labels: ['Available', 'Assigned', 'Un Assigned', 'Damage'],
        series: array_set,
        colors: [
        '#fee802',
        '#3fd0bd',
        '#826bf8',
        '#2b9bf4'
        ],
        stroke: {
        show: false,
        curve: 'straight'
        },
        dataLabels: {
        enabled: true,
        formatter: function (val, opt) {
            return parseInt(val, 10) + '%';
        }
        },
        legend: {
        show: true,
        position: 'bottom',
        markers: { offsetX: -3 },
        itemMargin: {
            vertical: 3,
            horizontal: 10
        },
        labels: {
            colors: legendColor,
            useSeriesColors: false
        }
        },
        plotOptions: {
        pie: {
            donut: {
                labels: {
                    show: true,
                    name: {
                    fontSize: '1rem',
                    fontFamily: 'Open Sans'
                    },
                    value: {
                    fontSize: '1.1rem',
                    color: legendColor,
                    fontFamily: 'Open Sans',
                    formatter: function (val) {
                        return parseInt(val, 10);
                    }
                    },
                }
            }
        }
        },
        responsive: [
        {
            breakpoint: 992,
            options: {
            chart: {
                height: 380
            },
            legend: {
                position: 'bottom',
                labels: {
                colors: legendColor,
                useSeriesColors: false
                }
            }
            }
        },
        {
            breakpoint: 576,
            options: {
            chart: {
                height: 320
            },
            plotOptions: {
                pie: {
                donut: {
                    labels: {
                    show: true,
                    name: {
                        fontSize: '1.5rem'
                    },
                    value: {
                        fontSize: '1rem'
                    },
                    total: {
                        fontSize: '1.5rem'
                    }
                    }
                }
                }
            },
            legend: {
                position: 'bottom',
                labels: {
                colors: legendColor,
                useSeriesColors: false
                }
            }
            }
        },
        {
            breakpoint: 420,
            options: {
            chart: {
                height: 280
            },
            legend: {
                show: false
            }
            }
        },
        {
            breakpoint: 360,
            options: {
            chart: {
                height: 250
            },
            legend: {
                show: false
            }
            }
        }
        ]
    };
    if (typeof assetsChartEl !== undefined && assetsChartEl !== null) {
    const assetsChart = new ApexCharts(assetsChartEl, assetsChartConfig);
    assetsChart.render();
    }
</script>
@endpush
