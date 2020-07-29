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

        #founder-info {
            margin: 6px 0px 0px 10px;
            border: dashed 1px black;
        }

        .group-wrapper {
            border: solid 1px black;
            margin: 5px 5px;
        }

        #groups {
            display: grid;
            grid-template-columns: 2fr 2fr 2fr;
            margin-top: 20px;
        }

        .group {
            display: grid;
            grid-template-columns: 2fr 2fr;
        }

        .applications {
            display: grid;
            grid-template-columns: 2fr 4fr 2fr 2fr;
        }

        .applications * {
            border: dashed 1px black;
        }

        * {
            text-decoration: none;
            color: black;
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
<h3 align="center">그룹</h3>
<div id="groups">
    @foreach($groups as $group)
        <div class="group-wrapper">
            <div class="group">
                <div><strong>{{$group->name}}</strong></div>
                <br>
                <div>{{$group->apply_start_date}}</div>
                <div>{{$group->apply_end_date}}</div>
                <div>{{$group->act_start_date}}</div>
                <div>{{$group->act_end_date}}</div>
                <div>정원 : {{$group->applications_count}}/{{$group->capacity}}</div>
                <div>승인방식 : {{$group->approval_opt}}</div>
                <br>
            </div>
            <div class="applications">
                <div>username</div>
                <div>사유</div>
                <div>승인 여부</div>
                <div></div>
                @foreach($applications as $application)
                    @if($application['group_id'] == $group->id)
                        <div>{{$application['username']}}</div>
                        <div>{{$application['reason']}}</div>
                        @if ($application['approval'] == 1)
                            <div>승인됨</div>
                        @else
                            <div>거절됨</div>
                        @endif
                        <form method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="username" value="{{$application['username']}}">
                            <input type="hidden" name="group_id" value="{{$group->id}}">
                            <input type="submit" value="승인"
                                   formaction="/meetings/{{$meeting->id}}/modify/groups/accept">
                            <input type="submit" value="거절" formaction="/meetings/{{$meeting->id}}/modify/groups/deny">
                        </form>
                    @endif
                @endforeach
            </div>
        </div>
    @endforeach
</div>
</body>
</html>