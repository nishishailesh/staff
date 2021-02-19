<?php
session_start();

require_once('../tcpdf/tcpdf.php');
#require_once('Numbers/Words.php');
require_once '../common/common.php';

class ACCOUNT1 extends TCPDF {

	public function Header() 
	{
	}
	
	public function Footer() 
	{
	    $this->SetY(-15);
		$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
	}	
}	
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


$met=false;
$met=get_raw($link,'select * from met where id=\''.$_SESSION['login'].'\' ');

$degree_attachment_str='';
$reg_attachment_str='';


$last_mci_date=get_raw($link,'select max(`date`) md from mci where staff_id=\''.$_SESSION['login'].'\'');

/*$last_mci_sql='select * from mci where 
			staff_id=\''.$_SESSION['login'].'\'
			and `date`=(select max(`date`) from mci where staff_id=\''.$_SESSION['login'].'\')';
$last_mci_date=get_raw($link,$last_mci_sql);*/

$publication=get_raw($link,'select * from publication where staff_id=\''.$_SESSION['login'].'\'');


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

 ob_start();
    echo'<!DOCTYPE html>
<html lang="en">
<head>
</head>
<body>
        <div style="display:table;width:20cm;margin:1cm 1cm 1cm 1cm;">

<h2 align="center"><u>FACULTY DECLARATION FORM(For AY 2021-22)</b></h2><b>';
echo'</b>
<br>
<h4>Name of the College :';
echo $current_appointment['institute'];
echo '</h4><br>
<table border="1" width="100%" cellpadding="4">
        <tr>
                <td align="center" style="width:25%;">Assessment Date</td>
                <td align="center" style="width:25%;">__/__/____</td>
                <td align="center" style="width:50%;"><b>Remarks & Signature of Assessor</b></td>
        </tr>
        <tr>
                <td align="center">Accepted</td>
                <td align="center">YES/NO</td>
                <td align="center" rowspan="3"></td>
        </tr>
        <tr>
                <td align="center">Assessor\'s Name</td>
                <td></td>
        </tr>

</table>
<p style="font-size:0.7em">Note: It is the responsibility of the Dean to ensure that the submitted Declaration form is ONLY of a Faculty memberwhois working 
as a full-time employee has notappeared for assessment in anyother college for any 
discipline and in any capacity during the stated academic year.</p>

<table cellpadding="2px">
  <tr>
     <td width="85%">1.Name of Faculty:';
	echo $staff_detail['fullname']; 
        echo'<br>2.Age & Date of Birth :';
        $diff=get_date_diff_as_ymd($staff_detail['dob'],strftime("%Y-%m-%d"));
        echo $diff.'___';
        echo mysql_to_india_date($staff_detail['dob']); 
                echo'<br>3.';
		echo '<table ><tr><td>';
		echo 'Photo ID submitted: '.$photo['proof_type'];
		echo '</td></tr>';
		echo '<tr><td>';
		echo 'Number:'.$photo['proof_number'];
		echo ' </td></tr><tr><td>Issuing Authority :'.$photo['proof_issued_by'];
		echo '</td></tr>';
		echo '</table>
    </td>
    <td width="15%" border="1">
<p style="font-size:0.7em">Attach a recent passport size color photograph with signature and seal of the Principal/Dean across it</p>
</td>
  </tr>
</table>

            <p style="font-size:0.8em">Note:<br>
(i)   Declaration forms without a valid government issued photo ID will NOT be accepted.<br>
(ii)  It is mandatory to produce Original certificate at the time of verification.<br>
(iii) Only certificates/documents/certified translation in the English language will be accepted.<br></p>

<br><br>4.Present Designation:';
echo $current_appointment['post'];

echo '
<br><ul>
<li>a. Appointment order: a Certified copy of order at this institute attached:   YES/NO</li>
<li>b. Department:- ';echo $current_appointment['department'].'</li>';
echo '<li>c. College/Institute:- ';echo $current_appointment['institute'];echo '</li>';
echo '<li>d. City/District:-';
                $ex=explode(' ', $current_appointment['institute']);
                $city=$ex[count($ex)-1];
                echo $city.'</li>';
echo'<li>e. Appointment:<ul>';
 if     ($current_appointment['type']=='Adhoc'){echo '<li>(i)Adhoc';}
 else if($current_appointment['type']=='Other'){echo '<li>(i)Other';}
 else if($current_appointment['type']=='Out Source'){echo '<li>(i)Out Source';}
 else {echo '<li>(i) Regular';}

        echo '<li>(ii) Full time/Part time <li>(iii) With / Without private practice</ul>';

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
		
		echo '<li>f. Date of appearance in Last MCI/NMC assessment:'.$last_mci_date['md'].' in '.$last_mci_details['institute'];
                echo '<ul><li>i.   UG / PG / Any other:'. $last_mci_date['md'].' in '.$last_mci_details['institute'];
		echo '<li>ii.  Name of College:'. $last_mci_details['institute'];
		echo '<li>iii. Whether appeared and accepted at the same College: '.$last_mci_in_current_institute;
		echo '<li>Iv.  Whether appeared and accepted for the same Designation '.$last_mci_as_current_post;
		echo '<li>v.   Whether retired from Government Medical College - Yes/No';
		echo '<li>iv   If yes, designation at the time of retirement - ____________</ul>';

echo '</ul>';
echo '<br>.<br>.<br>.<br>.<br>
<table style="width:95%">
<tr>
	<td>______________________</td><td align=right>______________________________</td>
</tr>
<tr>
	<td>Signature of Faculty</td><td align=right>Signature and Seal of Dean</td>
</tr>
</table>

<p style="page-break-after:always;"></p>';

echo '<p>5. Complete Residential  Address of employee :</p>

<table >
	<tr>
		<td width="20%">';
			echo 'a. Present:</td><td width="80%">'.$staff_detail['present_residential_address'];
		echo'</td>
	</tr>
	<tr>';
		echo '<td>b. Permanent:</td><td>'.$staff_detail['permanent_residential_address'];
		echo'</td>
	</tr>
</table>

<p>6. Copy of Proof of Residence submitted and original verified:';echo $proof_of_residence_attached.'</p>';
echo '<p style="font-size:0.8em" >(Only copies of Passport/Aadhar card/Voter ID/Passport/Electricity bill/Landline 
Phone bill will be considered)</p>

<p>7.Contact details:</p>

<table>
	<tr><td>a. Office telephone with STD code:</td><td>';echo $staff_detail['office_phone'];echo'</td></tr>
	<tr><td>b. Residence telephone with STD code:</td><td>';echo $staff_detail['residencial_phone'];echo'</td></tr>';
	echo '<tr><td>c. Mobile Phone Number: </td><td>';echo $staff_detail['mobile'];echo'</td></tr>';
	echo '<tr><td>d. Email address: </td><td>';echo $staff_detail['email'];echo'</td></tr>
</table>';

echo '<p>8. Date of joining present institution:';
       echo mysql_to_india_date($current_appointment['from_date']).' as '. $current_appointment['post'];
   echo'</p>';
echo '<p>9. Joining report verified/attached:';echo $current_joining_attached;echo'</p>';

echo '10. Have you atteded the \'Basic Course Workshop\' for Training in MET';
if($met!==FALSE)
{
	echo ' :Yes';
}
else
{
	echo ' :No';
}
echo '<p>If Yes, give details (strike out whichever is not applicable):</p>';


if($met!==false)
{
	echo '<ul>
		<li>a. at MCI/NMC Regional MET Centre: Yes /No.
		<li>b. at your college under Regional Centre observership: Yes / No
		<li>
			<ul><li>i. Name of Observer: ';echo $met['observer'].'</ul>
	</ul>';
}

echo '<p>11. Educational Qualifications:</p>
 <br>
 <table border="1">
  <tr>
	<th align="center">Degree</th>
	<th align="center">Year</th>
	<th align="center">Name of the College & University</th>
	<th align="center">Registration Number with date of Registration</th>
	<th align="center">Name of State Medical Council</th>
  </tr>';

view_table_qualification($link);

echo'</table>

<br>
<p>a.MD/MS Subject:_________
<p>b.DM/MCh Subject:________
<p>c.PhD Subject:________


<p style="font-size:0.8em">Note: For PG & Post PG qualification, particulars of Registration of Additinal Qualification 
Certificates are to be furnished for them to be Accepted Strike out whichever Section is not applicable.</p>

<p>12. Copies of educational qualifications:</p>
<ul><li>a. Copy of Degree certificates  of MBBS and PG degree attached:'; 
	if(strlen($degree_attachment_str)>0){echo 'Yes';}else{echo 'No';} 
echo'<li>b. </b>Copy of Registration of MBBS and PG degree attached';
	if(strlen($reg_attachment_str)>0){echo 'Yes';}else{echo 'No';} 
echo'</ul>';

echo '<p>13. Details of the teaching experience till date:</p>
	<br>
	<table border="1">';
		$ar=get_experience_mci($link,$_SESSION['login']);
		print_experience_delcaration($ar);
	echo'</table>';
echo '<p>* Write NA (Not Applicable) for the designations not held</p>';


echo '<p>To be filled in by Personnal form Indian Defence Service ONLY:</p>';

echo '
	<table border="1" >
		<tr>
			<th width="20%" align="center" >Designation</th>
			<th width="20%" align="center">Institution*</th>
			<th width="20%" align="center" >From</th>
			<th width="20%" align="center" >To</th>
			<th width="20%" align="center" >Total</th>
		</tr>
		<tr>
			<td>Graded Specialist</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td>Classified Specialist</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>	
	     <tr>
			<td>Advisor</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
	</table>';
echo '<p>* Note: Documents in support of each posting to be furnished for verification</p>';

echo '<p>14.Have you been considered in UG/PG, MCI/NMC inspection at any other medical college in 
a teaching or Adminstrative Capacity during last 3 years? If yes, please give details:</p>';

view_table_mci($link);
//echo'
//Date of appearance:';
//echo mysql_to_india_date($last_mci_date['md']);
//echo'';
 $previous_institute_details=find_staff_movement_details_of_previous_institute($link,$current_appointment['institute']);
                  echo' <p>15.Details of Employement Before joining present institution.</p>';
                   echo '<li>a. Name of College/Institute:- ';echo $current_appointment['institute'];echo '</li>';        
			echo '<li>b. Designation:- ';echo $current_appointment['post'];echo '</li>';
		echo'<li>c.Reason for being Relieved: Tendered resignation / Retired / Transferred / Terminated';'</li>';
		echo'<li>d.Relieving order issued by previous institution verified and attached: YES/NO';echo'</li>';
//echo $current_appointment['post'];

			echo'';
echo' <p>16. PAN Card No. is :';
                                          echo $pan['pan'];
    echo'
	</p>
	<p>17.AADHAR Card No. is :';	
                                          echo $staff_detail['id'];
	echo'
	
<p>
18.I have drawn total emoluments from this college in the current financial year as under:-
<br><br>
<table border="1" width="100%">	
	<tr align="center"><th width="20%" align="center"><b>Month</b></th><th width="40%" align="center"><b>Amount Received</b></th><th width="40%" align="center"><b>TDS</b></th></tr>
	<tr align="center"><td>Apr 2020</td><td></td><td></td></tr>	
	<tr align="center"><td>May 2020</td><td></td><td></td></tr>	
	<tr align="center"><td>Jun 2020</td><td></td><td></td></tr>	
	<tr align="center"><td>Jul 2020</td><td></td><td></td></tr>	
	<tr align="center"><td>Aug 2020</td><td></td><td></td></tr>	
	<tr align="center"><td>Sep 2020</td><td></td><td></td></tr>	
	<tr align="center"><td>Oct 2020</td><td></td><td></td></tr>	
	<tr align="center"><td>Nov 2020</td><td></td><td></td></tr>	
	<tr align="center"><td>Dec 2020</td><td></td><td></td></tr>	
	<tr align="center"><td>Jan 2021</td><td></td><td></td></tr>	
	<tr align="center"><td>Feb 2021</td><td></td><td></td></tr>	
	<tr align="center"><td>Mar 2021</td><td></td><td></td></tr>	
	</table>
<br><br>
<table>
  <tr>
	  <td>
		 [Copy of my PAN and Form 16 (Downloaded from TRACES) for fy 2019-20(Assessment year 2020-21) are attached]
	  </td>
  </tr>
</table>
<br>';
echo '
<p>19. Number of  Research publications in Index Journals:</p>
<ul>
	<li>a.International Journals:'.$publication['international'].'
	<li>b.National Journals:'.$publication['national'].'
	<li>c.State/Institutional Journals:'.$publication['state'].'
</ul>
<p>20. Details of other Publications:</p>
<ul>
<li>a.Number of Books Published:
<li>b.Number of Chapter in Books:
</ul>';

echo '<H3 align="center"><u>DECLARATION</u></H3>
<table>
<tr><td valign="top" style="width:5%;">1.</td><td style="width:95%;"> I, Dr. ';
echo $staff_detail['fullname']; echo'.am working in the Capacity of ';
echo $current_appointment['post'];echo' in the Department of ';
echo $current_appointment['department']; echo' at ';
echo $current_appointment['institute']; echo',';
	$ex=explode(' ', $current_appointment['institute']);
	$city=$ex[count($ex)-1]; 
	echo $city ;
echo' and do hereby give an undertaking that I am Employed as a full time teaching Faculty, ';
echo $current_appointment['department'];
echo', working from  9 A.M. to 5 P.M. daily at this Institute.</td></tr>
<tr><td></td></tr>
<tr><td  valign="top">2.</td><td>I have not made my self available to any other Medical College / Institution in any Disipline, in the capacity of teaching faculty, administrator or advisor in the current academic year for the purpose of NMC/MCI assessments.</td></tr>
<tr><td></td></tr>
<tr><td  valign="top">3</td>
<td>
	<table>
	<tr><td>
		I do hearby Solemnly Declare that(Tic the applicable  clause):
              <br>a. I state that I am not Doing any private practice or working in any other Hospital during College hours.
              <br>b. I Practice at_________________________Nursing home, clinic, hospital in the city of_______________in ___________state and my hours of private practice are from______:_____AM/PM to ______:_______AM/PM.
	</td></tr>
	</table>
</td></tr>
<tr><td></td></tr> 
<tr><td valign="top">4.</td><td>I am not working in any other medical college/dental college in or outside the state in any capacity Regular/Contractual/Adhoc or Fulltime/Part time/Honorary.</td></tr>
<tr><td></td></tr>
<tr><td valign="top">5.</td><td>I declare thet I have provided all the details with regard to my work and teaching Experiance and no information has been concealed by me.</td></tr>
<tr><td></td></tr>
<tr><td valign="top">6.</td><td>I do Solemnly Declare that all the details/information furnished by me in this declaration form is absolutely true and correct, and all the documents/certificates that were made available by me for varification or have been dubmited by me along with this declaration form are authentic. In the event of any information furnished or statement made in this declaration subciquantly turning out to be false/incorrect or any document/s or certificate/s is/are found to be out of order, or it comes to the lite that there has been suppression of any material information, i understand and accept that it shall be considered as gross missconduct there by rendering me liable to disiplinery and/or legal proceedings. It might also lead to suspension/cacellation of my Registration with the state medical council and/or removel of my name from Indian Medical Register.</td></tr>
</table>

<br><br><br>
<table style="width:100%">
	<tr><td  style="width:20%"><br>Date:</td><td  style="width:20%">________________</td><td align="right" style="width:60%">SIGNATURE OF THE FACULTY</td></tr>
	<tr><td ><br><br>Place:</td><td><br><br>________________</td><td align="right"><br><br>________________</td></tr>
</table>
<br>
<H3 align="center"><u>ENDORSEMENT</u></H3>
<table>
<tr><td align="top" style="width:5%;">1.</td><td style="width:95%;">
This endorsement is the certification that the undersigned has satisfied himself /herself about the correctness, 
authenticity and veracity of the content of this declaration form in 
it\'s entirety and endorses the above declaration as true and correct.
I have personally verified all the certificates / documents submitted by the 
teaching faculty  with the original certificates/documents that were submitted by her/him to the Institute 
and Conformed the same with the concerned Institute and have found them to be correct and authentic.</td></tr>
<tr><td></td></tr>
<tr><td >2.</td><td >I also confirm that Dr.';

echo $staff_detail['fullname'];

echo'
is not practicing or carrying out any other activity during college working hours i.e. from 9.00 AM to 5 PM , since he/she has joined the Institute.</td></tr>
<tr><td></td></tr>
<tr><td>3.</td><td>In the event of this declaration turning out to be either incorrect or any part of this declaration subsequently turning out to be incorrect or false it is understood and accepted that the undersigned shall also be equally responsible besides the declarant himself/herself for any such misdeclaration or misstatement.                             </td></tr>
</table>
<br><br>
<table style="width:100%">
	<tr>
		<td  style="width:10%"  align="left">Date:</td>
		<td  style="width:20%">________________</td>
		<td   style="width:30%" align="center">Signed by the HOD</td>
		<td  style="width:40%" align="right">Countersigned with stamp by the Director/Dean/Principal</td>
	</tr>
	<tr>
		<td align="left"><br><br>Place:</td>
		<td><br><br>________________</td>
		<td align="center"><br><br>________________</td>
		<td align="right"><br><br>________________</td>
	</tr>
</table>
<p style="page-break-after:always;"></p>
<H3 align="center">REMARKS</H3>
<table border="1" align="center">
<tr><td width="15%"><b>S.No</b></td><td width="70%"><b>Documents</b></td><td width="15%"><b>Submitted</b></td></tr>
<tr><td>1</td><td>Recent Passport size photo of the Employee Signed by Dean / Principal of the college.</td><td>Yes / No</td></tr>
<tr><td>2</td><td>Photo ID proof ( Govt. Authority issued): Passport /PAN Card/Voter ID/Aadhar Card</td><td>Yes / No</td></tr>
<tr><td>3</td><td>Certified copy of appointment order of the present Institute.</td><td>Yes / No</td></tr>
<tr><td>4</td><td>Proof of Residence: passport /Voter Card / Electricity Bill / Telephone Bill / Aadhar Card / Dean`s allotement letter attached as a proof of present residence. </td><td>Yes / No</td></tr>
<tr><td>5</td><td>Joining report at the present institute.</td><td>Yes / No</td></tr>
<tr><td>6</td><td>Copies of MBBS, PG, PhD Degrees(as applicable).</td><td>Yes / No</td></tr>
<tr><td>7</td><td>Copies of MBBS, PG, PhD degree Registration certificates(as applicable).</td><td>Yes / No</td></tr>
<tr><td>8</td><td>Copy of experience certificate of  all teaching appointments before joining present post.</td><td>Yes / No</td></tr>
<tr><td>9</td><td>Relieving order from the previous institution/posting.</td><td>Yes / No</td></tr>
<tr><td>10</td><td>Copy of PAN Card</td><td>Yes / No</td></tr>
<tr><td>11</td><td>Form 16A (Downloaded from TRACES) for FY 2019-20(Assessment year 2020-21). </td><td>Yes / No</td></tr>
<tr><td>12</td><td>Letter head (in case of teachers who are practicing)</td><td>Yes / No</td></tr>
<tr><td>13</td><td>Copy of letter from affiliating university recognizing as UG teacher.</td><td>Yes / No</td></tr>
<tr><td>14</td><td>Copy of letter from affiliating university recognizing as PG teacher(for PG assessment)</td><td>Yes / No</td></tr>
<tr><td>15</td><td>Copy of AADHAR card</td><td>Yes / No</td></tr>
</table>
<br><br><br>
<table  style="width:100%;">
<tr><td align=right</td>><br><u>Signed by the Teacher:</u></td> <td align=left</td>><br><u>Signed by the HOD:</u></td></tr>
<tr><td align=right</td>><u>Date:</u></td><td align=left</td>><u>Date:</u></td></tr>
</table>
<br><br><br>
<table>
<tr><th><br><br><u></u></th></tr>
<tr><td><b><u></u></b></td></tr>
</table>
<br><br><br>
<table>
<tr><td align=right</td>><br><u>Signature of Head of Institute:</u></td> <td align=left</td>><br><u>Signed & Verify(Assessor) :</u></td></tr>
<tr><td align=right</td>><u>Date:</u></td><td align=left</td>><u>Date:</u></td></tr>


</table>
<H3 align="center"><u>NOTE:</u></H3>
<br><b>1.</b> The Declaration Form will not be accepted and the Faculty member will not be consider as a teachingfaculty in case  any of documents listed are not enclosed /attached with the Declaration Form.<br>
<br><b>2.</b> The Faculty member will not be consider as a teaching faculty if the original Appointment letter,Reliving order, Experience certificates, Government Photo ID, Degrees, Registration Certificates, PAN Card, Aadhar Card, State Medical Council ID (if issued) are not produced for verification at the time of assessment.<br>
<br><b>3.</b> Faculty member must submit revised declaration form in this format only. Submissions in the old format will be rejected and Faculty members will not be considered as Teaching Faculty.<br>
';
?>
<?php

function add_qualification_raw($link)
{

	echo '<tr style="background-color:lightblue;">
			<td></td><td>';
	mk_select_from_table($link,'qualification_degree','','');
	
	$sql_qs='select department from department';
	mk_select_from_sql($link,$sql_qs,'department','qualification_subject','','');
	
	echo '<input  class="upload" type="file" name="file_qualification_degree" ><br>^Upload qualification^';
	
	echo '	</td>
			<td><input  type="text" name="college_qualification"></td>
			<td><input  type="text" name="university_qualification"></td>
			<td>';
			read_year('year_qualification',date("Y")-100,date("Y"));
			echo '</td>
			<td>
				<table><tr><td>
				<input placeholder="Reg. No" type="text" name="reg_no_qualification" id="reg_no_qualification">
				</td></tr><tr><td>
				<input placeholder="Reg. Dt" readonly name=reg_date_qualification id=reg_date_qualification class="datepicker" >
				</td></tr><tr><td>';

			echo '<input  type=file class=upload name=file_qualification_reg ><br>^Upload Reg^';
			echo '</div>';				
					
			echo '</td></tr></table>
			</td>
			<td><input type="text" name="council_qualification" id="council_qualification"></td>
			</tr>
			';
	echo '<tr>
			<td colspan="7"><button type="submit" name="action" value="add_qualification" style="background-color:lightgreen;">Add Qualification</button></td>
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
	$raw_html='<tr align="center">
			<td>
			'.$ra['qualification'].'('.$ra['subject'].')
			</td>
			<td>'.$ra['year'].'</td>
			<td>'.$ra['college'].', '.$ra['university'].'</td>
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
	

	echo '	<tr><td></td><td>';
			mk_select_from_sql($link,'select designation_type from designation_type',
			'designation_type','experience_designation','','');

	echo '	</td><td>';
			mk_select_from_sql($link,'select appointment_type from appointment_type',
			'appointment_type','experience_type','','');
	echo '	</td><td>';
			mk_select_from_sql($link,'select department from department',
			'department','experience_department','','');
	echo '	</td>
			<td >';
			mk_select_from_sql_with_separate_id($link,'select institute from institute',
						'institute','experience_institute_select','experience_institute_select','','');
						
	echo 	'<table class="noborder" ><tr><td>
								<input size="30" placeholder="Write Institute Name Here" style="display:block;" 
								type="text" name="experience_institute_text" id="experience_institute_text">	
									</td></tr>
									<tr><td>Other Institutes:
								<input type=checkbox
								id="experience_institute_checkbox"  name="experience_institute_checkbox" title="Tick to enter name of other medical colleges"
								onclick="my_combo(this,\'experience_institute_text\',\'experience_institute_select\' )" >
							</td></tr>
							</table>
			</td><td>
				<table>
					<tr>
						<td>From:</td>
						<td><input readonly class="datepicker" name="from_experience" id="from_experience"></td>
						<td><select name="from_experience_time"><option selected>FN</option><option>AN</option></select></td>
					</tr>
					<tr>
						<td rowspan="2">To:</td>
						<td><div id="to_experience_date">
							<input  readonly class="datepicker" name="to_experience_pk" id="to_experience_date_pk">
							</div>
							<input readonly style="display:block;" id="to_experience_text" 
							name="to_experience_text" type="text" value="till_date" >
						</td>
						<td>
							<select name="to_experience_time"><option>FN</option><option selected>AN</option></select>
						</td>
					</tr>
					<tr>
						<td>
							<input type="checkbox" name="to_experience_checkbox" id="to_experience_checkbox"
							onclick="my_combo(this,\'to_experience_text\',\'to_experience_date\' )";				
						>Till Date (Current)
						</td>
					</tr>
				</table>
			</td>
			</tr>
			';
	echo '<tr>
	<td colspan="7"><button type="submit" name="action" value="add_experience" style="background-color:lightgreen;"  >Add Experience</button></td>';
	echo '</tr>';
}


function view_table_mci($link)
{
	$sql='select * from mci where staff_id=\''.$_SESSION['login'].'\' order by `date`';
	if(!$result=mysqli_query($link,$sql)){return FALSE;}
	echo '<table border="1">';
	echo '<tr><th>Designation</th><th>Subject</th><th>College</th><th>Dates</th></tr>';
	while($ra=mysqli_fetch_assoc($result))
	{	
		echo '<tr>
				<td>'.$ra['designation'].'</td>
				<td>'.$ra['subject'].'</td>
				<td>'.$ra['college'].'</td>
				<td>'.$ra['date'].'</td>
			</tr>';
	}
	echo '</table>';
}

function print_experience_delcaration($ar)
{

	echo'
	<table border="1"><tr>
	<td align="center">Designation</td>
	<td align="center">Department</td>
	<td align="center">Institute</td>
	<td align="center">From</td>
	<td align="center">To</td>
	<td align="center">Total</td>
	</tr>';
    foreach($ar as $v)
	{
		
		if(strlen($v['to_date'])==0)
		{
			$to_date='till date';
			$diff=get_date_diff_as_ymd($v['from_date'],date('Y-m-d'));
		}
		else
		{
			$to_date=mysql_to_india_date($v['to_date']);
			$diff=get_date_diff_as_ymd($v['from_date'],$v['to_date']);
		}
	
		echo '<tr align="center">';
		echo '<td>'.$v['post'].'</td>';
		echo '<td>'.$v['department'].'</td>';
		echo '<td>'.$v['institute'].'</td>';
		echo '<td>'.mysql_to_india_date($v['from_date']).'</td>';
		echo '<td>'.$to_date.'</td>';
		echo '<td>'.$diff.'</td>';
		echo '</tr>';
		
	}
	echo '</table>';
}

	$myStr = ob_get_contents();
	//echo $mystr;
	//exit(0);
	ob_end_clean();
   
//echo $myStr;
//exit(0);
     $pdf = new ACCOUNT1('P', 'mm', 'A4', true, 'UTF-8', false);
             $pdf->SetFont('dejavusans', '', 9);
             $pdf->SetMargins(30, 20, 30);
             $pdf->AddPage();
             $pdf->writeHTML($myStr, true, false, true, false, '');
	     ob_end_clean();
             $pdf->Output($_SESSION['login'].'_declaration.pdf', 'I');
?>

