<?php

/**echo '

<style>
	.menu {border:0px;border-spacing: 0;border-collapse: collapse;background-color:lightgreen;}
</style>**/
/**echo '<html lang="en" >
<head>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>
  <body>
	  <div >
		 <img src="../image/college_logo.png"  width="15%" height="130">
	     <img src="../image/hostel.png" width="65%" height="130">
	     <img src="../image/gujarat.png"  width="15%" height="130">
	  </div>
	</form>
</body>';*/
echo '
<script type="text/javascript" >
		function showhidemenu(one) 
		{		
			xx=document.getElementsByClassName(\'menu\');			
			for(var i = 0; i < xx.length; i++)
			{
				if(xx[i]!=document.getElementById(one))
				{
					xx[i].style.display = "none";		
				}
				
				else if(xx[i]==document.getElementById(one))
				{
					if(xx[i].style.display == "block")
					{
						xx[i].style.display = "none";
					}
					else
					{
						xx[i].style.display = "block";
					}		
				}
			}	
		}
		
		function hidemenu() {
		
			xx=document.getElementsByClassName(\'menu\');
			for(var i = 0; i < xx.length; i++)
			{
				xx[i].style.display = "none";		
			}
		}
		
		//document.onclick=function(){hidemenu();};
		</script>';

function menu()
{	
	echo'<head>

   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <link rel="stylesheet" href="../css/style.css">
   <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
   <script src="script.js"></script>
   <title>CSS MenuMaker</title>
</head>
<body>

<div id="cssmenu">
<ul>
     <li class="active has-sub"><a href=""><span>Declaration</span></a>
      <ul>
         <li class="has-sub"><a href='.$GLOBALS['rootpath'].'/declaration/new.php "><span>Prepare</span></a></li>
         <li class="has-sub"><a href='.$GLOBALS['rootpath'].'/declaration/check.php "><span>Check</span></a></li>
         <li class="has-sub"><a href='.$GLOBALS['rootpath'].'/declaration/view.php "><span>Print</span></a></li>
         <li class="has-sub"><a href='.$GLOBALS['rootpath'].'/declaration/download_attachment.php "><span>Download Attachment</span></a></li>
      </ul>
   </li>
   
   <li class="active has-sub"><a href=""><span>Leave</span></a>
      <ul>
         <li class="has-sub"><a href='.$GLOBALS['rootpath'].'/leave/new_leave.php "><span>Apply</span></a></li>
      </ul>
   </li>
    <li class="active has-sub"><a href=""><span>Manage My Account</span></a>
      <ul>
         <li class="has-sub"><a href='.$GLOBALS['rootpath'].'/common/logout.php "><span>Logout</span></a></li>
         <li class="has-sub"><a href='.$GLOBALS['rootpath'].'/common/change_pass.php "><span>Change Password</span></a></li>
      </ul>
   </li>
</div>

</body>
<html>';
	
}


function menu_office()
{	
		
echo '
<form method=post>
<table class=\"menu\">
<tr><td>
		<button type=button onclick="showhidemenu(\'button1\')">Change Service Records</button>
		<table  id="button1" class="menu" style="position:absolute; display:none;">
			<tr><td>
				<button formaction='.$GLOBALS['rootpath'].'/sr/staff-wise.php type=submit onclick="hidemenu()" name=new>Manage staff-wise</button>
			</td></tr>
			<tr><td>
				<button formaction='.$GLOBALS['rootpath'].'/sr/detail-wise.php type=submit onclick="hidemenu()" name=new>Manage detail-wise</button>
			</td></tr>
		</table>
</td><td>
		<button type=button onclick="showhidemenu(\'button3\')">View Service Records</button>
		<table  id="button3" class="menu" style="position:absolute; display:none;">
			<tr><td>
				<button formaction='.$GLOBALS['rootpath'].'/sr/view_data.php type=submit onclick="hidemenu()" name=new>View data</button>
			</td></tr>

                        <tr><td>
				<button formaction='.$GLOBALS['rootpath'].'/sr/experience.php 
						type=submit onclick="hidemenu()" 
						name=new>View Experience</button>
                        </td></tr>

		</table>	
</td><td>
		<button  type=button onclick="showhidemenu(\'button2\')">Manage My Account('.$_SESSION['login'].')</button>
		<table  id="button2" class="menu" style="position: absolute;display:none;"><tr><td>

			<button formaction='.$GLOBALS['rootpath'].'/common/logout.php type=submit onclick="hidemenu()" name=new>Logout</button></td></tr><tr><td>

			<button formaction='.$GLOBALS['rootpath'].'/common/change_pass_office.php type=submit onclick="hidemenu()" name=new>Change Password</button></td></tr>

		</table>	
</td></tr>
</table>
</form>
';

}

//menu();

?>
