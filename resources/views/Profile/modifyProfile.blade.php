<!doctype html>
<html>
<head>
    <title>Modify</title>
    <style>
        * {
            text-decoration: none;
            color: black;
        }
    </style>
</head>
<body>
<div id="profile">
    @csrf
    <p><strong>현재 Username</strong> : {{$user->username}}</p>
    <p><strong>현재 Email</strong> : {{$user->email}} </p>
</div>
<form action="/profile/modify" method="post">
    @csrf
    <h3>수정할 정보</h3>
    <p><strong>Username</strong> : <input name="username" type="text" value="{{$user->username}}"></p>
    <p><strong>Email</strong> : <input name="email" type="text" value="{{$user->email}}"></p>
    <p><strong>Name</strong> : <input name="name" type="text" value="{{$user->name}}"></p>
    <p><strong>Password</strong> : <input name="password" type="password" value=""></p>
    <p><strong>Password 확인</strong> : <input name="password_confirmation" type="password" value=""></p>
    <input type="submit" id="postBtn" value="수정하기">
</form>
<script type="text/javascript">
</script>
</body>
</html>