<div class="friend-request" style="display: grid; grid-template-columns: 2fr 1fr;">
    <div>
    <a href="/users/{{ $sendReqUserId }}"><strong>{{ $sendReqUser->name }}</strong></a> wants to be your friend
    </div>
    <div style="text-align: end">
        <a style="background-color: #0a0302; color: white; padding: 5px; border-radius: 3px" href="/users/friend_req/accept/{{ $sendReqUserId }}/{{ $receiveReqUserId }}">Accept</a>
        <a style="background-color: #0a0302; color: white; padding: 5px; border-radius: 3px" herf="/users/friend_req/ignore/{{ $sendReqUserId }}/{{ $receiveReqUserId }}">Ignore</a>
    </div>
</div>
