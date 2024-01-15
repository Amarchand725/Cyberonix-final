@foreach(SubPermissions($model->label) as $label)
    @php $permission_lab = explode('-', $label->name) @endphp
    @if($permission_lab[1]=='list')
        <span class="badge bg-label-success">View</span>
    @elseif($permission_lab[1]=='create')
        <span class="badge bg-label-primary">Create</span>
    @elseif($permission_lab[1]=='edit')
        <span class="badge bg-label-info">Edit</span>
    @elseif($permission_lab[1]=='delete')
        <span class="badge bg-label-danger">Delete</span>
    @elseif($permission_lab[1]=='status')
        <span class="badge bg-label-success">Status</span>
    @else
        <span class="badge bg-label-success">
            {{ Str::title($label->name) }}
        </span>
    @endif
@endforeach
