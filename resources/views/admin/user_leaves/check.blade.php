<div class="form-check  id_{{$model->id}}" >
    @if($model->status==1)
        <input class="form-check-input" type="checkbox" disabled checked>
    @else
        <input class="form-check-input checkbox" type="checkbox" value="{{ $model->id }}" id="checkbox">
    @endif
</div>
