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
            border-bottom: solid 2px black;
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
        #content {
            border: double 5px black;
            padding: 10px 10px;
            background-color: whitesmoke;
        }

        #founder-info {
            margin: 6px 0px 0px 10px;
            border: dashed 1px black;
        }

        #groups {
            display: grid;
            grid-template-columns: 2fr 2fr 2fr;
            margin-top: 20px;
        }

        .group {
            display: grid;
            grid-template-columns: 2fr 2fr;
            border: solid 1px black;
            margin: 5px 5px;
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
        <div><strong>내용</strong></div>
        <div id="content"> {!! $meeting->content !!} </div>
    </div>
    <div>
        <div id="founder-info">
            <div>개설자 : {{$founder->username}}</div>
            <div>개설자 email : {{$founder->email}}</div>
        </div>
    </div>
</div>
<h3 align="center">그룹</h3>
<div id="groups">
    @foreach($group_list as $group)

        <div class="group">
            <div><strong>{{$group->name}}</strong></div>
            <br>
            <div>{{$group->apply_start_date}}</div>
            <div>{{$group->apply_end_date}}</div>
            <div>{{$group->act_start_date}}</div>
            <div>{{$group->act_end_date}}</div>
            <div>정원 : {{$group->capacity}}</div>
            {{--승인 방식 한글로 바꿔야 함.--}}
            <div>승인방식 : {{$group->approval_opt}}</div>
            <br>
            <a href="{{route('meetings')}}/detail/{{$meeting->id}}/{{$group->id}}">
                <button>신청하기</button>
            </a>
        </div>

    @endforeach
</div>
</body>
</html>