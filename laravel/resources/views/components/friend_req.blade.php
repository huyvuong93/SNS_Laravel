<div class="friend-request" style="display: grid; grid-template-columns: 2fr 1fr;">
    <div>
    <a href="/users/{{ $sendReqUser->id }}"><strong>{{ $sendReqUser->name }}</strong></a> wants to be your friend
    </div>
    <div style="text-align: end">
        <a style="background-color: #0a0302; color: white; padding: 5px; border-radius: 3px" href="/users/friend_req/accept/{{ $sendReqUser->id }}/{{ $receiveReqUserId }}">Accept</a>
        <a style="background-color: #0a0302; color: white; padding: 5px; border-radius: 3px" href="/users/friend_req/ignore/{{ $sendReqUser->id }}/{{ $receiveReqUserId }}">Ignore</a>
    </div>
</div>
