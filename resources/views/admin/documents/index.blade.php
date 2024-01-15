@extends('admin.layouts.app')
@section('title', $title . ' - ' . appName())

@section('content')
@if (!isset($temp))
<input type="hidden" id="page_url" value="{{ route('documents.index') }}">
@else
<input type="hidden" id="page_url" value="{{ route('documents.trashed') }}">
@endif
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card mb-4">
            <div class="row">
                <div class="col-md-6">
                    <div class="card-header">
                        <h4 class="fw-bold mb-0"><span class="text-muted fw-light">Home /</span> {{ $title }}
                        </h4>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex justify-content-end align-item-center mt-4">
                        @if (!isset($temp))
                        <div class="dt-buttons flex-wrap">
                            <a data-toggle="tooltip" data-placement="top" title="All Trashed Records" href="{{ route('documents.trashed') }}" class="btn btn-label-danger me-1">
                                <span>
                                    <i class="ti ti-trash me-0 me-sm-1 ti-xs"></i>
                                    <span class="d-none d-sm-inline-block">All Trashed Records</span>
                                </span>
                            </a>
                        </div>
                        <div class="dt-buttons btn-group flex-wrap">
                            <button onclick="selectInit()" data-toggle="tooltip" data-placement="top" title="Add Attachments" type="button" class="btn btn-secondary add-new btn-primary mx-3" id="add-btn" data-url="{{ route('documents.store') }}" tabindex="0" aria-controls="DataTables_Table_0" type="button" data-bs-toggle="modal" data-bs-target="#addDocumentsModal">
                                <span>
                                    <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>
                                    <span class="d-none d-sm-inline-block">Add New</span>
                                </span>
                            </button>
                        </div>
                        @else
                        <div class="dt-buttons btn-group flex-wrap">
                            <a data-toggle="tooltip" data-placement="top" title="Show All Records" href="{{ route('documents.index') }}" class="btn btn-success btn-primary mx-3">
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
                                    <th scope="col">S.No#</th>
                                    <th scope="col" class="w-20">Employee</th>
                                    <th scope="col">Department</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Status</th>
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

<!-- add documents modal  -->
<div class="modal fade" id="addDocumentsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-top modal-simple modal-add-new-cc">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-4">
                    <h3 class="mb-2" id="modal-label"></h3>
                </div>
                <form action="{{ route('documents.store') }}" id="uploadDocumentsFromHr" class="row g-3 uploadDocumentsFromHr" enctype="multipart/form-data" method="POST">
                    @csrf
                    <span id="edit-content">
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <label class="form-label" for="employee">Employees <span class="text-danger">*</span></label>

                                <select id="selectEmployee" name="selectEmployee" class="form-select select2" required>
                                    <option value="" selected>Select</option>
                                    @if (isset($employees))
                                    @foreach ($employees as $employee)
                                    <option value="{{ $employee->slug }}">{{ $employee->first_name }}
                                        {{ $employee->last_name }} - (
                                        {{ $employee->jobHistory->designation->title ?? '' }} )
                                    </option>
                                    @endforeach
                                    @endif
                                </select>
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                <span id="employee_error" class="text-danger error"></span>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="row mt-3 border-top py-3">
                                <div class="col-12 d-flex justify-content-between align-items-center">
                                    <h5 class="card-title">Education Documents </h5>
                                    <div class="btn-wrapper">
                                        <button type="button" data-val="2" class="btn btn-label-primary btn-sm add-more-btn"><i class="fa fa-plus"></i></button>
                                    </div>
                                </div>
                                <!--document_data-->
                                <span class="document_data">
                                    <div class="row mt-2">
                                        <div class="col-md-12 mt-3">
                                            <label class="form-label" for="title">Title <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" id="title" name="titles[]"
                                                class="form-control titles titles_value_1" data-count="1"
                                                placeholder="Enter Title" />
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                            <span id="titles_error_1" class="text-danger error"></span>
                                        </div>
                                        <div class="col-md-12 mt-3">
                                            <label class="form-label" for="attachments">Attachment <span
                                                    class="text-danger">*</span></label>
                                            <input type="file" id="attachments" name="attachments[]" accept="image/*,.doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document , .pdf, .txt"
                                                data-val="1"
                                                class="form-control input-file attachments attachments_1" />
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                            <span id="attachments_error_1" class="text-danger error"></span>
                                        </div>
                                        <div class="col-md-12 mt-3">
                                            <div class="preview-container-1"></div>
                                        </div>
                                    </div>
                                </span>
                                <span id="add-more-data"></span>
                            </div>
                        </div>
                    </span>
                    <div class="col-12 mt-3 ">
                        <div class="demo-inline-spacing">
                            <button type="submit" class="btn btn-primary me-sm-3 me-1   btn_save "> <span class="spinner-border me-1 d-none" role="status" aria-hidden="true"></span>
                                Submit</button>
                            <button type="reset" class="btn btn-label-secondary btn-reset" data-bs-dismiss="modal" aria-label="Close">
                                Cancel
                            </button>
                        </div>
                        {{-- <div class="demo-inline-spacing loading-btn" style="display: none;">
                                <button class="btn btn-primary waves-effect waves-light" type="button" disabled="">
                                    <span class="spinner-border me-1" role="status" aria-hidden="true"></span>
                                    Loading...
                                </button>
                                <button type="reset" class="btn btn-label-secondary btn-reset" data-bs-dismiss="modal"
                                    aria-label="Close">
                                    Cancel
                                </button>
                            </div> --}}
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- add documents modal  -->


{{-- edit document modal --}}
<div class="modal fade" id="editDocumentModalHr" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-top modal-simple modal-add-new-cc">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-4">
                    <h3 class="mb-2" id="modal-label">Edit Documents</h3>
                </div>
            </div>
            <div class="modal-body">
                <div class="row " id="main_spinner_div">
                    <div class="col-md-12" style="text-align: center;">
                        <span class="spinner-border me-1" role="status" aria-hidden="true" style="width: 100px !important;height: 100px !important;color: #e75b9d;font-weight: bold;font-size: 25px;"></span>
                    </div>
                </div>
                <div class="row" id="editDocumentModalHrBody">

                </div>
            </div>


        </div>
    </div>
</div>
{{-- edit document modal --}}


<input type="hidden" name="" id="file_icon_url" value="{{ asset('public/admin/assets/img/fileicon.png') }}">
<input type="hidden" name="" id="doc_icon_url" value="{{ asset('public/admin/assets/img/doc.png') }}">
<input type="hidden" name="" id="pdf_icon_url" value="{{ asset('public/admin/assets/img/pdf.png') }}">
<input type="hidden" name="" id="xls_icon_url" value="{{ asset('public/admin/assets/img/xls.png') }}">

<!-- view documents modal -->
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
                    <button type="reset" class="btn btn-label-primary btn-reset" data-bs-dismiss="modal" aria-label="Close">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- view documents modal -->
@endsection
@push('js')
<script type="text/javascript">
    function showSuccess(msg) {
        toastr.options = {
            "closeButton": true,
            "progressBar": true
        }
        toastr.success(msg);
    }

    $(document).on('change', ".input-file", function() {
        var val = $(this).attr('data-val');
        var file = this.files[0];
        var fileIcon = $("#file_icon_url").val();
        var docfileIcon = $("#doc_icon_url").val();
        var pdffileIcon = $("#pdf_icon_url").val();
        var xlsfileIcon = $("#xls_icon_url").val();


        if (file) {
            var type = file.type;
            if (type) {

                var reader = new FileReader();
                var inputElement = this; // Capture the 'this' reference
                reader.onload = function(e) {
                        // Create an image element
                        if (type == "image/jpeg" || type == "image/png" || type == "image/gif" || type == "image/bmp") {
                            var img = $('<img style="width:100%; max-width:60px;">').attr("src", e.target.result);
                        } else if(type == "application/pdf") {
                            var img = $('<img style="width:100%; max-width:60px;">').attr("src", pdffileIcon);
                        } else if(type == "application/vnd.openxmlformats-officedocument.wordprocessingml.document") {
                            var img = $('<img style="width:100%; max-width:60px;">').attr("src", docfileIcon);
                        }else if(type == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" || type == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") {
                            var img = $('<img style="width:100%; max-width:60px;">').attr("src", xlsfileIcon);
                        } else {
                            var img = $('<img style="width:100%; max-width:60px;">').attr("src", fileIcon);
                        }
                        // Display the image preview
                        $(inputElement).parents('.document_data').find(".preview-container-" + val)
                            .html(img);
                    };
                // Read the image file as a data URL
                reader.readAsDataURL(file);

            }
        } else {
            $(".preview-container-" + val).html("")
        }
    });



    $(document).ready(function() {
        function showSuccess(msg) {
            toastr.options = {
                "closeButton": true,
                "progressBar": true
            }
            toastr.success(msg);
        }
        // error function
        function showError(msg) {
            toastr.options = {
                "closeButton": true,
                "progressBar": true
            }
            toastr.error(msg);
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
                    data: 'department',
                    name: 'department'
                },
                {
                    data: 'date',
                    name: 'date'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ]
        });

        $(document).on('click', '.add-more-btn', function() {
            var val = parseInt($(this).attr('data-val'));
            var html = '';
            html = '<span class="document_data">' +
                '<div class="row mt-4 w-full  position-relative">' +
                '<div class="close-btn-wrapper position-absolute d-flex flex-row-reverse mb-3" style="top: -20px !important;">' +
                '<button type="button" class="btn btn-label-primary btn-sm btn-data-close p-2"><i class="fa fa-close icon-close"></i></button>' +
                '</div>' +
                '<div class="col-md-12 mt-3">' +
                '<input type="text" id="title" name="titles[]" class="form-control titles titles_value_' +
                val +
                '"  placeholder="Enter Title" data-count="' +
                val + '" />' +
                '<div class="fv-plugins-message-container invalid-feedback"></div>' +
                '<span id="titles_error_' + val + '" class="text-danger error"></span>' +
                '</div>' +
                '<div class="col-md-12 mt-3">' +
                '<input type="file" id="attachments" name="attachments[]" data-val="' + val +
                '" class="form-control input-file attachments attachments_' + val + '" accept="image/*,.doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document , .pdf, .txt" />' +
                '<div class="fv-plugins-message-container invalid-feedback"></div>' +
                '<span id="attachments_error_' + val + '" class="text-danger error"></span>' +
                '</div>' +
                '<div class="col-md-3 mt-3">' +
                '<div class="preview-container-' + val + '"></div>' +
                '</div>' +
                '</div>' +
                '</span>';
            $(this).attr('data-val', val + 1);

            $('#add-more-data').append(html);
        });

        $(document).on('click', '.btn-data-close', function() {
            $(this).parents('.document_data').remove();
        });
         // on file change
         $(document).on('change', ".input-file", function() {
            var val = $(this).attr('data-val');
            var file = this.files[0];
            var fileIcon = $("#file_icon_url").val();
            var docfileIcon = $("#doc_icon_url").val();
            var pdffileIcon = $("#pdf_icon_url").val();
            var xlsfileIcon = $("#xls_icon_url").val();

            if (file) {
                var type = file.type;
                if (type) {

                    var reader = new FileReader();
                    var inputElement = this; // Capture the 'this' reference
                    reader.onload = function(e) {
                        // Create an image element
                        if (type == "image/jpeg" || type == "image/png" || type == "image/gif" || type == "image/bmp") {
                            var img = $('<img style="width:100%; max-width:60px;">').attr("src", e.target.result);
                        } else if(type == "application/pdf") {
                            var img = $('<img style="width:100%; max-width:60px;">').attr("src", pdffileIcon);
                        } else if(type == "application/vnd.openxmlformats-officedocument.wordprocessingml.document") {
                            var img = $('<img style="width:100%; max-width:60px;">').attr("src", docfileIcon);
                        }else if(type == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" || type == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") {
                            var img = $('<img style="width:100%; max-width:60px;">').attr("src", xlsfileIcon);
                        } else {
                            var img = $('<img style="width:100%; max-width:60px;">').attr("src", fileIcon);
                        }
                        // Display the image preview
                        $(inputElement).parents('.document_data').find(".preview-container-" + val)
                            .html(img);
                    };
                    // Read the image file as a data URL
                    reader.readAsDataURL(file);

                }
            } else {
                $(".preview-container-" + val).html("")
            }
        });


        // upload documents
        $(document).on("submit", "#uploadDocumentsFromHr", function(e) {
            if ($(".spinner-border").hasClass("d-none")) {
                $(".spinner-border").removeClass("d-none")
            }
            $(".btn_save").attr("disabled", true)
            var errors = [];
            var attachments = [];
            var titles = [];
            var countArray = [];
            $("#employee_error").html("");
            var employee = $("#selectEmployee").val();
            if (!employee) {
                $("#employee_error").html("Please select an employee")
                errors.push(1)
            }
            $(".titles").each(function() {
                var count = $(this).attr('data-count');
                console.log(count)
                titles.push($(this).val());
                attachments.push($(".attachments_" + count).val());
                countArray.push(count)
            });
            $.each(countArray, function(key, value) {
                var title = $(".titles_value_" + value).val();
                var attachment = $(".attachments_" + value).val();
                if (!title) {
                    $("#titles_error_" + value).html("Required");
                    errors.push(1);
                } else {
                    $("#titles_error_" + value).html("");
                }
                if (!attachment) {
                    $("#attachments_error_" + value).html("Required");
                    errors.push(1);
                } else {
                    $("#attachments_error_" + value).html("");
                }
            });
            if (errors.length > 0) {
                showError("Please fill all required fields")
                if (!$(".spinner-border").hasClass("d-none")) {
                    $(".spinner-border").addClass("d-none")
                }
                $(".btn_save").attr("disabled", false)
                e.preventDefault();

            } else {

            }
        });
        // upload documents


        $(document).on("click", ".editDocumentHrBtn", function() {
            $("#main_spinner_div").removeClass("d-none")
            var id = $(this).attr("data-id");
            var route = $(this).attr("data-route");
            $("#editDocumentModalHr").modal("show");
            
            $.ajax({
                url: route,
                method: "GET",
                data: {
                    id: id,
                },
                beforeSend: function() {
                    $("#editDocumentModalHrBody").empty()
                },
                success: function(res) {
                    $("#main_spinner_div").addClass("d-none")
                    $("#editDocumentModalHrBody").html(res)
                    
                },
                error: function(xhr, status, error) {
                    console.log(res);
                }
            });
        });

        $(document).on("submit", "#updateDocumentsFormHr", function(e) {
            if ($(".spinner-border").hasClass("d-none")) {
                $(".spinner-border").removeClass("d-none")
            }
            $(".btn_submit").attr("disabled", true)
            var editErrors = [];
            var attachments = [];
            var titles = [];
            var countArray = [];
            $(".edit_titles").each(function() {
                var count = $(this).attr('data-count');
                titles.push($(this).val());
                countArray.push(count)
            });

            $.each(countArray, function(key, value) {
                var title = $(".edit_titles_value_" + value).val();
                console.log(title)
                if (!title) {
                    $("#edit_titles_error_" + value).html("Required");
                    editErrors.push(1);
                } else {
                    $("#edit_titles_error_" + value).html("");
                }
            });
            if (editErrors.length > 0) {
                showError("Please fill all required fields")
                if (!$(".spinner-border").hasClass("d-none")) {
                    $(".spinner-border").addClass("d-none")
                }
                $(".btn_submit").attr("disabled", false)
                e.preventDefault();
            }
        });

        $(document).on("click", ".delete-document-btn", function() {
            var id = $(this).attr("data-id");
            var route = $(this).attr("data-route");
            $.ajax({
                url: route,
                method: "GET",
                data: {
                    id: id,
                },
                beforeSend: function() {},
                success: function(res) {
                    console.log(res)
                    if (res.success == true) {
                        $(".document_row_" + id).remove()
                        table.draw()
                        showSuccess(res.message)
                    }
                },
                error: function(xhr, status, error) {
                    console.log(res);
                }
            });
        });

        $(document).on("click", ".delete-document-with-attachment", function() {
            var id = $(this).attr("data-id");
            var route = $(this).attr("data-route");


            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert user!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Delete!',
                customClass: {
                    confirmButton: 'btn btn-primary me-2',
                    cancelButton: 'btn btn-label-secondary'
                },
                buttonsStyling: false
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        url: route,
                        method: "GET",
                        data: {
                            id: id,
                        },
                        beforeSend: function() {},
                        success: function(res) {
                            console.log(res)
                            if (res.success == true) {
                                table.draw();
                                showSuccess(res.message)
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log(res);
                        }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {

                }
            });





        });



        // $(document).on('click', '.del-btn', function() {
        //     var slug = $(this).attr('data-slug');
        //     var thi = $(this);
        //     var delete_url = $(this).attr('data-del-url');

        //     $.ajaxSetup({
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         }
        //     });
        //     $.ajax({
        //         url: delete_url,
        //         type: 'POST',
        //         success: function(response) {
        //             if (response) {
        //                 $('#id-' + slug).remove();
        //                 toastr.success('You have deleted record successfully.');
        //             } else {
        //                 toastr.error('Sorry something went wrong.');
        //             }
        //         }
        //     });
        // });

        // $(document).on('click', '.update-btn', function() {
        //     var update_url = $(this).attr('data-url');
        //     var title = $(this).parents('.document_data').find('#title').val();

        //     $.ajaxSetup({
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         }
        //     });
        //     $.ajax({
        //         url: update_url,
        //         type: 'POST',
        //         data: {
        //             title: title
        //         },
        //         success: function(response) {
        //             if (response) {
        //                 toastr.success('You have updated title successfully.');
        //                 setTimeout(function() {
        //                     location.reload();
        //                 }, 1500); // 5000 milliseconds = 5 seconds
        //             } else {
        //                 toastr.error('Sorry something went wrong.');
        //             }
        //         }
        //     });
        // });
        // var table = $('.data_table').DataTable();
        // if ($.fn.DataTable.isDataTable('.data_table')) {
        //     table.destroy();
        // }


    });

    function selectInit(){
            setTimeout(() => {
                console.log('select');
                $('.select2').each(function () {
                    $(this).select2({
                        // theme: 'bootstrap-5',
                        dropdownParent: $(this).parent(),
                    });
                });
            }, 1000);
        }
</script>
@endpush