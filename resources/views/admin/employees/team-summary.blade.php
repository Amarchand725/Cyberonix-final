    @if(!empty($employees_check_ins))
        @php $counter = 1; @endphp 
        @foreach($employees_check_ins as $employees_check_in)
            <tr>
                <td>{{ $counter++ }} .</td>
                @if($employees_check_in->type=='absent')   
                    <td>
                        {{ $employees_check_in->first_name??'-' }} {{ $employees_check_in->last_name??'-' }}
                    </td>
                    <td>{{ date('d F Y', strtotime($employees_check_in->in_date)) }}</td>
                    <td>
                        {!! $employees_check_in->label !!}
                    </td>
                    <td>
                        {{ $employees_check_in->in_date }}
                    </td>
                @else
                    <td>
                        @if(isset($employees_check_in->hasEmployee) && !empty($employees_check_in->hasEmployee->first_name))
                            {{ $employees_check_in->hasEmployee->first_name }} {{ $employees_check_in->hasEmployee->last_name }}
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ date('d F Y', strtotime($employees_check_in->in_date)) }}</td>
                    <td>
                        {!! $employees_check_in->label !!}
                    </td>
                    <td>
                        {{ date('h:i A', strtotime($employees_check_in->in_date)) }}
                    </td>
                @endif
            </tr>
        @endforeach
    @endif
                                