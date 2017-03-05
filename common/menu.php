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
		<table  id="button1" class="menu" style="position:absolute; display:none;">
			<tr><td>
				<button formaction='.$GLOBALS['rootpath'].'/declaration/new.php type=submit onclick="hidemenu()" name=new>Prepare</button>
			</td></tr>			
			<tr><td>
				<button formtarget=_blank formaction='.$GLOBALS['rootpath'].'/declaration/check.php type=submit onclick="hidemenu()" name=new>Check</button>
			</td></tr>
                        <tr><td>
                                <button formaction='.$GLOBALS['rootpath'].'/declaration/view.php type=submit onclick="hidemenu()" name=view>View</button>
                        </td></tr>

		</table>
		
</td><td>
		<button type=button onclick="showhidemenu(\'button3\')">Leave</button>
		<table  id="button3" class="menu" style="position:absolute; display:none;">
		<tr><td>
			<button formaction='.$GLOBALS['rootpath'].'/leave/new_leave.php type=submit onclick="hidemenu()" name=new>Apply</button>
		</td></tr>
		</table>
		
</td><td>
		<button  type=button onclick="showhidemenu(\'button2\')">Manage My Account('.$_SESSION['login'].')</button>
		<table  id="button2" class="menu" style="position: absolute;display:none;">
		<tr><td>
			<button formaction='.$GLOBALS['rootpath'].'/common/logout.php type=submit onclick="hidemenu()" name=new>Logout</button>
		</td></tr>
		<tr><td>
			<button formaction='.$GLOBALS['rootpath'].'/common/change_pass.php type=submit onclick="hidemenu()" name=new>Change Password</button>
		</td></tr>
		</table>	
</td></tr>
</table>
</form>
';

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
