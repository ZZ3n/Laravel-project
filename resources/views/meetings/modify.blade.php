<!doctype html>
<html>
<head>
    <title>Modify Meeting</title>
    <style>
        h3 {
            border-bottom: dashed 2px black;
        }

        body {
            margin: 0px 20px;
        }

        * {
            text-decoration: none;
            color: black;
        }
    </style>
    <script src="https://cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script>
</head>
<body>
@topNav
@endtopNav

<form id="meeting_form" method="POST" action="/meetings/modify/meeting/{{$meeting->id}}">
    @csrf
    <h3>모임 정보</h3>
    <div>제목</div>
    <div><input type="text" name="name" value="{{$meeting->name}}"></div>
    <div>내용</div>
    <div>
        <textarea name="meeting_content">{{$meeting->content}}</textarea>
    </div>
    <br>
    <input type="submit" value="수정 완료">
</form>
<script>
    CKEDITOR.replace('meeting_content');
</script>
</body>
</html>