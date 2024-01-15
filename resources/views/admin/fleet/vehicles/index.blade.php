@extends('admin.layouts.app')
@section('title', $title.' - ' . appName())

@section('content')
    @if(isset($data['body_types']))
        <input type="hidden" id="page_url" value="{{ route('vehicles.index') }}">
    @else
        <input type="hidden" id="page_url" value="{{ route('vehicles.trashed') }}">
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
                            @if(isset($data['body_types']))
                                <div class="dt-buttons flex-wrap">
                                    <a data-toggle="tooltip" data-placement="top" title="All Trashed Records" href="{{ route('vehicles.trashed') }}" class="btn btn-label-danger mx-1">
                                        <span>
                                            <i class="ti ti-trash me-0 me-sm-1 ti-xs"></i>
                                            <span class="d-none d-sm-inline-block">All Trashed Records </span>
                                        </span>
                                    </a>
                                </div>
                                <div class="dt-buttons btn-group flex-wrap">
                                    <button
                                        data-toggle="tooltip"
                                        data-placement="top"
                                        title="Add New Vehicle"
                                        type="button"
                                        class="btn btn-secondary add-new btn-primary mx-3"
                                        id="add-btn"
                                        data-url="{{ route('vehicles.store') }}"
                                        tabindex="0" aria-controls="DataTables_Table_0"
                                        type="button" data-bs-toggle="modal"
                                        data-bs-target="#offcanvasAddAnnouncement"
                                        >
                                        <span>
                                            <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>
                                            <span class="d-none d-sm-inline-block">Add New</span>
                                        </span>
                                    </button>
                                </div>
                            @else
                                <div class="dt-buttons btn-group flex-wrap">
                                    <a data-toggle="tooltip" data-placement="top" title="Show All Records" href="{{ route('vehicles.index') }}" class="btn btn-success btn-primary mx-3">
                                        <span>
                                            <i class="ti ti-eye me-0 me-sm-1 ti-xs"></i>
                                            <span class="d-none d-sm-inline-block">View All Records</span>
                                        </span>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <!-- Users List Table -->
            <div class="card">
                <div class="card-datatable">
                    <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                        <div class="container">
                            <table class="dt-row-grouping table dataTable dtr-column border-top table-border data_table table-responsive">
                                <thead>
                                    <tr>
                                        <th>S.No#</th>
                                        <th style="width:25%">Vehicle</th>
                                        <th>Owner</th>
                                        <th>Reg. Number</th>
                                        <th>Rent(PKR)</th>
                                        <th>Created At</th>
                                        <th>Status</th>
                                        <th>Action</th>
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
        <div class="modal-dialog modal-xl modal-dialog-centered1 modal-simple modal-add-new-cc">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-4">
                        <h3 class="mb-2" id="modal-label"></h3>
                    </div>

                    <form class="pt-0 fv-plugins-bootstrap5 fv-plugins-framework submitBtnWithFileUpload" data-method="post"  data-modal-id="offcanvasAddAnnouncement" id="create-form" enctype="multipart/form-data">
                        @csrf

                        <span id="edit-content">
                            <div class="row">
                                <div class="mb-3 fv-plugins-icon-container col-6">
                                    <label class="form-label" for="owner_id">Owner <span class="text-danger">*</span></label>
                                    <select id="owner_id" name="owner_id" class="form-control">
                                        <option value="">Select Owner</option>
                                        @if(isset($data['vehicle_owners']))
                                            @foreach($data['vehicle_owners'] as $VehicleOwner)
                                                <option value="{{$VehicleOwner->id}}">{{$VehicleOwner->company_name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <span id="owner_id_error" class="text-danger error"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-3 fv-plugins-icon-container col-6">
                                    <label class="form-label" for="name">Vehicle <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" value="" placeholder="Toyota" name="name">
                                    <span id="name_error" class="text-danger error"></span>
                                </div>
                                <div class="mb-3 fv-plugins-icon-container col-6">
                                    <label class="form-label" for="model_year">Model Year <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="model_year" value="" placeholder="2023" name="model_year">
                                    <span id="model_year_error" class="text-danger error"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-3 fv-plugins-icon-container col-6">
                                    <label class="form-label" for="body_type">Body Type <span class="text-danger">*</span></label>
                                    <select id="body_type" name="body_type" class="form-control">
                                        <option value="">Select Body Type</option>
                                        @if(isset($data['body_types']))
                                            @foreach($data['body_types'] as $body_type)
                                                <option value="{{ $body_type->id }}">{{ $body_type->body_type }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <span id="body_type_error" class="text-danger error"></span>
                                </div>
                                <div class="mb-3 fv-plugins-icon-container col-6">
                                    <label class="form-label" for="color">Color <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="color" value="" placeholder="Black" name="color">
                                    <span id="color_error" class="text-danger error"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-3 fv-plugins-icon-container col-6">
                                    <label class="form-label" for="registration_number">Registration Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="registration_number" value="" placeholder="BNR 804" name="registration_number">
                                    <span id="registration_number_error" class="text-danger error"></span>
                                </div>
                                <div class="mb-3 fv-plugins-icon-container col-6">
                                    <label class="form-label" for="transmission">Transmission <span class="text-danger">*</span></label>
                                    <select id="transmission" name="transmission" class="form-control">
                                        <option value="">Select Transmission</option>
                                        <option value="Automatic">Automatic</option>
                                        <option value="Manual">Manual</option>
                                    </select>
                                    <span id="transmission_error" class="text-danger error"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-3 fv-plugins-icon-container col-6">
                                    <label class="form-label" for="engine_capacity">ENGINE CAPACITY (CC) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="engine_capacity" value="" placeholder="1300" name="engine_capacity">
                                    <span id="engine_capacity_error" class="text-danger error"></span>
                                </div>
                                <div class="mb-3 fv-plugins-icon-container col-6">
                                    <label class="form-label" for="mileage">Mileage (KM) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="mileage" value="" placeholder="60,000" name="mileage">
                                    <span id="mileage_error" class="text-danger error"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-3 fv-plugins-icon-container col-6">
                                    <label class="form-label" for="registration_province">Registration Province <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="registration_province" value="" placeholder="Sindh" name="registration_province">
                                    <span id="registration_province_error" class="text-danger error"></span>
                                </div>
                                <div class="mb-3 fv-plugins-icon-container col-6">
                                    <label class="form-label" for="registration_city">Registration City <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="registration_city" value="" placeholder="Karachi" name="registration_city">
                                    <span id="registration_city_error" class="text-danger error"></span>
                                </div>
                            </div>

                            <div class="row">
                                <div class="mb-3 fv-plugins-icon-container col-6">
                                    <label class="form-label" for="rent">Rent (PKR) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="rent" value="" placeholder="90,000" name="rent">
                                    <span id="rent_error" class="text-danger error"></span>
                                </div>
                                <div class="mb-3 fv-plugins-icon-container col-6">
                                    <label class="form-label" for="status">Status</label>
                                    <select id="status" name="status" class="form-control">
                                        <option value="">Select Status</option>
                                        <option value="1">Available</option>
                                        <option value="0">Not available</option>
                                    </select>
                                    <span id="status_error" class="text-danger error"></span>
                                </div>
                            </div>

                            <div class="row">
                                <div class="mb-3 fv-plugins-icon-container col-12">
                                    <label class="form-label" for="additional">Additional Detail (Optional)</label>
                                    <textarea rows="4" class="form-control" id="additional" placeholder="Additional Detail......" name="additional"></textarea>
                                    <span id="additional_error" class="text-danger error"></span>
                                </div>
                            </div>

                            <div class="row upload__inputfile_div">
                                <div class="mb-3 fv-plugins-icon-container col-12">
                                    <label class="form-label" for="thumbnail">Thumbnail </label>
                                    <div class="input_file_inner">
                                        <input type="file" name="thumbnail" class="form-control" id="thumbnail" accept="image/gif, image/jpeg, image/png">
                                    </div>
                                    <img id="preview-image" src="" style="display:none; width:80px" class="mt-2" alt="Image Preview">
                                    <span id="thumbnail_error" class="text-danger error"></span>
                                </div>
                            </div>

                            <div class="row upload__inputfile_div">
                                <div class="mb-3 fv-plugins-icon-container col-12">
                                    <label class="form-label" for="images">Vehicle Images (Optional)</label>
                                    <div class="input_file_inner">
                                        <input type="file" multiple name="images[]" class="form-control fileUploadWithPreview" id="fileUpload" accept="image/gif, image/jpeg, image/jpg, image/png" data-id=1 >
                                    </div>
                                    <div id="image-holder1" class="image-holder" style="display:none;"></div>
                                </div>
                            </div>

                            <div class="row upload__inputvideo_div">
                                <div class="mb-3 fv-plugins-icon-container col-12">
                                    <label class="form-label" for="video">Vehicle Video (Optional)</label>
                                    <input id="file-input" type="file" class="form-control videoInputWithPreview" onchange="videoInputWithPreview('video1')" name="video" accept="video/*">
                                    <div class="video-holder" @if(isset($Vehicle->video) && $Vehicle->video!='') style="display: block;" @else style="display: none;" @endif>
                                        <div>
                                            <div id="video1" ></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </span>

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
    <!--/ Edit Employment Status Modal -->

    <div class="modal fade" id="details-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-top modal-simple modal-add-new-cc">
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

    <div class="modal fade" id="history-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered1 modal-simple modal-add-new-cc">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-4">
                        <h3 class="mb-2" id="modal-label"></h3>
                    </div>

                    <div class="col-12">
                        <span id="show-content"></span>
                    </div>

                    <div class="col-12 mt-3">
                        <button
                            type="reset"
                            class="btn btn-label-secondary btn-reset"
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
        $(document).on("input", "#rent", function () {
            // Get the current input value
            var inputValue = $(this).val();

            // Remove non-digit characters using regular expression
            var digitValue = inputValue.replace(/\D/g, '');

            // Update the input field with only digits
            $(this).val(digitValue);
        });

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
                    { data: 'name', name: 'name' },
                    { data: 'owner_id', name: 'owner_id' },
                    { data: 'registration_number', name: 'registration_number' },
                    { data: 'rent', name: 'rent' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'status', name: 'status' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });
        });

        $(document).on('change', '#thumbnail', function() {
            var input = this;
            if (input.files && input.files[0]) {
              var reader = new FileReader();

              reader.onload = function(e) {
                $('#preview-image').attr('src', e.target.result);
                $('#preview-image').css('display', 'block');
              }

              reader.readAsDataURL(input.files[0]);
            }
        });

        //multiple images upload
        $(document).on('change', '.fileUploadWithPreview', function (e) {
            var dataID = $(this).attr('data-id');
            var $imageHolder = $('#image-holder' + dataID);
            var files = e.target.files,
                filesLength = files.length;
            var filesArray = [];

            for (var i = 0; i < filesLength; i++) {
                var f = files[i];
                filesArray.push(f.name);
                var fileReader = new FileReader();

                fileReader.onload = (function (e) {
                    var file = e.target;
                    $imageHolder.append('<div class="previewImage"><img src="' + e.target.result + '" class="thumb-image" /><i class="fa fa-times previewImage_images_remove"></i></div>');
                });

                fileReader.readAsDataURL(f);
            }

            setTimeout(function () {
                for (var j = 0; j < filesArray.length; j++) {
                    var k_f = filesArray[j];
                    var imgPath = k_f;
                    var k = j + 1;
                    $imageHolder.find('.previewImage:nth-child(' + k + ')').append('<input type="hidden" name="image_names[]" value="' + imgPath + '" />');
                }
            }, 1000);

            $imageHolder.show();

            var fileUploadFiles = filesArray.length;
            if (fileUploadFiles > 0) {
                $(this).parent().find('label').text(fileUploadFiles + ' files');
            } else {
                $(this).parent().find('label').text('No file chosen');
            }
        });

        //video upload
        function videoInputWithPreview(id) {
            $('#'+id).parent().parent().hide();
            $('#'+id).html('');
            $('#'+id).html('<video class="video" controls><source src="'+URL.createObjectURL(event.target.files[0])+'"></source></video>');
            $('#'+id).parent().parent().show();
        }
        //remove images
        $(document).on('click','.previewImage_images_remove',function(){
            $(this).parent().remove();
            var fileUploadFiles=$('#image-holder .previewImage').length;
             if(fileUploadFiles>0){
                $('.upload__inputfile_div .input_file_inner label').text(fileUploadFiles+' files');
             }else{
                $('.upload__inputfile_div .input_file_inner label').text('No file chosen');
             }
        });
        $(document).on('click','.previewImage_video_remove',function(){
            var dataID=$(this).attr('data-id');
            $('#video'+dataID).html('');
            $('#video'+dataID).parent().parent().hide();
            $('#video'+dataID).parent().parent().parent().find('input[type="file"]').val('');
        });

        $('.slider-for-vehicle-preview').slick({
          slidesToShow: 1,
          slidesToScroll: 1,
          arrows: false,
          fade: true,
          asNavFor: '.slider-nav-vehicle-preview'
        });
        $('.slider-nav-vehicle-preview').slick({
          slidesToShow: 3,
          slidesToScroll: 1,
          asNavFor: '.slider-for-vehicle-preview',
          dots: true,
          centerMode: true,
          focusOnSelect: true
        });
        $(document).on('click','button.Vehicle_Detail_btn',function(){
            setTimeout(function(){
                $('.slider-for-vehicle-preview').slick('setPosition');
                $('.slider-nav-vehicle-preview').slick('setPosition');
            }, 500);
        });
    </script>
@endpush
