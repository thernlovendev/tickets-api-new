<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- <link href="https://file.myfontastic.com/6tkDvaBT8S52S4nU8THupM/icons.css" rel="stylesheet"> -->
    <!-- <link rel="stylesheet" href="css/styles.css"> -->
    <title>{{__('Upgraded Order Successfully')}}</title>
</head>
<body style="margin: 0;
    box-sizing: border-box;
    background-color: rgb(224, 224, 224);
    justify-content: center;
    align-items: center;">
    <div style="margin: 0 auto;
        box-sizing: border-box;
        width: 60%;
        height: 80%;
        background-color: #fff;
        border-radius: 5px;
        justify-content: center;
        border-bottom: 0.5em solid #71C8FC;
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
        <div style="margin: 0;
                box-sizing: border-box;
                padding: 0 3em 0 3em">
            <p style="margin: 0;
                box-sizing: border-box;
                color: #9A9A9A;
                font-family: sans-serif;
                font-size: 0.9em;
                line-height: 1.5">{{__('Hi!')}} {{$fullname}}<br>
                {{__('We’ve received your upgrade for your reservation for amount of:')}} {{$amount}}$</p>
                <p style="margin: 0;
                        box-sizing: border-box;
                        color: #9A9A9A;
                        font-family: sans-serif;
                        font-size: 0.9em;
                        line-height: 1.5;
                        padding-bottom: 1em">{{__('Thanks')}},<br>
                {{env('APP_NAME')}} Team</p>
                <footer style="margin: 0;
                        box-sizing: border-box;
                        width: 100%;
                        justify-content: center;
                        border-top: 1px solid #A3A3A3;
                        padding-top: 1em;
                        justify-content: space-between;">
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
                            <img style="margin: 0 auto; width: 13px; height: 20px" src="https://tamice.com/site/images/v2/ico/sns_circle_blog.png">
                        </a>
                        <a style="margin: 0;
                        box-sizing: border-box;
                        color:#A3A3A3;
                        text-decoration: none;
                        font-weight: bold;
                        font-size: 0.8em;
                        margin-right: 0.5em" href="https://www.facebook.com/NYTamice">
                            <img style="margin: 0 auto; width: 13px; height: 20px" src="https://i.imgur.com/9fberpr.png">
                        <a style="margin: 0;
                        box-sizing: border-box;
                        color:#A3A3A3;
                        text-decoration: none;
                        font-weight: bold;
                        font-size: 0.8em;
                        margin-right: 0.5em" href="https://www.instagram.com/with.tamice">
                            <img style="margin: 0 auto; width: 16px; height: 21px" src="https://joga.nyc3.digitaloceanspaces.com/icons/email_instagram.png">
                        </a>
                        <a style="margin: 0;
                        box-sizing: border-box;
                        color:#A3A3A3;
                        text-decoration: none;
                        font-weight: bold;
                        font-size: 0.8em;" href="#"></a>
                    </div>
                </footer>
        </div>
    </div>
    
</body>
</html>
