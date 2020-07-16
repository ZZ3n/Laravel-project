<!doctype html>
<html>
<head>
    <title>Meetings List</title>
    <style>
        #meetings {
            display: grid;
            grid-template-columns: 2fr 2fr 2fr;

        }
        #meetings div {
            margin : 6px 6px;
            border-right: solid 1px black;
            border-bottom: solid 1px black;
        }
        #meetings div:nth-child(3) {
            border-right: none;
        }
        .meeting-card {
            height: 200px;
        }
    </style>
</head>
<body>
@topNav
@endtopNav
    <div id="meetings">
        @foreach($meetings as $meeting)
        <div class="meeting-card">
            <h3>{{$meeting->name}}</h3>
            {{$meeting->content}}<br><br>
            {{$meeting->act_end_date}}
        </div>
        @endforeach
            {{--{{ddd($meetings)}}--}}
    </div>
</body>
</html>