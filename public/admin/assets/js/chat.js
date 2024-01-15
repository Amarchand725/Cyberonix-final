$(document).on('click','ul#chat-list li.userChat',function(){
    $('ul#chat-list li.userChat').removeClass('active');
    $(this).addClass('active');
    var userID=$(this).attr('data-userid');
    var userRole=$(this).attr('data-role');
    var userDesignation=$(this).attr('data-designation');
    var userName=$(this).attr('data-username');
    var userImage=$(this).attr('data-userimage');
    var userImageType=$(this).attr('data-userImageType');
    var onlineStatus=$(this).attr('data-onlinestatus');
    $("form.chat_message_send input.id").val(userID);
    $('h6#chatUserboxName').text(userName);
    $('small#chatUserboxDesignation').text(userDesignation);
    $("#chatmedia_popup .modal-body").html('');
    if(userImageType=='image'){
        $('div#chatUserboxImage').html('<img src="/public/admin/assets/img/avatars/'+userImage+'" alt="Avatar" class="rounded-circle app-chat-sidebar-right-btn" data-target="'+userID+'" />');
        $("form.chat_message_send input.userProfile").val(userImage);
        $("form.chat_message_send input.userProfileName").val('');
    }else{
        $('div#chatUserboxImage').html('<span class="avatar-initial rounded-circle bg-label-primary app-chat-sidebar-right-btn" data-target="'+userID+'">'+userImage+'</span>');
        $("form.chat_message_send input.userProfile").val('');
        $("form.chat_message_send input.userProfileName").val(userImage);
    }
    if(onlineStatus!=''){
        if(onlineStatus.includes('Online')){
            $('div#chatUserboxImage').removeClass('avatar-offline');
            $('div#chatUserboxImage').addClass('avatar-online');
        }else{
            $('div#chatUserboxImage').removeClass('avatar-online');
            $('div#chatUserboxImage').addClass('avatar-offline');
        }
    }else{
        $('div#chatUserboxImage').removeClass('avatar-offline');
        $('div#chatUserboxImage').removeClass('avatar-online');
    }
    $('div#chatUserboxImage').show();
    $('div#headerRightSideIcons').attr('style','display: flex !important;');
    $('.app-chat .app-chat-history .chat-history-footer').show();
    $("ul#chat-history").html('');
    setTimeout(function(){ retrive_chat_history(); }, 500);
});

$(document).on('click','.app-chat-sidebar-right-btn',function(){
    var actionID=$(this).attr('data-target');
    $('.col.app-chat-sidebar-right.app-sidebar').removeClass('show');
    $('#app-chat-sidebar-right'+actionID).addClass('show');
});

$(document).on('click','div.col.app-chat-sidebar-right.app-sidebar i.close-sidebar',function(){
    $('.col.app-chat-sidebar-right.app-sidebar').removeClass('show');
});

$(document).on('keyup','input#search_user_chat_input',function(){
  var searchString = $(this).val();
  $("ul#chat-list li.chat-contact-list-item.userChat").each(function(index, value) {
    var currentName = $(value).text()
    if(currentName.toUpperCase().indexOf(searchString.toUpperCase()) > -1) {
      $(value).show();
    } else {
      $(value).hide();
    }
  });
});

$(document).on('keyup','input#search_chat',function(){
  var searchString = $(this).val();
  $("ul#chat-history li.chat-message").each(function(index, value) {
    var currentName = $(value).text()
    if(currentName.toUpperCase().indexOf(searchString.toUpperCase()) > -1) {
      $(value).show();
    } else {
      $(value).hide();
    }
  });
});


//chat firebase integrations start here

 // Your web app's Firebase configuration
  var firebaseConfig = {
        apiKey: "AIzaSyCksA-VWJ1odbNsImwxsjtc3lpTpaJaal8",
        authDomain: "cyberonix-ltd.firebaseapp.com",
        databaseURL: "https://cyberonix-ltd-default-rtdb.firebaseio.com",
        projectId: "cyberonix-ltd",
        storageBucket: "cyberonix-ltd.appspot.com",
        messagingSenderId: "974468962637",
        appId: "1:974468962637:web:4ac9d3e07f12946ab9ff04"
      };
      // Initialize Firebase
      firebase.initializeApp(firebaseConfig);
    var database = firebase.database(),
        d = new Date,
        t = d.getTime(),
        counter = t;

//chat firebase integrations end here

function formatAMPM(date) {
  var hours = date.getHours();
  var minutes = date.getMinutes();
  var ampm = hours >= 12 ? 'PM' : 'AM';
  hours = hours % 12;
  hours = hours ? hours : 12; // the hour '0' should be '12'
  minutes = minutes < 10 ? '0'+minutes : minutes;
  var strTime = date.getFullYear()+'-'+(date.getMonth()+1)+'-'+date.getDate()+' '+hours + ':' + minutes + ' ' + ampm;
  return strTime;
}

var nodeCounter=0;

database.ref("messages/").on("child_added", function(firebaseData) {
    var userID=$("form.chat_message_send input.id").val();
    var senderID=$("form.chat_message_send input.senderid").val();
    var senderProfile=$("form.chat_message_send input.senderProfile").val();
    var senderProfileName=$("form.chat_message_send input.senderProfileName").val();
    var userProfile=$("form.chat_message_send input.userProfile").val();
    var userProfileName=$("form.chat_message_send input.userProfileName").val();
    var firebaseValue = firebaseData.val();
    nodeCounter=firebaseValue.unique_id;
    var msg_senderID=senderID;
    var allUsersIDs=$("ul#chat-list li").length;
    var allUsersIDs_array=[];
    $('ul#chat-list li').each(function(){
        if($(this).attr('data-userid')!=undefined){
            allUsersIDs_array.push(parseInt($(this).attr('data-userid')));
        }
    });
    if((msg_senderID!=parseInt(firebaseValue.sender) && (msg_senderID==parseInt(firebaseValue.user1) || 
        msg_senderID==parseInt(firebaseValue.user2))) && (allUsersIDs_array.indexOf(parseInt(firebaseValue.user1)) != -1 || 
        allUsersIDs_array.indexOf(parseInt(firebaseValue.user2)) != -1)){
        if(firebaseValue.MsgSend=='no'){
            database.ref("messages/").child(firebaseValue.unique_id).update({
                MsgSend: 'yes'
            });
            $("div.customize_alert_message.alert-success").text(firebaseValue.recievername+' sent you new message!');
            $("div.customize_alert_message.alert-success").show();
            setTimeout(function(){ $('.customize_alert_message').hide(); }, 5000);
        }
    }
    only_check_unread_messages();
    if(userID!='' && senderID!=''){
        if ((userID == firebaseValue.user1 && senderID == firebaseValue.user2) || (userID == firebaseValue.user2 && senderID == firebaseValue.user1)) {

            if(senderID!=firebaseValue.sender){
                database.ref("messages/").child(firebaseValue.unique_id).update({
                    status: 'read'
                });
            }

            var sender_title=senderID==firebaseValue.sender?'my':'other';
            var msgImage='';
            if(firebaseValue.images!=''){
                var sepImages=firebaseValue.images.split(',');
                msgImage+="<div class='msg_images'>";
                for(var i=0;i<sepImages.length;i++){
                    msgImage+="<img src='/public/upload/chat/"+sepImages[i]+"' />";
                }
                msgImage+="</div>";
            }
            $("#chatmedia_popup .modal-body").append(msgImage);
            var profileImage=senderProfile!=''?'<img src="/public/admin/assets/img/avatars/'+senderProfile+'" alt="Avatar" class="rounded-circle">':'<span>'+senderProfileName+'</span>';
            var userProfileImage=userProfile!=''?'<img src="/public/admin/assets/img/avatars/'+userProfile+'" alt="Avatar" class="rounded-circle">':'<span>'+userProfileName+'</span>';
            
            var messageDateFromArray=firebaseValue.created_at.split(' ');
            var messageDateFromArrayDate=messageDateFromArray[0].split('-');
            var messageDateFromArrayTime=messageDateFromArray[1].split(':');
            var messageDateFromYear=messageDateFromArrayDate[0];
            var messageDateFromMonth=parseInt(messageDateFromArrayDate[1])<10?'0'+messageDateFromArrayDate[1]:messageDateFromArrayDate[1];
            var messageDateFromDay=parseInt(messageDateFromArrayDate[2])<10?'0'+messageDateFromArrayDate[2]:messageDateFromArrayDate[2];
            var messageDateFromHour=parseInt(messageDateFromArrayTime[0])<10?'0'+messageDateFromArrayTime[0]:messageDateFromArrayTime[0];
            var messageDateFromMinute=parseInt(messageDateFromArrayTime[1])<10?'0'+messageDateFromArrayTime[1]:messageDateFromArrayTime[1];
            var messageDateFromSecond=parseInt(messageDateFromArrayTime[2])<10?'0'+messageDateFromArrayTime[2]:messageDateFromArrayTime[2];
            var messageDateFrom=!isNaN(new Date(messageDateFromYear+'-'+messageDateFromMonth+'-'+messageDateFromDay+'T'+messageDateFromHour+':'+messageDateFromMinute+':'+messageDateFromSecond))?formatAMPM(new Date(messageDateFromYear+'-'+messageDateFromMonth+'-'+messageDateFromDay+'T'+messageDateFromHour+':'+messageDateFromMinute+':'+messageDateFromSecond)):'';
            
            if(sender_title=='my'){
                $("ul#chat-history").append(`<li class="chat-message chat-message-right">
                    <div class="d-flex overflow-hidden">
                      <div class="chat-message-wrapper flex-grow-1">
                        <div class="chat-message-text">
                          `+msgImage+`
                          <p class="mb-0">`+firebaseValue.message+`</p>
                        </div>
                        <div class="text-end text-muted mt-1">
                          <small>`+messageDateFrom+`</small>
                        </div>
                      </div>
                      <div class="user-avatar flex-shrink-0 ms-3">
                        <div class="avatar avatar-sm">
                          `+profileImage+`
                        </div>
                      </div>
                    </div>
                  </li>`);
            }else{
                $("ul#chat-history").append(`<li class="chat-message">
                    <div class="d-flex overflow-hidden">
                      <div class="user-avatar flex-shrink-0 me-3">
                        <div class="avatar avatar-sm">
                          `+userProfileImage+`
                        </div>
                      </div>
                      <div class="chat-message-wrapper flex-grow-1">
                        <div class="chat-message-text">
                          `+msgImage+`
                          <p class="mb-0">`+firebaseValue.message+`</p>
                        </div>
                        
                        <div class="text-muted mt-1">
                          <small>`+messageDateFrom+`</small>
                        </div>
                      </div>
                    </div>
                  </li>`);
            }
            $('.app-chat .app-chat-history .chat-history-body').scrollTop($('.app-chat .app-chat-history .chat-history-body')[0].scrollHeight);
        }
    }
})

only_check_unread_messages();
function only_check_unread_messages(){
    //top of the function start
        var insideID=0;
        var allUsersIDs=$("ul#chat-list li").length;
        var allUsersIDs_array=[];
        $('ul#chat-list li').each(function(){
            if($(this).attr('data-userid')!=undefined){
                allUsersIDs_array.push({id:parseInt($(this).attr('data-userid')),c:0});
            }
        });
    //top of the function end

        database.ref("messages/").once("value", function(firebaseData) {
            var userID=$("form.chat_message_send input.id").val();
            var senderID=$("form.chat_message_send input.senderid").val();
            var firebaseValue = firebaseData.val();
            if(firebaseValue!=null){
                var keys = Object.keys(firebaseValue).map((i) => Number(i));
                for (var i = 0; i < keys.length; i++){
                    database.ref('messages/' + keys[i]).once("value", function(detail){
                        var detailed = detail.val();
                        
                        if(detailed!=null){
                            //show unread message numbers here start
                                if(((senderID==detailed.user1 || senderID==detailed.user2) && detailed.sender!=detailed.user1) && allUsersIDs_array.findIndex(x => x.id === parseInt(detailed.user2))!=-1){
                                    if(detailed.status=='unread'){
                                        allUsersIDs_array[allUsersIDs_array.findIndex(x => x.id === parseInt(detailed.user2))].c=(allUsersIDs_array[allUsersIDs_array.findIndex(x => x.id === parseInt(detailed.user2))].c)+1;
                                    }
                                }
                            //show unread message numbers here end 

                            if((userID == detailed.user1 && senderID == detailed.user2) || (userID == detailed.user2 && senderID == detailed.user1)) {
                                $("#unread_messages_appear_here_set"+userID).html('');
                                $("#unread_messages_appear_here_set"+userID).hide();
                                $("span#message_total_show_number").hide();
                                $("span#message_total_show_number").html('');
                                insideID=userID;
                            }
                        }
                    })
                }
            }
        })

    //final loop start here
        setTimeout(function(){
            var overallmessages=0;
            for(var x=0;x<allUsersIDs_array.length;x++){
                if(allUsersIDs_array[x].id!=insideID && allUsersIDs_array[x].c>0){
                    $("#unread_messages_appear_here_set"+allUsersIDs_array[x].id).html(allUsersIDs_array[x].c);
                    $("#unread_messages_appear_here_set"+allUsersIDs_array[x].id).attr('style','display:flex;');
                    $("#unread_messages_appear_here_set"+allUsersIDs_array[x].id).parent().parent().attr('data-position',allUsersIDs_array[x].c);
                }else{
                    $("#unread_messages_appear_here_set"+allUsersIDs_array[x].id).html('');
                    $("#unread_messages_appear_here_set"+allUsersIDs_array[x].id).hide();
                }
                overallmessages+=allUsersIDs_array[x].c;
            }
            if(overallmessages>0){
                $(function() {
                  $("group#groupMyteam li").sort(sort_li).appendTo('group#groupMyteam');
                  function sort_li(b, a) {
                    return ($(b).data('position')) < ($(a).data('position')) ? 1 : -1;
                  }
                });

                $("span#message_total_show_number").html(overallmessages); 
                $("span#message_total_show_number").attr('style','display:flex;');   
            }else{
                $("span#message_total_show_number").html('');
                $("span#message_total_show_number").hide();
            }
            
        }, 1000);
    //final loop end here
}
function retrive_chat_history(){

    database.ref("messages/").once("value", function(firebaseData) {
        var userID=$("form.chat_message_send input.id").val();
        var senderID=$("form.chat_message_send input.senderid").val();
        var senderProfile=$("form.chat_message_send input.senderProfile").val();
        var senderProfileName=$("form.chat_message_send input.senderProfileName").val();
        var userProfile=$("form.chat_message_send input.userProfile").val();
        var userProfileName=$("form.chat_message_send input.userProfileName").val();
        var firebaseValue = firebaseData.val();
        if(firebaseValue!=null){
            var keys = Object.keys(firebaseValue).map((i) => Number(i));
            $("ul#chat-history").html('');
            $("#chatmedia_popup .modal-body").html('');
            for (var i = 0; i < keys.length; i++){
                database.ref('messages/' + keys[i]).once("value", function(detail){
                    var detailed = detail.val();
                    
                    if(detailed!=null){

                        if((userID == detailed.user1 && senderID == detailed.user2) || (userID == detailed.user2 && senderID == detailed.user1)) {
                            
                            $("#unread_messages_appear_here_set"+userID).html('');
                            $("#unread_messages_appear_here_set"+userID).hide();
                            $("span#message_total_show_number").html('');
                            $("span#message_total_show_number").hide();
                            if(senderID!=detailed.sender){
                                database.ref("messages/").child(detailed.unique_id).update({
                                    status: 'read'
                                });
                            }

                            var sender_title=senderID==detailed.sender?'my':'other';
                            var msgImage='';
                            if(detailed.images!=''){
                                var sepImages=detailed.images.split(',');
                                msgImage+="<div class='msg_images'>";
                                for(var i=0;i<sepImages.length;i++){
                                    msgImage+="<img src='/public/upload/chat/"+sepImages[i]+"' />";
                                }
                                msgImage+="</div>";
                            }
                            $("#chatmedia_popup .modal-body").append(msgImage);

                            var profileImage=senderProfile!=''?'<img src="/public/admin/assets/img/avatars/'+senderProfile+'" alt="Avatar" class="rounded-circle">':'<span>'+senderProfileName+'</span>';
                            var userProfileImage=userProfile!=''?'<img src="/public/admin/assets/img/avatars/'+userProfile+'" alt="Avatar" class="rounded-circle">':'<span>'+userProfileName+'</span>';
                            
                            var messageDateFromArray=detailed.created_at.split(' ');
                            var messageDateFromArrayDate=messageDateFromArray[0].split('-');
                            var messageDateFromArrayTime=messageDateFromArray[1].split(':');
                            var messageDateFromYear=messageDateFromArrayDate[0];
                            var messageDateFromMonth=parseInt(messageDateFromArrayDate[1])<10?'0'+messageDateFromArrayDate[1]:messageDateFromArrayDate[1];
                            var messageDateFromDay=parseInt(messageDateFromArrayDate[2])<10?'0'+messageDateFromArrayDate[2]:messageDateFromArrayDate[2];
                            var messageDateFromHour=parseInt(messageDateFromArrayTime[0])<10?'0'+messageDateFromArrayTime[0]:messageDateFromArrayTime[0];
                            var messageDateFromMinute=parseInt(messageDateFromArrayTime[1])<10?'0'+messageDateFromArrayTime[1]:messageDateFromArrayTime[1];
                            var messageDateFromSecond=parseInt(messageDateFromArrayTime[2])<10?'0'+messageDateFromArrayTime[2]:messageDateFromArrayTime[2];
                            var messageDateFrom=!isNaN(new Date(messageDateFromYear+'-'+messageDateFromMonth+'-'+messageDateFromDay+'T'+messageDateFromHour+':'+messageDateFromMinute+':'+messageDateFromSecond))?formatAMPM(new Date(messageDateFromYear+'-'+messageDateFromMonth+'-'+messageDateFromDay+'T'+messageDateFromHour+':'+messageDateFromMinute+':'+messageDateFromSecond)):'';
                            
                            if(sender_title=='my'){
                                $("ul#chat-history").append(`<li class="chat-message chat-message-right">
                                    <div class="d-flex overflow-hidden">
                                      <div class="chat-message-wrapper flex-grow-1">
                                        <div class="chat-message-text">
                                          `+msgImage+`
                                          <p class="mb-0">`+detailed.message+`</p>
                                        </div>
                                        <div class="text-end text-muted mt-1">
                                          <small>`+messageDateFrom+`</small>
                                        </div>
                                      </div>
                                      <div class="user-avatar flex-shrink-0 ms-3">
                                        <div class="avatar avatar-sm">
                                          `+profileImage+`
                                        </div>
                                      </div>
                                    </div>
                                  </li>`);
                            }else{
                                $("ul#chat-history").append(`<li class="chat-message">
                                    <div class="d-flex overflow-hidden">
                                      <div class="user-avatar flex-shrink-0 me-3">
                                        <div class="avatar avatar-sm">
                                          `+userProfileImage+`
                                        </div>
                                      </div>
                                      <div class="chat-message-wrapper flex-grow-1">
                                        <div class="chat-message-text">
                                          `+msgImage+`
                                          <p class="mb-0">`+detailed.message+`</p>
                                        </div>
                                        
                                        <div class="text-muted mt-1">
                                          <small>`+messageDateFrom+`</small>
                                        </div>
                                      </div>
                                    </div>
                                  </li>`);
                            }
                        }
                    }

                }) 
            }
        }
        $('.app-chat .app-chat-history .chat-history-body').scrollTop($('.app-chat .app-chat-history .chat-history-body')[0].scrollHeight);
    })

}

$('form.chat_message_send input#attach-doc').change(function(){
    $("form.chat_message_send span.uploadedNumber").attr('style','display:inline-flex;');
    $("form.chat_message_send span.uploadedNumber").html($(this)[0].files.length);
});

$("form.chat_message_send").on('submit', function(e){
    e.preventDefault();
    
    var msg=$("form.chat_message_send input.message-input").val();
    var userID=$("form.chat_message_send input.id").val();
    var senderID=$("form.chat_message_send input.senderid").val();
    var recievername=$("form.chat_message_send input.recievername").val();
    var actionURL=$("form.chat_message_send").attr('action');
    var filesLengthCount=$('form.chat_message_send input#attach-doc')[0].files.length;
    $.ajax({
            type: 'POST',
            url: actionURL,
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData:false,
            beforeSend: function(){
                if(filesLengthCount>0){
                    $('form.chat_message_send div.files_uploading_load').attr('style','display:flex;');
                    $('form.chat_message_send .message-actions').attr('style','display:none !important;');
                }
            },
            success: function(image){
                $('form.chat_message_send div.files_uploading_load').attr('style','display:none;');
                $('form.chat_message_send .message-actions').removeAttr('style');
                var date = new Date;
                var created_at = date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate() + " " + date.getHours() + ":" + date.getMinutes() + ":" + date.getSeconds();
                var make_me_unique=nodeCounter+1;
                i = {
                    user1: userID,
                    user2: senderID,
                    sender: senderID,
                    recievername: recievername,
                    images: image,
                    message: msg,
                    status: 'unread',
                    MsgSend: 'no',
                    created_at: created_at,
                    unique_id: make_me_unique
                };
                database.ref("messages/" + make_me_unique).set(i);
                $("form.chat_message_send input.message-input").val('');
                $('form.chat_message_send input[type="file"]').val('');
                $('form.chat_message_send span.uploadedNumber').hide();
                $('form.chat_message_send span.uploadedNumber').html('0');
            }
    });
    
});