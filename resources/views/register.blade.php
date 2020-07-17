<!doctype html>
<html>
<head>
    <title>Register</title>
    <style>
    </style>
</head>
<body>
@topNav
@endtopNav
<h1>Register</h1>
<div id="wrapper">

        @if ($errors->any())
        {{--{{ddd($errors)}}--}}
        <ul>
            {{$errors->first()}}
        </ul>
        @endif

    <form method="post" action="{{route('tryRegister')}}">
        @csrf
        <div>ID</div>
        <div><input type="text" name="username"></div>
        <div>이름</div>
        <div><input type="text" name='name'></div>
        <div>Email</div>
        <div><input type="email" name='email'></div>
        <div>비밀번호</div>
        <div><input type="password" name="password"></div>
        <div>비밀번호 확인</div>
        <div><input type="password" name="password_confirmation"></div>
        <br>
        <input type="submit" value="생성">
    </form>

</div>

</body>
</html>

