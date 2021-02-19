<?php
session_start();
require_once 'config.php';
require_once '/var/gmcs_config/staff.conf';
require_once 'common_table_function.php';
//require_once 'print_lables.php';
require_once('../tcpdf/tcpdf.php'); //if in /usr/share/php folder

//my_print_r($_POST);
//RVogdc4R!

$ex=explode('|',$_POST['action']);
//print_r($ex);
if(count($ex)==3)
{
	$d=$ex[1];
	$t=$ex[2];
	$action=$ex[0];
}
else
{
	$d=$_POST['^database'];
	$t=$_POST['^table'];
	$action=$_POST['action'];	
}

if(isset($action))
{
	if($_POST['action']=='download' || $action=='print_pdf' || $action=='generate_pdf' || $action=='pp')
	{
		$GLOBALS['nojunk']=TRUE;
	}
}

if(isset($_POST['offset']))
{
	$offset=$_POST['offset'];
}
else
{
	$offset=0;
}

$link=set_session();

$dk=get_dependant_table($link,$d,$t);

//my_print_r($dk);

//if primary key is to be made readonly, it must be autoincrement
//autoincrement and default are readonly
$pk=get_primary_key($link,$d,$t);	
$pka=array();
$pka_value=false;

	foreach($pk as $pk_key)
	{
		if(isset($_POST[$pk_key['Field']]))
		{
			$pka[$pk_key['Field']]=$_POST[$pk_key['Field']];
			$pka_value=true;
		}
		else
		{
			$pka[$pk_key['Field']]='';
		}
	}
	if($action=='download')									
	{														
		download($link,$d,$t,$_POST['blob_field'],$pka);	
		exit(0);											
	}																	

if(isset($_POST['offset']))
{
	$offset=$_POST['offset'];
}
else
{
	$offset=0;
}
	
head();
menu();

///////////basic///////////
if($action=='save')
{
	save($link,$d,$t,$_POST,$_FILES);
}
elseif($action=='insert')
{
	insert($link,$d,$t,$_POST,$_FILES);
}
elseif($action=='edit')										
{															
	edit($link,$d,$t,$pka,$GLOBALS['default']);			
}															
elseif($action=='delete')									
{															
	delete($link,$d,$t,$pka);							
}															
elseif($action=='new')									
{															
	add($link,$d,$t,$GLOBALS['default']);									
}
elseif($action=='pp')									
{	
  
  
    //display_staff_pdf_upload($link,$_SESSION['login']);												
	$sql='SELECT topic,level,year FROM `publication_upload` where staff_id= \''.$_SESSION['login'].'\' ORDER BY year ASC';
	print_pdf($link,$d,$t,$sql);								
}
////////////////////////////////
	echo '<form method=post action=../common/start.php>';
			echo '<button class="btn btn-info" type=submit name=action value=xyz>Home</button>';
	echo '</form>';
		
	//show_horizontal_all_sql($link,$d,$t,'select * from `'.$t.'` where staff_id=\''.$_SESSION['login'].'\'');
	
	$sar=array('staff_id'=>$_SESSION['login'],'cb_staff_id'=>'');
	show_search_rows_h($link,$d,$t,$sar,$offset);
	add($link,$d,$t,$GLOBALS['default'],'style="display:none"');	
	
tail();
?>

