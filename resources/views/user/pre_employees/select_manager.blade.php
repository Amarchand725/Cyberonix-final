<div class="row mt-2">
    <div class="">
        <label class="form-label" for="manager_id">Manager</label> <span class="text-danger">*</span>
        <select class="form-select select2" id="manager_id" name="manager_id">
            <option value="" selected>Select Manager</option>
            @foreach ($data['managers'] as $manager)
                <option value="{{ $manager->id }}">{{ $manager->first_name }} {{ $manager->last_name }}</option>
            @endforeach
        </select>
        <div class="fv-plugins-message-container invalid-feedback"></div>
        <span id="manager_id_error" class="text-danger error"></span>
    </div>
</div>