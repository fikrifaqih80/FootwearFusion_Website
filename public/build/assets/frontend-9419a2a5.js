function a(i){const t={year:"numeric",month:"short",day:"2-digit",hour:"2-digit",minute:"2-digit"};return new Intl.DateTimeFormat("en-Us",t).format(new Date(i))}function e(){mainChatInbox.scrollTop(mainChatInbox.prop("scrollHeight"))}window.Echo.private("message."+USER.id).listen("MessageEvent",i=>{console.log(i);let t=$(".wsus__chat_area_body");if(t.attr("data-inbox")==i.sender_id)var s=`<div class="wsus__chat_single">
                <div class="wsus__chat_single_img">
                    <img src="${i.sender_image}"
                        alt="user" class="img-fluid">
                </div>
                <div class="wsus__chat_single_text">
                    <p>${i.message}</p>
                    <span>${a(i.date_time)}</span>
                </div>
            </div>`;t.append(s),e(),$(".chat-user-profile").each(function(){$(this).data("id")==i.sender_id&&$(this).find(".wsus_chat_list_img").addClass("msg-notification")})});
