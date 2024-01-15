@extends('admin.layouts.app')
@section('title', $title. ' -  ' . appName())
@push('styles')
    <link rel="stylesheet" href="{{ asset('public/admin') }}/assets/vendor/libs/bootstrap-maxlength/bootstrap-maxlength.css" />
    <link rel="stylesheet" href="{{ asset('public/admin') }}/assets/vendor/css/pages/app-chat.css" />
@endpush
@section('content')
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="app-chat card overflow-hidden">
            <div class="row g-0">
                <!-- Sidebar Left -->
                <div class="col app-chat-sidebar-left app-sidebar overflow-hidden" id="app-chat-sidebar-left">
                    <div
                      class="chat-sidebar-left-user sidebar-header d-flex flex-column justify-content-center align-items-center flex-wrap px-4 pt-5"
                    >
                        <div class="avatar avatar-xl avatar-online">
                            @if(!empty($model->profile->profile))
                                <img src="{{ resize(asset('public/admin/assets/img/avatars').'/'.$model->profile->profile, null) }}" alt="Avatar" class="rounded-circle">
                            @else
                                <span class="avatar-initial rounded-circle bg-label-primary">{{substr($model->first_name, 0, 1).substr($model->last_name, 0, 1)}}</span>
                            @endif
                        </div>
                        <h5 class="mt-2 mb-0">{{ $model->first_name }} {{ $model->last_name }}</h5>
                        @if(isset($model->jobHistory->designation->title) && !empty($model->jobHistory->designation->title))
                            <small>{{$model->jobHistory->designation->title}}</small>
                        @else
                            <small>N/A</small>
                        @endif
                      <i
                        class="ti ti-x ti-sm cursor-pointer close-sidebar"
                        data-bs-toggle="sidebar"
                        data-overlay
                        data-target="#app-chat-sidebar-left"
                      ></i>
                      <div class="userDetail">
                            <p class="mt-4 small text-uppercase text-muted">Details</p>
                            <div class="info-container">
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                      <span class="fw-semibold me-1">Employee:</span>
                                      <span>
                                          {{ $model->first_name }} {{ $model->last_name }}
                                      </span>
                                    </li>
                                    <li class="mb-2 pt-1">
                                      <span class="fw-semibold me-1">Email:</span>
                                      <span>{{ $model->email??'-' }}</span>
                                    </li>
                                    <li class="mb-2">
                                      <span class="fw-semibold me-1">Department:</span>
                                      <span>
                                          @if(isset($model->departmentBridge->department) && !empty($model->departmentBridge->department->name)) {{ $model->departmentBridge->department->name }} @else - @endif
                                      </span>
                                    </li>
                                    <li class="mb-2">
                                      <span class="fw-semibold me-1">Timing:</span>
                                      <span>
                                          @if(isset($model->userWorkingShift->workShift) && !empty($model->userWorkingShift->workShift->name))
                                              {{ $model->userWorkingShift->workShift->name }}
                                          @else
                                              @if(isset($model->departmentBridge->department->departmentWorkShift->workShift) && !empty($model->departmentBridge->department->departmentWorkShift->workShift->name))
                                                  {{ $model->departmentBridge->department->departmentWorkShift->workShift->name }}
                                              @else
                                              -
                                              @endif
                                          @endif
                                      </span>
                                  </li>
                                  <li class="mb-2 pt-1">
                                      <span class="fw-semibold me-1">Role:</span>
                                      <span>
                                          @if(!empty($model->getRoleNames())) @foreach ($model->getRoleNames() as $roleName) {{ $roleName }}, @endforeach @else - @endif
                                      </span>
                                  </li>
                                  <li class="mb-2">
                                      <span class="fw-semibold me-1">Employment Status:</span>
                                      <span>
                                          @if(isset($model->jobHistory->userEmploymentStatus->employmentStatus) && !empty($model->jobHistory->userEmploymentStatus->employmentStatus->name))
                                          <span class="badge bg-label-success"> {{ $model->jobHistory->userEmploymentStatus->employmentStatus->name }}</span>
                                          @else - @endif
                                      </span>
                                  </li>
                                  <li class="mb-2 pt-1">
                                      <span class="fw-semibold me-1">Status:</span>
                                      @if($model->status)
                                      <span class="badge bg-label-success">Active</span>
                                      @else
                                      <span class="badge bg-label-danger">In-Active</span>
                                      @endif
                                  </li>
                                  <li class="mb-2 pt-1">
                                      <span class="fw-semibold me-1">Phone:</span>
                                      <span>
                                          @if(isset($model->profile) && !empty($model->profile->phone_number)) {{ $model->profile->phone_number }} @else - @endif
                                      </span>
                                  </li>
                                  <li class="mb-2 pt-1">
                                      <span class="fw-semibold me-1">Gender:</span>
                                      <span>
                                          @if(isset($model->profile) && !empty($model->profile->gender)) {{ Str::ucfirst($model->profile->gender) }} @else - @endif
                                      </span>
                                  </li>
                                  <li class="mb-2 pt-1">
                                      <span class="fw-semibold me-1">Birth Day:</span>
                                      <span>
                                          @if(isset($model->profile) && !empty($model->profile->date_of_birth)) {{ date('d M Y', strtotime($model->profile->date_of_birth)) }} @else - @endif
                                      </span>
                                  </li>
                              </ul>
                          </div>
                      </div>
                    </div>
                </div>
                <!-- /Sidebar Left-->

                <!-- Chat & Contacts -->
                <div
                    class="col app-chat-contacts app-sidebar flex-grow-0 overflow-hidden border-end"
                    id="app-chat-contacts"
                  >
                    <div class="sidebar-header">
                      <div class="d-flex align-items-center me-3 me-lg-0">
                        <div
                          class="flex-shrink-0 avatar avatar-online me-3"
                          data-bs-toggle="sidebar"
                          data-overlay="app-overlay-ex"
                          data-target="#app-chat-sidebar-left"
                        >
                          @if(!empty($data['authProfile']->profile))
                              <img src="{{ resize(asset('public/admin/assets/img/avatars').'/'.$data['authProfile']->profile, null) }}" alt="Avatar" class="rounded-circle">
                          @else
                              <span class="avatar-initial rounded-circle bg-label-primary">{{substr($data['authUser']->first_name, 0, 1).substr($data['authUser']->last_name, 0, 1)}}</span>
                          @endif
                        </div>
                        <div class="flex-grow-1 input-group input-group-merge rounded-pill">
                          <span class="input-group-text" id="basic-addon-search31"><i class="ti ti-search"></i></span>
                          <input
                            type="text"
                            class="form-control"
                            placeholder="Search Users..."
                            aria-label="Search..."
                            id="search_user_chat_input"
                          />
                        </div>
                      </div>
                      <i
                        class="ti ti-x cursor-pointer d-lg-none d-block position-absolute mt-2 me-1 top-0 end-0"
                        data-overlay
                        data-bs-toggle="sidebar"
                        data-target="#app-chat-contacts"
                      ></i>
                    </div>
                    <hr class="container-m-nx m-0" />
                    <div class="sidebar-body">
                      <!-- Chats -->
                      <ul class="list-unstyled chat-contact-list" id="chat-list">
                        @if(!Auth::user()->hasRole('Admin'))
                            <li class="chat-contact-list-item chat-contact-list-item-title">
                              <h5 class="text-primary mb-0">Administrator</h5>
                            </li>
                        @endif
                        <group id="groupAdministrator">
                        @php $chatUsers=array();  @endphp
                        @foreach ($data['adminUsers'] as $key=>$adminUser)
                          @if($data['authUser']->id!=$adminUser->id && !in_array($adminUser->id, $chatUsers))
                          @php
                            if(isset($adminUser->jobHistory->designation->title) && !empty($adminUser->jobHistory->designation->title)){
                              $designationName=$adminUser->jobHistory->designation->title;
                            }else{
                              $designationName='N/A';
                            }
                          @endphp
                          <li class="chat-contact-list-item userChat userChatMessage{{$adminUser->id}}" data-designation='{{$designationName}}' data-role='administrator' data-userID={{$adminUser->id}} data-userName="{{$adminUser->first_name.' '.$adminUser->last_name}}" @if(!empty($adminUser->profile->profile)) data-userImage="{{ $adminUser->profile->profile }}" data-userImageType="image" @else data-userImage="{{substr($adminUser->first_name, 0, 1).substr($adminUser->last_name, 0, 1)}}" data-userImageType="name" @endif data-onlineStatus="Offline" data-position=0>
                            <a class="d-flex align-items-center">
                              <div class="avatar avatar-offline d-block flex-shrink-0">
                                @if(!empty($adminUser->profile->profile))
                                    <img src="{{ resize(asset('public/admin/assets/img/avatars').'/'.$adminUser->profile->profile, null) }}" alt="Avatar" class="rounded-circle">
                                @else
                                    <span class="avatar-initial rounded-circle bg-label-primary">{{substr($adminUser->first_name, 0, 1).substr($adminUser->last_name, 0, 1)}}</span>
                                @endif
                              </div>
                              <div class="chat-contact-info flex-grow-1 ms-2">
                                <h6 class="chat-contact-name text-truncate m-0">{{$adminUser->first_name.' '.$adminUser->last_name}}</h6>
                                <p class="chat-contact-status text-muted text-truncate mb-0">{{$designationName}}</p>
                              </div>
                              <span class="unread_messages_appear_here_set" id="unread_messages_appear_here_set{{$adminUser->id}}"></span>
                            </a>
                          </li>
                          @php array_push($chatUsers,$adminUser->id); @endphp
                          @endif
                        @endforeach
                        </group>
                        @if(count($data['financeUsers']) > 0)
                            <li class="chat-contact-list-item chat-contact-list-item-title">
                              <h5 class="text-primary mb-0">HR</h5>
                            </li>
                            <group id="groupFinance">
                            @foreach ($data['financeUsers'] as $key=>$financeUser)
                              @if($data['authUser']->id!=$financeUser->id && !in_array($financeUser->id, $chatUsers))
                              @php
                                if(isset($financeUser->jobHistory->designation->title) && !empty($financeUser->jobHistory->designation->title)){
                                  $designationName=$financeUser->jobHistory->designation->title;
                                }else{
                                  $designationName='N/A';
                                }
                              @endphp
                              <li class="chat-contact-list-item userChat userChatMessage{{$financeUser->id}}" data-designation='{{$designationName}}' data-role='finance' data-userID={{$financeUser->id}} data-userName="{{$financeUser->first_name.' '.$financeUser->last_name}}" @if(!empty($financeUser->profile->profile)) data-userImage="{{ $financeUser->profile->profile }}" data-userImageType="image" @else data-userImage="{{substr($financeUser->first_name, 0, 1).substr($financeUser->last_name, 0, 1)}}" data-userImageType="name" @endif data-onlineStatus="Offline" data-position=0>
                                <a class="d-flex align-items-center">
                                  <div class="avatar avatar-offline d-block flex-shrink-0">
                                    @if(!empty($financeUser->profile->profile))
                                        <img src="{{ resize(asset('public/admin/assets/img/avatars').'/'.$financeUser->profile->profile, null) }}" alt="Avatar" class="rounded-circle">
                                    @else
                                        <span class="avatar-initial rounded-circle bg-label-primary">{{substr($financeUser->first_name, 0, 1).substr($financeUser->last_name, 0, 1)}}</span>
                                    @endif
                                  </div>
                                  <div class="chat-contact-info flex-grow-1 ms-2">
                                    <h6 class="chat-contact-name text-truncate m-0">{{$financeUser->first_name.' '.$financeUser->last_name}}</h6>
                                    <p class="chat-contact-status text-muted text-truncate mb-0">{{$designationName}}</p>
                                  </div>
                                  <span class="unread_messages_appear_here_set" id="unread_messages_appear_here_set{{$financeUser->id}}"></span>
                                </a>
                              </li>
                              @php array_push($chatUsers,$financeUser->id); @endphp
                              @endif
                            @endforeach
                            </group>
                        @endif
                        @if(count($data['itUsers']) > 0)
                            <li class="chat-contact-list-item chat-contact-list-item-title">
                              <h5 class="text-primary mb-0">I.T</h5>
                            </li>
                            <group id="groupIT">
                                @foreach ($data['itUsers'] as $key=>$itUser)
                                    @if($data['authUser']->id!=$itUser->id && !in_array($itUser->id, $chatUsers))
                                        @php
                                            if(isset($itUser->jobHistory->designation->title) && !empty($itUser->jobHistory->designation->title)){
                                              $designationName=$itUser->jobHistory->designation->title;
                                            }else{
                                              $designationName='N/A';
                                            }
                                        @endphp
                                        <li class="chat-contact-list-item userChat userChatMessage{{$itUser->id}}" data-designation='{{$designationName}}' data-role='it' data-userID={{$itUser->id}} data-userName="{{$itUser->first_name.' '.$itUser->last_name}}" @if(!empty($itUser->profile->profile)) data-userImage="{{ $itUser->image }}" data-userImageType="image" @else data-userImage="{{substr($itUser->first_name, 0, 1).substr($itUser->last_name, 0, 1)}}" data-userImageType="name" @endif data-onlineStatus="Offline" data-position=0>
                                            <a class="d-flex align-items-center">
                                              <div class="avatar avatar-offline d-block flex-shrink-0">
                                                @if(!empty($itUser->profile->profile))
                                                    <img src="{{ resize(asset('public/admin/assets/img/avatars').'/'.$itUser->profile->profile, null) }}" alt="Avatar" class="rounded-circle">
                                                @else
                                                    <span class="avatar-initial rounded-circle bg-label-primary">{{substr($itUser->first_name, 0, 1).substr($itUser->last_name, 0, 1)}}</span>
                                                @endif
                                              </div>
                                              <div class="chat-contact-info flex-grow-1 ms-2">
                                                <h6 class="chat-contact-name text-truncate m-0">{{$itUser->first_name.' '.$itUser->last_name}}</h6>
                                                <p class="chat-contact-status text-muted text-truncate mb-0">{{$designationName}}</p>
                                              </div>
                                              <span class="unread_messages_appear_here_set" id="unread_messages_appear_here_set{{$itUser->id}}"></span>
                                            </a>
                                        </li>
                                        @php array_push($chatUsers,$itUser->id); @endphp
                                    @endif
                                @endforeach
                            </group>
                        @endif
                        <li class="chat-contact-list-item chat-contact-list-item-title">
                          <h5 class="text-primary mb-0">My Team</h5>
                        </li>
                        <group id="groupMyteam">
                        @php
                            $randomColorArray = array('#FF6633', '#FFB399', '#FF33FF', '#FFFF99', '#00B3E6', '#E6B333', '#3366E6', '#999966', '#99FF99', '#B34D4D', '#80B300', '#809900', '#E6B3B3', '#6680B3', '#66991A', '#FF99E6', '#CCFF1A', '#FF1A66', '#E6331A', '#33FFCC', '#66994D', '#B366CC', '#4D8000', '#B33300', '#CC80CC', '#66664D', '#991AFF', '#E666FF', '#4DB3FF', '#1AB399', '#E666B3', '#33991A', '#CC9999', '#B3B31A', '#00E680', '#4D8066', '#809980', '#E6FF80', '#1AFF33', '#999933', '#FF3380', '#CCCC00', '#66E64D', '#4D80CC', '#9900B3', '#E64D66', '#4DB380', '#FF4D4D', '#99E6E6', '#6666FF');
                        @endphp
                        @foreach ($data['team_members'] as $key=>$team_member)
                          @if($data['authUser']->id!=$team_member->id && !in_array($team_member->id, $chatUsers))
                          @php
                            if(isset($team_member->jobHistory->designation->title) && !empty($team_member->jobHistory->designation->title)){
                              $designationName=$team_member->jobHistory->designation->title;
                            }else{
                              $designationName='N/A';
                            }
                          @endphp
                          <li class="chat-contact-list-item userChat userChatMessage{{$team_member->id}}" data-designation='{{$designationName}}' data-role='team' data-userID={{$team_member->id}} data-userName="{{$team_member->first_name.' '.$team_member->last_name}}" @if(!empty($team_member->profile->profile)) data-userImage="{{ $team_member->profile->profile }}" data-userImageType="image" @else data-userImage="{{substr($team_member->first_name, 0, 1).substr($team_member->last_name, 0, 1)}}" data-userImageType="name" @endif data-onlineStatus="Offline" data-position=0>
                            <a class="d-flex align-items-center">
                              <div class="avatar avatar-offline d-block flex-shrink-0">
                                @if(!empty($team_member->profile->profile))
                                    <img src="{{ resize(asset('public/admin/assets/img/avatars').'/'.$team_member->profile->profile, null) }}" alt="Avatar" class="rounded-circle">
                                @else
                                    @php
                                        $k = array_rand($randomColorArray);
                                    @endphp
                                    <span style="background-color:{{$randomColorArray[$k]}} !important;color:#fff !important;" class="avatar-initial rounded-circle bg-label-primary">{{substr($team_member->first_name, 0, 1).substr($team_member->last_name, 0, 1)}}</span>
                                @endif
                              </div>
                              <div class="chat-contact-info flex-grow-1 ms-2">
                                <h6 class="chat-contact-name text-truncate m-0">{{$team_member->first_name.' '.$team_member->last_name}}</h6>
                                <p class="chat-contact-status text-muted text-truncate mb-0">{{$designationName}}</p>
                              </div>
                              <span class="unread_messages_appear_here_set" id="unread_messages_appear_here_set{{$team_member->id}}"></span>
                            </a>
                          </li>
                          @php array_push($chatUsers,$team_member->id); @endphp
                          @endif
                        @endforeach
                        </group>
                      </ul>
                    </div>
                </div>
                <!-- /Chat contacts -->

                <!-- Chat History -->
                <div class="col app-chat-history bg-body">
                    <div class="chat-history-wrapper">
                      <div class="chat-history-header border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                          <div class="d-flex overflow-hidden align-items-center">
                           <div class="flex-shrink-0 avatar" id="chatUserboxImage">
                              <img
                                src=""
                                alt="Avatar"
                                class="rounded-circle"
                                data-bs-toggle="sidebar"
                                data-overlay
                                data-target="#app-chat-sidebar-right" style="display: none;"
                              />
                            </div>
                            <div class="chat-contact-info flex-grow-1 ms-2">
                              <h6 class="m-0" id="chatUserboxName"></h6>
                              <small class="user-status text-muted" id="chatUserboxDesignation"></small>
                            </div>
                          </div>
                          <div class="d-flex align-items-center" id="headerRightSideIcons" style="display: none !important;">
                            <span class="search-chat-box">
                              <i class="ti ti-search cursor-pointer d-sm-block d-none me-3"></i>
                              <input type="text" name="search_chat" placeholder="Search Chat..." class="form-control" id="search_chat">
                            </span>
                            <div class="dropdown">
                              <i
                                class="ti ti-dots-vertical cursor-pointer"
                                id="chat-header-actions"
                                data-bs-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false"
                              >
                              </i>
                              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="chat-header-actions">
                                <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#chatmedia_popup">Media</a>
                              </div>
                            </div>
                          </div>
                        </div>
                    </div>
                    <div class="chat-history-body bg-body">
                        <ul class="list-unstyled chat-history" id="chat-history">
                          <li class="chat-message">
                            <div class="d-flex overflow-hidden">
                              <div class="user-avatar flex-shrink-0 me-3">
                                <div class="avatar avatar-sm">
                                  <span>CC</span>
                                </div>
                              </div>
                              <div class="chat-message-wrapper flex-grow-1">
                                <div class="chat-message-text">
                                  <p class="mb-0">Lets get started! Choose the users you want to connect</p>
                                  <p class="mb-0">With, initiate conversations, and let the exchange of ideas begin.</p>
                                </div>
                                <div class="chat-message-text mt-2">
                                  <p class="mb-0">If you have any questions or need assistance, don't hesitate to reach out to our support team.</p>
                                  <p class="mb-0">We're here to ensure you have the best possible chat experience.</p>
                                </div>
                              </div>
                            </div>
                          </li>
                        </ul>
                    </div>
                      <!-- Chat message form -->
                    <div class="chat-history-footer shadow-sm">
                        <form class="chat_message_send d-flex justify-content-between align-items-center" action="{{ URL::to('/chat') }}" enctype="multipart/form-data">
                          @csrf
                          <div class="files_uploading_load" style="display: none;">
                            Files uploading
                            <div class="loading">
                              <div></div>
                              <div></div>
                              <div></div>
                            </div>
                          </div>
                          <input type="hidden" name="id" class="id" />
                          <input type="hidden" name="senderid" class="senderid" value="{{$model->id}}">
                          <input type="hidden" name="recievername" class="recievername" value="{{$model->first_name.' '.$model->last_name}}">
                          @php
                            if(!empty($model->profile->profile)){
                              $senderProfile=$model->profile->profile;
                              $senderProfileName='';
                            }else{
                              $senderProfile='';
                              $senderProfileName=substr($model->first_name, 0, 1).substr($model->last_name, 0, 1);
                            }
                          @endphp
                          <input type="hidden" name="senderProfile" class="senderProfile" value="{{$senderProfile}}">
                          <input type="hidden" name="senderProfileName" class="senderProfileName" value="{{$senderProfileName}}">
                          <input type="hidden" name="userProfile" class="userProfile" value="">
                          <input type="hidden" name="userProfileName" class="userProfileName" value="">
                          <span class="text-danger" id="text-message"></span>
                          <input class="form-control message-input border-0 me-3 shadow-none" id="message-input"
                            placeholder="Type your message here"
                          />

                          <div class="message-actions d-flex align-items-center">
                            <label for="attach-doc" class="form-label mb-0">
                              <i class="ti ti-photo ti-sm cursor-pointer mx-3"></i>
                              <input type="file" id="attach-doc" name="file[]" multiple accept="image/*" hidden />
                              <span class="uploadedNumber"></span>
                            </label>
                            <button class="btn btn-primary d-flex send-msg-btn">
                              <i class="ti ti-send me-md-1 me-0"></i>
                              <span class="align-middle d-md-inline-block d-none">Send</span>
                            </button>
                          </div>
                        </form>
                      </div>
                    </div>
                </div>
                <!-- /Chat History -->

                <!-- Sidebar Right -->
                @for($z=0;$z<count($chatUsers);$z++)
                    @foreach($data['all_users'] as $all_users)
                        @if($all_users->id == $chatUsers[$z])
                           <div class="col app-chat-sidebar-right app-sidebar overflow-hidden" id="app-chat-sidebar-right{{$chatUsers[$z]}}">
                              <div
                                class="sidebar-header d-flex flex-column justify-content-center align-items-center flex-wrap px-4 pt-5"
                              >
                                <div class="avatar avatar-xl avatar-online">
                                  @if(!empty($all_users->profile->profile))
                                      <img src="{{ resize(asset('public/admin/assets/img/avatars').'/'.$all_users->profile->profile, null) }}" alt="Avatar" class="rounded-circle">
                                  @else
                                      <span class="avatar-initial rounded-circle bg-label-primary">{{substr($all_users->first_name, 0, 1).substr($all_users->last_name, 0, 1)}}</span>
                                  @endif
                                </div>
                                <h6 class="mt-2 mb-0">{{ $all_users->first_name }} {{ $all_users->last_name }}</h6>
                                @if(isset($all_users->jobHistory->designation->title) && !empty($all_users->jobHistory->designation->title))
                                <small>{{$all_users->jobHistory->designation->title}}</small>
                                @else
                                <small>N/A</small>
                                @endif
                                <i
                                  class="ti ti-x ti-sm cursor-pointer close-sidebar d-block"
                                  data-bs-toggle="sidebar"
                                ></i>
                                <div class="userDetail">
                                    <p class="mt-4 small text-uppercase text-muted">Details</p>
                                    <div class="info-container">
                                        <ul class="list-unstyled">
                                            <li class="mb-2">
                                                <span class="fw-semibold me-1">Employee:</span>
                                                <span>
                                                    {{ $all_users->first_name }} {{ $all_users->last_name }}
                                                </span>
                                            </li>
                                            <li class="mb-2 pt-1">
                                                <span class="fw-semibold me-1">Email:</span>
                                                <span>{{ $all_users->email??'-' }}</span>
                                            </li>
                                            <li class="mb-2">
                                                <span class="fw-semibold me-1">Department:</span>
                                                <span>
                                                    @if(isset($all_users->departmentBridge->department) && !empty($all_users->departmentBridge->department->name)) {{ $all_users->departmentBridge->department->name }} @else - @endif
                                                </span>
                                            </li>
                                            <li class="mb-2">
                                                <span class="fw-semibold me-1">Timing:</span>
                                                <span>
                                                    @if(isset($all_users->userWorkingShift->workShift) && !empty($all_users->userWorkingShift->workShift->name))
                                                        {{ $all_users->userWorkingShift->workShift->name }}
                                                    @else
                                                        @if(isset($all_users->departmentBridge->department->departmentWorkShift->workShift) && !empty($all_users->departmentBridge->department->departmentWorkShift->workShift->name))
                                                            {{ $all_users->departmentBridge->department->departmentWorkShift->workShift->name }}
                                                        @else
                                                        -
                                                        @endif
                                                    @endif
                                                </span>
                                            </li>
                                            <li class="mb-2 pt-1">
                                                <span class="fw-semibold me-1">Role:</span>
                                                <span>
                                                    @if(!empty($all_users->getRoleNames())) @foreach ($all_users->getRoleNames() as $roleName) {{ $roleName }}, @endforeach @else - @endif
                                                </span>
                                            </li>
                                            <li class="mb-2">
                                                <span class="fw-semibold me-1">Employment Status:</span>
                                                <span>
                                                    @if(isset($all_users->jobHistory->userEmploymentStatus->employmentStatus) && !empty($all_users->jobHistory->userEmploymentStatus->employmentStatus->name))
                                                    <span class="badge bg-label-success"> {{ $all_users->jobHistory->userEmploymentStatus->employmentStatus->name }}</span>
                                                    @else - @endif
                                                </span>
                                            </li>
                                            <li class="mb-2 pt-1">
                                                <span class="fw-semibold me-1">Status:</span>
                                                @if($all_users->status)
                                                <span class="badge bg-label-success">Active</span>
                                                @else
                                                <span class="badge bg-label-danger">In-Active</span>
                                                @endif
                                            </li>
                                            <li class="mb-2 pt-1">
                                                <span class="fw-semibold me-1">Phone:</span>
                                                <span>
                                                    @if(isset($all_users->profile) && !empty($all_users->profile->phone_number)) {{ $all_users->profile->phone_number }} @else - @endif
                                                </span>
                                            </li>
                                            <li class="mb-2 pt-1">
                                                <span class="fw-semibold me-1">Gender:</span>
                                                <span>
                                                    @if(isset($all_users->profile) && !empty($all_users->profile->gender)) {{ Str::ucfirst($all_users->profile->gender) }} @else - @endif
                                                </span>
                                            </li>
                                            <li class="mb-2 pt-1">
                                                <span class="fw-semibold me-1">Birth Day:</span>
                                                <span>
                                                    @if(isset($all_users->profile) && !empty($all_users->profile->date_of_birth)) {{ date('d M Y', strtotime($all_users->profile->date_of_birth)) }} @else - @endif
                                                </span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                              </div>
                            </div>
                        @endif
                    @endforeach
                @endfor
                <!-- /Sidebar Right -->

                <div class="app-overlay"></div>
            </div>
        </div>
    </div>
    <!-- / Content -->

    <div class="content-backdrop fade"></div>
  </div>

<div class="modal fade in" id="chatmedia_popup" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content -->
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-bs-dismiss="modal">×</button>
              <h4 class="modal-title">Chat Media</h4>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>

@endsection
@push('js')
   <script src="{{ asset('public/admin') }}/assets/vendor/libs/bootstrap-maxlength/bootstrap-maxlength.js"></script>

   <script>
       $(document).on('click', '.send-msg-btn', function(){
            var input_message = $('#message-input').val();
            var attachment = $('#attach-doc').val();

            if(input_message=='' && attachment==''){
                return false;
            }else{
                return true;
            }
        });
     </script>
@endpush
