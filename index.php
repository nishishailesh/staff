<?php

echo '
<style>
	
table{
   border-collapse: collapse;
}

.border td , .border th{
    border: 1px solid gray;
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

.section_header
{
	background-color:gray;
}
</style>



';

if(isset($_GET['message'])){echo "<h3 align=center style='color:red;'>".$_GET['message']."</h3>";}

echo '<table class=border align=center style="background-color:lightgray;">';
echo '<tr><td style="background-color:lightpink;"><h2>Government Medical College Surat</h2></td></tr>';
echo '<tr><td align=center><h3>Staff Database</h3></td></tr>';
echo '<tr><td  align=center><br><a href=common/index.php>Login For Teaching staff</a><br><br></td></tr>';
echo '<tr><td  align=center><br><a href=common/index_office.php>Login for Administrative Office Staff</a><br><br></td></tr>';
echo '</table>';
echo '<br><br>';
echo '<table align=center class=border style="background-color:lightgreen;">';
echo '<tr><td><h4 style=\'color:red;\' >Information provided by you in declaration form is confidential. <br>It can be retrived by only YOU and office staff authorized by the office of the dean.</h4></td></tr>';
echo '<tr><td><h4 style=\'color:red;\' >The information is not shared and not available to general public on Internet</h4></td></tr>';
echo '<tr><td><h4 style=\'color:blue;\' >There is separate login for Admin office staff</h4></td></tr>';
echo '</table>';
//echo  $_SERVER['REMOTE_ADDR'];
?>
