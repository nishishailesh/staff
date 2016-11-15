<?php
session_start();
require_once '../common/common.php';

$link=connect();

$staff_detail=get_raw($link,'select * from staff where id=\''.$_SESSION['login'].'\'');
$photo=get_raw($link,'select * from photo where id=\''.$_SESSION['login'].'\'');

?>

<!DOCTYPE html>
<html>
<head>

<script type="text/javascript" src="../date/datepicker.js"></script>
<script src="../js/jquery-3.1.1.min.js"></script>
<link rel="stylesheet" type="text/css" href="../date/datepicker.css" /> 

<style>
	
table{
   border-collapse: collapse;
}

.border td , .border th{
    border: 1px solid black;
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
        fields[i].value = ''
    }
}

function hide(one) {
				document.getElementById(one).style.display = "none";
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
		document.getElementById(yes_target).disabled=false;
		
		document.getElementById(no_target).style.display="none";
		document.getElementById(no_target).disabled=true;
	}
	else
	{
		document.getElementById(no_target).style.display="block";
		document.getElementById(no_target).disabled=false;
		
		document.getElementById(yes_target).style.display="none";
		document.getElementById(yes_target).disabled=true;
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

function add_qualification_raw() { 
	qr=qr+1;
	raw_html='\
			<td  style=\"width:5%\">\
				<input  placeholder=\"Degree(Subject)\"  style=\"width:95%;height:100%;\" type=text name=qualification_'+ qr +' id=qualification_' + qr +'>\
				<input style="width:95%;height:100%;" class=upload type=file name=file_qualification'+ qr +' id=file_qualification_' + qr +'>\
			</td>\
			<td  style=\"width:20%\"><input style=\"width:95%;height:100%;\"  type=text name=college_qualification_'+ qr +' id=college_qualification_'+ qr +'></td>\
			<td  style=\"width:20%\"><input style=\"width:95%;height:100%;\"  type=text name=university_qualification_'+ qr +' id=university_qualification_'+ qr +'></td>\
			<td  style=\"width:10%\"><input style=\"width:95%;height:100%;\"  type=text name=year_qualification_'+ qr +' id=year_qualification_'+ qr +'></td>\
			<td  style=\"width:25%\">\
				<input style=\"width:35%;height:100%;\" placeholder=\"Reg. No\" type=text name=reg_no_qualification_'+ qr +' id=reg_no_qualification_'+ qr +'>\
				<input style=\"width:45%;height:100%;\" placeholder=\"Reg. Dt\" readonly name=reg_date_qualification_'+ qr +' id=reg_date_qualification_'+ qr +' class=\"datepicker\" size=\"10\">\
				<input style="width:95%;height:100%;" type=file class=upload name=file_reg_qualification'+ qr +' id=file_reg_qualification_' + qr +'>\
			</td>\
			<td  style=\"width:20%\"><input style=\"width:95%;height:100%;\" type=text name=council_qualification_'+ qr +' id=council_qualification_'+ qr +'></td>\
			';

	 AddBefore("qualification_add",raw_html);
	 //Following just clear all values So, AddBefore is needed SMP
	//document.getElementById("qualification_table").innerHTML =document.getElementById("qualification_table").innerHTML + raw_html; 
	load_datepicker_dynamically("reg_date_qualification_"+qr);
}


function add_experience_raw() { 
	er=er+1;
	raw_html='\
			<td  style=\"width:15%\">\
			<select style=\"width:95%;height:100%;\" name=designation_experience_'+ er +' id=designation_experience_' + er +'>\
				<option>Junior Resident</option>\
				<option>Senior Resident</option>\
				<option>Tutor</option>\
				<option>Assistant Professor</option>\
				<option>Associate Professor</option>\
				<option>Professor</option>\
				<option>Graded Specialist (Army)</option>\
				<option>Classified Specialist(Army)</option>\
				<option>Adviser(Army)</option>\
			</select>\
			</td>\
			<td  style=\"width:15%\">\
			<select style=\"width:95%;height:100%;\" name=type_experience_'+ er +' id=type_experience_' + er +'>\
				<option>Adhoc appointment</option>\
				<option>GPSC appointment</option>\
				<option>Promotion</option>\
				<option>Contactual appointment</option>\
				<option>Transfer</option>\
				<option>Non-government</option>\
				<option>Army</option>\
			</select>\
			</td>\
			<td  style=\"width:15%\">\
			<select style=\"width:95%;height:100%;\"  name=department_experience_'+ er +' id=department_experience_'+ er +'>\
				<option>Anatomy</option>\
				<option>Physiology</option>\
				<option>Biochemistry</option>\
				<option>Pathology</option>\
				<option>Pharmacology</option>\
				<option>Microbiology</option>\
				<option>Forensic Medicine</option>\
				<option>Community Medicine</option>\
				<option>Medicine</option>\
				<option>General Surgery</option>\
				<option>Paediatrics</option>\
				<option>Obstetrics and Gynacology</option>\
				<option>Opthalmology</option>\
				<option>Orthopaedics</option>\
				<option>Psychiatry</option>\
				<option>ENT</option>\
				<option>Dentistry</option>\
				<option>Respiratory Medicine</option>\
				<option>Immunohematology and Blood Transfusion</option>\
				<option>Radiology</option>\
				<option>Dematology</option>\
				<option>Plastic Surgery</option>\
				<option>Emergency Medicine</option>\
				<option>Anesthesiology</option>\
		</select>\
			</td>\
			<td  style=\"width:15%\">\
			<table class=\"noborder\" ><tr><td>\
					<input placeholder="Other Institutes" style=\"width:90%;height:100%;display:none;disabled=true;\"  type=text name=institution_experience_'+ er +' id=text_institution_experience_'+ er +'>\
					<select style=\"width:90%;height:100%;\"  type=text name=institution_experience_'+ er +' id=select_institution_experience_'+ er +'>\
						<option>Government Medical College Surat</option>\
						<option>Medical College Vadodara</option>\
						<option>BJ Medical College Ahmedabad</option>\
						<option>Medical College Bhavnagar</option>\
						<option>PDU Medical College Rajkot</option>\
						<option>MP Shah Medical Collge Jamnagar</option>\
						<option>GMERS Medical college Valsad</option>\
						<option>GMERS Medical College Gotri</option>\
						<option>GMERS Medical College Sola</option>\
						<option>GMERS Medical College Gandhinagar</option>\
						<option>GMERS Medical College Dharpur-Patan</option>\
						<option>GMERS Medical College Vadnagar</option>\
						<option>GMERS Medical College Himmatnagar</option>\
						<option>GMERS Medical College Junagadh</option>\
					</select>\
					</td><td>\
					<input \
						id=checkbox_institution_experience style="width:10%;display:inline;" institution_experience title=\"Tick to enter name of other medical colleges\"\
						onclick=\"my_combo(this,\'text_institution_experience_'+ er + '\',\'select_institution_experience_'+ er +'\')\" type=checkbox>\
			</td></tr></table>\
			</td>\
			<td  style=\"width:15%\"><input style=\"width:75%;height:100%;\"  readonly class=datepicker name=from_experience_'+ er +' id=from_experience_'+ er +'></td>\
			<td  style=\"width:15%\"><input style=\"width:75%;height:100%;\"  readonly class=datepicker name=to_experience_'+ er +' id=to_experience_'+ er +'></td>\
			</td>\
			<td  style=\"width:10%\">\
			<input title=\"click to refresh\" onclick=get_date_diff(from_experience_'+er+',to_experience_'+er+',total_experience_'+ er+') style=\"width:95%;height:100%;\" type=text name=total_experience_'+ er +' id=total_experience_'+ er +'></td>\
			';

	 AddBefore("experience_add",raw_html);
	 //Following just clear all values So, AddBefore is needed SMP
	//document.getElementById("experience_table").innerHTML =document.getElementById("experience_table").innerHTML + raw_html; 
	load_datepicker_dynamically("from_experience_"+er);
	load_datepicker_dynamically("to_experience_"+er);
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




<!-- A4=210x297 so width=840 height=1200 -->

<body>



<div style="background-color:lightgreen;width:210mm;margin-left:15mm;margin-right:10mm;">
<?php menu();?>
</div>
<!-- menu() have its own <form>. Never enclose it in another form -->
<form method=post action=save.php enctype='multipart/form-data' target=_blank>
<input type=hidden name=id value=<?php echo '\''.$staff_detail['id'].'\''; ?>>
<p><input style="background-color:lightgreen;position:fixed;" type=submit value=save title="save frequently to prevent repeat attempts"></p>
<p style="position:fixed; top:60px;background-color:yellow;" >Click<br> yellow<br> button<br> to get <br>help</p>
<p style="position:fixed; top:160px;background-color:lightpink;" >Red<br> field<br> need <br> pdf/jpg <br>upload</p>



<div style="background-color:lightblue;width:210mm;margin-left:15mm;margin-right:10mm;">
<p><b>NAME OF THE COLLEGE: 
	<input type=text placeholder="click to autofill" readonly size=30 onclick="copyfrom(this,'present_college_name')" name=declaration_college id=declaration_college>
	<input type=text placeholder="click to autofill" readonly size=15 onclick="copyfrom(this,'present_city')" name=declaration_city id=declaration_city>
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

<h2><p align=center><b><u>DECLARATION FORM : 2017 - 2018 - FACULTY</u></b></p></h2>




<table class="noborder">
<tr>
<td class=noborder>
	
<p>1.(a)Name<input type=text placeholder="Lastname Firstname Middlename" name=name id=name size=40 value=

<?php echo '\''.$staff_detail['fullname'].'\''; ?>

></p>

<p>1.(b) Date of Birth 

<input readonly	id=dob class="datepicker" size="10" name=dob value=
<?php echo '\''.mysql_to_india_date($staff_detail['dob']).'\''; ?>
>

&amp; Age<input readonly type=date name=age value=

<?php 
$diff=date_diff_to_year_month_days($staff_detail['dob'],strftime("%Y-%m-%d"));
echo '\''.$diff.'\'';
?>

>

</p>

<p><button style="background-color:yellow;" type=button onclick="alert('(1) Upload pdf/jpg copy of photo ID proof. (2) Upload pdf/jpg of passport size photo')"><b>1.(c)</b></button> Submit Photo ID proof issued by Govt. Authorities :</p>

<p>Photo ID submitted :

	<?php
	mk_select_from_sql($link,'select * from photo_id_proof_type','photo_id_proof_type','photo_id','',$photo['proof_type']);
	?>

</select><input name=photo_id type=file class=upload>
</p>

<p>
	
Number
	<input type=text name=photo_id_number value=
	<?php echo '\''.$photo['proof_number'].'\'' ?>
	>

Issued by 
	<input type=text name=photo_id_issued_by value=
	<?php echo '\''.$photo['proof_issued_by'].'\'' ?>
	>
	
</p>

</td>
<td style="position:absolute;width: 3.52cm;height: 4.15cm;border: 1px solid black;">
RECENT PHOTOGRAPH TO BE COUNTER SIGNED BY  THE DEAN/ PRINCIPAL
<input name=photo type=file class=upload>
</span>
</tr>
</table>

<p><b>Note:1) Without Photo ID, Declaration form will be rejected and will notbe considered as teaching   faculty. 2) Original Certificates are mandatory for verification. All Certificates/Documents/Certified Translations, must be in English</b></p>
<p>1.(d)i.Present Designation:
<select name=present_designation id=present_designation>
	<option>Tutor</option>
	<option>Assistant Professor</option>
	<option>Associate Professor</option>
	<option>Professor</option>
	<option>Dean</option>
	<option>Medical Superintendent</option>
</select></p>

<p><table class="noborder"><tr><td>
<button style="background-color:yellow;" type=button onclick="alert('Upload pdf/jpg copy of present institute appointment order')"><b>1.(d)(i)a</b></button> Certified copies of present appointment order at present institute attached.</td><td><input type=file class=upload name=present_appointment_order></td></tr></table></p>

<p>1.(d)ii. Department: 

	<?php
	mk_select_from_sql($link,'select department from department','department','present_department','',$staff_detail['department']);
	?>

</p>
<p>1.(d)iii.College: <input size=40 type=text name=present_college_name id=present_college_name value="Government Medical College" ></p>
<p>1.(d)iv.City:<input type=text name=present_city id=present_city value=Surat ></p>
<p>1.(d)v.Nature of appointment: 
<select name=present_appointment_nature>
	<option>Regular</option>
	<option>Contractual</option>
</select></p>

<p>

<table>
	<tr>
		<td>1.(d)vi. Date of appearance in Last MCI - UG/PG/Any Other Assessment</td>
		<td><input readonly	id=last_mci_date class="datepicker" size="10" name=last_mci_date/></td>
	</tr>
</table>

</p>
<p>1.(d)vii Whether appeared in Last MCI - UG/PG Assessment in the same Institute - <input type=radio name=appeared_in_same_institute value=yes>Yes /<input type=radio name=appeared_in_same_institute value=no>No</p>
<p>1.(d)viii Whether appeared in Last MCI - UG/PG Assessment on same Designation - <input type=radio name=appeared_in_same_designation value=yes>Yes /<input type=radio name=appeared_in_same_designation value=no>No</p>
<p>
<table>
	<tr>
		<td>1.(e)Residential  Address of employee :</td>
		<td><textarea cols=40 name=residencial_address></textarea></td>
	</tr>
</table>
</p>

<p><table class=noborder style="width:190mm;"><tr><td>Signature of Faculty</td><td><input type=text readonly placeholder="required in physical copy"></td><td>Signature of Dean</td<td><input type=text  placeholder="required in physical copy" readonly></td></tr><table></p>
<p><b>1.(f) </b>Have you undergone Training in "Basic Course Workshop" at MCI Regional Centre in MET or in your college under Regional Centre observership
<input type=radio name=MET value=yes onclick="show('MET_details')">Yes /<input type=radio name=MET value=no onclick="hide_and_clear('MET_details')">No     </p>

	<table class=border id=MET_details style="display:none;">
		<tr>
			<th colspan=2  style="width:60%">Name of MCI Regional Centre where Training was done/If training was done in college, give the details of the observer from RC</th>
			<th colspan=2>Date and place of training</th>
		</tr>
		<tr>
			<td style="width:20%">MET Center:</td><td><textarea style="width:95%;height:100%;" name=met_center></textarea></td>
			<td style="width:20%">MET Place:</td><td><textarea  style="width:95%;height:100%;"  name=met_place></textarea></td>

		</tr><tr>
			<td  style="width:20%">MET Observer:</td><td><textarea style="width:95%;height:100%;"  name=met_observer></textarea></td>
			<td  style="width:20%">MET Date:</td><td><input readonly	id=met_date class="datepicker" size="10" name=met_date></td>

		</tr>
	</table>


<p>
	<table class="noborder">
		<tr>
			<td><button style="background-color:yellow;" type=button onclick="alert('Upload pdf/jpg copy of address proof')"><b>1.(g)</b></button>Copy of Passport /Voter Card / Electricity Bill /Landline Telephone Bill / Aadhar Card / attached as a proof of residence.</td>
			<td id=proof_of_residence_attached>No</td>
			<td><input type=file class=upload name=proof_of_residence></td>
		</tr>
	</table>
</p>
			

<p><b>1.(h)</b>	Contact Particulars:</p>
<p>
<table>
<tr><td>Tel (Office)(with STD code):</td><td><input type=text name=office_telephone value="0261-2244175"></td><tr>
<tr><td>Tel(Residence): (with STD code)</td><td><input type=text name=residence_telephone></td><tr>
<tr><td>E-mail address: </td><td><input type=text name=email></td><tr>
<tr><td>Mobile Number: </td><td><input type=text name=mobile></td><tr>
</table>
<table>
<tr><td><b>1.(i)</b>Date of joining present institution :</td><td><input readonly id=present_institute_joining_date class="datepicker" size="10" name=present_institute_joining_date> as 
</td><td>
	<select name=present_institute_joined_as>
		<option>Tutor</option>
		<option>Assistant Professor</option>
		<option>Associate Professor</option>
		<option>Professor</option>
		<option>Dean</option>
		<option>Medical Superintendent</option>
	</select>
</td><tr>
</table>

<table class="noborder">
	<tr>
		<td><button style="background-color:yellow;" type=button onclick="alert('Upload pdf/jpg copy of photo ID proof')"><b>1.(j)</b></button> Joining report at the present institute attached</td>
		<td id=joining_report_present_institute_attached>No</td>
		<td><input type=file class=upload name=joining_report_present_institute></td>
	</tr>
</table>
	
</p>

<p>
<table class="border"   id="qualification_table">
<tr><th colspan=6 style="text-align:left;"><button style="background-color:yellow;" type=button onclick="alert('(1) Click [[Add Qulification]] buttons. (2)Upload pdf/jpg of degree certificate and Council Registration')"><b>2.Qualifications :</b></button></th></tr>
<tr>
	<th style="width:5%">Qualification</th><th style="width:20%">College</th><th style="width:20%">	University	</th><th style="width:15%">Year</th><th style="width:20%">Registration No of UG & PG with date</th><th style="width:20%">	Name of the State Medical Council</th>
</tr>
<!-- Dynamicalyy added by javascript-->
<tr  id="qualification_add">
	<td><button type=button onclick="add_qualification_raw()">Add Qualification</button></td>
</tr>
<tr>
	<th  colspan=6 style="text-align:left;">Note: For PG-Post PG qualification additional Registration certificate particulars be furnished and subject be indicated within brackets
after scoring out whichever is not applicable.</td></tr>
<tr><td  colspan=6 ><b>2.(a)</b> Copy of Degree certificates  of MBBS and PG degree attached - <span id=degree_attached>No</span></td></tr>
<tr><td  colspan=6 ><b>2.(b)</b> Copy of Registration of MBBS and PG degree attached - <span id=reg_attached>No</span></td></tr>
</table>

</p>

<p>
<table class="border"   id="experience_table">
<tr><th colspan=7 style="text-align:left;"><button style="background-color:yellow;" type=button 
			onclick="showhide('experience_help')"> <b>3.(a)</b></button> Details of the teaching experience till date.</th></tr>
<tr><td colspan=7>
	<table class=border style="background-color:lightgray;display:none;" id=experience_help>
		<tr><td colspan=0 >(1) Click [[Add experience]]</td></tr>
		<tr><td colspan=0 >(2) Add all appointments/transfers/promotions saparately in new row.</td></tr>
		<tr><td colspan=0 >(3) Change of type of appointment also needs to be indicated saparately in new row.</td></tr>
		<tr><td colspan=0 >(4) Put CheckMark checkbox in "Name of Institution" column to write name of institute not present in dropdown list.</td></tr>
		<tr><td colspan=0 >(5) Ex-Army person can use this table to fill their details</td></tr>
		<tr><td colspan=0 >(6) Appintments with transfers shall be entered as respective appointment type</td></tr>
		</table>
</td></tr>
<tr>
	<th style="width:15%">Designation</th>
	<th style="width:15%">Type</th>
	<th style="width:15%">Department</th>
	<th style="width:15%">Name of Institution</th>
	<th style="width:15%">From DD/MM/YY</th>
	<th style="width:15%">To DD/MM/YY</th>
	<th style="width:10%">Total Experience in years & months</th>
</tr>

<!-- Added by javascript -->

<tr  id="experience_add">
	<td><button type=button onclick="add_experience_raw()">Add experience</button></td>
</tr>
<tr>
<tr><td  colspan=7><b>Note:-</b>Tutor working in Anesthesia and Radio-diagnosis must have 3 years teaching experience in the respective departments in a recognized/permitted
medical institute to be consider as senior resident.</td></tr>
</table>

	<table class=border style="width:100%;">
		<tr><td colspan=10><b>3(b).</b>To be filled in by Ex Army Personnel only: <b>(Not used for online filling of declaration)</b></td></tr>
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
			<td>1.</td>
			<td>Classified Specialist</td>
			<td></td>
			<td></td>
			<td></td>
		</tr>		<tr>
			<td>1.</td>
			<td>Adviser</td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
</table>
</p>
<p>
	<table class=border>
	<tr><td><b>Note:</b>Have you been considered in any UG/PG inspection at any other institution/medical college during last 3 years.  If yes, please give details.</td></tr>
	<tr><td><textarea  style="width:90%;" name="working_as_assessor_MCI"></textarea></td></tr>
	</table>
</p>


<p>

	
<table>
	<tr><td><button style="background-color:yellow;" type=button onclick="alert('Upload pdf/jpg copy of last relieving order ')"><b>4.(a)</b></button> Before joining present institution I was working at <input type=text> as 
		<select name=last_institute>
			<option>Junior Resident</option>
			<option>Senior Resident</option>			
			<option>Tutor</option>
			<option>Assistant Professor</option>
			<option>Associate Professor</option>
			<option>Professor</option>
			<option>Dean</option>
			<option>Medical Superintendent</option>
		</select>
	 and relieved on
<input readonly id=last_relieved_date class="datepicker" size="10" name=last_relieved_date> after resigning / retiring . Relieving order is enclosed from the previous institution)
<input class=upload name=last_relieving_order type=file>
</td></tr>


</p>

<p>
<tr><td><b>4.(b)</b> I am not working in any other medical college/dental college in the State or outside the State in any capacity Regular / Contractual.</td></tr>
</table>
</p>

<p>
<table>
	<tr><td colspan=0><b>5.</b>  Number of  Research publications in Index Journals:</td></tr>
	<tr><td><b>5.(a)</b> International Journals:</td><td><input type=text name=ijournal_number></td></tr>
	<tr><td><b>5.(b)</b> National Journals:</td><td><input type=text name=njournal_number></td></tr>
	<tr><td><b>5.(c)</b> State/Institutional Journals:</td><td><input type=text name=sjournal_number></td></tr>
</table>
</p>

<p>
<table>
	<tr><td><button type=button style="background-color:yellow;" onclick="alert('Upload scanned / camara-photo of PAN card')" ><b>6.(a)</b></button> My PAN Card No. is <input type=text name=PAN_number><input type=file class=upload name=PAN_card></td></tr>
	
	<tr><td><button type=button style="background-color:yellow;" type=button onclick="alert('To be of filled manually when declaration form is printed')"><b>6.(b)</b></button> I have drawn total emoluments from this college in the current financial year as under:-</td></tr>
</table>

<table class=border style="width:70%;" align=center>	

	<tr><th>Month</th><th>Amount Received</th><th>TDS</th></tr>
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

<table><tr><td><button  type=button style="background-color:yellow;" onclick="alert('PAN uploaded above. Form 16 to be attached manually, when declaration form is printed')"><b>6.(c)</b></button> (Copy of my PAN &amp; Form 16 (TDS certificate) for financial year 2015-16 are attached)</td></tr></table>
</p>

<table><tr><td><b>7</b> I have appeared in the last inspection of the same College in the same post. <input type=radio name=appeared_in_same_college_post value=yes>Yes /<input type=radio name=appeared_in_same_college_post value=no>No</td></tr></table>

<p>
<table align="top" >
	
<tr><th colspan=0>DECLARATION</th></tr>
<tr><td valign="top" style="width:5%;">1.</td><td style="width:95%;"> I, Dr. 
<input type=text readonly placeholder="click to fill" onclick="copyfrom(this,'name')"> am working as 
<input type=text readonly placeholder="click to fill" onclick="copyfrom(this,'present_designation')"> in the Department of 
<input type=text readonly placeholder="click to fill" onclick="copyfrom(this,'present_department')">  at 
<input type=text readonly placeholder="click to fill" onclick="copyfrom(this,'present_college_name')"> ,
<input type=text readonly placeholder="click to fill" onclick="copyfrom(this,'present_city')"> and do hereby give an undertaking that I am a full time teacher in 
<input type=text readonly onclick="copyfrom(this,'present_department')">, working from  9 A.M. to 5 P.M. daily at this Institute.</td></tr>
<tr><td  valign="top">2.</td><td>I have not presented myself to any other Institution as a faculty in the current academic year for the purpose of MCI assessment.</td></tr>
<tr><td  valign="top"><button  type=button style="background-color:yellow;" onclick="alert('If doing private practice fill details, when declaration form is printed')"><b>3</b></button></td>
<td>
	<table>
	<tr><td>
		<input type=radio name=private_practice value=no>I am not having private practice anywhere.
	</td></tr>
	<tr><td>
	<input type=radio name=private_practice value=yes>I am practicing at ___________ in the city of __________ and my hours of practice are _____ to _____ .Further I state that I am not doing any Private Practice or not working in any other hospital during college hours.
	</td></tr>
	</table>
</td></tr>
<tr><td valign="top">4.</td><td>Complete details with regard to work experience has been provided; nothing has been concealed by me.</td></tr>
<tr><td valign="top">5.</td><td>It is declared that each statement and/or contents of this declaration and /or documents, certificates submitted along with the declaration form, by the undersigned are absolutely true, correct and authentic.  In the event of any statement made in this declaration subsequently turning out to be incorrect or false the undersigned has understood and accepted that such misdeclaration in respect to any content of this declaration shall also be treated as a gross misconduct thereby rendering the undersigned liable for necessary disciplinary action (including removal of his name from Indian Medical Register).</td></tr>
</table>


</p>
<p>
<table class=noborder style="width:90%">
	<tr><td  style="width:15%">Date:</td><td  style="width:15%"><input readonly type=text placeholder="to written when signed"></td><td   align=right style="width:50%">SIGNATURE OF THE EMPLOYEE</td></tr>
	<tr><td >Place:</td><td><input readonly type=text placeholder="to written when signed"></td><td align=right ><input type=text readonly placeholder="to be signed physically"></td></tr>
</table>
</p>


<H3 align=center>ENDORSEMENT</H3>
<p>1. This endorsement is the certification that the undersigned has satisfied himself /herself about the correctness and veracity of each content of this declaration and endorses the above mentioned declaration as true and correct.I have verified the certificates / documents submitted by the candidatewith the original certificates/documents as submitted by the teacher to the Institute and with the concerned Institute and have found themto be correct and authentic.
</p>
<p>2. I also confirm that Dr.<input type=text readonly onclick="copyfrom(this,'name')"> is not practicing or carrying out any other activity during college working hours i.e. from 9.00 AM to 5 PM , since he/she has joined the Institute.
</p>
<p>3.	In the event of this declaration turning out to be either incorrect or any part of this declaration subsequently turning out to be incorrect or false it is understood and accepted that the undersigned shall also be equally responsible besides the declarant himself/herself for any such misdeclaration or misstatement.                             
</p>
<table class=border style="width:100%">
	<tr><td  style="width:25%">Date:</td><td  style="width:25%"><input readonly type=text placeholder="to written when signed"></td><td   style="width:25%" align=center>Signed by HOD</td><td  style="width:25%">Countersigned by the Director/Dean/Principal</td></tr>
	<tr><td >Place:</td><td><input readonly type=text placeholder="to written when signed"></td><td align=right ><input type=text readonly placeholder="to be signed physically"></td><td align=right ><input type=text readonly placeholder="to be signed physically"></td></tr>
</table>
</p>

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
<table>
<tr><th>Signed by the Teacher</th><th>Signed by the HOD</th></tr>
<tr><td><input readonly type=text placeholder="Sign in physical copy"></td><td><input type=text readonly placeholder="Sign in physical copy"></td></tr>
<tr><td>Date</td><td>Date</td></tr>
<tr><td><input type=text readonly placeholder="Enter in physical copy"></td><td><input type=text readonly placeholder="Enter in physical copy"></td></tr>
</table>
</p>

<p>
<table>
<tr><th>Countersigned by Dean / Principal:</th></tr>
<tr><td><input type=text readonly placeholder="Sign in physical copy"></td></tr>
<tr><td>Date</td></tr>
<tr><td><input type=text readonly placeholder="Enter in physical copy"></td></tr>
</table>
</p>

<p>
<table>
<tr><th>Signed & Verified by the Assessor:</th></tr>
<tr><td><input type=text readonly placeholder="NA"></td></tr>
<tr><td>Date</td></tr>
<tr><td><input type=text readonly placeholder="NA"></td></tr>
</table>
</p>

<p>1. The Declaration Form will not be accepted and the person will not be counted as teacher if any of the above documents are not enclosed /attached with the Declaration Form.</p>
<p>2. The person will not be counted as a teacher if the original of Photo ID proof, Registration Certificates / Degree certificates / PAN Card /State Medical Council ID (if issued) are not produced for verification at the time of assessment.</p>
<p>3. All the teachers must submit the revised declaration form in this format only. (Any declaration form submitted in an old format will not be accepted and he will not be counted as a teacher.)</p>

</form>
</body>
</html>

