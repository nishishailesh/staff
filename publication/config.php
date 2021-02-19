<?php
$GLOBALS['login_message']='';
$GLOBALS['user_database']='staff';
$GLOBALS['user_table']='staff';
$GLOBALS['user_id']='id';
$GLOBALS['user_pass']='epassword';
$GLOBALS['expiry_period']='+ 6 months';
$GLOBALS['nojunk']=false;
$GLOBALS['expirydate_field']='expirydate';

$GLOBALS['textarea_size']=70;	//for input vs textarea
$GLOBALS['limit']=10;			//for show all
$GLOBALS['search_limit']=10;	//for search


$GLOBALS['menu']=array
					(
						'Publication'=>array(
											'Edit'=>array('xyz|staff|publication_upload','main.php',''),
											'Print'=>array('pp|staff|publication_upload','main.php','target=_blank')
										)										
					);

if(isset($_SESSION['login']))
{
	//$GLOBALS['default']=array('department'=>$_SESSION['login']);
	$GLOBALS['default']=array('staff_id'=>$_SESSION['login']);
}
?>
