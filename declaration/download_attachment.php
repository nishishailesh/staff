<?php
session_start();
require_once '../common/common.php';
require_once 'save.php';

$link=connect();


function download_blob($post)
{
	$link=start_nchsls();
	
	$pri=find_primary($post['tname']);

	$where_condition=' ';
	$file=$post['tname'];
	foreach($pri as $key=>$value)
	{
		$where_condition=$where_condition.' `'.$value.'`=\''.$post[$value].'\' and ';
		$file=$file.'-'.$value;
	}
	$where_condition_final=substr($where_condition,0,-4);	
	
	
	$blob=find_blob($post['tname']);

	foreach($blob as $keyy=>$valuee)
	{
		$sql='select `'.$valuee.'` , `'.$valuee.'_name` from `'.$post['tname'].'` where '.$where_condition_final;	
		//echo $sql;
		if(!$result=mysql_query($sql,$link))
		{
			echo 'download_blob() error:'.mysql_error();
		}
		else
		{
			$result_array=mysql_fetch_assoc($result);	
			$name=$result_array[$valuee.'_name'];
			$length=10000000000;
			$type='pdf';
			header("Content-Disposition: attachment; filename=$name");
			header("Content-length: $length");
			header("Content-type: $type");		
			echo $result_array[$valuee];
		}
	}
	exit(0);
}
//echo '<pre>';
//print_r($GLOBALS);

menu();


echo '<table border=1>';
/////////PAN//////////////
echo '<form method=post action=download.php target=_blank>';
$sql_pan='select staff_id,pan from pan where staff_id=\''.$_SESSION['login'].'\'';
$pan=get_raw($link,$sql_pan);
//echo '<table border=1>';
echo '<tr>';
echo '<td>'.$pan['staff_id'].'</td>';
echo '<td></td>';
echo '<th>PAN</th>';
echo '<td>'.$pan['pan'].'</td>';
//echo '<td>'.$pan['attachment_filename'].'</td>';
echo '<td>';
echo '<input type=hidden name=table value=pan>';
echo '<input type=hidden name=filename value=attachment_filename>';
echo '<input type=hidden name=file value=attachment>';
$wr=base64_encode('where staff_id=\''.$_SESSION['login'].'\'');
echo '<input type=hidden name=where value=\''.$wr.'\' >';
echo '<button>download</button>';
echo '</td>';
echo '</tr>';
//echo '</table>';
echo '</form>';

/////////address//////////////
echo '<form method=post action=download.php target=_blank>';
$sql_add='select id,filename from residencial_address_proof where id=\''.$_SESSION['login'].'\'';
$add=get_raw($link,$sql_add);
//echo '<table border=1>';
echo '<tr>';
echo '<td>'.$add['id'].'</td>';
echo '<td></td>';
echo '<th>Address Proof</th>';
echo '<td>'.$add['filename'].'</td>';
echo '<td>';
echo '<input type=hidden name=table value=residencial_address_proof>';
echo '<input type=hidden name=filename value=filename>';
echo '<input type=hidden name=file value=proof>';
$wr=base64_encode('where id=\''.$_SESSION['login'].'\'');
echo '<input type=hidden name=where value=\''.$wr.'\' >';
echo '<button>download</button>';
echo '</td>';
echo '</tr>';
//echo '</table>';
echo '</form>';



/////////photo//////////////
echo '<form method=post action=download.php target=_blank>';
$sql_photo='select id,photo_id_filename,proof_type from photo where id=\''.$_SESSION['login'].'\'';
$photo=get_raw($link,$sql_photo);
//echo '<table border=1>';
echo '<tr>';
echo '<td>'.$photo['id'].'</td>';
echo '<th>Photo ID Proof</th><th>'.$photo['proof_type'].'</th>';
echo '<td>'.$photo['photo_id_filename'].'</td>';
echo '<td>';
echo '<input type=hidden name=table value=photo>';
echo '<input type=hidden name=filename value=photo_id_filename>';
echo '<input type=hidden name=file value=photo_id>';
$wr=base64_encode('where id=\''.$_SESSION['login'].'\'');
echo '<input type=hidden name=where value=\''.$wr.'\' >';
echo '<button>download</button>';
echo '</td>';
echo '</tr>';
//echo '</table>';
echo '</form>';


/////////QUALIFICATION/////////////
echo '<form method=post action=download.php target=_blank>';
$sql_qa='select staff_id,q.qualification_id,qualification,type,attachment_id from qualification q,qualification_attachment qa where q.qualification_id=qa.qualification_id and staff_id=\''.$_SESSION['login'].'\'';


if(!$result=mysqli_query($link,$sql_qa)){echo mysqli_error($link);return FALSE;}
	else
	{
		while($ar=mysqli_fetch_assoc($result))
		{
			echo '<form method=post action=download.php target=_blank>';
			//echo '<table border=1>';
			echo '<tr>';
			echo '<td>'.$ar['staff_id'].'</td>';
			echo '<td>'.$ar['qualification_id'].'</td>';
			echo '<td>'.$ar['qualification'].'</td>';
			echo '<td>'.$ar['type'].'</td>';
			echo '<td>';
			echo '<input type=hidden name=table value=qualification_attachment>';
			echo '<input type=hidden name=filename value=attachment_filename>';
			echo '<input type=hidden name=file value=attachment>';
			$wr=base64_encode('where attachment_id=\''.$ar['attachment_id'].'\'');
			echo '<input type=hidden name=where value=\''.$wr.'\' >';
			echo '<button>download</button>';
			echo '</td>';
			echo '</tr>';
			//echo '</table>';
			echo '</form>';			
		}
	}
	
	
/////////////experience/////////////////////////

echo '<form method=post action=download.php target=_blank>';
$sql_qa='select staff_id,q.movement_id,from_date,qa.type,attachment_id from staff_movement q,staff_movement_attachment qa where q.movement_id=qa.movement_id and staff_id=\''.$_SESSION['login'].'\'';


if(!$result=mysqli_query($link,$sql_qa)){echo mysqli_error($link);return FALSE;}
	else
	{
		while($ar=mysqli_fetch_assoc($result))
		{
			echo '<form method=post action=download.php target=_blank>';
			//echo '<table border=1>';
			echo '<tr>';
			echo '<td>'.$ar['staff_id'].'</td>';
			echo '<td>'.$ar['movement_id'].'</td>';
			echo '<td>'.$ar['from_date'].'</td>';
			echo '<td>'.$ar['type'].'</td>';
			echo '<td>';
			echo '<input type=hidden name=table value=staff_movement_attachment>';
			echo '<input type=hidden name=filename value=attachment_filename>';
			echo '<input type=hidden name=file value=attachment>';
			$wr=base64_encode('where attachment_id=\''.$ar['attachment_id'].'\'');
			echo '<input type=hidden name=where value=\''.$wr.'\' >';
			echo '<button>download</button>';
			echo '</td>';
			echo '</tr>';
			//echo '</table>';
			echo '</form>';			
		}
	}

echo '</table>';	

?>
