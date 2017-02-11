<?php
session_start();


require_once '../common/common.php';

function view_data($link,$id)
{
	if(!$result_id=mysqli_query($link,'select * from view_data where id=\''.$id.'\'')){echo mysqli_error($link);}
	$array_id=mysqli_fetch_assoc($result_id);
	
	$sql=$array_id['sql'];
	$info=$array_id['info'];
		
	$first_data='yes';
	
	if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link);}
	echo '<table border=1><tr><th colspan=20 bgcolor=lightblue>'.$info.'</th></tr>';
	
	$first_data='yes';
	
	while($array=mysqli_fetch_assoc($result))
	{
		if($first_data=='yes')
		{
			echo '<tr bgcolor=lightgreen>';
			foreach($array as $key=>$value)
			{
				echo '<th>'.$key.'</hd>';
			}
			echo '</tr>';
			$first_data='no';
		}
		foreach($array as $key=>$value)
		{
			echo '<td>'.$value.'</td>';
		}
		echo '</tr>';

	}
	echo '</table>';
}


function get_sql($link)
{
	if(!$result=mysqli_query($link,'select * from view_data')){echo mysqli_error($link);}
	echo '<form method=post>';
	echo '<table border=1><tr><th colspan=20>Select the data to view</th></tr>';
	
	$first_data='yes';
	
	while($array=mysqli_fetch_assoc($result))
	{
		if($first_data=='yes')
		{
			echo '<tr>';
			foreach($array as $key=>$value)
			{
				echo '<th bgcolor=lightgreen>'.$key.'</th>';
			}
			echo '</tr>';
			$first_data='no';
		}
		foreach($array as $key=>$value)
		{
			if($key=='id')
			{
				echo '<td><input type=submit name=id value=\''.$value.'\'></td>';
			}
			else
			{
				echo '<td>'.$value.'</td>';
			}
		}
		echo '</tr>';

	}
	echo '</table>';
	echo '</form>';
}


$link=connect_office();

menu_office();

echo '<h2 style="page-break-before: always;"></h2>';	
if(isset($_POST['id']))
{
	view_data($link,$_POST['id']);
}
get_sql($link);
?>
