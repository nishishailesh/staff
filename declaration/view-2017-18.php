<?php
session_start();
require_once '../common/common.php';
require_once 'save.php';

$link=connect();

$save_msg='';
if(		isset($_POST['action']) || 
		isset($_POST['delete_qualification'])===true || 
		isset($_POST['delete_experience'])===true ||
		isset($_POST['delete_mci'])===true)
{
	if(save($link)===true )
	{
		$save_msg='Saved at --->'.strftime("%T").'';
	}
}

//even if following variables are false, thet are created and donot result in 'variable not found' error
//A variable set to FALSE is very useful, to prevent unnesseary errors
 
$staff_detail=get_raw($link,'select * from staff where id=\''.$_SESSION['login'].'\'');
$photo=get_raw($link,'select * from photo where id=\''.$_SESSION['login'].'\'');


//find current appointment
//find appointment order only if current appointment exist
$current_appointment=FALSE;
$current_appointment=get_raw($link,'select * from staff_movement where staff_id=\''.$_SESSION['login'].'\' and to_date is NULL');
//print_r($current_appointment);
$current_appointment_order=FALSE;
$current_joining_order=FALSE;
$current_joining_attached='No';
$previous_relieving_order=FALSE;

	$current_appointment=get_raw($link,'select * from staff_movement where staff_id=\''.$_SESSION['login'].'\' and to_date is NULL');
	
if($current_appointment)
{
	$current_appointment_order=get_raw($link,'select * from staff_movement_attachment 
			where movement_id=\''.$current_appointment['movement_id'].'\' and type=\'appointment_order\'');
	$current_joining_order=get_raw($link,'select * from staff_movement_attachment 
			where movement_id=\''.$current_appointment['movement_id'].'\' and type=\'joining_order\'');

	$previous_relieving_order=get_raw($link,'select * from staff_movement_attachment 
			where movement_id=\''.$current_appointment['movement_id'].'\' and type=\'relieving_order\'');

						
	if($current_joining_order!==FALSE){$current_joining_attached='Yes';}
}

//$last_mci=get_raw($link,'select * from last_mci where id=\''.$_SESSION['login'].'\' ');


$proof_of_residence_attached='No';
$r_proof=get_raw($link,'select * from residencial_address_proof where id=\''.$_SESSION['login'].'\' ');
if(!$r_proof){$proof_of_residence_attached='No';}else{$proof_of_residence_attached='Yes';}



$met=get_raw($link,'select * from met where id=\''.$_SESSION['login'].'\' ');

$degree_attachment_str='';
$reg_attachment_str='';


$last_mci_date=get_raw($link,'select max(`date`) md from mci where staff_id=\''.$_SESSION['login'].'\'');



//if same date joining-relieving with FN,AN, this function will give wrong result
function find_staff_movement_details_at_specific_date($link,$dt)
{
	$sql='select * from staff_movement where staff_id=\''.$_SESSION['login'].'\'';
	if(!$result=mysqli_query($link,$sql)){return FALSE;}
	
	while($ra=mysqli_fetch_assoc($result))
	{		
		if(strlen($ra['to_date'])==0)
		{
			$tdate=date("Y-m-d");
			//echo '<h1>'.$tdate.'</h1>';
		}
		else
		{
			$tdate=$ra['to_date'];
		}

		if(if_in_interval($dt,$ra['from_date'],$tdate)==0)
		{
			return $ra;
		}
	}
}

$last_mci_details=find_staff_movement_details_at_specific_date($link,$last_mci_date['md']);

function find_staff_movement_details_of_previous_institute($link,$current_institute_name)
{
	$sql='select * from staff_movement where staff_id=\''.$_SESSION['login'].'\' order by from_date desc';
	if(!$result=mysqli_query($link,$sql)){return FALSE;}
	

	while($ra=mysqli_fetch_assoc($result))
	{		
		if($ra['institute']!=$current_institute_name)
		{
			return $ra;
		}
	}
}

$publication=get_raw($link, 'select * from publication where staff_id=\''.$_SESSION['login'].'\'');

$pan=get_raw($link,'select * from pan where staff_id=\''.$_SESSION['login'].'\' ');

$donot_hide='';
if(isset($_POST['action']))
{
	if($_POST['action']=='add_experience')
	{
		$donot_hide='experience';
	}
	
	if($_POST['action']=='add_qualification')
	{
		$donot_hide='qualification';
	}
}


?>

<html>
<head>
<!--
<script type="text/javascript" src="../date/datepicker.js"></script>
<script src="../js/jquery-3.1.1.min.js"></script>
<link rel="stylesheet" type="text/css" href="../date/datepicker.css" /> 
-->
<style>
	
table{
   border-collapse: collapse;
   font-family:arial;
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
	display:block;diabled:true
}

.section_header
{
	background-color:gray;
}
</style>



<script>
	
qr=0;
er=0;

function getfrom(one,two) {
			document.getElementById(two).value =one.value;
		}

function copyfrom(target,source) {
			target.value =document.getElementById(source).value
		}
		

function show(one) {
				document.getElementById(one).style.display = "block";
		}

function clearFields(container) {
    var selects = container.getElementsByTagName('select');

    for(var i=0, len=selects.length; i < len; i++) {
        selects[i].selectedIndex = -1;
    }

    var fields = container.getElementsByTagName('input');
    for(var i=0, len=fields.length; i < len; i++) {
        var field = fields[i];
        switch(field.type)
        {
            case 'radio':
            case 'checkbox':
                field.checked = false;
                break;

            case 'text':
            case 'password':
            case 'hidden':
                field.value = ''
        }
    }

    var fields = container.getElementsByTagName('textarea');
    for(var i=0, len=fields.length; i < len; i++) {
        fields[i].value = '';
    }
}

function hide(one) {
				document.getElementById(one).style.display = "none";
		}

function hide_class(eclass,tclass) {
				cls=document.getElementsByClassName(eclass);
				for(var x=0;x<cls.length;x++){
					cls[x].style.display ="none";
					}	
				tls=document.getElementsByClassName(tclass);
				for(var x=0;x<tls.length;x++){
					tls[x].style.background ="gray";
					}									
		}
		
function showhide(one) {
				if(document.getElementById(one).style.display == "none")
				{
					document.getElementById(one).style.display = "block";
				}
				else
				{
					document.getElementById(one).style.display = "none";
				}

		}

function showhide_with_label(one,labell,textt) {
				if(document.getElementById(one).style.display == "none")
				{
					document.getElementById(one).style.display = "block";
					labell.innerHTML="hide "+textt;
				}
				else
				{
					document.getElementById(one).style.display = "none";
					labell.innerHTML="show "+textt;
				}

		}
		
function showhide_with_tab(one,eclass,tclass,myself) {
				//hide_class(eclass,tclass);
				if(document.getElementById(one).style.display == "none")
				{
					document.getElementById(one).style.display = "block";
					myself.style.background="#E4DDDD";
				}
				else
				{
					document.getElementById(one).style.display = "none";
					myself.style.background="gray";

				}

		}
								
function hide_and_clear(one) {
				document.getElementById(one).style.display = "none";
				element=document.getElementById(one);
				clearFields(element);
		}

function my_combo(ck,yes_target,no_target)
{
	if(ck.checked==true)
	{
		document.getElementById(yes_target).style.display="block";
		document.getElementById(no_target).style.display="none";
	}
	else
	{
		document.getElementById(no_target).style.display="block";	
		document.getElementById(yes_target).style.display="none";
	}	
	
}		
<!-- " ' and linebreak need to be escaped by \ in javascript  SMP-->

<!-- following function is required to load datepicker dynamically  SMP-->
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
  
function AddBefore(rowId,code){
    var target = document.getElementById(rowId);
    var newElement = document.createElement('tr');
    target.parentNode.insertBefore(newElement, target);
    newElement.innerHTML=code;
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


<!-- A4=210x297 so width=840 height=1200 -->



<div style="display:table;width:20cm;background-color:white;margin:1cm 1cm 1cm 1cm;border:1px solid lightgray;">
<input type=hidden name=id value=<?php echo '\''.$staff_detail['id'].'\'';?>

<p><b>NAME OF THE COLLEGE: 
	<?php echo $current_appointment['institute'];?>
</b></p>

<table class="border">
	<tr>
		<td><b>Date of <br>Assessment</b></td>
		<td style="width:40mm;"></td>
		<td style="width:120mm;" align=center><b>Remarks</b></td>
	</tr>
	<tr>
		<td><b>Accepted?<br>(YES/NO)</b></td>
		<td></td>
		<td rowspan="3"></td>
	</tr>
	<tr>
		<td><b>Name of the <br>Assessor</b></td>
		<td></td>
	</tr>
	<tr>
		<td><b>Signature of <br>Assessor</b></td>
		<td></td>
	</tr>
</table>


<h3><p align=center><b><u>DECLARATION FORM : 2017 - 2018 - FACULTY</u></b></p></h3>


</p>
	

<table class="noborder">
<tr>
<td>
	
	<p>1.(a)Name: 

	<?php echo $staff_detail['fullname']; ?>

	</p>

	<p>1.(b) Date of Birth 


	<?php echo mysql_to_india_date($staff_detail['dob']); ?>

	&amp; Age:

	<?php 
	$diff=get_date_diff_as_ymd($staff_detail['dob'],strftime("%Y-%m-%d"));
	echo $diff;
	?>
	</p>

	<p><b>1.(c)</b>Submit Photo ID proof issued by Govt. Authorities :</p>

	<p>
	<?php
		echo '<table class=noborder><tr><td>';
		echo 'Photo ID submitted: '.$photo['proof_type'];
		echo '</td></tr>';
		echo '<tr><td>';
		echo 'Number:'.$photo['proof_number'];	
		echo ' </td></tr><td>Issued by:'.$photo['proof_issued_by'];	
		echo '</td></tr>';
		echo '</table>';
	?>
	</p>

</td>

<td>
	<?php
			echo '<table class=noborder>
			<tr>
			<td align=center>';
				display_photo($link,$photo['photo']);
			echo '</td>
			</tr>';
			echo '</table>';
	?>

</td>
</tr>
</table>

<?php

?>



<p>Note:1) Without Photo ID, Declaration form will be rejected and will notbe considered as teaching   faculty. 2) Original Certificates are mandatory for verification. All Certificates/Documents/Certified Translations, must be in English</p>
<p>1.(d)i.Present Designation:
<?php 
	echo $current_appointment['post'];
?>
</p>
<p>1.(d)(i)a Certified copies of present appointment order at present institute attached.</p>

<p>1.(d)ii. Department: 

	<?php
		echo $current_appointment['department'];
	?>

</p>
<p>1.(d)iii.College: 
	<?php
		echo $current_appointment['institute'];
	?>

<p>1.(d)iv.City:
	<?php
		$ex=explode(' ', $current_appointment['institute']);
		$city=$ex[count($ex)-1];
		echo $city;
	?>

<p>1.(d)v.Nature of appointment: 
<?php
	if($current_appointment['type']=='Contract'){echo 'Contactual';}else{echo 'Regular';}
?>

</p>

<p>








	<?php
		if($last_mci_details['institute']==$current_appointment['institute'] && strlen($current_appointment['institute'])>0)
		{
			$last_mci_in_current_institute='Yes';
		}
		else
		{			
			$last_mci_in_current_institute='No';
		}

		if($last_mci_details['post']==$current_appointment['post'] && strlen($current_appointment['post'])>0)
		{
			$last_mci_as_current_post='Yes';
		}
		else
		{			
			$last_mci_as_current_post='No';
		}
		
		echo '<p>1.(d)vi. Date of appearance in Last MCI – UG/PG/Any Other Assessment:'.$last_mci_date['md'].'</p>';
		echo '<p>1.(d)vii Whether appeared in Last MCI - UG/PG Assessment in the same Institute - <b>'.$last_mci_in_current_institute.'</b></p>';

		echo '<p>1.(d)viii Whether appeared in Last MCI - UG/PG Assessment on same Designation -<b>'.
		$last_mci_as_current_post.'</b></p>';
		 
	?>



<table>
	<tr>
		<td>1.(e)Residential  Address of employee :</td>
		<td><?php

		echo '<pre>'.$staff_detail['residencial_address'].'</pre>';

?>		
		</td>
	</tr>
</table>
</p>


<p><table class=noborder style="width:190mm;"><tr><td>_____________</td><td>_______________</td></tr><tr><td>Signature of Faculty</td><td>Signature of Dean</td></tr><table></p>

<p style="page-break-after:always;"></p>
<p><b>1.(f) </b>Have you undergone Training in "Basic Course Workshop" at MCI Regional Centre in MET or in your college under Regional Centre observership

<?php
//find if met is found, if yes, change radio and default display
if($met!==FALSE)
{
	echo ':Yes';	
	
	echo '<table class=border id=MET_details style="display:block;">';
}
else
{
	echo ':No';
 
	echo '<table class=border id=MET_details style="display:block;">';
}

?>
		
		<tr>
			<th colspan=2  style="width:60%">Name of MCI Regional Centre where Training was done/If training was done in college, give the details of the observer from RC</th>
			<th colspan=2>Date and place of training</th>
		</tr>
		<tr>
			<td style="width:20%">MET Center:</td><td>
			<?php 
					echo $met['center'];
			?>
			
			</td>
			<td style="width:20%">MET Place:</td><td>
			<?php 
					echo $met['place'];
			?>
			
			</td>

		</tr><tr>
			<td  style="width:20%">MET Observer:</td><td>
			<?php 
					echo $met['observer'];
			?></td><td>Date</td><td>
			<?php 
					echo mysql_to_india_date($met['date']);
			?></td>

		</tr>
	</table>



	
<p>
	<table class="noborder">
		<tr>
			<td colspan=2>
<b>1.(g)</b>
Copy of Passport /Voter Card / Electricity Bill /Landline Telephone Bill / Aadhar Card / attached as a proof of residence.:
			<?php
				echo '<td>'.$proof_of_residence_attached.'</td>';
				echo '</tr>';
			?>
			
		</tr>
	</table>
</p>
			

<p><b>1.(h)</b>	Contact Particulars:</p>
<p>
<table>
<tr><td>Tel (Office)(with STD code):</td><td>
			<?php 
					echo $staff_detail['office_phone'];
			?>
			
</td><tr>
<tr><td>Tel(Residence): (with STD code)</td><td>
			<?php 
					echo $staff_detail['residencial_phone'];
			?>
</td><tr>
<tr><td>E-mail address: </td><td>
			<?php 
					echo $staff_detail['email'];
			?>
</td><tr>
<tr><td>Mobile Number: </td><td>
			<?php 
					echo $staff_detail['mobile'];
			?>
</td><tr>
</table>


</td></tr></table>
<tr><td><b>1.(i)</b>Date of joining present institution :</td><td>
	
<?php
//<input readonly id=present_institute_joining_date class="datepicker" size="10" name=present_institute_joining_date >

echo mysql_to_india_date($current_appointment['from_date']).' as '. $current_appointment['post']
?>

</td><tr>
</table>

<table class="noborder">
	<tr>
		<td><b>1.(j)</b>Joining report at the present institute attached:<?php echo $current_joining_attached; ?></td>
	</tr>
</table>

</p>

	
<!---start of qualification and experience -->
<p>

<b>2.</b>
 Qualifications :</p>
<table class="border" id="qualification_table">
<tr>
	<th>Qualification</th><th>College</th><th>	University	</th><th >Year</th><th>Registration No of UG & PG with date</th><th>	Name of the State Medical Council</th>
</tr>
<?php
view_table_qualification($link);
?>
</table>

<p>Note: For PG-Post PG qualification additional Registration certificate particulars be furnished and subject be indicated within brackets after scoring out whichever is not applicable.</p>
<p><b>2.(a)</b>Copy of Degree certificates  of MBBS and PG degree attached - 
<?php 
if(strlen($degree_attachment_str)>0){echo 'Yes';}else{echo 'No';} 
?>
</p>
<p>
<b>2.(b)</b> Copy of Registration of MBBS and PG degree attached -  
<?php 
if(strlen($reg_attachment_str)>0){echo 'Yes';}else{echo 'No';} 
?>
</p>



<p>
 <b>3.(a)</b> Details of the teaching experience till date.
 </p>
 
<p>
	<table class="border"   id="experience_table">

<?php
$ar=get_experience_mci($link,$_SESSION['login']);
print_experience_delcaration($ar);
//view_table_experience($link);

?>
</table>
</font>
</p>


<p><b>Note:-</b>Tutor working in Anesthesia and Radio-diagnosis must have 3 years teaching experience in the respective departments in a recognized /permitted medical institute to be consider as senior resident.</p>


<p><b>3(b).</b>To be filled in by Ex Army Personnel only</p>
	<table class=border style="width:100%;">
		<tr>
		<tr>
			<th rowspan=2 >S.No.</th>
			<th  rowspan=2 >Designation</th>
			<th  rowspan=2 >Institution</th>
			<th colspan=2>Period</th>
		</tr>
		<tr>
			<th>From</th>
			<th>To</th>
		</tr>
		<tr>
			<td>1.</td>
			<td>Graded Specialist</td>
			<td></td>
			<td></td>
			<td></td>
		</tr>		<tr>
			<td>2.</td>
			<td>Classified Specialist</td>
			<td></td>
			<td></td>
			<td></td>
		</tr>		<tr>
			<td>3.</td>
			<td>Adviser</td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
</table>
<b>Note:</b>Have you been considered in any UG/PG inspection at any other institution/medical college during last 3 years.  <br>If yes, please give details.
<br>Date of appearance:
<?php echo mysql_to_india_date($last_mci_date['md']);?> 
</p>

<!-- end of qualification and experience -->



<?php
		$previous_institute_details=find_staff_movement_details_of_previous_institute($link,$current_appointment['institute']);
?>
	
<table class=noborder>
	<tr>
		<td>
<b>4.(a)</b>
Before joining present institution I was working at 
				<?php echo $previous_institute_details['institute']; ?> 
				as 
				<?php echo $previous_institute_details['post']; ?>
				 and relieved on
				<?php echo mysql_to_india_date($previous_institute_details['to_date']); ?>
				after resigning / retiring .<br> (Relieving order is enclosed from the previous institution)
		</td>
	</tr>
	<tr>
		<td>
			<b>4.(b)</b> I am not working in any other medical college/dental college in the State or outside the State in any capacity Regular / Contractual.
		</td>
	</tr>
</table>
	
</p>





<p>
<table>
	<tr><td colspan=0><b>5.</b>  Number of  Research publications in Index Journals:</td></tr>
	<tr><td><b>5.(a)</b> International Journals:</td><td>
	<?php
	echo $publication['international'];
	?>
	
	</td></tr>
	<tr><td><b>5.(b)</b> National Journals:</td><td>
	<?php
	echo $publication['national'];
	?>
	</td></tr>
	<tr><td><b>5.(c)</b> State/Institutional Journals:</td><td>
	<?php
	echo $publication['state'];
	?>
	</td></tr>
</table>
</p>



<p>
<table class=noborder>
	<tr><td><b>6.(a)</b>My PAN Card No. is 

	
	
	<?php
		echo $pan['pan'];
	?>
	
	</td><tr>
	

</table>
</p>
<p>
	<b>6.(b)</b>I have drawn total emoluments from this college in the current financial year as under:-
<table class=border style="width:80%;" >	

	
	
	<tr><th>Month</th><th>Amount Received</th><th width=30%>TDS</th></tr>
	<tr><td>Apr 2016</td><td></td><td></td></tr>	
	<tr><td>May 2016</td><td></td><td></td></tr>	
	<tr><td>Jun 2016</td><td></td><td></td></tr>	
	<tr><td>Jul 2016</td><td></td><td></td></tr>	
	<tr><td>Aug 2016</td><td></td><td></td></tr>	
	<tr><td>Sep 2016</td><td></td><td></td></tr>	
	<tr><td>Oct 2016</td><td></td><td></td></tr>	
	<tr><td>Nov 2016</td><td></td><td></td></tr>	
	<tr><td>Dec 2016</td><td></td><td></td></tr>	
	<tr><td>Jan 2017</td><td></td><td></td></tr>	
	<tr><td>Feb 2017</td><td></td><td></td></tr>	
	<tr><td>Mar 2017</td><td></td><td></td></tr>	
	</table>
</table>

<table><tr><td><b>6.(c)</b>(Copy of my PAN &amp; Form 16 (TDS certificate) for financial year 2015-16 are attached)</td></tr></table>
</p>
<table><tr><td><b>7</b> I have appeared in the last inspection of the same College in the same post. </td><td>

<?php

	if($last_mci_as_current_post=='Yes' && $last_mci_in_current_institute=='Yes')
	{
		echo '<b>Yes</b>';
	}
	else
	{
		echo '<b>No</b>';
	}

?>
</tr></td></table>
<p style="page-break-after:always;"></p>
<p>
	
<table align="top" >


	
<tr><th colspan=3>DECLARATION</th></tr>
<tr><td valign="top" style="width:5%;">1.</td><td style="width:95%;"> I, Dr. 
<?php echo $staff_detail['fullname'] ?> am working as 
<?php echo $current_appointment['post'] ?> in the Department of 
<?php echo $current_appointment['department'] ?>  at 
<?php echo $current_appointment['institute'] ?>,
<?php 
	$ex=explode(' ', $current_appointment['institute']);
	$city=$ex[count($ex)-1]; 
	echo $city ;
?>
 and do hereby give an undertaking that I am a full time teacher in 
<?php echo $current_appointment['department'] ?>, working from  9 A.M. to 5 P.M. daily at this Institute.</td></tr>
<tr><td  valign="top">2.</td><td>I have not presented myself to any other Institution as a faculty in the current academic year for the purpose of MCI assessment.</td></tr>
<tr><td  valign="top"><b>3</b></td>
<td>
	<table>
	<tr><td>
		I am not having private practice anywhere.
	</td></tr>
	<tr><td>
	I am practicing at ___________ in the city of __________ and my hours of practice are _____ to _____ .Further I state that I am not doing any Private Practice or not working in any other hospital during college hours.
	</td></tr>
	</table>
</td></tr>
<tr><td valign="top">4.</td><td>Complete details with regard to work experience has been provided; nothing has been concealed by me.</td></tr>
<tr><td valign="top">5.</td><td>It is declared that each statement and/or contents of this declaration and /or documents, certificates submitted along with the declaration form, by the undersigned are absolutely true, correct and authentic.  In the event of any statement made in this declaration subsequently turning out to be incorrect or false the undersigned has understood and accepted that such misdeclaration in respect to any content of this declaration shall also be treated as a gross misconduct thereby rendering the undersigned liable for necessary disciplinary action (including removal of his name from Indian Medical Register).</td></tr>
</table>


</p>
<p>
<table class=noborder style="width:90%">
	<tr><td  style="width:15%">Date:</td><td  style="width:15%">__________</td><td   align=right style="width:50%">SIGNATURE OF THE EMPLOYEE</td></tr>
	<tr><td >Place:</td><td>__________</td><td align=right >__________</td></tr>
</table>
</p>


<H3 align=center>ENDORSEMENT</H3>
<p>1. This endorsement is the certification that the undersigned has satisfied himself /herself about the correctness and veracity of each content of this declaration and endorses the above mentioned declaration as true and correct.I have verified the certificates / documents submitted by the candidate with the original certificates/documents as submitted by the teacher to the Institute and with the concerned Institute and have found themto be correct and authentic.
</p>
<p>2. I also confirm that Dr.
<?php

echo $staff_detail['fullname'];

?>
 is not practicing or carrying out any other activity during college working hours i.e. from 9.00 AM to 5 PM , since he/she has joined the Institute.
</p>
<p>3.	In the event of this declaration turning out to be either incorrect or any part of this declaration subsequently turning out to be incorrect or false it is understood and accepted that the undersigned shall also be equally responsible besides the declarant himself/herself for any such misdeclaration or misstatement.                             
</p>
<table class=noborder style="width:100%">
	<tr>
		<td  style="width:10%">Date:</td>
		<td  style="width:10%">__________</td>
		<td   style="width:40%" align=center>Signed by HOD</td>
		<td  style="width:40%">Countersigned by the Director/Dean/Principal</td>
	</tr>
	<tr>
		<td >Place:</td>
		<td>__________</td>
		<td align=center>__________</td>
		<td>__________</td>
	</tr>
</table>
</p>
<p style="page-break-after:always;"></p>
<p>
<H3 align=center>REMARKS</H3>
<table class=border align=center>
<tr><td>S.No</td><td>Documents</td><td>Submitted</td></tr>
<tr><td>1</td><td>Recent Passport size photo of the Employee Signed by Dean / Principal of the college.</td><td>Yes / No</td></tr>
<tr><td>2</td><td>Photo ID proof issued by Govt. Authorities : Passport / PAN Card / Voter ID / Aadhar Card</td><td>Yes / No</td></tr>
<tr><td>3</td><td>Certified copies of present appointment order at present Institute.</td><td>Yes / No</td></tr>
<tr><td>4</td><td>Copy of Passport /Voter Card / Electricity Bill / Telephone Bill / Aadhar Card attached as a proof of residence. </td><td>Yes / No</td></tr>
<tr><td>5</td><td>Joining report at the present institute.</td><td>Yes / No</td></tr>
<tr><td>6</td><td>Copies of Degree certificates of MBBS and PG degree.</td><td>Yes / No</td></tr>
<tr><td>7</td><td>Copies of Registration of MBBS and PG degree.</td><td>Yes / No</td></tr>
<tr><td>8</td><td>Copy of experience certificate for all teaching appointments held before joining present institute.</td><td>Yes / No</td></tr>
<tr><td>9</td><td>Relieving order from the previous institution.</td><td>Yes / No</td></tr>
<tr><td>10</td><td>PAN Card</td><td>Yes / No</td></tr>
<tr><td>11</td><td>Form 16 (TDS certificate) for the last financial year. </td><td>Yes / No</td></tr>
<tr><td>12</td><td>Letter head (in case of teachers who are practicing)</td><td>Yes / No</td></tr>
</table>
</p>

<p>
<table class=noborder style="width:100%;">
<tr><td><b>Signed by the Teacher</td><td><b>Signed by the HOD</td></tr>
<tr><td><b>Date</td><td><b>Date</td></tr>
</table>
</p>

<p>
<table>
<tr><th>Countersigned by Dean / Principal:</th></tr>

<tr><td><b>Date</td></tr>

</table>
</p>

<p>
<table>
<tr><th>Signed & Verified by the Assessor:</th></tr>

<tr><td><b>Date</td></tr>
</td></tr>
</table>
</p>
<p align=center><b>Note:</b></p>
<p>1. The Declaration Form will not be accepted and the person will not be counted as teacher if any of the above documents are not enclosed /attached with the Declaration Form.</p>
<p>2. The person will not be counted as a teacher if the original of Photo ID proof, Registration Certificates / Degree certificates / PAN Card /State Medical Council ID (if issued) are not produced for verification at the time of assessment.</p>
<p>3. All the teachers must submit the revised declaration form in this format only. (Any declaration form submitted in an old format will not be accepted and he will not be counted as a teacher.)</p>


</div>
</body>
</html>
<?php

function add_qualification_raw($link)
{

	echo '<tr style="background-color:lightblue;">
			<td></td><td>';
	mk_select_from_table($link,'qualification_degree','','');
	
	$sql_qs='select department from department';
	mk_select_from_sql($link,$sql_qs,'department','qualification_subject','','');
	
	echo '<input  class=upload type=file name=file_qualification_degree ><br>^Upload qualification^';
	
	echo '	</td>
			<td  ><input  type=text name=college_qualification ></td>
			<td  ><input  type=text name=university_qualification ></td>
			<td  >';
			read_year('year_qualification',date("Y")-100,date("Y"));
			echo '</td>
			<td >
				<table class=noborder><tr><td>
				<input placeholder="Reg. No" type=text name=reg_no_qualification id=reg_no_qualification>
				</td></tr><tr><td>
				<input placeholder="Reg. Dt" readonly name=reg_date_qualification id=reg_date_qualification class="datepicker" >
				</td></tr><tr><td>';

			echo '<input  type=file class=upload name=file_qualification_reg ><br>^Upload Reg^';
			echo '</div>';				
					
			echo '</td></tr></table>
			</td>
			<td  ><input type=text name=council_qualification id=council_qualification></td>
			</tr>
			';
	echo '<tr>
			<td colspan=7><button type=submit name=action value=add_qualification style="background-color:lightgreen;"  >Add Qualification</button></td>
			</tr>';
}

function find_qualification_attachment_name($link,$qualification_id,$type)
{
	$sql='select * from qualification_attachment where qualification_id=\''.$qualification_id.'\' and type=\''.$type.'\'';
	if(!$result=mysqli_query($link,$sql)){return FALSE;}
	
	$ra=mysqli_fetch_assoc($result);
	return $ra['attachment_filename'];
}

function view_table_qualification($link)
{
	$sql='select * from qualification where staff_id=\''.$_SESSION['login'].'\' order by `year`';
	if(!$result=mysqli_query($link,$sql)){return FALSE;}
	
	while($ra=mysqli_fetch_assoc($result))
	{		
	$raw_html='<tr>
			<td>
			'.$ra['qualification'].'('.$ra['subject'].')
			</td>
			<td  >'.$ra['college'].'</td>
			<td  >'.$ra['university'].'</td>
			<td>'.$ra['year'].'</td>
			<td>
				'.$ra['registration_number'].', date:'.mysql_to_india_date($ra['registration_date']).'
			</td>
			<td>'.$ra['medical_council'].'</td>
			</tr>
			';
			echo $raw_html;
			$GLOBALS['degree_attachment_str'].=find_qualification_attachment_name($link,$ra['qualification_id'],'degree_certificate').',';
			$GLOBALS['reg_attachment_str'].=find_qualification_attachment_name($link,$ra['qualification_id'],'reg_certificate').',';
		}
}



function add_experience_raw($link)
{
	//Designation 	Type 	Department 	Name of Institution 	From - To - Total
	

	echo '	<td></td><td>';
			mk_select_from_sql($link,'select designation_type from designation_type',
			'designation_type','experience_designation','','');

	echo '	</td><td>';
			mk_select_from_sql($link,'select appointment_type from appointment_type',
			'appointment_type','experience_type','','');
	echo '		<td>';
			mk_select_from_sql($link,'select department from department',
			'department','experience_department','','');
	echo '	</td>
			<td >';
					mk_select_from_sql_with_separate_id($link,'select institute from institute',
						'institute','experience_institute_select','experience_institute_select','','');
						
					echo 	'<table class="noborder" ><tr><td>
								<input size=30 placeholder="Write Institute Name Here" style="display:block;" 
								type=text name=experience_institute_text id=experience_institute_text>	
									</td></tr><tr><td>Other Institutes:
								<input type=checkbox
								id=experience_institute_checkbox  name=experience_institute_checkbox title="Tick to enter name of other medical colleges"
								onclick="my_combo(this,\'experience_institute_text\',\'experience_institute_select\' )" >
							</td></tr></table>
			</td><td>
				<table>
					<tr>
						<td>From:</td>
						<td><input readonly class=datepicker name=from_experience id=from_experience></td>
						<td><select name=from_experience_time><option selected>FN</option><option>AN</option></select></td>
					</tr>
					<tr>
						<td rowspan=2>To:</td>
						<td><div id=to_experience_date>
							<input  readonly class=datepicker name=to_experience_pk id=to_experience_date_pk>
							</div>
							<input readonly style="display:block;" id=to_experience_text 
							name=to_experience_text type=text value=till_date >
						</td>
						<td>
							<select name=to_experience_time><option>FN</option><option selected>AN</option></select>
						</td>
					</tr>
					<tr>
						<td>
							<input type=checkbox name=to_experience_checkbox id=to_experience_checkbox
							onclick="my_combo(this,\'to_experience_text\',\'to_experience_date\' )";				
						>Till Date (Current)
						</td>
					</tr>
				</table>
			</td>
			</tr>
			';
	echo '<tr>
	<td colspan=7><button type=submit name=action value=add_experience style="background-color:lightgreen;"  >Add Experience</button></td>';
	echo '</tr>';
}


function view_table_experience($link)
{
	//Designation 	Type 	Department 	Name of Institution 	From - To - Total

	$sql='select * from staff_movement where staff_id=\''.$_SESSION['login'].'\' order by `from_date`';
	if(!$result=mysqli_query($link,$sql)){return FALSE;}
	while($ra=mysqli_fetch_assoc($result))
	{		
	if(strlen($ra['to_date'])==0)
	{
		$to_date='<span style="background-color:lightpink;">till_date</span>';
		$diff=get_date_diff_as_ymd($ra['from_date'],date('Y-m-d'));
	}
	else
	{
		$to_date=$ra['to_date'];
		$diff=get_date_diff_as_ymd($ra['from_date'],$ra['to_date']);
	}
			
	$raw_html='<tr>
			<td>
			'.$ra['post'].'('.$ra['type'].')</td>
			<td  >'.$ra['department'].'</td>
			<td  >'.$ra['institute'].'</td>
			<td>'.$ra['from_date'].'</td>
			<td>'.$to_date.'</td>
			<td>'.$diff.'</td>
			';
			echo $raw_html;
		}
}


function view_table_mci($link)
{
	//Designation 	Type 	Department 	Name of Institution 	From - To - Total

	$sql='select * from mci where staff_id=\''.$_SESSION['login'].'\' order by `date`';
	if(!$result=mysqli_query($link,$sql)){return FALSE;}
	echo '<table class=border style="background-color:lightblue;">';
	echo '<tr><th>Del</th><th>MCI appearance dates</th></tr>';
	while($ra=mysqli_fetch_assoc($result))
	{	
		echo '<tr>
				<td><button type=submit name=delete_mci value=\''.mysql_to_india_date($ra['date']).'\'>X</button></td>
				<td>'.mysql_to_india_date($ra['date']).'</td>
			</tr>';
	}
	echo '</table>';
}


function print_experience_delcaration($ar)
{
	echo 	'<table class=border>';
	echo '<th>Designation</th><th>Department</th><th>Institute</th><th>From</th><th>To</th><th width=17%>Total</th>';
	foreach($ar as $v)
	{
		
		if(strlen($v['to_date'])==0)
		{
			$to_date='till date';
			$diff=get_date_diff_as_ymd($v['from_date'],date('Y-m-d'));
		}
		else
		{
			$to_date=$v['to_date'];
			$diff=get_date_diff_as_ymd($v['from_date'],$v['to_date']);
		}
	
		echo 	'<tr>';
		echo '<td>'.$v['post'].'</td>';
		echo '<td>'.$v['department'].'</td>';
		echo '<td>'.$v['institute'].'</td>';
		echo '<td>'.$v['from_date'].'</td>';
		echo '<td>'.$to_date.'</td>';
		echo '<td>'.$diff.'</td>';
		echo 	'</tr>';
		
	}
	echo '</table>';
}

?>


