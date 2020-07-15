<!doctype html>
<html>
<head>
    <title>Home</title>
    <style>
        #wrapper {
            display: grid;
            grid-template-columns: 6fr 2fr;
        }
        #main  {
            border: dashed 1px black;
            border-right: none;
        }
        #sub {
            border: dashed 1px black;
            padding : 10px;
        }

    </style>
</head>
<body>
<div>
    @topNav
    @endtopNav
</div>
<div id="wrapper">
    <div id="main">
        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean vitae aliquam ipsum. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Morbi viverra rhoncus mi. Suspendisse id turpis sodales magna vulputate tincidunt eget a erat. Donec sodales velit et ullamcorper porttitor. Nam id tempor orci. Cras sollicitudin, lectus sit amet porta consectetur, mi enim molestie erat, in venenatis diam lacus quis justo. Aliquam eleifend in erat sit amet porta. Nulla auctor elit nisi, eget ultrices nisl varius et. In hac habitasse platea dictumst. Nulla eu accumsan libero. Cras gravida, urna eget ornare ultricies, nisl quam ultrices nibh, ac euismod quam ante non ante. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Nullam at velit enim.

        Phasellus varius velit vel enim ultrices, quis mattis risus consequat. In vitae dui eget nibh venenatis faucibus. Ut blandit elit sapien, a aliquet erat efficitur et. Curabitur urna leo, congue id nunc in, mattis semper nunc. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Pellentesque fermentum, nisl eu pulvinar porta, ex orci rutrum neque, sit amet efficitur eros leo id nibh. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Sed varius eget leo vel euismod. Nullam eget condimentum nulla. Nullam sagittis cursus augue, at posuere enim varius sed. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Praesent consectetur mauris id tortor faucibus accumsan. Proin eu augue nec libero imperdiet lobortis. Praesent porttitor elementum turpis, nec rhoncus lectus laoreet eget. Mauris tristique eros quis magna viverra dignissim. Suspendisse molestie tellus ac orci accumsan, et rutrum libero tempor.

        In tempus, risus nec rhoncus tristique, turpis metus posuere arcu, et sodales ligula mi ut augue. Nulla et faucibus nunc. Ut at ex congue tellus aliquet placerat. Aenean tincidunt dui eu elit ultricies, eu molestie turpis sodales. Aenean finibus maximus elit eu dignissim. Cras nec egestas neque. Proin vitae orci scelerisque, vestibulum ex at, fringilla enim. Suspendisse nisl dui, ullamcorper eget accumsan eget, fermentum et libero. Ut mollis tincidunt pharetra. Cras tempus scelerisque quam, finibus volutpat tellus lobortis vel. Vivamus volutpat dignissim justo, a viverra enim feugiat vitae.

        Nam tempor nibh felis. Praesent et purus vel magna efficitur feugiat sit amet et metus. Praesent sed mauris sit amet felis dignissim interdum eu vel velit. Nulla vitae elit at lorem imperdiet porttitor. Curabitur neque sem, gravida in posuere eu, accumsan tristique nisl. Nam vel faucibus velit, eu efficitur lorem. Sed vitae tristique est. Sed vel aliquet tellus. Sed rhoncus porta tempus. Phasellus lectus urna, accumsan in venenatis nec, aliquam non arcu.

        Quisque posuere nulla sed nibh pharetra, mattis cursus nisl sagittis. Sed at finibus nibh. Integer quis massa sit amet dolor viverra condimentum sit amet in lorem. In pellentesque ex et elit feugiat, ac lacinia nunc maximus. Aenean urna nunc, molestie eget maximus sit amet, bibendum vel augue. Maecenas blandit velit imperdiet magna placerat imperdiet. Morbi vel faucibus eros. Curabitur posuere commodo nulla et laoreet.
    </div>
    <div id="sub">
        <div id="login-card">
            {{--login_ed--}}
            @if(session()->get('is_login'))
                <div id="user-profile">
                    <form>
                        @csrf
                        <h3>{{session()->get('username')}}님 환영합니다.</h3>
                        <input type="submit" value="Logout" formmethod="post" formaction="{{route('logout')}}">
                    </form>
                </div>

            @else
                <strong>로그인</strong>
                @loginBox
                loginBox
                @endloginBox
            @endif
        </div>
    </div>
</div>
</body>

</html>