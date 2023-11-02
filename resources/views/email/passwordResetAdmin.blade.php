<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- <link href="https://file.myfontastic.com/6tkDvaBT8S52S4nU8THupM/icons.css" rel="stylesheet"> -->
    <!-- <link rel="stylesheet" href="css/styles.css"> -->
    <title>{{__('After Password by Admin Mail')}}</title>
    <style>
    body { font-family: 'Poppins', sans-serif; }
    </style>
</head>

<body style="margin: 0;
    box-sizing: border-box;
    background-color: #fff;
    justify-content: center;
    align-items: center;">
    <div style="margin: 0 auto;
        box-sizing: border-box;
        width: 70%;
        height: 80%;
        background-color: #fff;
        border-radius: 5px;
        justify-content: center;
        padding-bottom: 1.2em;
        margin-bottom: 0.5em;
        min-width: 320px;">
        
        <div style="margin: 0 auto;
        box-sizing: border-box;
        width: 100%;
        align-content: center;
        margin-bottom: 4em;">
            <img style="margin: 0 auto;
                box-sizing: border-box;
                display: block;
                width: 100%;
                max-width: 300px;" src="https://tamice.com/site/images/tamice/logo/tamice-logo-225px.png" alt="logo-joga">
        </div>
        <table style="margin-left:-45px">
            <tr>
                <td style="padding: 4px;">
                    <div style=" width: 100px; height: 100px; display: inline-block;">
                    <a style="margin: 0;
                    box-sizing: border-box;
                    color:#A3A3A3;
                    text-decoration: none;
                    font-weight: bold;
                    font-size: 0.6em;
                    margin-right: 6.6em" href="https://tamice.com/searchproduct">
                                <img style="margin-left: 20px;" src="https://testing.thernloven.com/tickets-api-new/public/images/dashboard-square.svg" alt="icon booking" >
                    </a>
                    <br>
                       <span>My bookings</span>
                    </div>
                </td>
                <td style="padding: 4px;">
                    <div style=" width: 115px; height: 100px; display: inline-block;">
                        <a style="margin: 0;
                        box-sizing: border-box;
                        color:#A3A3A3;
                        text-decoration: none;
                        font-weight: bold;
                        font-size: 0.6em;
                        margin-right: 6.6em" href="https://accounts.kakao.com/login/?continue=http%3A%2F%2Fpf.kakao.com%2F_AAelu%2Fchat#login">
                                    <img style="margin-left: 25px;" src="https://testing.thernloven.com/tickets-api-new/public/images/message.svg" alt="icon Message" >
                        </a>
                        <br>
                            <span style="font-size: 0.9em;" >카톡 상담원 채팅</span>
                    </div>
                </td>
                <td style="padding: 4px;">
                    <div style=" width: 125px; height: 100px; display: inline-block;">
                        <a style="margin: 0;
                        box-sizing: border-box;
                        color:#A3A3A3;
                        text-decoration: none;
                        font-weight: bold;
                        font-size: 0.6em;
                        margin-right: 6.6em" href="#">
                                    <img style="margin-left: 30px;" src="https://testing.thernloven.com/tickets-api-new/public/images/book-open.svg" alt="icon Book Open" >
                        </a>
                        <br>
                            <span style="font-size: 0.9em;" >빅애플패스 이용방법 </span>
                    </div>
                </td>
                <td style="padding: 4px;">
                    <div style=" width: 125px; height: 100px; display: inline-block;">
                    <a style="margin: 0;
                                box-sizing: border-box;
                                color:#A3A3A3;
                                text-decoration: none;
                                font-weight: bold;
                                font-size: 0.6em;
                                margin-right: 6.6em" href="#">
                                    <img style="margin-left: 30px;" src="https://testing.thernloven.com/tickets-api-new/public/images/dollar-circle.svg" alt="icon Money" >
                        </a>
                        <br>
                            <span style="font-size: 0.9em;" >취소 및 환불규정 </span>
                    </div>
                </td>
                <td style="padding: 4px;">
                    <div style=" width: 150px; height: 100px; display: inline-block;">
                     <a style="margin: 0;
                                box-sizing: border-box;
                                color:#A3A3A3;
                                text-decoration: none;
                                font-weight: bold;
                                font-size: 0.6em;" href="#">
                                    <img style="margin-left: 30px;" src="https://testing.thernloven.com/tickets-api-new/public/images/location.svg" alt="icon Location" >
                        </a>
                        <br>
                        <span style="font-size: 0.9em;" >타미스 오시는길 </span>
                    </div>
                </td>
            </tr>
        </table>
    </div>
        <div style="margin: 0;
                box-sizing: border-box;
                padding: 0 3em 0 3em">
            <p style="margin: 0;
                box-sizing: border-box;
                color: #5D5D5F;
                font-family: sans-serif;
                font-size: 0.9em;
                line-height: 1.5"> {{__('Hi!')}} {{$fullname}}, <br>
                @if($template->content == 'default')
                {{__('Your Password was updated successfully')}}:
            </p>
                @else
                <p style="margin: 0;
                box-sizing: border-box;
                color: #5D5D5F;
                font-family: sans-serif;
                font-size: 0.9em;
                line-height: 1.5">
                {!!$template->content!!}
                </p>
            </p>
                @endif
                <footer style="margin: 0;
                        box-sizing: border-box;
                        width: 100%;
                        justify-content: center;
                        border-top: 1px solid #A3A3A3;
                        padding-top: 1em;">
                    <div style="justify-content: space-between;">
                        <div style="margin: 0;
                            box-sizing: border-box;
                            display: inline-block;">
                        </div>
                        <div style="margin: 0 auto;
                            box-sizing: border-box;
                            display: inline-block;
                            float: right">
                            <a style="margin: 0;
                            box-sizing: border-box;
                            color:#A3A3A3;
                            text-decoration: none;
                            font-weight: bold;
                            font-size: 0.8em;
                            margin-right: 0.5em" href="https://blog.naver.com/tamice">
                                <img style="margin: 0 auto; width: 20px; height: 20px" src="https://testing.thernloven.com/tickets-api-new/public/images/circle-blog.png">
                            </a>
                            <a style="margin: 0;
                            box-sizing: border-box;
                            color:#A3A3A3;
                            text-decoration: none;
                            font-weight: bold;
                            font-size: 0.8em;
                            margin-right: 0.5em" href="https://www.facebook.com/NYTamice">
                                <img style="margin: 0 auto; width: 20px; height: 20px" src="https://testing.thernloven.com/tickets-api-new/public/images/circle-facebook.png">
                            <a style="margin: 0;
                            box-sizing: border-box;
                            color:#A3A3A3;
                            text-decoration: none;
                            font-weight: bold;
                            font-size: 0.8em;
                            margin-right: 0.5em" href="https://www.instagram.com/with.tamice">
                                <img style="margin: 0 auto; width: 20px; height: 20px" src="https://testing.thernloven.com/tickets-api-new/public/images/circle-instagram.png">
                            </a>
                            <a style="margin: 0;
                            box-sizing: border-box;
                            color:#A3A3A3;
                            text-decoration: none;
                            font-weight: bold;
                            font-size: 0.8em;" href="#"></a>
                        </div>           
                    </div>
                    <div style="justify-content: center; text-align: center">
                        <span>@2023 Tamice INC Privacy Policy</span>
                    </div>
                    <div style="justify-content: center; text-align: center;margin-left: -84px;">
                        <span>151 West 46th Street, Suite 1002, New York, NY 10036</span>
                    </div>
                </footer>
                <div></div>
        </div>
    </div>
    
</body>
</html>
