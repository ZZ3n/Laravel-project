<!doctype html>
<html>
<head>
    <title>Create Meeting</title>
    <style>
        h3 {
            border-bottom:dashed 2px black;
        }
        body {
            margin : 0px 20px;
        }
        .group {
            border : solid 1px red;
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
    <div class="group">
        <div>
            이름 <input type="text" name="gName">
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

    <br><input type="submit" value="생성">
</form>
</body>
</html>