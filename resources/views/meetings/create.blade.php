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
            display: grid;
            grid-template-columns: 2fr 5fr;
            width: 300px;
        }

        .group * {
            margin: 5px 5px;
        }

        * {
            text-decoration: none;
            color: black;
        }

        #groups {
            display: grid;
            grid-template-columns: 2fr 2fr;
            border-bottom: dashed 2px black;
        }

        #group_list {
            border-left: dashed 1px black;
        }
    </style>
    <script src="https://cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
</head>
<body>
@topNav
@endtopNav

<form id="meeting_form" method="POST" action="{{route('tryCreateMeeting')}}">
    @csrf
    <h3>모임 정보</h3>
    <div>제목</div>
    <div><input type="text" name="name"></div>

    <div>내용</div>
    <div>
        <textarea name="meeting_content"></textarea>
    </div>
    <br>
</form>
<h3>그룹 설정</h3>
<div id="group_section">
    <div id="groups">
        <div class="group">
            <div>
                이름
            </div>
            <input type="text" name="group_name">

            <div>
                정원
            </div>
            <input type="number" name="capacity" max="999" min="1" value="1">

            <div>
                신청 기간
            </div>
            <div>
                <input type="datetime-local" name="apply_start_date"> ~
                <input type="datetime-local" name="apply_end_date">
            </div>
            <div>
                활동 기간
            </div>
            <div>
                <input type="datetime-local" name="action_start_date"> ~
                <input type="datetime-local" name="action_end_date">
            </div>
            <div>
                승인 방식
            </div>
            <select name="apv_opt">
                <option value="first">선착순</option>
                <option value="check">승인</option>
            </select>

            <button class="save">save</button>
        </div>
        <ul id="group_list">
            <h3>생성된 그룹</h3>
        </ul>
    </div>

</div>
<br>
<input id="submitBtn" type="submit" value="생성">

<ul id="errors">
    @if (session('groupError'))
        <h3>{{session('groupError')}}</h3>
    @endif
    @if ($errors->any())
        <h3>Error</h3>
        @foreach($errors->all() as $errorMessage)
            <li>{{$errorMessage}}</li>
        @endforeach
    @endif
</ul>
<br>
<br>

<script>
    CKEDITOR.replace('meeting_content');
</script>
<script type="text/javascript">
    var submitBtn = $('input[id="submitBtn"]');
    submitBtn.on('click', function (e) {
        var meeting_form = document.getElementById('meeting_form');
        meeting_form.submit();
    })

    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').attr('value')
            }
        });
        $(".save").click(function (e) {
            e.preventDefault();

            var group_name = $('input[name="group_name"]').val();
            var capacity = $('input[name="capacity"]').val();
            var apply_start_date = $('input[name="apply_start_date"]').val();
            var apply_end_date = $('input[name="apply_end_date"]').val();
            var action_start_date = $('input[name="action_start_date"]').val();
            var action_end_date = $('input[name="action_end_date"]').val();
            var apv_opt = $('select[name="apv_opt"]').val();

            var error_list = $('ul[id="errors"]');

            $.ajax({
                        type: 'POST',
                        url: 'ajaxGroup',
                        data: {
                            group_name: group_name,
                            capacity: capacity,
                            apply_start_date: apply_start_date,
                            apply_end_date: apply_end_date,
                            action_start_date: action_start_date,
                            action_end_date: action_end_date,
                            apv_opt: apv_opt
                        }
                    })
                    .done(function (data, textStatus, xhr) {
                        alert('저장되었습니다.');
                                {{--data는 group이라는 키 - json으로 parse할 수 있는 string을 가짐.--}}
                        var saved_group_name = JSON.parse(data.group).group_name;
                        $('ul[id="group_list"]').append('<li>' + saved_group_name + '</li>');
                    })
                    .fail(function (data, textStatus, xhr) {
                        for (var error in data.responseJSON['errors']) {
                            var errorChild = document.createElement('li');
                            errorChild.textContent = data.responseJSON['errors'][error];
                            error_list.append(errorChild);
                        }
                    });
        })

    });
</script>
</body>
</html>