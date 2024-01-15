<div class="form-check">
    @php $bool = '' @endphp 
        
    @can('allow_approve_additional_discrepancies-create')
        @php $bool = 1 @endphp
    @endcan
    
    @if($model->is_additional==1 && Auth::user()->hasRole('Department Manager') && $bool=='' )
        @if($model->status==1)
            <input class="form-check-input" type="checkbox" disabled checked>
        @else
            <input class="form-check-input" type="checkbox" disabled value="{{ $model->id }}" id="checkbox">
        @endif 
    @elseif(Auth::user()->hasRole('Admin'))
        @if($model->status==1)
            <input class="form-check-input" type="checkbox" disabled checked>
        @else
            <input class="form-check-input checkbox" type="checkbox" value="{{ $model->id }}" id="checkbox">
        @endif 
    @else
        @if($model->status==1)
            <input class="form-check-input" type="checkbox" disabled checked>
        @else
            <input class="form-check-input checkbox" type="checkbox" value="{{ $model->id }}" id="checkbox">
        @endif    
    @endif
</div>