OTUS Highload Architect Study Homework

Methods implemented:
	/index.php
		If auth session created when move to /index.php?uid=<id>
			<id> - UserID for current auth user
		Else move to /index.php?login
	/index.php?login
		If user login exist and specified password is correct when create session and move to /index.php
		Else refresh page /index.php?login
	/index.php?adduser
		If specified user login not exist when create user and move to /index.php?login
		Else refresh page /index.php?adduser
	/index.php?uid=<id>
		Display user info by specified UserID
			<id> - UserID for current auth user