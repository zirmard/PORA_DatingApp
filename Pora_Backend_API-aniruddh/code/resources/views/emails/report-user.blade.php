<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="format-detection" content="date=no" />
  <meta name="format-detection" content="address=no" />
  <meta name="format-detection" content="telephone=no" />
  <meta name="x-apple-disable-message-reformatting" />
  <title> {{ config('app.name') }}</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

  <style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap');

    *, * * {
 margin: 0;
 padding: 0;
 box-sizing: border-box;
 -webkit-tap-highlight-color: transparent;
 -webkit-text-size-adjust: 100%;
 -webkit-font-smoothing: antialiased
}
body {
  background: #f9fafb;
  font-family: 'Montserrat', sans-serif;
  font-size: 15px;
  line-height: 20px;
  font-weight: 400;
  color: #1F2A37;
  overflow-x: hidden;
  padding: 10px;
}

.reset-password-action-btn {
    color: #fff !important;
	border-radius: 8px;
	background-color: #FF9A01;
	font-size: 14px;
	padding: 8px 12px;
	font-weight: 500;
    text-decoration: none;
}

.table-main-body{
  border-collapse: collapse;
    border: 0;
    margin-left: auto;
    margin-right: auto;
    padding: 0;
    background-color: #fff;
    width: 100%;
    max-width: 600px;
    margin-top: 30px;
    margin-bottom: 30px;
 }
.table-border-main{
    border-collapse: collapse;
    border: 1px solid #eeeff0;
    margin: 0;
    padding: 20px 0px;
    -webkit-text-size-adjust: none;
}
.main-mail-title{
  font-size: 25px;
  color: #1F2A37;
  font-weight: 700;
  margin-bottom: 20px;
}
.sub-mail-title{
   font-size: 18px;
  color: #1F2A37;
  font-weight: 600;
  margin-bottom: 15px;
}
.main-content p{
  margin-bottom: 15px;
  color: #6B7280;
}
.latest-title-col{
  width:33.333%;
  padding-left: 10px;
  padding-right: 10px;
}
.light-orange-btn{
  background-color: #FFF3E1;
    border-radius: 15px;
    font-size: 14px;
    color: #FF9A01;
    padding: 8px 30px;
    font-weight: 500;
    box-shadow: none;
    -webkit-transition: 0.3s ease-in-out;
    transition: 0.3s ease-in-out;
    display: inline-block;
    text-decoration: none;
}
.light-orange-btn:hover{
  color: #fff;
  background-color: #FF9A01;
}
.social-media-link a{
  display: inline-block;
  margin-right: -4px;
  padding: 0px 15px;
  font-size: 18px;
  color: #FF9A01;
}
.pora-mail-logo{
  display: inline-block;
  text-decoration: none;
  vertical-align: middle;
}
.pora-mail-logo img{
  vertical-align: middle;
}
.pora-mail-logo span{
  display: inline-block;
  vertical-align: middle;
  margin-bottom: 0px;
  margin-left: 10px;
}
@media only screen and (max-width: 600px){
  .main-mail-title{
    font-size: 20px;
  }
}
@media only screen and (max-width: 480px){
  .pora-mail-logo img {
    display: block;
    margin-bottom: 10px;
}
.pora-mail-logo span{
  margin-left: 0px;
}
}

.sub-mail-title a {
    font-size: 18px;
    color: #ff9a01;
    font-weight: 600;
    margin-bottom: 15px;
    text-decoration: none;
}
</style>

</head>
 <body>
<table class="table-main-body">
<caption></caption>
  <thead>
    <tr>
        <th scope="col"></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td class="table-border-main">
        <table style="width:100%">
          <caption></caption>
          <thead>
            <tr>
                <th scope="col"></th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td style="border-bottom: 4px solid #FF9A01; padding: 10px 15px;">
                <a href="javascript:void(0)" class="pora-mail-logo" title="{{ config('app.name') }}">
                  <img src="{{ asset('theme/dist/img/logo.png') }}" alt="{{ config('app.name') }}">
                </a>
              </td>
            </tr>
            <tr>
              <td style="padding: 10px 15px;">
                <div class="mail-main-title-body">
                  <h2 class="main-mail-title">
                    Report Reason
                  </h2>
                </div>
              </td>
            </tr>

            <tr>
              <td style="padding: 10px 15px;">
                <div class="main-content">
                  <strong>Hello Admin,</strong>
                  <br>
                  <br>
                  <p>
                    <strong> Reported By: </strong> {{ $reportedByUserName }} <br/><br/>
                    <strong> Reported User: </strong> {{ $reportedUserName }} <br/><br/>
                    <strong> Report Reason: </strong> {{ $reportReason }}
                  </p>
                </div>
              </td>
            </tr>
            <tr>
              <td style="padding: 10px 15px; border-bottom: 4px solid #FF9A01; text-align: center;">
              </td>
            </tr>
          </tbody>
        </table>
      </td>
    </tr>
  </tbody>
</table>
</body>
</html>
