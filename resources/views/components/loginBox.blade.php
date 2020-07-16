

<div id="loginBox">
    {{--에러 메시지 커스텀 하기 !--}}
    @if ($errors->has('username'))
        ID가 존재하지 않습니다.
    @endif
    {{--패스워드는 Validator 에서 체크하지 않기 때문에 session으로 오류를 보냄. --}}
    @if(session()->has('loginError'))
        {{session()->get('loginError')}}
    @endif
    <form method="post" action={{route('login')}}>
        @csrf
        <div>ID</div>
        <div><input type="text" name="username"></div>
        <div>비밀번호</div>
        <div><input type="password" name="password"></div>
        <br>
        <input type="submit" value="Login">
    </form>
    <a href="{{route('register')}}">
        <button>Register</button>
    </a>
</div>
