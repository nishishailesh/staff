<?php



function menu($link)
{	
		
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
		
echo '
<form method=post>
  <link rel="stylesheet" href="../css/style.css">

<table width=100% cellpadding="0"  cellspacing="0">
<tr><td width=70%>
		<table cellpadding="0"  cellspacing="0">
			<tr><td>
					<button class=mainmenubutton type=button onclick="showhidemenu(\'button1\')">Declaration</button>
					<table  id="button1" cellpadding="0"  cellspacing="0" class="menu" style="position:absolute; display:none;">
						<tr><td>
							<button  class=dropdownbutton formaction='.$GLOBALS['rootpath'].'/declaration/new.php type=submit onclick="hidemenu()" name=new>Prepare</button>
						</td></tr>			
						<tr><td>
							<button class=dropdownbutton formtarget=_blank formaction='.$GLOBALS['rootpath'].'/declaration/check.php type=submit onclick="hidemenu()" name=new>Check</button>
						</td></tr>
						<tr><td>
								<button class=dropdownbutton formaction='.$GLOBALS['rootpath'].'/declaration/viewpdf.php type=submit onclick="hidemenu()" formtarget="_blank" name=view>Print</button>
						</td></tr>
						<tr><td>
								<button class=dropdownbutton formaction='.$GLOBALS['rootpath'].'/declaration/viewpdf.php type=submit onclick="hidemenu()" formtarget="_blank" name=view>Print PDF</button>
                                                </td></tr>

						<tr><td>
								<button class=dropdownbutton formaction='.$GLOBALS['rootpath'].'/declaration/download_attachment.php type=submit onclick="hidemenu()" formtarget="_blank" name=download_attachment>Download Attachment</button>
						</td></tr>
					</table>
					
			</td><td>
					<button class=mainmenubutton type=button onclick="showhidemenu(\'button3\')">Salary</button>
					<table  id="button3" class="menu" style="position:absolute; display:none;">
					<tr><td>
						<button class=dropdownbutton formaction='.$GLOBALS['rootpath'].'/salary/export_selected1.php type=submit onclick="hidemenu()" name=new>View</button>
					</td></tr>
					</table>	
			</td>


			<td>
					<button class=mainmenubutton type=button onclick="showhidemenu(\'button4\')">Publication</button>
					<table  id="button4" class="menu" style="position:absolute; display:none;">
					<tr><td>
						<button class=dropdownbutton formaction='.$GLOBALS['rootpath'].'/publication/start.php type=submit onclick="hidemenu()" name=new>Edit</button>
					</td></tr>
					</table>	
			</td>
</tr>
		</table>
</td><td  style="text-align:right" width=30%>
		<table cellpadding="0"  cellspacing="0">
			<tr><td>Password expires on ';echo expirydate($link,'staff',$_SESSION['login']); 
echo'
			</td>
			<td>
					<button  class=mainmenubutton style="padding:0px;" type=button onclick="showhidemenu(\'button2\')">
					
					<img src="../image/settings.png" height=25></button>
					<table  cellpadding="0"  cellspacing="0" id="button2" class="menu" style="position: absolute;display:none;">
					<tr><td>
						<button class=dropdownbutton formaction='.$GLOBALS['rootpath'].'/common/logout.php type=submit onclick="hidemenu()" name=new>Logout</button>
					</td></tr>
					<tr><td>
						<button class=dropdownbutton formaction='.$GLOBALS['rootpath'].'/common/change_pass.php type=submit onclick="hidemenu()" name=new>Change Password</button>
					</td></tr>
					</table>	
			</td></tr>
		</table>
</td></tr></table>
</form>

';

}

//Manage My Account('.$_SESSION['login'].')

function menu_office()
{	

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



		
echo '
<form method=post>
<table class=\"menu\">
<tr><td>
		<button type=button onclick="showhidemenu(\'button1\')">Change Service Records</button>
		<table  id="button1" class="menu" style="position:absolute; display:none;">
			<tr><td>
				<button class=dropdownbutton formaction='.$GLOBALS['rootpath'].'/sr/staff-wise.php type=submit onclick="hidemenu()" name=new>Manage staff-wise</button>
			</td></tr>
			<tr><td>
				<button class=dropdownbutton formaction='.$GLOBALS['rootpath'].'/sr/detail-wise.php type=submit onclick="hidemenu()" name=new>Manage detail-wise</button>
			</td></tr>
		</table>
</td><td>
		<button type=button onclick="showhidemenu(\'button3\')">View Service Records</button>
		<table  id="button3" class="menu" style="position:absolute; display:none;">
			<tr><td>
				<button class=dropdownbutton formaction='.$GLOBALS['rootpath'].'/sr/view_data.php type=submit onclick="hidemenu()" name=new>View data</button>
			</td></tr>

                        <tr><td>
				<button class=dropdownbutton formaction='.$GLOBALS['rootpath'].'/sr/experience.php 
						type=submit onclick="hidemenu()" 
						name=new>View Experience</button>
                        </td></tr>

		</table>	
</td><td>
		<button  type=button onclick="showhidemenu(\'button2\')">Manage My Account('.$_SESSION['login'].')</button>
		<table  id="button2" class="menu" style="position: absolute;display:none;"><tr><td>

			<button class=dropdownbutton formaction='.$GLOBALS['rootpath'].'/common/logout.php type=submit onclick="hidemenu()" name=new>Logout</button></td></tr><tr><td>

			<button class=dropdownbutton formaction='.$GLOBALS['rootpath'].'/common/change_pass_office.php type=submit onclick="hidemenu()" name=new>Change Password</button></td></tr>

		</table>	
</td></tr>
</table>
</form>
';

}

//menu();

?>
