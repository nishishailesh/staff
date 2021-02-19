
<?php
session_start();

require_once 'common.php';

echo '<html><head><script type="text/javascript" >
		function showhide(one) {
			if(document.getElementById(one).style.display == "block")
			{
				document.getElementById(one).style.display = "none";
			}
			else
			{
				document.getElementById(one).style.display = "block";
			}	
		}
		</script>
		
		<link rel="stylesheet" href="../css/style.css">
		</head>';
			
echo '<body>

      <div >
		 <img src="../image/college_logo.png"  width="15%" height="130">
	     <img src="../image/hostel.png" width="65%" height="130">
	     <img src="../image/gujarat.png"  width="15%" height="130">
	  </div><br><br>';


if(!isset($_SESSION['login']))
{
	$_SESSION['login']=$_POST['login'];
}


if(!isset($_SESSION['password']))
{
	$_SESSION['password']=$_POST['password'];
}

$link=connect();

menu($link);


//echo '<pre>';print_r($_POST);echo '</pre>';


echo '</body></html>';


?>
