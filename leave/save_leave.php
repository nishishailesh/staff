<?php
session_start();
require_once '../common/common.php';

//echo '<pre>';print_r($GLOBALS);echo '</pre>';

function date_diff_to_days($from,$to)
{
	//dates as yyyy-mm-dd format only
	//To    2016-03-04
	//From  2015-05-20
	//      0000-09-(N) 
	
	$exf=explode('-',$from);
	$ext=explode('-',$to);
	if(count($exf)!=3||count($ext)!=3)
	{
		return false;
	}
	
	if(in_array('00',$exf)===TRUE || in_array('0000',$exf)===TRUE)
	{
		//print_r($exf);
		return false;
	}
	
	$days_of_from_month=cal_days_in_month(CAL_GREGORIAN,$exf[1],$exf[0]);
	if($days_of_from_month===FALSE)
	{
		return FALSE;
	}
	$days=$ext[2]+($days_of_from_month-$exf[2]);
	
	
	$months=$ext[1]+12-$exf[1]-1;
	
	$years=$ext[0]-$exf[0]-1;
	
	if($days>cal_days_in_month(CAL_GREGORIAN,$exf[1],$exf[0])){$days=abs($ext[2]-$exf[2]);$months=$months+1;}
	if($months>11){$years=$years+1;$months=$months-12;}
	
	//echo "<h1>".$to." and ".$from."</h1>";
	//echo "<h1>".$years.",".$months.",".$days."</h1>";
	
	return $years." yr, ".$months." mo, ".$days." d";

}

$link=connect();
menu();

if (!isset($_POST["save_leave"]))
{
	exit();
}

//$ret=insert_field_by_id($link,"leave","application_id","","nature",$_POST["nature"]);

//staff_id is foreign key, it must be added first

$ret=insert_field_by_id($link,"leave","application_id",'',"staff_id",$_SESSION['login']);

//echo $ret;
if($ret === false)
{
	exit();
}

update_field_by_id($link,"leave","application_id",$ret,"reason",$_POST["reason"]);

update_field_by_id($link,"leave","application_id",$ret,"prefix",$_POST["prefix_date"]);

update_field_by_id($link,"leave","application_id",$ret,"postfix",$_POST["postfix_date"]);

//update_field_by_id($link,"leave","application_id",$ret,"staff_id",$_SESSION['login']);
update_field_by_id($link,"leave","application_id","","nature",$_POST["nature"]);


$from=india_to_mysql_date($_POST["from_date"]);
update_field_by_id($link,"leave","application_id",$ret,"from_date",$from);

$to=india_to_mysql_date($_POST["to_date"]);
update_field_by_id($link,"leave","application_id",$ret,"to_date",$to);

$application=india_to_mysql_date($_POST["application_date"]);
update_field_by_id($link,"leave","application_id",$ret,"application_date",$application);

$sql= "select * from `leave` where application_id=".$ret;
//echo ($sql);
$data = get_raw($link,$sql);
//print_r($data);

if($data === false)
{
	echo mysqli_error($link);
}

$staff_sql= "select * from staff where id=".$_SESSION["login"];
$staff_data= get_raw($link,$staff_sql);
 if($staff_data === false)
{
	echo mysqli_error($link);
}
 $current_appointment_sql="select * from staff_movement where staff_id=".$_SESSION["login"]." and to_date is NULL" ;
 
 $current_appointment_data=get_raw($link,$current_appointment_sql);
 //print_r($current_appointment_data);

?>


<html>
<head> 
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

</style>


</head>
<body>
<table border=1 cellpadding=5 cellspacing=5  style="width:200mm;" align=center>
	<tr>
		<td colspan="3" ><input type="submit" name="submit" value="print"></td>
	</tr>

	 <tr><td colspan=3>	
		<table border=0 cellpadding=5 cellspacing=5  align=center  style="width:100%">
			<tr>
				<th colspan="3" align="center"> 
					<img src="logo.png" alt="Mountain View" style="width:125px;height:100px;" align="left">
				</th>
				<th>
					Application For Leave <br> <?php echo $current_appointment_data["institute"];?>
				</th>	
				<th>
					<img src="logo2.png" alt="Mountain View" style="width:125px;height:100px;" align="right">
				</th>
			</tr>
		</table>
	</td></tr>
	  <tr><td colspan="3">Notes: <br> -> Item 1 to 8 must be filled in by all applicants whether gazetted or non-gazetted. <br> -> Item 9 applies on the case of gazetted officers. <br> 
	  Application No. <?php echo $data["application_id"]; ?> </td></tr>
	  <tr><td>1.</td><td>Name of Applicant:</td><td> <?php echo $staff_data["fullname"]; ?></td></tr>
	  
  
  <tr><td>2.</td><td>Post held :</td><td> <?php echo $current_appointment_data["post"];?></td></tr>
  
  
<tr><td>3.</td><td>Department :</td> <td><?php echo $current_appointment_data["department"];?></td></tr>
  
  <tr><td>4.</td><td>Pay(Basic) :      </td><td></td></tr>
   
  <tr><td>5.</td><td>House Allowances, Conveyance allowances <br>or Other Compensotory allowance draw in the present post : </td> 
 <td></td></tr>
  
  
<tr>
	<td>6.</td>
	<td>Nature and Period of leave applied for <br> and date from leave required : </td>
	<td> <?php 
	
	$dd=date_diff_grand($data["from_date"],$data["to_date"]);
	
	echo $data["nature"].",  <br> From:".$data["from_date"].
			" <br>  To:".$data["to_date"];
			echo "<br>Total:". $dd->format('%r%a')."<br> Prefix:".$data["prefix"]."<br> postfix:".$data["postfix"];
			?></td>
</tr>
<tr>
	<td>7.</td>
	<td>Reason For Leave :</td>
	<td><?php echo $data["reason"]; ?></td>
</tr>
<tr>
	<td>8.</td>
	<td>Date of return from last leave and the nature and period of that leave : </td>
	<td></td>
</tr>
 <tr>
	  <td>9.</td>
	  <td colspan="3">I undertake to refund by deduction from my pension if nesessary the difference,if any between average pay and half average pay for the period of leave of average pay granted in exexess if. I retire from Government service at the<br> end of this leave on or an extension of it. </td>
</tr>
<tr>
	<td> </td>
	<td valign="top"> Date of Application.: <?php echo $data["application_date"];?></td>
	<td>Signature of Applicant <br>  <br>  <br> <br> <br></td>
</tr>
<tr>
	<td>10.</td>
	<td>Remarks or Recommendation of the controlling officer : </td>
	<td> <br>  <br>  <br> <br> <br></td>
</tr>

<tr>
	<td>11.</td>
	<td>Report of the Audit Officer:</td>
	<td> <br>  <br>  <br> <br> <br></td>
</tr>
 
 

</table>
</body>
</html>








