<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <style>
        #meeting {
            display: grid;
            grid-template-columns: 5fr 2fr;
            padding-bottom: 20px;
        }

        #meeting-info {
            display: grid;
            grid-template-columns: 2fr 20fr;
        }

        #meeting-info div {
            margin: 15px 10px;
        }

        .underline {
            border-bottom: solid 1px black;
        }

        #founder-info {
            margin: 6px 0px 0px 10px;
            border: dashed 1px black;
        }
        #group {
            display:grid;
            grid-template-columns: 2fr 6fr;;
            border:4px double black;
        }

    </style>
</head>
<body>
@topNav
@endtopNav
<h3 align="center">모임</h3>
<div id="meeting">
    <div id="meeting-info">
        <div class="underline"><strong>제목</strong></div>
        <div class="underline">{{$meeting->name}}</div>
    </div>
    <div>
        <div id="founder-info">
            <div>개설자 : {{$founder->username}}</div>
            <div>개설자 email : {{$founder->email}}</div>
        </div>
    </div>
</div>
<div id ="group">
    <div>그룹 이름</div>
    <div>{{$group->name}}</div>
    <div>신청 가능 날짜</div>
    <div>
        {{$group->apply_start_date}} ~ {{$group->apply_start_date}}
    </div>
    <div>활동 가능 날짜</div>
    <div>{{$group->act_start_date}} ~ {{$group->act_start_date}}</div>
    <div>정원</div>
    <div>{{$group->capacity}}</div>
    <div>승인방식</div>
    <div>{{$group->approval_opt}}</div>
</div>
<br>
<form action="" method="post">
    <input type="submit" value="신청 하기">
</form>
</body>
</html>