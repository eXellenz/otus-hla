<h1>OTUS Highload Architect Study Homework</h1>
<br/>
<p>
	<b>Methods implemented:</b>
</p>
<p>
	&nbsp;&nbsp;&nbsp;&nbsp;/index.php<br/>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;If auth session created when move to /index.php?uid=int<br/>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;int - UserID for current auth user<br/>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Else move to /index.php?login
</p>
<p>
	&nbsp;&nbsp;&nbsp;&nbsp;/index.php?login<br/>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;If user login exist and specified password is correct when create session and move to /index.php<br/>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Else refresh page /index.php?login
</p>
	&nbsp;&nbsp;&nbsp;&nbsp;/index.php?adduser<br/>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;If specified user login not exist when create user and move to /index.php?login<br/>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Else refresh page /index.php?adduser
</p>
<p>
	&nbsp;&nbsp;&nbsp;&nbsp;/index.php?uid=int<br/>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Display user info by specified UserID<br/>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;int - UserID for current auth user
</p>
<p>
	&nbsp;&nbsp;&nbsp;&nbsp;/index.php?search<br/>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Search user by specified string<br/>
</p>
<p>
	&nbsp;&nbsp;&nbsp;&nbsp;/friend.php?add&id=int<br/>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Add friend by specified id<br/>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;int - Friend UserID<br/>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;If UserID exist in friend list when warning<br/>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;If UserID not exist in user table when warning<br/>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;If friend added when update cached friend list and move to /index.php
</p>
<p>
	&nbsp;&nbsp;&nbsp;&nbsp;/friend.php?delete&id=int<br/>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Delete friend by specified id<br/>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;int - Friend UserID<br/>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;If UserID not exist in friend list when warning<br/>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;If friend deleted when update cached friend list and move to /index.php
</p>