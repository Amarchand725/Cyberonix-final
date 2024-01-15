@php $cc_emails = json_decode($model->cc_emails) @endphp
@if(!empty($cc_emails))
    <ul class="list-unstyled mb-0">
        @foreach($cc_emails as $cc_email)
            @php $cc_email = $cc_email @endphp
            @if($cc_email=='to_employee')
                @php $cc_email = 'To Employee' @endphp
            @elseif($cc_email=='to_ra')
                @php $cc_email = 'To Reporting Authority' @endphp
            @endif
            @if(is_numeric($cc_email))
                @php $cc_email = getAuthorizeUserName($cc_email) @endphp
            @endif

            <li class="mb-2"><i class="fa fa-check-circle text-primary me-2"></i> {{ $cc_email }}</li>
        @endforeach
    </ul>
@else
    N/A
@endif
