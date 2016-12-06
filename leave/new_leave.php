<?php
session_start();
require_once '../common/common.php';
//echo "<pre>";
//print_r($GLOBALS);
//echo "</pre>";
connect();


menu();

$link=connect();

$staff_sql= "select * from staff where id=".$_SESSION["login"];
$staff_data= get_raw($link,$staff_sql);
 if($staff_data === false)
{
	echo mysqli_error($link);
}
 $current_appointment_sql="select * from staff_movement where staff_id=".$_SESSION["login"]." and to_date is NULL" ;
 
 $current_appointment_data=get_raw($link,$current_appointment_sql);
?>

<!DOCTYPE HTML>
  
<html>
<head>
	<script type="text/javascript" src="date/datepicker.js"></script>
<script src="jquery-3.1.1.min.js"></script>
<link rel="stylesheet" type="text/css" href="date/datepicker.css" />
	
	
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
	<Script> 
		er=0;
function load_datepicker_dynamically(idd)
{ 
  targett = document.getElementById(idd);
    var className = targett.className;
    if (className=='datepicker' || className.indexOf('datepicker ') != -1 || className.indexOf(' datepicker') != -1) {
      var a = document.createElement('a');
      a.href='#';
      a.className="datepickershow";
      a.setAttribute('onclick','return showDatePicker("' + targett.id + '")');
      var img = document.createElement('img');
      img.src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAIAAACQkWg2AAAABGdBTUEAAK/INwWK6QAAABh0RVh0U29mdHdhcmUAUGFpbnQuTkVUIHYzLjM2qefiJQAAAdtJREFUOE+Vj+9PUnEUxvPvar3xja96Q1hGEKG0ubZqbfHCNqIVA4eYLAwFp0LYD4iIJEdeRGGZwDAEcUOn9oNIvPcGgjBQfHE69/YFihe1zs59du7d83nOuR0AcOq/CgEqWbaHDqaD+clF1rLAmija6MsZ5vb0s9nB1xm168s9x67y6Y7q2TaXjo8tVKjUTv7Zt61pAkwt/UA3zFwFuxysV2BKAuYeMAnBcBaGukDdCaozaLg5sUGAiQDLA3IIDIBfAfO34N118PaDRwYvRfBcCMrTaLg2liTAOEW3NjzpBZsMpqUwKQaLCMYvwGMhjArQIDfGCTDqy3EAX47lfVTnCo3qCnOzJ8IpW6pJR2IEGHn7/bBaR5MLO8y8CtPuKO2J0nMfGdKr+5uZ4kVdhAD6N99K1bo7ynB5vHpj3AZ0NxWBbs0KAbTur8VKfTbGeFcbkc1sfnBHuA1CzTIB7js/H5SPffFW3q9sau2PDdLhxkl3X+wiQCVYf4Jt3h1Itmb8iBvEusZJd2a2CuXjxXUWU5dSnAZ5/b0QkOobgMKWzh8eMcXaXr6aYSqfcuXtbAkdbS3RfSD/MGDfvGFO9ZuSfY/ilx/GLumi57Vhgfp9W597ECJA2/a/v/4ENLpYKsDo3kgAAAAASUVORK5CYII=';
      img.title='Show calendar';
      a.appendChild(img);
      insertAfter(a, targett);
    }
}

function toDate(selector) {
    var from = $(selector).val().split("-");
    return new Date(from[2], from[1], from[0]);
}
	
<!-- jquery , require its library -->
function get_date_diff(from,to, target)
{
	var start = toDate(from);
	var end =   toDate(to);

	// end - start returns difference in milliseconds 
	var diff = new Date(end - start);

	// get days
	var year = Math.floor(diff/1000/60/60/24/365);
	var month = Math.floor(((diff/1000/60/60/24)%365)/30);
	$(target).val(year+" yr,"+month+" mo");
}


</script> 
</head>
<body>  



<form method="post" action="save_leave.php">  
  <table border=1 cellpadding=5 cellspacing=5  align=center>
	 <tr><td colspan=3>	
		<table border=0 cellpadding=5 cellspacing=5  align=center  style="width:100%">
			<tr>
				<th colspan="3" align="center"> 
					<img src="logo.png" alt="Mountain View" style="width:125px;height:100px;" align="left">
				</th>
				<th>
					Application For Leave <br> Government Medical College, Surat.
				</th>	
				<th>
					<img src="logo2.png" alt="Mountain View" style="width:125px;height:100px;" align="right">
				</th>
			</tr>
		</table>
	</td></tr>
	  <tr>
		  <td colspan="3">Notes: <br> -> Item 1 to 8 must be filled in by all applicants whether gazetted or non-gazetted. <br> -> Item 9 applies on the case of gazetted officers. 
		  </td>
		  <!--
		  <td>
		  Application No. <input type="text" readonly id="application_id" name="application_id" placeholder="System Generated"> </td>-->
		  
		</tr>
	  <tr><td>1.</td><td>Name of Applicant:</td><td> <?php echo $staff_data["fullname"]; ?></td></tr>
	  
  
<tr>
	  <td>2.</td>
	  <td>Post held : </td>
	  <td><?php echo $current_appointment_data["post"];?></td>
</tr>
  
  
<tr>
	<td>3.</td>
	<td>Department.</td>
	<td><?php echo $current_appointment_data["department"];?></td>
</tr>
  
<!-- 
<tr>
	<td>4.</td>
	<td>Pay(Basic) : </td>
	<td><input type="text" name="pay" id="pay"></td>
</tr>
  
  
<tr>
	<td>5.</td>
	<td>House Allowances, Conveyance allowances <br>or Other Compensotory allowance draw in the present post : </td>
	 <td><input type="text" name="allowances" id="allowances"></td>
</tr>
 --> 
  
  
<tr>
	<td>6.</td>
	<td>Nature and Period of leave applied for <br> and date from leave required : </td>
	
	
    <td>
		<table>
			<tr>
				<td colspan=5>
					<select name="nature" id="nature">
					  <option>Earned Leave</option>
					  <option>Leave Without Pay</option>
					  <option>Maternity Leave</option>
					  <option>Half pay Leave</option>
					  <option>Commuted Leave</option>
					</select>
				</td>
			</tr>
			<tr><td>  
					From:</td><td><input readonly	id=startdate class="datepicker" size="10" name=from_date>
				</td>
				<td>
					To :</td><td><input readonly	id=enddate class="datepicker" size="10" name=to_date>
				</td>
			</tr>
			<tr>
				<td> 
					Prefix Date: </td><td><input size=10 type="text" id=prefix_date name="prefix_date"> 
		   		</td>
				<td>
					Postfix Date:</td><td><input size=10 type="text" id=postfix_date name="postfix_date"> 
		   		</td>
			</tr>
	</table>
   </td>
</tr>
  
<tr>
	  <td>7.</td>
	  <td>Reason For Leave :</td>
	  <td><input type="text" name="reason" id="reason_leave" size="30"></td>
</tr>

<!--
<tr>
	<td>8.</td>
	<td>Date of return from last leave and the nature and period of that leave : </td>
	<td><textarea placeholder="Describe last levae nature and date" name="lastleave" name="lastleave"cols="30" rows="3"></textarea></td>
</tr>

<tr>
	<td>9.</td>
	<td colspan="3">I undertake to refund by deduction from my pension if nesessary the difference,if any between average pay<br> and half average pay for the period of leave of average pay granted in exexess if. I retire from Government service at the<br> end of this leave on or an extension of it. </td></tr>

<tr>

-->

	<td> </td>
	<td colspan=2>Date of Application.:<input readonly id=appl_date class="datepicker" value='<?php echo date('d-m-Y'); ?>' size="10" name="application_date"></td>
	<!--
	<td><textarea cols="35" rows="3" placeholder="sign and stamp after print" readonly></textarea>
	<br>Signature of Applicant</td>-->
</tr>
<!--
<tr>
	<td>10.</td>
	<td>Remarks or Recommendation of the controlling officer : </td>
	<td><textarea cols="35" rows="3" readonly placeholder="sign and stamp of controlling officer after print"></textarea></td>
</tr>
 
<tr>
	<td>11.</td>
	<td>Report of the Audit Officer:</td>
	<td><textarea cols="35" rows="3" readonly placeholder="Remarks from Audit officer after print"></textarea></td>
</tr>
-->
 
<tr>
	<td></td><td colspan="2"><input type="submit" value="submit" style="background-color:gray" name="save_leave"></td>
</tr>

</table>


</form>


</body>
</html>
