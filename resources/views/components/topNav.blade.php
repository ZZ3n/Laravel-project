<nav id ="on-top"  style="height: 100px; margin: 0px -10px 20px;
text-align: center;display: grid;grid-template-columns: 9fr 1fr 1fr 1fr;border-bottom: solid 3px black;
text-decoration: none;color : black;">
    <a href="{{route('home')}}"><h1>OffOn</h1></a>
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
    @if (session()->get('is_login'))
        <form action="{{route('logout')}}" method="post">
            @csrf
            <input type="submit" value="로그아웃">
        </form>
    @endif
</nav>
