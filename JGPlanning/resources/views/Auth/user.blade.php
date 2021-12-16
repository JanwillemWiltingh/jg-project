@extends('layouts.guest')
@section('content')
    <table cellspacing="0" border="0" cellpadding="0" width="100%" bgcolor="#f2f3f8"
           style="@import url(https://fonts.googleapis.com/css?family=Rubik:300,400,500,700|Open+Sans:300,400,600,700); font-family: 'Open Sans', sans-serif;">
        <tr>
            <td>
                <table style="background-color: #f2f3f8; max-width:670px;  margin:0 auto;" width="100%" border="0"
                       align="center" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="height:20px;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="margin-left: 20% !important;">
                            <a href="{{route('login')}}" style="margin-left: 40%;"><img src="https://i.imgur.com/jlj0oEc.png" alt="JGPlanning"></a>
                        </td>
                    </tr>
                    <tr>
                        <td style="height:20px;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td>
                            <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0"
                                style="max-width:670px;background:#fff; border-radius:3px; text-align:center;-webkit-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);-moz-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);box-shadow:0 6px 18px 0 rgba(0,0,0,.06);">

                                <tr>
                                    <td style="height:40px;"></td>
                                </tr>
                                <tr>
                                    <td style="padding:0 35px;">
                                        <p style="color:#455056; font-size:15px;line-height:24px; margin:0;">
                                            Hallo {{$request['firstname']}} {{$request['lastname']}},
                                        </p>
                                        <p style="color:#455056; font-size:15px;line-height:24px; margin:0;">
                                            Welkom bij het roostersysteem van JG Webmarketing
                                        </p>
                                        <hr>
                                        <p style="color:#455056; font-size:15px;line-height:24px; margin:0;">

                                            Door op de onderstaande knop te klikken kan jij jouw wachtwoord aanmaken
                                        </p>
                                        <a href="{{route('forget.password.get')}}"
                                           style="background: black;text-decoration:none !important; font-weight:500; margin-top:20px; color:#fff; font-size:14px;padding:8px 18px;display:inline-block;border-radius:5px;">
                                            Wachtwoord instellen</a><br><br>

                                        <p style="color:#455056; font-size:15px;line-height:24px; margin:0;">
                                            Met vriendelijke groet,<br>Het JG Rooster Team
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="height:40px;">&nbsp;</td>
                                </tr>
                            </table>
                        </td>
                    <tr>
                        <td style="height:20px;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="height:80px;">&nbsp;</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <!--/100% body table-->
@endsection
