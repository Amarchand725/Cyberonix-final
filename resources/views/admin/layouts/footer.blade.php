<div style="display: none;" class="customize_alert_message alert alert-success" role="alert"></div>
<div style="display: none;" class="customize_alert_message alert alert-danger" role="alert"></div>
<div class="chat_suppot_end" style="display:none !important;">
@if(!request()->is('chat'))
  @php
    $chatSupportData=chatSupportData();
  @endphp
  <ul class="list-unstyled chat-contact-list" id="chat-list">
      <li class="chat-contact-list-item chat-contact-list-item-title">
        <h5 class="text-primary mb-0">Administrator</h5>
      </li>
      <group id="groupAdministrator">
      @php $chatUsers=array();  @endphp
      @foreach ($chatSupportData['adminUsers'] as $key=>$adminUser)
        @if($chatSupportData['authUser']->id!=$adminUser->id && !in_array($adminUser->id, $chatUsers))
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
                  <img src="{{ asset('public/admin/assets/img/avatars') }}/{{ $adminUser->profile->profile }}" alt="Avatar" class="rounded-circle">
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
      <li class="chat-contact-list-item chat-contact-list-item-title">
        <h5 class="text-primary mb-0">Finance</h5>
      </li>
      <group id="groupFinance">
      @foreach ($chatSupportData['financeUsers'] as $key=>$financeUser)
        @if($chatSupportData['authUser']->id!=$financeUser->id && !in_array($financeUser->id, $chatUsers))
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
                  <img src="{{ asset('public/admin/assets/img/avatars') }}/{{ $financeUser->profile->profile }}" alt="Avatar" class="rounded-circle">
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
      <li class="chat-contact-list-item chat-contact-list-item-title">
        <h5 class="text-primary mb-0">I.T</h5>
      </li>
      <group id="groupIT">
      @foreach ($chatSupportData['itUsers'] as $key=>$itUser)
        @if($chatSupportData['authUser']->id!=$itUser->id && !in_array($itUser->id, $chatUsers))
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
                  <img src="{{ asset('public/admin/assets/img/avatars') }}/{{ $itUser->profile->profile }}" alt="Avatar" class="rounded-circle">
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
      <li class="chat-contact-list-item chat-contact-list-item-title">
        <h5 class="text-primary mb-0">My Team</h5>
      </li>
      <group id="groupMyteam">
      @php
          $randomColorArray = array('#FF6633', '#FFB399', '#FF33FF', '#FFFF99', '#00B3E6', '#E6B333', '#3366E6', '#999966', '#99FF99', '#B34D4D', '#80B300', '#809900', '#E6B3B3', '#6680B3', '#66991A', '#FF99E6', '#CCFF1A', '#FF1A66', '#E6331A', '#33FFCC', '#66994D', '#B366CC', '#4D8000', '#B33300', '#CC80CC', '#66664D', '#991AFF', '#E666FF', '#4DB3FF', '#1AB399', '#E666B3', '#33991A', '#CC9999', '#B3B31A', '#00E680', '#4D8066', '#809980', '#E6FF80', '#1AFF33', '#999933', '#FF3380', '#CCCC00', '#66E64D', '#4D80CC', '#9900B3', '#E64D66', '#4DB380', '#FF4D4D', '#99E6E6', '#6666FF');
      @endphp
      @foreach ($chatSupportData['team_members'] as $key=>$team_member)
        @if($chatSupportData['authUser']->id!=$team_member->id && !in_array($team_member->id, $chatUsers))
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
                  <img src="{{ asset('public/admin/assets/img/avatars') }}/{{ $team_member->profile->profile }}" alt="Avatar" class="rounded-circle">
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
    <div class="chat-history-footer shadow-sm">
      <form class="chat_message_send d-flex justify-content-between align-items-center">
        <input type="hidden" name="id" class="id" />
        <input type="hidden" name="senderid" class="senderid" value="{{Auth::user()->id}}">
        <input type="hidden" name="recievername" class="recievername" value="{{Auth::user()->first_name.' '.Auth::user()->last_name}}">
        @php
          if(!empty(Auth::user()->profile->profile)){
            $senderProfile=Auth::user()->profile->profile;
            $senderProfileName='';
          }else{
            $senderProfile='';
            $senderProfileName=substr(Auth::user()->first_name, 0, 1).substr(Auth::user()->last_name, 0, 1);
          }
        @endphp
        <input type="hidden" name="senderProfile" class="senderProfile" value="{{$senderProfile}}">
        <input type="hidden" name="senderProfileName" class="senderProfileName" value="{{$senderProfileName}}">
        <input type="hidden" name="userProfile" class="userProfile" value="">
        <input type="hidden" name="userProfileName" class="userProfileName" value="">
        <input
          class="form-control message-input border-0 me-3 shadow-none"
          placeholder="Type your message here"
        />
        <div class="message-actions d-flex align-items-center">
          <label for="attach-doc" class="form-label mb-0">
            <i class="ti ti-photo ti-sm cursor-pointer mx-3"></i>
            <input type="file" id="attach-doc" name="file[]" multiple accept="image/*" hidden />
            <span class="uploadedNumber"></span>
          </label>
        </div>
      </form>
    </div>
@endif
</div>
<footer class="content-footer footer bg-footer-theme">
    <div class="container-xxl">
      <div
        class="footer-container d-flex align-items-center justify-content-between py-2 flex-md-row flex-column"
      >
        <div>
            ©
            <script>
                document.write(new Date().getFullYear());
            </script>
            <a href="javascript:void(0);" class="fw-semibold">{{ settings()->name??'-' }}</a>
        </div>
      </div>
    </div>
</footer>
