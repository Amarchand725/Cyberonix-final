@extends('admin.layouts.app')
@section('title', $title.' - '. appName())

@push('styles')
@endpush

@section('content')
@if(isset($temp))
    <input type="hidden" id="page_url" value="{{ route('stationary.index') }}">
@else
    <input type="hidden" id="page_url" value="{{ route('stationary.trashed') }}">
@endif

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
                    <div class="d-flex justify-content-end align-item-center mt-4">
                        @if(isset($temp))
                            @can('stationary-delete')
                            <div class="dt-buttons flex-wrap">
                                <a data-toggle="tooltip" data-placement="top" title="All Trashed Records" href="{{ route('stationary.trashed') }}" class="btn btn-label-danger me-1">
                                    <span>
                                        <i class="ti ti-trash me-0 me-sm-1 ti-xs"></i>
                                        <span class="d-none d-sm-inline-block">All Trashed Records</span>
                                    </span>
                                </a>
                            </div>
                            @endcan
                            @can('stationary-create')
                                <div class="dt-buttons btn-group flex-wrap">
                                    <button
                                        data-toggle="tooltip"
                                        data-placement="top"
                                        title="Add Stationary"
                                        id="add-btn"
                                        data-url="{{ route('stationary.store') }}"
                                        class="btn btn-success add-new btn-primary mx-3"
                                        tabindex="0" aria-controls="DataTables_Table_0"
                                        type="button" data-bs-toggle="modal"
                                        data-bs-target="#create-form-modal"
                                        >
                                        <span>
                                            <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>
                                            <span class="d-none d-sm-inline-block">Add New </span>
                                        </span>
                                    </button>
                                </div>
                            @endcan
                            <div class="dt-buttons btn-group flex-wrap">
                                <button
                                    data-toggle="tooltip"
                                    data-placement="top"
                                    title="Bundle Package Creator"
                                    id="add-btn"
                                    class="btn btn-success add-new btn-primary me-3"
                                    tabindex="0" aria-controls="DataTables_Table_0"
                                    type="button" data-bs-toggle="modal"
                                    data-bs-target="#bundle-package-creator"
                                    >
                                    <span>
                                        <i class="ti ti-tags me-0 me-sm-1 ti-xs"></i>
                                        <span class="d-none d-sm-inline-block">Package </span>
                                    </span>
                                </button>
                            </div>
                        @else
                            @can('stationary-delete')
                                <div class="dt-buttons btn-group flex-wrap">
                                    <a data-toggle="tooltip" data-placement="top" title="Show All Records" href="{{ route('stationary.index') }}" class="btn btn-success btn-primary mx-3">
                                        <span>
                                            <i class="ti ti-eye me-0 me-sm-1 ti-xs"></i>
                                            <span class="d-none d-sm-inline-block">View All Records</span>
                                        </span>
                                    </a>
                                </div>
                            @endcan
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-datatable table-responsive">
                <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                    <div class="container">
                        <table class="datatables-users table border-top dataTable no-footer dtr-column data_table">
                            <thead>
                                <tr>
                                    <th>S.No#</th>
                                    <th>Stationary Category</th>
                                    <th style="width:25%;">Quantity</th>
                                    <th>Price</th>
                                    {{-- <th aria-label="Role: activate to sort column ascending">Created At</th>
                                    <th>Status</th> --}}
                                    <th>Actions</th>
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
<!-- Add Stationary Modal -->
<div class="modal fade" id="create-form-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered1 modal-simple modal-add-new-cc">
    <div class="modal-content p-3 p-md-5">
        <div class="modal-body">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="text-center mb-4">
            <h3 class="mb-2" id="modal-label"></h3>
        </div>
        <form id="create-form" data-method="" data-modal-id="create-form-modal" class="row g-3">
            @csrf
            <div id="edit-content">
                <div class="col-12 col-md-12 mb-3">
                    <label class="form-label" for="status">Stationary Category<span class="text-danger">*</span></label>
                    <select name="stationary_category" class="form-control" id="status">
                        <option value="" >Select Stationary Category</option>
                        @foreach($stationaryCategories as $stationary_category)
                            <option value="{{ $stationary_category->id }}" >{{ $stationary_category->stationary_category }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-12 mb-3">
                    <label class="form-label" for="name">Stationary Quantity<span class="text-danger">*</span></label>
                    <input type="number" name="stationary_quantity" id="stationary_quantity" class="form-control" placeholder="Enter Stationary Quantity" />
                    <div class="fv-plugins-message-container invalid-feedback"></div>
                    <span id="stationary_quantity_error" class="text-danger error"></span>
                </div>

                <div class="col-12 col-md-12">
                    <label class="form-label" for="name">Stationary Price<span class="text-danger">*</span></label>
                    <input type="number" name="stationary_price" id="stationary_price" class="form-control" placeholder="Enter Stationary Price" />
                    <div class="fv-plugins-message-container invalid-feedback"></div>
                    <span id="stationary_price_error" class="text-danger error"></span>
                </div>
            </div>
            <div class="col-12 mt-3 action-btn">
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
<!-- Add Stationary Modal -->


<!-- Calculator Modal -->
<div class="modal fade" id="bundle-package-creator" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered1 modal-simple modal-add-new-cc">
    <div class="modal-content p-3 p-md-5">
        <div class="modal-body">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="text-center mb-4">
            <h3 class="mb-2" id="modal-label"></h3>
            <small>Select Quantity from Dropdown Below to Calculate Bundle Price</small>
        </div>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr class="fw-bolder">
                        <td>Stationary</td>
                        <td>Quantity</td>
                        <td>Selling Price</td>
                    </tr>
                </thead>
                <tbody class="fs-6">
                    @foreach($stationaryCategories as $stationary_category)
                        <tr>
                            <td>{{$stationary_category->stationary_category}}</td>
                            <td>
                                <select name="quantity" id="qp{{$stationary_category->id}}">
                                    @foreach($stationary_category->qPprice as $qp)
                                        <option value="{{$qp->price}}">{{$qp->quantity}}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td id="price{{$stationary_category->id}}"> {{$qp->price}} </td>
                        </tr>
                    @endforeach
                    <input type="hidden" name="total_categories" id="total_categories" value="{{$stationary_category->id}}">
                    <tr>
                        <td colspan="2" class="fw-bolder">Total</td>
                        <td id="total" class="fw-bolder"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    </div>
</div>
<!-- Calculator Modal -->

<!-- View Model -->
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

                <div class="col-12 mt-3 text-end">
                    <button
                        type="reset"
                        class="btn btn-label-primary btn-reset"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    >
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- View Model -->

@endsection
@push('js')
    <script>
        //datatable
        var table = $('.data_table').DataTable();
        if ($.fn.DataTable.isDataTable('.data_table')) {
            table.destroy();
        }
        $(document).ready(function() {
            var page_url = $('#page_url').val();
            var table = $('.data_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: page_url+"?loaddata=yes",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'stationary_category_id', name: 'stationary_category_id' },
                    { data: 'quantity', name: 'quantity' },
                    { data: 'price', name: 'price' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            function calculateAndUpdate() {
                var totalCategoriesValue = parseInt($('#total_categories').val());
                var totalSum = 0;

                for (var i = 1; i <= totalCategoriesValue; i++) {
                    var selectedValue = $('#qp' + i + ' option:first').val();
                    $('#price' + i).text('$'+selectedValue);
                    totalSum += parseInt(selectedValue) || 0;
                }

                $('#total').text('$'+totalSum);
            }

            calculateAndUpdate(); // Initial calculation on page load

            // Change event handler for select elements
            $('select[name="quantity"]').on('change', function() {
                var selectedValue = $(this).val();
                var index = $(this).attr('id').replace('qp', '');
                $('#price' + index).text('$'+selectedValue);

                var totalSum = 0;
                $('select[name="quantity"]').each(function() {
                    totalSum += parseInt($(this).val()) || 0;
                });
                $('#total').text('$'+totalSum);
            });
        });
    </script>
@endpush
