<?php
session_start();

require_once 'common.php';

echo '<html>';
echo '<head>';

echo '

<style>
	
table{
   border-collapse: collapse;
}

.border td , .border th{
    border: 1px solid black;
}

.upload{
	background-color:lightpink;	
}

.noborder{
 border: none;
}


.hidedisable
{
	display:none;diabled:true
}

</style>


';

echo '</head>';
echo '<body>';
unset($_SESSION['login']);
unset($_SESSION['password']);
echo '
<form method=post action='.$GLOBALS['rootpath'].'/common/start_office.php>
<table style="background-color:lightblue;"" align=center class=border>
<tr><th colspan=2>Government Medical College Surat</th></tr>
<tr><th  colspan=2>Staff Database</th></tr>
<tr><th colspan=2 style="color:blue;">Login for ADMINISTRATIVE Staff</th>
<tr>
<td>Login ID</td>
<td><input style="width:100%" type=text name=login placeholder=Username></td>
</tr>
<tr>
<td>Password</td>
<td><input  style="width:100%" type=password name=password  placeholder=Password></td>
</tr>
<tr>
<td colspan=2 align=center><input type=submit name=action value=Login></td>
</tr>
</table>
</form> ';

echo '</body></html>';

?>
