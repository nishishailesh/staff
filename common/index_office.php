<?php
session_start();

require_once 'common.php';
unset($_SESSION['login']);
unset($_SESSION['password']);

echo '<html lang="en" >
<head>

  <title>Login Form</title>
      <link rel="stylesheet" href="../css/style.css">
</head>
<body>
  <body>
  <form method=post action='.$GLOBALS['rootpath'].'/common/start_office.php>
	  <div >
		 <img src="../image/college_logo.png"  width="15%" height="130">
	     <img src="../image/hostel.png" width="65%" height="130">
	     <img src="../image/gujarat.png"  width="15%" height="130">
	  </div>
	  <div>
	  <br>
	  <center><h1 style="color:white;">Login For Administrative Staff</h1></div></center>
	<div class="login">
		<div class="login-screen">
			<div class="app-title">
				<h1>Login</h1>
			</div>

			<div class="login-form">
				<div class="control-group">
				<input type="text" class="login-field" name="login" value="" placeholder="Username" id="login-name">
				<label class="login-field-icon fui-user" for="login-name"></label>
				</div>

				<div class="control-group">
				<input type="password" class="login-field" name="password" value="" placeholder="Password" id="login-pass">
				<label class="login-field-icon fui-lock" for="login-pass"></label>
				</div>
				<input type="submit" class="btn btn-primary btn-large btn-block" name="action" value="Login">
				
				
			</div>
		</div>
	</div>
	</form>
</body>';


echo '</body></html>';

?>
