<!doctype html>
<html>
<head>
    <title>Create Meeting</title>
    <style>
        h3 {
            border-bottom: dashed 2px black;
        }

        body {
            margin: 0px 20px;
        }

        .group {
            border: solid 1px red;
        }
    </style>

</head>
<body>
@topNav
@endtopNav
<form method="POST" action="{{route('tryCreateMeeting')}}">
    @csrf
    <h3>모임 정보</h3>
    <div>제목</div>
    <div><input type="text" name="name"></div>

    <div>내용</div>
    <div><textarea cols="70" rows="20" name="meeting_content">상세 정보를 입력하세요.</textarea></div>
    <br>

    <h3>그룹 설정</h3>
    <div id="groups">
        <ul id="group_list"></ul>
        <div class="group">
            <div>
                이름 <input type="text" name="group_name">
            </div>
            <div>
                정원 <input type="number" name="capacity" max="999" min="1" value="1">
            </div>
            <div>
                신청 기간 <input type="datetime-local" name="apply_start_date"> ~
                <input type="datetime-local" name="apply_end_date">
            </div>
            <div>
                활동 기간
                <input type="datetime-local" name="action_start_date"> ~
                <input type="datetime-local" name="action_end_date">
            </div>
            <div>
                승인 방식
                <select name="apv_opt">
                    <option value="first">선착순</option>
                    <option value="check">승인</option>
                </select>
            </div>
        </div>
    </div>
</form>

<button class="save">save</button>

    @if ($errors->any())
        <ul><h3>Error</h3>
            @foreach($errors->all() as $errorMessage)
                <li>{{$errorMessage}}</li>
            @endforeach
        </ul>
    @endif

    <br><input type="submit" value="생성">

<br><br>
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script>
    $(document).ready(function() { // 머임?
    });
    @if(session()->has('message'))
    console.log({{session()->get('message')}})
    @endif
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').attr('value')
            }
        });

        $(".save").click(function (e) {
            e.preventDefault();

            var name = $('input[name="group_name"]').val();
            var capacity = $('input[name="capacity"]').val();
            var apply_start_date = $('input[name="apply_start_date"]').val();
            var apply_end_date = $('input[name="apply_end_date"]').val();
            var action_start_date = $('input[name="action_start_date"]').val();
            var action_end_date = $('input[name="action_end_date"]').val();
            var apv_opt = $('select[name="apv_opt"]').val();



            $.ajax({
                type: 'POST',
                url: 'ajaxGroup',
                data: {
                    name: name,
                    capacity:capacity,
                    apply_start_date:apply_start_date,
                    apply_end_date:apply_end_date,
                    action_start_date:action_start_date,
                    action_end_date:action_end_date,
                    apv_opt:apv_opt
                },
                success: function (data) {
                    alert(data.groups);
                    console.log(data);

                    $('ul[id="group_list"]').html('<li>' + data.groups + '</li>');
                },
                fail
            });
        })

</script>
</body>
</html>