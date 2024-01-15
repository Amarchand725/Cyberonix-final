@extends('admin.layouts.app')
@section('title', $title)

@push('styles')
@endpush

@section('content')
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <div class="container-xxl flex-grow-1 container-p-y">
                <div class="card mb-3">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card-header">
                                <h4 class="fw-bold mb-0"><span class="text-muted fw-light">Home /</span>
                                    {{ $title ?? 'Log Details' }}</h4>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-end align-item-center mt-4">
                                {{--  <div class="dt-buttons btn-group flex-wrap">
                                    <a href="{{ route('pre_employees.convert-pdf', $model->id) }}" data-toggle="tooltip" data-placement="top" title="Download as PDF" class="btn btn-info">
                                        <span>
                                            <span class="d-none d-sm-inline-block"><i class="fa fa-download"></i> </span>
                                        </span>
                                    </a>
                                </div>  --}}
                                <div class="dt-buttons btn-group flex-wrap">
                                    <a href="{{ route('logs.index') }}" class="btn btn-secondary btn-primary mx-3"
                                        data-toggle="tooltip" data-placement="top" title="List of Pre-Employees"
                                        tabindex="0" aria-controls="DataTables_Table_0" type="button">
                                        <span>
                                            <i class="ti ti-list me-0 me-sm-1 ti-xs"></i>
                                            <span class="d-none d-sm-inline-block">View All</span>
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md mb-4 mb-md-2">
                        <div class="accordion accordion-b mt-3" id="accordionExample">
                            <!--Manager-->
                            <div class="card accordion-item mb-4">
                                <h2 class="accordion-header py-2 fw-bold" id="headingThree">
                                    <button type="button" class="accordion-button show" data-bs-toggle="collapse"
                                        data-bs-target="#managerDetail" aria-expanded="false" aria-controls="managerDetail">
                                        <h5 class="m-0 fw-bold text-dark">Log Details</h5>
                                    </button>
                                </h2>
                                <div id="managerDetail" class="accordion-collapse show" aria-labelledby="headingThree"
                                    data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <div class="datatable mb-3">
                                            <div class="table-responsive custom-scrollbar table-view-responsive">
                                                <table class="table table-striped table-responsive custom-table ">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">User</th>
                                                            <th scope="col">Data</th>
                                                            <th scope="col">Type</th>
                                                            <th scope="col">Event</th>
                                                            <th scope="col">Remarks</th>
                                                            <th scope="col">IP</th>
                                                            <th scope="col">Date</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>{!! isset($record->user) && !empty($record->user) ? userEmployeeWithHtml($record->user) : '-' !!}</td>
                                                            <td>{!! isset($record->model_name) && !empty($record->model_name) ? getModelTitleName($record) : '-' !!}</td>
                                                            <td>
                                                                <span
                                                                    class="badge bg-label-{{ getLogTypeClass($record)->class ?? 'info' }}">{{ getLogTypeClass($record)->name ?? 'N/A' }}</span>
                                                            </td>
                                                            <td>{{ isset($record->modelEvent->event_name) && !empty($record->modelEvent->event_name) ? $record->modelEvent->event_name : '-' }}</td>
                                                            <td>{{ isset($record->remarks) && !empty($record->remarks) ? $record->remarks : '-' }}</td>
                                                            <td>{{ isset($record->ip) && !empty($record->ip) ? $record->ip : '-' }}
                                                            </td>
                                                            <td>{{ formatDate($record->created_at) }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if (isset($file_name) && !empty($file_name))
                                @include("admin.logs.".$file_name, ['old' => $old, "new" => $new])
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
@endpush
