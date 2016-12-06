<?php

echo '

<style>
	.menu {border:0px;border-spacing: 0;border-collapse: collapse;background-color:lightgreen;}
</style>

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
		
echo '
<form method=post>
<table class=\"menu\">
<tr><td>
		<button type=button onclick="showhidemenu(\'button1\')">Declaration</button>
		<table  id="button1" class="menu" style="position:absolute; display:none;"><tr><td>
			<button formaction='.$GLOBALS['rootpath'].'/declaration/new.php type=submit onclick="hidemenu()" name=new>Prepare</button></td></tr><tr><td>
		</table>
		
</td><td>
		<button type=button onclick="showhidemenu(\'button3\')">Leave</button>
		<table  id="button3" class="menu" style="position:absolute; display:none;"><tr><td>
			<button formaction='.$GLOBALS['rootpath'].'/leave/new_leave.php type=submit onclick="hidemenu()" name=new>Apply</button></td></tr><tr><td>
		</table>
		
</td><td>
		<button  type=button onclick="showhidemenu(\'button2\')">Manage My Account('.$_SESSION['login'].')</button>
		<table  id="button2" class="menu" style="position: absolute;display:none;"><tr><td>
		
			<button formaction='.$GLOBALS['rootpath'].'/common/logout.php type=submit onclick="hidemenu()" name=new>Logout</button></td></tr><tr><td>
			
			<button formaction='.$GLOBALS['rootpath'].'/common/change_pass.php type=submit onclick="hidemenu()" name=new>Change Password</button></td></tr>
			
		</table>	
</td></tr>
</table>
</form>
';

}



//menu();

?>
