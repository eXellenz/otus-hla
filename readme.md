<h1>OTUS Highload Architect Study Homework</h1>
<br/>
<p><b>Methods implemented:</b></p>
<p>&nbsp;&nbsp;&nbsp;&nbsp;/index.php</p>
<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;If auth session created when move to /index.php?uid=id</p>
<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;id - UserID for current auth user</p>
<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Else move to /index.php?login</p>
<p>&nbsp;&nbsp;&nbsp;&nbsp;/index.php?login</p>
<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;If user login exist and specified password is correct when create session and move to /index.php</p>
<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Else refresh page /index.php?login</p>
<p>&nbsp;&nbsp;&nbsp;&nbsp;/index.php?adduser</p>
<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;If specified user login not exist when create user and move to /index.php?login</p>
<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Else refresh page /index.php?adduser</p>
<p>&nbsp;&nbsp;&nbsp;&nbsp;/index.php?uid=id</p>
<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Display user info by specified UserID</p>
<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;id - UserID for current auth user</p>