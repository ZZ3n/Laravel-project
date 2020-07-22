<nav id="on-top" style="height: 100px; margin: 0px -10px 20px;
text-align: center;display: grid;grid-template-columns: 6fr 4fr;border-bottom: solid 3px black;
text-decoration: none;color : black; width: 100vw">
    <a href="{{route('home')}}"><h1>OffOn</h1></a>
    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr">
        <div>
            <a href="{{route('createMeeting')}}">
                모임 개설
            </a>
        </div>
        <div>
            <a href="{{route('meetings')}}">
                모임 찾기
            </a>
        </div>
        <div>

        </div>
        @if (session()->get('is_login') == true)

            <div><strong>{{session()->get('username')}}</strong> 님</div>
                <form action="{{route('logout')}}" method="post">
                    @csrf
                    <input type="submit" value="로그아웃">
                </form>
                <a href="/profile">
                    <button>내 정보</button>
                </a>
        @endif
    </div>
</nav>
