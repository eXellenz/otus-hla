<h1>OTUS Highload Architect Study Homework</h1>
<br/>
<p><b>Methods implemented:</b></p>
<p style="margin-left: 50px;">/index.php</p>
<p style="margin-left: 100px;">If auth session created when move to /index.php?uid=id</p>
<p style="margin-left: 150px;">id - UserID for current auth user</p>
<p style="margin-left: 100px;">Else move to /index.php?login</p>
<p style="margin-left: 50px;">/index.php?login</p>
<p style="margin-left: 100px;">If user login exist and specified password is correct when create session and move to /index.php</p>
<p style="margin-left: 100px;">Else refresh page /index.php?login</p>
<p style="margin-left: 50px;">/index.php?adduser</p>
<p style="margin-left: 100px;">If specified user login not exist when create user and move to /index.php?login</p>
<p style="margin-left: 100px;">Else refresh page /index.php?adduser</p>
<p style="margin-left: 50px;">/index.php?uid=id</p>
<p style="margin-left: 100px;">Display user info by specified UserID</p>
<p style="margin-left: 150px;">id - UserID for current auth user</p>