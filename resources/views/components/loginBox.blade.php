

<div id="loginBox">
    {{--에러 메시지 커스텀 하기 !--}}
    @if ($errors->has('username'))
        ID가 존재하지 않습니다.
    @elseif ($errors->has('password'))
        비밀번호가 맞지 않습니다.
    @endif
    <form method="post" action={{route('login')}}>
        @csrf
        <div>ID</div>
        <div><input type="text" name="username"></div>
        <div>비밀번호</div>
        <div><input type="password" name="password"></div>
        <br>
        <input type="submit" value="Login">
        <input type="submit" value="Register" formmethod="GET" formaction="{{route('register')}}">
    </form>
</div>
