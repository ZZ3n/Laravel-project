<!doctype html>
<html>
<head>
    <title>Profile</title>
    <style>
        #meetings, #applications {
            display: grid;
            grid-template-columns: 2fr 2fr 2fr 2fr 2fr;
        }

        .card {
            height: 100px;
            width: 200px;
            margin: 10px 10px;
            padding: 5px 5px;
            border: solid 1px black;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        * {
            text-decoration: none;
            color: black;
        }

        #profile {
            width: 300px;
            height: 230px;
            border: dashed 1px black;
        }

        h2 {
            border-bottom: solid 1px black;
        }

        .name {
            display: inline-block;
            font-size: 20px;
            font-weight: 800;
            height: 60px;
        }
    </style>
</head>
<body>
@topNav
@endtopNav
<div id="profile">
    <form action="/profile" method="post">
        @csrf
        <h3>내 정보</h3>
        <p><strong>Username</strong> : <input name="username" type="text" value="{{$user->username}}" readonly></p>
        <p><strong>Email</strong> : <input name="email" type="text" value="{{$user->email}}" readonly></p>
        <p><strong>Password</strong> : <input name="password" type="password" value=""></p>
        <input type="submit" id="postBtn" value="수정하기">
    </form>
</div>
<h2>개설한 모임</h2>
<div id="meetings">
    @if(! $meetings->first())
        <h3>개설한 모임이 없습니다.</h3>
    @endif
    @foreach($meetings as $meeting)
        <div class="card">
            <div>
                <a href="{{route('meetings').'/detail/'.$meeting->id}}">
                    <div class="name">{{$meeting->name,40}}</div>
                </a>
            </div>
            <a href="/meetings/modify/meeting/{{$meeting->id}}">
                <button>수정하기</button>
            </a>
            <a href="/meetings/modify/groups/{{$meeting->id}}">
                <button>참가자 관리</button>
            </a>
        </div>
    @endforeach
</div>
<h2>지원 한 곳</h2>
<div id="applications">
    @if(! $user_apps->first())
        <h3>지원한 모임이 없습니다.</h3>
    @endif
    @foreach($user_apps as $user_app)
        <a href="{{route('meetings').'/detail/'.$user_app['meeting_id']}}">
            <div class="card">
                <div class="name">{{$user_app['meeting_name']}}</div>
                <br>
                그룹 이름: {{$user_app['group_name']}}<br>
                @if ($user_app['approval'] == 0)
                    승인 여부: 거절
                @else
                    승인 여부: 승인
                @endif
            </div>
        </a>
    @endforeach
</div>
<script type="text/javascript">
</script>
</body>
</html>