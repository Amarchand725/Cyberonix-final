<div class="modal fade" id="showManagerModel" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered1 modal-simple modal-add-new-cc">
        <div class="modal-content p-3 p-md-5">
            <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <h3 class="role-title mb-2" id="modal-label">{{ isset($title) && !empty($title) ? $title : "Update Manager" }}</h3>
                </div>
                <div class="col-md-12">
                    <label class="form-label" for="title">Manager <span class="text-danger">*</span></label>
                    <select name="modal_manager_id" id="modal_manager_id" class="form-control select2">
                        @if(isset($managers) && count($managers) > 0)
                        @foreach ($managers as $manager)
                        <option value="{{ $manager->id }}" @if(isset($manager_id) && !empty($manager_id) && $manager_id == $manager->id) selected @endif>{{ getUserName($manager) }}</option>
                        @endforeach
                        @endif
                    </select>
                    <div class="fv-plugins-message-container invalid-feedback"></div>
                    <span id="modal_manager_id_error" class="text-danger error"></span>
                </div>
                <input type="hidden" id="modal_employee_id" value="{{ isset($employee_id) && !empty($employee_id) ? $employee_id : '' }}">
                <div class="col-12 mt-3 action-btn">
                    <div class="demo-inline-spacing sub-btn-all" style="float: right;">
                        <button type="button" class="btn btn-primary me-sm-3 me-1 updateManager">Submit</button>
                        <button type="reset" class="btn btn-label-secondary btn-reset" data-bs-dismiss="modal" aria-label="Close">
                            Cancel
                        </button>
                    </div>
                    <div class="demo-inline-spacing loading-btn-all" style="display: none; float: right;">
                        <button class="btn btn-primary waves-effect waves-light" type="button" disabled="">
                          <span class="spinner-border me-1" role="status" aria-hidden="true"></span>
                          Loading...
                        </button>
                        <button type="reset" class="btn btn-label-secondary btn-reset" data-bs-dismiss="modal" aria-label="Close">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
