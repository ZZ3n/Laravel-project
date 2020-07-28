<!doctype html>
<html>
<head>
    <title>Meetings List</title>
    <style>
        #meetings {
            display: grid;
            grid-template-columns: 2fr 2fr 2fr 2fr 2fr;
        }

        .meeting-card {
            margin: 10px 10px;
            padding: 15px 5px 5px 5px;
            border: solid 1px black;
            height: 200px;
            width: 200px;
        }

        .meeting-card div {
            overflow: hidden;
            text-overflow: ellipsis;
        }

        h3 {
            height: 100px;
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
<div id="meetings">
    @foreach($meetings as $meeting)
        <a href="{{route('meetings').'/detail/'.$meeting->id}}">
            <div class="meeting-card">
                <div><h3>{{$meeting->name,20}}</h3></div>
                <br>
                <div>
                    조회수: {{$meeting->views}}<br>
                    신청자 수 : {{$meeting->applications_count}}
                </div>
            </div>
        </a>
    @endforeach
</div>
</body>
</html>