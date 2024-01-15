<div class="content py-primary">
    <div class="card-body pb-0">
        <ul class="timeline mt-5 mb-0">
            @if(!empty($histories))
            @foreach ($histories as $history)
            <li class="timeline-item timeline-item-secondary pb-4 border-left-dashed">
                <span class="timeline-indicator timeline-indicator-primary">
                    <i class="ti ti-user-circle"></i>
                </span>
                <div class="timeline-event">
                    <div class="timeline-header border-bottom mb-3">
                        <h6 class="mb-0">
                            {{$history->assetDetail->asset->name ?? '-'}} / {{$history->assetDetail->uid ?? '-'}}
                        </h6>
                        <span class="text-muted">
                            @if(!empty($history->performer))
                            {!! userWithHtml($history->performer) !!}
                            @endif
                        </span>
                    </div>
                    <div class="d-flex justify-content-between flex-wrap mb-2">
                        <div>
                            <span>
                                @if(!empty($history->employee))
                                {!! userWithHtml($history->employee) !!}
                                @endif
                            </span>
                            <i class="ti ti-arrow-right scaleX-n1-rtl mx-3"></i>
                            <span>
                                @if(!empty($history->date))
                                {{ formatDate($history->date) }}
                                @endif
                                @if(!empty($history->type))
                                @if($history->type == 1)
                                 - &nbsp;<span class="badge bg-label-success">Assign</span>
                                @else
                                - &nbsp;<span class="badge bg-label-danger">UnAssign</span>
                                @endif
                                @endif
                            </span>
                            <p class="mt-1 mb-0">
                                <i class="ti ti-arrow-right scaleX-n1-rtl mx-3"></i>  <strong>Remarks: </strong> {{$history->remarks ?? '-'}}
                            </p>
                        </div>
                        <div>

                        </div>
                    </div>
                </div>
            </li>
            @endforeach
            @endif

            @if(isset($damage) && !empty($damage))
            <li class="timeline-item timeline-item-secondary pb-4 border-left-dashed">
                <span class="timeline-indicator timeline-indicator-primary">
                    <i class="ti ti-user-circle"></i>
                </span>
                <div class="timeline-event">
                    <div class="timeline-header border-bottom mb-3">
                        <h6 class="mb-0">
                            {{$damage->assetDetail->asset->name ?? '-'}} / {{$damage->assetDetail->uid ?? '-'}}
                        </h6>
                        <span class="text-muted">
                            @if(!empty($damage->performer))
                            {!! userWithHtml($damage->performer) !!}
                            @endif
                        </span>
                    </div>
                    <div class="d-flex justify-content-between flex-wrap mb-2">
                        <div>
                            <span class="mb-1 d-block">
                                @if(!empty($damage->lastAssignee))
                                {!! userWithHtml($damage->lastAssignee) !!}
                                @else
                                <strong class="text-danger">Not assigned to any user at the time of damage</strong>
                                @endif
                            </span>
                            <i class="ti ti-arrow-right scaleX-n1-rtl mx-3"></i>
                            <span>
                                @if(!empty($damage->created_at))
                                {{ formatDateTime($damage->created_at) }} - <span class="badge bg-label-danger">Damage</span>
                                @endif

                            </span>
                            <p class="mt-1 mb-0">
                                <i class="ti ti-arrow-right scaleX-n1-rtl mx-3"></i>  <strong>Remarks: </strong> {{$damage->remarks ?? '-'}}
                            </p>
                        </div>
                        <div>

                        </div>
                    </div>
                </div>
            </li>
            @endif
        </ul>
    </div>
</div>