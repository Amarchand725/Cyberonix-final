<input type="hidden" name="user_id" id="user_id" value="{{ $user->id }}">
<div class="row">
    <div class="mb-3 col-12">
        <label class="form-label" for="working_shift_id">Work Shifts <span class="text-danger">*</span></label>
        <select id="working_shift_id" name="working_shift_id" class="form-select">
            <option value="" selected>Select Work Shift</option>
            @foreach ($shifts as $shift)
                <option value="{{ $shift->id }}"
                    @if(isset($user->userWorkingShift) && !empty($user->userWorkingShift->working_shift_id))
                        {{ $user->userWorkingShift->working_shift_id==$shift->id?'selected':'' }}
                    @endif>
                    {{ $shift->name }}
                </option>
            @endforeach
        </select>
        <div class="fv-plugins-message-container invalid-feedback"></div>
        <span id="work_shift_id_error" class="text-danger error"></span>
    </div>
</div>
<div class="row">
    <div class="mb-3 col-12">
        <label class="form-label" for="start_date">Start Date <span class="text-danger">*</span></label>
        <input type="date" class="form-control flatpickr-validation" value="" id="start_date" name="start_date" required />
        <div class="fv-plugins-message-container invalid-feedback"></div>
        <span id="start_date_error" class="text-danger error"></span>
    </div>
</div>