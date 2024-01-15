<div class="col-12 col-md-12 mb-2">
    <label class="form-label" for="title">Category <span class="text-danger">*</span></label>
    <select name="category_id" id="category_id" class="form-control select2">
        <option value="">---Select---</option>
        @if(!empty(categoryList()))
        @foreach(categoryList() as $category)
        <option value="{{$category->id ?? ''}}" @if(!empty($model->category_id) && $model->category_id == $category->id ) selected @endif>{{$category->name ?? ''}}</option>
        @endforeach
        @endif
    </select>
    <div class="fv-plugins-message-container invalid-feedback"></div>
    <span id="category_id_error" class="text-danger error"></span>
</div>

<div class="col-12 col-md-12 mb-2">
    <label class="form-label" for="title">Name <span class="text-danger">*</span></label>
    <input type="text" id="name" name="name" class="form-control" placeholder="Enter name" value="{{$model->name ?? '' }}" />
    <div class="fv-plugins-message-container invalid-feedback"></div>
    <span id="name_error" class="text-danger error"></span>
</div>

<div class="col-12 col-md-12 mb-2">
    <label class="form-label" for="title">Quantity <span class="text-danger">*</span></label>
    <input type="number" id="quantity" name="quantity" class="form-control" placeholder="Enter Quantity" min="1" value="{{$model->quantity ?? '' }}" />
    <div class="fv-plugins-message-container invalid-feedback"></div>
    <span id="quantity_error" class="text-danger error"></span>
</div>

 