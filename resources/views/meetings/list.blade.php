<!doctype html>
<html>
<head>
    <title>Meetings List</title>
    <style>
        #meetings {
            display: grid;
            grid-template-columns: 2fr 2fr 2fr 2fr 2fr;
        }

        #meetings .meeting-card {
            margin: 10px 10px;
            padding: 5px 5px;
            border: solid 1px black;
        }

        .meeting-card {
            height: 200px;
            min-width: 200px;
            word-break: break-all;
        }

        a {
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
        <div class="meeting-card">
            <a href="{{route('meetings').'/detail/'.$meeting->id}}">
                <div>
                    <h3>{{\Illuminate\Support\Str::limit($meeting->name,20)}}</h3>
                    <br>
                    {{$meeting->act_end_date}}
                </div>
            </a>
        </div>
    @endforeach
</div>
</body>
</html>