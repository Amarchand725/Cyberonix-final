@extends('admin.layouts.app')
@section('title', $title.' - '. appName())

@push('styles')
@endpush

@section('content')


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
                        <div class="dt-buttons btn-group flex-wrap">
                            @can('stationary_category-create')
                                <button
                                    data-toggle="tooltip"
                                    data-placement="top"
                                    title="Add Stationary Category"
                                    id="add-btn"
                                    data-url="{{ route('stationary_categories.store') }}"
                                    class="btn btn-success add-new btn-primary"
                                    tabindex="0" aria-controls="DataTables_Table_0"
                                    type="button" data-bs-toggle="modal"
                                    data-bs-target="#create-form-modal"
                                    >
                                    <span>
                                        <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>
                                        <span class="d-none d-sm-inline-block">Add Stationary Category</span>
                                    </span>
                                </button>
                            @endcan
                            @can('stationary-create')
                                <button
                                    data-toggle="tooltip"
                                    data-placement="top"
                                    title="Add Stationary"
                                    id="add-btn"
                                    data-url="{{ route('stationary.store') }}"
                                    class="btn btn-success add-new btn-primary mx-3"
                                    tabindex="0" aria-controls="DataTables_Table_0"
                                    type="button" data-bs-toggle="modal"
                                    data-bs-target="#create-form-modal-stationary"
                                    >
                                    <span>
                                        <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>
                                        <span class="d-none d-sm-inline-block">Add Stationary </span>
                                    </span>
                                </button>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-datatable table-responsive">
                <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                    <div class="container mt-3">
                        <div class="row row-cols-1 row-cols-md-3 g-4" style="overflow-x: auto; white-space: nowrap;">
                            @foreach($models as $model)
                                <div class="col" style="display: inline-block;">
                                    <div class="card my-2" style="height: 500px;">

                                        <div class="card-body">
                                            <h4 class="card-title text-uppercase">
                                                {{$model->stationary_category}}
                                                <span class="float-end">
                                                    <div class="d-flex align-items-center">
                                                        @can('stationary_category-create')
                                                            <a href="javascript:;"
                                                                class="btn btn-icon btn-label-info waves-effect edit-btn me-2"
                                                                data-edit-url="{{ route('stationary_categories.edit', $model->id) }}"
                                                                data-url="{{ route('stationary_categories.update', $model->id) }}"
                                                                data-toggle="tooltip"
                                                                data-placement="top"
                                                                title="Edit Stationary Category"
                                                                tabindex="0" aria-controls="DataTables_Table_0"
                                                                type="button" data-bs-toggle="modal"
                                                                data-bs-target="#create-form-modal"
                                                                >
                                                                <i class="ti ti-edit ti-xs"></i>
                                                            </a>
                                                        @endcan
                                                        @can('stationary_category-delete')
                                                            <a data-toggle="tooltip" data-placement="top" title="Delete Record" href="javascript:;" class="btn btn-icon btn-label-primary waves-effect delete" data-slug="{{ $model->id }}" data-del-url="{{ route('stationary_categories.destroy', $model->id) }}">
                                                                <i class="ti ti-trash ti-xs"></i>
                                                            </a>
                                                        @endcan
                                                        {{-- @can('stationary_category-list')
                                                            <a href="javascript:;"
                                                                class="text-body dropdown-toggle hide-arrow"
                                                                data-bs-toggle="dropdown"
                                                                aria-expanded="false">
                                                                <i class="ti ti-dots-vertical ti-sm mx-1"></i>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-end m-0" style="">
                                                                <a href="#"
                                                                    class="dropdown-item show"
                                                                    tabindex="0"
                                                                    aria-controls="DataTables_Table_0"
                                                                    type="button"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#details-modal"
                                                                    data-toggle="tooltip"
                                                                    data-placement="top"
                                                                    title="Stationary Category Details"
                                                                    data-show-url="{{ route('stationary_categories.show', $model->id) }}">
                                                                    View Details
                                                                </a>
                                                            </div>
                                                        @endcan --}}
                                                    </div>
                                                <span>
                                            </h4>
                                            {{-- <div class="overflow-x-auto">

                                            </div> --}}
                                            <table class="w-100 datatables-users table border-top dataTable no-footer dtr-column">
                                                <thead>
                                                    <tr>
                                                        <th>Count</th>
                                                        <th>Min Selling Price</th>
                                                        @if(
                                                            Gate::check('stationary-create') ||
                                                            Gate::check('stationary-delete') ||
                                                            Gate::check('stationary-edit')
                                                        )
                                                            <th>Action</th>
                                                        @endif
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($model->qPprice as $categoryQPrice)
                                                        <tr>
                                                            <td>{{$categoryQPrice->quantity}}</td>
                                                            <td>${{number_format($categoryQPrice->price, 2)}}</td>
                                                            @if(
                                                                Gate::check('stationary-list') ||
                                                                Gate::check('stationary-delete') ||
                                                                Gate::check('stationary-edit')
                                                            )
                                                                <td>
                                                                    <div class="d-flex align-items-center">
                                                                        @can('stationary-edit')
                                                                            <a href="javascript:;"
                                                                                class="btn btn-icon btn-label-info waves-effect edit-btn me-2"
                                                                                data-edit-url="{{ route('stationary.edit', $categoryQPrice->id) }}"
                                                                                data-url="{{ route('stationary.update', $categoryQPrice->id) }}"
                                                                                data-toggle="tooltip"
                                                                                data-placement="top"
                                                                                title="Edit Stationary"
                                                                                tabindex="0" aria-controls="DataTables_Table_0"
                                                                                type="button" data-bs-toggle="modal"
                                                                                data-bs-target="#create-form-modal"
                                                                                >
                                                                                <i class="ti ti-edit ti-xs"></i>
                                                                            </a>
                                                                        @endif
                                                                        @can('stationary-delete')
                                                                            <a href="javascript:;"
                                                                                data-toggle="tooltip"
                                                                                data-placement="top"
                                                                                title="Delete Record"
                                                                                class="btn btn-icon btn-label-primary waves-effect delete"
                                                                                data-slug="{{ $categoryQPrice->id }}"
                                                                                data-del-url="{{ route('stationary.destroy', $categoryQPrice->id) }}">
                                                                                <i class="ti ti-trash ti-xs"></i>
                                                                            </a>
                                                                        @endif
                                                                        {{-- @can('stationary-list')
                                                                            <a href="javascript:;"
                                                                                class="text-body dropdown-toggle hide-arrow"
                                                                                data-bs-toggle="dropdown"
                                                                                aria-expanded="false">
                                                                                <i class="ti ti-dots-vertical ti-sm mx-1"></i>
                                                                            </a>

                                                                            <div class="dropdown-menu dropdown-menu-end m-0" style="">
                                                                                <a href="#"
                                                                                    class="dropdown-item show"
                                                                                    tabindex="0"
                                                                                    aria-controls="DataTables_Table_0"
                                                                                    type="button"
                                                                                    data-bs-toggle="modal"
                                                                                    data-bs-target="#details-modal"
                                                                                    data-toggle="tooltip"
                                                                                    data-placement="top"
                                                                                    title="Stationary Details"
                                                                                    data-show-url="{{ route('stationary.show', $categoryQPrice->id) }}">
                                                                                    View Details
                                                                                </a>
                                                                            </div>
                                                                        @endcan --}}
                                                                    </div>
                                                                </td>
                                                            @endif
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="row mt-5">
                            <div class="col-3"></div>
                            <div class="col-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h2 class="card-title text-center">Bundle Package Creator</h2>
                                        <p class="card-text text-center">Select Quantity from Dropdown Below to Calculate Bundle Price.</p>
                                        <table class="w-100 datatables-users table border-top dataTable no-footer dtr-column">
                                            <thead class="text-center">
                                                <tr class="fw-bolder">
                                                    <th>Stationary</th>
                                                    <th>Quantity</th>
                                                    <th>Selling Price</th>
                                                </tr>
                                            </thead>
                                            <tboby class="fs-6">
                                                @foreach($models as $stationary_category)
                                                    <tr>
                                                        <td>{{$stationary_category->stationary_category}}</td>
                                                        <td>
                                                            <select name="quantity" id="qp{{$stationary_category->id}}" class="form-control">
                                                                <option value="0"> Select {{$stationary_category->stationary_category}} Quantity </option>
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
                                            </tboby>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Add Stationary Category Modal -->
    <div class="modal fade" id="create-form-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered1 modal-simple modal-add-new-cc">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-body">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="text-center mb-4">
                <h3 class="mb-2" id="modal-label"></h3>
            </div>
            <form id="create-form" data-method="" data-modal-id="create-form-modal" class="row g-3 submitBtnWithFileUpload">
                @csrf

                <div id="edit-content">
                    <div class="col-12 col-md-12">
                        <label class="form-label" for="name">Stationary Category<span class="text-danger">*</span></label>
                        <input type="text" name="stationary_category" id="stationary_category" class="form-control" placeholder="Enter Stationary Category" />
                        <div class="fv-plugins-message-container invalid-feedback"></div>
                        <span id="stationary_category_error" class="text-danger error"></span>
                    </div>
                </div>

                <div class="col-12 mt-3 action-btn">
                    <div class="demo-inline-spacing sub-btn">
                        <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
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
<!-- Add Stationary Category Modal -->

<!-- Add Stationary Modal -->
    <div class="modal fade" id="create-form-modal-stationary" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered1 modal-simple modal-add-new-cc">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-body">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="text-center mb-4">
                <h3 class="mb-2" id="modal-label"></h3>
            </div>
            <form id="create-form" data-method="" data-modal-id="create-form-modal-stationary" class="row g-3 submitBtnWithFileUpload">
                @csrf
                <div id="edit-content">
                    <div class="col-12 col-md-12 mb-3">
                        <label class="form-label" for="status">Stationary Category<span class="text-danger">*</span></label>
                        <select name="stationary_category" class="form-control" id="status">
                            <option value="" >Select Stationary Category</option>
                            @foreach($models as $stationary_category)
                                <option value="{{ $stationary_category->id }}" >{{ $stationary_category->stationary_category }}</option>
                            @endforeach
                        </select>
                        <div class="fv-plugins-message-container invalid-feedback"></div>
                        <span id="stationary_category_error" class="text-danger error"></span>
                    </div>

                    <div class="col-12 col-md-12 mb-3">
                        <label class="form-label" for="name">Stationary Quantity<span class="text-danger">*</span></label>
                        <input type="number" name="stationary_quantity" id="stationary_quantity" class="form-control" placeholder="Enter Stationary Quantity" />
                        <div class="fv-plugins-message-container invalid-feedback"></div>
                        <span id="stationary_quantity_error" class="text-danger error"></span>
                    </div>

                    <div class="col-12 col-md-12 mb-3">
                        <label class="form-label" for="name">Stationary Price<span class="text-danger">*</span></label>
                        <input type="number" name="stationary_price" id="stationary_price" class="form-control" placeholder="Enter Stationary Price" />
                        <div class="fv-plugins-message-container invalid-feedback"></div>
                        <span id="stationary_price_error" class="text-danger error"></span>
                    </div>

                </div>
                <div class="col-12 mt-3 action-btn">
                    <div class="demo-inline-spacing sub-btn">
                        <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
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

@endsection
@push('js')
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

    $(document).on('click', '.delete', function() {
    var slug = $(this).attr('data-slug');
    var delete_url = $(this).attr('data-del-url');
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
                    type: 'DELETE',
                    success: function(response) {
                        if (response) {
                            toastr.success('You have deleted record successfully.');
                            location.reload(); // Reload the page
                        } else {
                            toastr.error('Sorry, something went wrong.');
                        }
                    },
                    error: function(xhr, status, error) {
                        toastr.error('Error occurred while deleting.');
                        console.error(error);
                    }
                });
            }
        });
    });
</script>
@endpush
