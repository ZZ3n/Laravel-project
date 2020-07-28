<!doctype html>
<html>
{{--{{ddd(session()->get('uid'))}}--}}
<head>
    <title>Home</title>
    <style>
        #wrapper {
            display: grid;
            grid-template-columns: 6fr 2fr;
        }

        #main {
            display: grid;
            grid-template-columns: 2fr 2fr 2fr;
        }

        .meeting-card {
            margin: 10px 10px;
            padding: 5px 5px;
            border: solid 1px black;
            height: 200px;
            min-width: 200px;
            word-break: break-all;
        }

        #sub {
            border: dashed 1px black;
            padding: 10px;
            margin-right: 20px;
            height: 190px;
            margin-top: 10px;
        }

        * {
            text-decoration: none;
            color: black;
        }
    </style>
</head>
<body>
<div>
    @topNav
    @endtopNav
</div>
<div id="wrapper">
    <div id="main">
        @foreach($meetings as $meeting)
            <div class="meeting-card">
                <a href="{{route('meetings').'/detail/'.$meeting->id}}">
                    <div>
                        <h3>{{$meeting->name}}</h3>
                        조회수: {{$meeting->views}}<br>
                        신청자 수 : {{$meeting->applications_count}}
                    </div>
                </a>
            </div>
        @endforeach
    </div>
    <div id="sub">
        <div id="login-card">
            {{--login_ed--}}
            @if(session()->get('is_login'))
                <div id="user-profile">
                    <form>
                        @csrf
                        <h3>{{session()->get('username')}}님 환영합니다.</h3>
                        <input type="submit" value="Logout" formmethod="post" formaction="{{route('logout')}}">
                    </form>
                    <a href="/profile">
                        <button>내 정보</button>
                    </a>
                </div>

            @else
                <strong>로그인</strong>
                @loginBox
                loginBox
                @endloginBox
            @endif
        </div>
    </div>
</div>
</body>

</html>