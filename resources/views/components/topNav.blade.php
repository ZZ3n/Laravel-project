
<nav id ="on-top" style="height: 100px; margin: 0px 0px; text-align: center; display: grid;
            grid-template-columns: 9fr 1fr 1fr 1fr;">
    <a href="{{route('home')}}"><h1>OffOn</h1></a>
    <a href="{{route('createMeeting')}}">
       모임 개설
    </a>
    <a href="{{route('meetings')}}">
        모임 찾기
    </a>
    @if (session()->get('is_login'))
        <form action="{{route('logout')}}" method="post">
            @csrf
            <input type="submit" value="로그아웃">
        </form>
    @endif

</nav>
