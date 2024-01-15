<div class="modal fade" id="documentUploadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-top modal-simple modal-add-new-cc">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-4">
                    <h3 class="mb-2" id="modal-label"></h3>
                </div>
                <form action="{{ route('profile.storeDocumentAsUser') }}" class="row g-3 "
                    id="uploadDocumentOfUserForm" data-method="" enctype="multipart/form-data" method="POST">
                    @csrf
                    <span id="edit-content">
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="card-title"> Add Documents </h5>
                            </div>
                            <div class="col-md-6" style="text-align: right">
                                <div class="btn-wrapper">
                                    <button type="button" data-val="2"
                                        class="btn btn-label-primary btn-sm add-more-btn"><i
                                            class="fa fa-plus"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3 border-top py-3">
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
                    </span>

                    <div class="col-12 mt-3 action-btn" style="text-align: right">
                        <div class="demo-inline-spacing sub-btn">
                            <button type="submit" class="btn btn-primary me-sm-3 me-1 btn_submit"
                                data-action="{{ route('profile.storeDocumentAsUser') }}">  <span class="spinner-border me-1 d-none"  role="status" aria-hidden="true"></span>
                                Submit</button>
                            <button type="reset" class="btn btn-label-secondary btn-reset"
                                data-bs-dismiss="modal" aria-label="Close">
                                Cancel
                            </button>
                        </div>
                        <div class="demo-inline-spacing loading-btn d-none" >
                            <button class="btn btn-primary waves-effect waves-light" type="button"
                                disabled="">
                                <span class="spinner-border me-1" role="status" aria-hidden="true"></span>
                                Loading...
                            </button>
                            <button type="reset" class="btn btn-label-secondary btn-reset"
                                data-bs-dismiss="modal" aria-label="Close">
                                Cancel
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
