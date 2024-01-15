<input type="hidden" name="user_id" value="{{$model->user_id}}" />
<div class="col-12 col-md-12 mb-3">
    <label class="form-label" for="status">Stationary Category<span class="text-danger">*</span></label>
    <select name="stationary_category" class="form-control" id="status">
        <option value="" >Select Stationary Category</option>
        @foreach($stationaryCategories as $stationary_category)
            <option @if($model->stationary_category_id == $stationary_category->id) selected @endif value="{{ $stationary_category->id }}" >{{ $stationary_category->stationary_category }}</option>
        @endforeach
    </select>
</div>
<div class="col-12 col-md-12 mb-3">
    <label class="form-label" for="name">Stationary Quantity<span class="text-danger">*</span></label>
    <input type="number" name="stationary_quantity" id="stationary_quantity" value="{{ $model->quantity }}" class="form-control" placeholder="Enter Stationary Quantity" />
    <div class="fv-plugins-message-container invalid-feedback"></div>
    <span id="stationary_quantity_error" class="text-danger error"></span>
</div>

<div class="col-12 col-md-12">
    <label class="form-label" for="name">Stationary Price<span class="text-danger">*</span></label>
    <input type="number" name="stationary_price" id="stationary_price" value="{{ $model->price }}" class="form-control" placeholder="Enter Stationary Price" />
    <div class="fv-plugins-message-container invalid-feedback"></div>
    <span id="stationary_price_error" class="text-danger error"></span>
</div>
