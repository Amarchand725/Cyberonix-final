@php $to_emails = json_decode($model->to_emails) @endphp
@if(!empty($to_emails))
<ul class="list-unstyled mb-0">
    @foreach($to_emails as $to_email)
        @php $to_email = $to_email @endphp
        @if($to_email=='to_employee')
            @php $to_email = 'To Employee' @endphp
        @elseif($to_email=='to_ra')
            @php $to_email = 'To Reporting Authority' @endphp
        @endif
        @if(is_numeric($to_email))
            @php $to_email = getAuthorizeUserName($to_email) @endphp
        @endif

        <li class="mb-2"><i class="fa fa-check-circle text-primary me-2"></i> {{ $to_email }}</li>
    @endforeach
</ul>
@else
    N/A
@endif
