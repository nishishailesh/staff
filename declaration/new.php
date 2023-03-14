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
		$save_msg='Saved at --->'.strftime("%T");
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



if(isset($_FILES))
{
	upload_misc_attachment($link,$_FILES,$_SESSION['login']);
}


?>

<!DOCTYPE html>
<html>
<head>
     <script type="text/javascript" src="../date/datepicker.js"></script>
     <script src="../js/jquery-3.1.1.min.js"></script>
     <link rel="stylesheet" type="text/css" href="../date/datepicker.css" /> 
<!--JAVASCRIPT-->
<script>	
qr=0;
er=0;

function getfrom(one,two) {
			document.getElementById(two).value =one.value;
		}

function copyfrom(target,source) {
			target.value =document.getElementById(source).value
		}
		
function copy_paste_id_to_id(target,source) {
			document.getElementById(target).value =document.getElementById(source).value
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
					tls[x].style.background ="black";
					tls[x].style.borderColor ="black";
					tls[x].style.color ="white";
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
	//style="background-color:#5BC0DE;color:white;font-size:20px;border-radius: 8px;padding:10px;"
				if(document.getElementById(one).style.display == "none")
				{
					document.getElementById(one).style.display = "block";
					labell.style="background-color:#5BC0DE;font-size:20px;border-radius: 8px;padding:10px;";
					labell.innerHTML="Hide "+textt;
				}
				else
				{
					document.getElementById(one).style.display = "none";
					labell.innerHTML="Show "+textt;
				}

		}
		
		/* one is section-id, two and three are section class and tabclass four is button */
		
function showhide_with_tab(one,eclass,tclass,myself) {
				
				hide_class(eclass,tclass);
				
				if(document.getElementById(one).style.display == "none")
				{
					document.getElementById(one).style.display = "block";
					document.getElementById(one).style.background="lightgray";
					myself.style.background="lightgray";
					myself.style.borderColor="lightgray";
					myself.style.color="black";
				}
				else
				{
					//document.getElementById(one).style.display = "none";
					alert("bye");
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
<!-- A4=210x297 so width=840 height=1200 -->
<body>
<?php 
menu($link);
//Form element at this place (outside table below , with closing outside required to make  POST visible if added by javascript
echo '<form method=post  enctype=\'multipart/form-data\' >';

echo '<table class=noborder ><tr><td>';
//<!-- menu() have its own <form>. Never enclose it in another form -->
//menu();
echo '</td><td valign=top >';

//Form element at this place (inside a table, with closing outside donot make POST visible if added by javascript
//echo '<form method=post  enctype=\'multipart/form-data\' >';

echo '</td><td  valign=top>';
echo $save_msg;
echo '</td></tr></table>';
?>


<h1 style="color:white;">
	<p align=center>
		<b>
			DECLARATION FORM : 2023 - 2024 - FACULTY
		</b>
	</p>
</h1>
<h3>
	<p align=center>
		<u>
			<span style="background:#cc0000;">
				Note: Use Declaration->Check menu to varify the correctness of details filled
			</span>
		</u>
	</p>
</h3>

<!--menu-->

<div>
<!--General Help-->
<div>
 
 <span class="menub" style="background-color:#5BC0DE;font-size:20px;border-radius: 8px;padding:10px;" id=genral_help_sh onclick="showhide_with_label('general_help',this,'General Help')"> Show General Help</span>
  <input type=hidden name=id value=<?php echo '\''.$staff_detail['id'].'\'';?>>
    <br><br>
     <div id=general_help style="border-style: solid;padding:10px;background-color:#f2f2f2;display:none;">
       <div>
           <h1 style='color:black;padding:5px;' >Help</h1>
          <ol>
             <h4><li >Click  <span style="background-color:black;color:white;margin:2px;">  Black  </span>  buttons to start filling details of respective section.</li></h4>
             <h4><li> Click <span style="background-color:lightblue;margin:2px;">Blue </span> buttons to get help.</li></h4>           
             <h4><li> If smart phone is used to fill this form, use camara to snap and upload documents.</li></h4>
             <h4><li>Some of the details will be automatically filled, AFTER data is saved.</li></h4>
             <h4><li>Save frequently (<span style="background-color:lightgreen;margin:2px;">Green Button</span>) . Saved data is automatically retrived.</li></h4>
             <h4><li>Keep elctronic/physical copy of photo id, photo, degree, registration, current appointment order, current joining order, last relieving order, PAN, address proof ready.</li></h4>
             <h4><li>Keep dates of each and every appointments/transfers/promotions ready.</li></h4>
          </ol>
       </div>
  </div>
</div>
<?php echo '<button style="background-color:lightgreen" class="menub" name=action type=submit value=save title="save frequently to prevent repeat attempts">Save</button>';?>
	
<button type=button class="section_header tabbutton" onclick="showhide_with_tab('identity'	,'section','section_header',this)">
	Identity Information
</button>
<button type=button class="section_header tabbutton" onclick="showhide_with_tab('contact'					,'section','section_header',this)">
	Contact
</button>
<button type=button class="section_header tabbutton" onclick="showhide_with_tab('service'					,'section','section_header',this)">
	Service
</button>
<button type=button class="section_header tabbutton" onclick="showhide_with_tab('qualification_section'	,'section','section_header',this)">
	Qualification
</button>
<button type=button class="section_header tabbutton" onclick="showhide_with_tab('experience_section'		,'section','section_header',this)">
	Experience
</button>
<button type=button class="section_header tabbutton" onclick="showhide_with_tab('present_appointment'		,'section','section_header',this)">
	Present Appointment
</button>
<button type=button class="section_header tabbutton" onclick="showhide_with_tab('MET'						,'section','section_header',this)">
	MET
</button>
<button type=button class="section_header tabbutton" onclick="showhide_with_tab('MCI'						,'section','section_header',this)">
	MCI
</button>
<button type=button class="section_header tabbutton" onclick="showhide_with_tab('publication'				,'section','section_header',this)">
	Publication
</button>
<button type=button class="section_header tabbutton" onclick="showhide_with_tab('income'					,'section','section_header',this)">
	Income
</button>
<button type=button class="section_header tabbutton" onclick="showhide_with_tab('misc_upload'					,'section','section_header',this)">
	Misc. Uploads
</button>
</div>	

<!--For Assessors-->


<!--identity-->

<div id=identity style="display:none;"  class="section main_div">
 <table class="border" border="1" width="100%">
	<tr>
		<th>
			1.(a)
		</th>
		<th>
			Name
		</th>
		<td>
			<input type=text placeholder="Lastname Firstname Middlename" name=name id=name size=40 value=<?php echo '\''.$staff_detail['fullname'].'\''; ?>>
		</td>
	</tr>
	<tr>
		<th>
			1.(b)
		</th>
		<th>
			Date of Birth
		</th>
		<td>
			<input readonly	id=dob class="datepicker" size="10" name=dob value=<?php echo '\''.mysql_to_india_date($staff_detail['dob']).'\''; ?>> &amp; Age <input readonly type=text name=age value=<?php 
				$diff=get_date_diff_as_ymd($staff_detail['dob'],strftime("%Y-%m-%d"));
				echo '\''.$diff.'\'';?>>
		</td>
		</tr> 

	<tr>
		<th>
			1.(c)
			<table>
				<tr>
			     <button class="menub" style="background-color:#5BC0DE;font-size:20px;border-radius: 8px;margin-top:5px;"  type=button onclick="alert('(1) Upload pdf/jpg copy of photo ID proof. (2) Upload jpg of passport size photo')">
			        Help
			     </button>
			     </tr>
			</table>
		</th>
		<th>
			Photo ID proof
		</th>
		<td>
			<?php
			echo'<table border="1"  width="100%">
					<tr>
							<tr style="background-color:#E7DEDE;">
								<td >
									Photo ID submitted
								</td>
								<td colspan=2>';
		mk_select_from_sql($link,'select * from photo_id_proof_type','photo_id_proof_type','photo_id','',$photo['proof_type']);
						  echo '</td>
							</tr>
							<tr>
								<td>
									Number: 
								</td>
								<td colspan=2>
									<input type=text name=photo_id_number value=\''.$photo['proof_number'].'\'>
								</td>
							</tr>
							<tr style="background-color:#E7DEDE;">
								<td>
									Issued by: 
								</td>
								<td colspan=2>
									<input type=text name=photo_id_issued_by value=\''.$photo['proof_issued_by'].'\'>
								</td>
							</tr>
							<tr >
								<td>
									Upload photo id
								</td>
								<td>
									<input name=photo_id type="file">		
								</td>
								<td>
									uploaded:<div style="color:blue;display:inline;">'.$photo['photo_id_filename'] ;
									echo'</div>';								
						echo '</td>
							</tr>';
	
				echo '
						<tr  style="background-color:#E7DEDE;">
							<td>
								Upload photo
							</td>
							<td>
								<input name=photo type=file>
							</td>
							<td>';
								display_photo($link,$photo['photo']);
				   echo 	'</td>
						</tr>';
		   echo'</table>';?>
		</td>
	</tr>
 </table>
</div>

<!--service-->


<?php
$de=get_raw($link,'select * from departmental_exam where staff_id=\''.$_SESSION['login'].'\'');
$opt=array('','Yes','No','Exempted');
$catagory=array('','General','SEBC','ST','SC');
?>

<div id=service style="display:none;"  class="section main_div" align=center >  
        <table border=1>
	       <tr>
		      <th colspan=2 >
		         <h4 style="display:inline">
		             Miscellaneous Service data
		         </h4>
		      </th>
	       </tr>
			<tr>
	          <th>
			       Catagory
	          </th>
		      <th>
			      <?php mk_select_from_array($catagory,'catagory','',$staff_detail['catagory']);?>
		      </th>
          </tr>
	      <tr>
		      <th>
			       CCC+ Passed
		      </th>
	          <th>
				<?php     mk_select_from_array($opt,'cccplus','',$de['cccplus']); ?>
		      </th>
          </tr>
          <tr>
		       <th>
		            Gujarati Passed
	           </th>
	           <th>
			        <?php   mk_select_from_array($opt,'gujarati','',$de['gujarati']); ?>
		       </th>
          </tr>
          <tr>
	           <th>
	                Hindi Passed
	           </th>
		       <th>
			        <?php   mk_select_from_array($opt,'hindi','',$de['hindi']); ?>
		       </th>
	      </tr>
		</table>
	</div>



<!---Qualification -->


<?php
if($donot_hide=='qualification')
{
	$qualification_style='display:block';
}
else
{
	$qualification_style='display:none';
}

if($donot_hide=='experience')
{
	$experience_style='display:block';
}
else
{
	$experience_style='display:none';
}

?>

<div id=qualification_section style="<?php echo $qualification_style; ?>"  class="section main_div" >
	
<!---start of qualification and experience -->

 


 <table border="1" style="background-color:lightgray;" width="100%">
	        <tr>
	        <th>Del</th>
	        <th>Qualification </th>
	        <th>College </th>
	        <th>University </th>
	        <th>Year </th>
	        <th>Registration No of UG &<br> PG with date </th>
	        <th>Name of the State <br>Medical Council </th>
	        <th>Degree certificates</th>
	        <th>Registration certificates</th>
	        </tr>
<?php
//add_qualification_raw($link);
view_table_qualification($link);
?>
<tr><td colspan=10>
<button type=button id=addnq style="background-color:lightgreen" class="menub"  
onclick="showhide('qualification_table');"
><img height=35 src="../image/add.png"/></button>
</td></tr>
</table>

<table style="margin: 0 auto;background-color:#ADAD3E;padding:20px;display:none;border:solid red 5px;" id="qualification_table" >
   <tr>
	   <th colspan=2 style="text-align:center;" width="15%">
		     Add Qualifications
		     <button style="background-color:#5BC0DE;font-size:20px;border-radius:8px;" class="menub" type=button id=genral_help_sh onclick="showhide_with_label('qualification_help',this,' Help')"> Show Help </button>  
	   </th>		   
   </tr>
   <tr>
       <td colspan=7>
	     <h2>
	        <table class=border style="background-color:#EBF4FA;display:none;border-color:gray;position:absolute" id=qualification_help>		      
		       <tr><td colspan=7 ><br>(1) After Inserting all details Click Add qualification</td></tr>
		       <tr><td colspan=7 ><br>(2) Click "X" beside the qualification to delete it</td></tr>
		       <tr><td colspan=7 ><br>(3) Upload Degree and Registration certificates where relavent</td></tr>
		       <tr><td colspan=7 ><br>(4) Delete if any mistake is done/ upload not done and add again</td></tr>
		    </table>
	     </h2>
	   </td>
   </tr>
   <tr>
 <!--      <th rowspan=3>
		   Qualification
	   </th>-->

		 <th width="35%">
	        Degree
		 </th>  
         <td>
			<?php mk_select_from_table($link,'qualification_degree','','');?>
		 </td>
	  </tr>
	  <tr>
		 <th>
	        Subject
		 </th>  
         <td>
			<?php $sql_qs='select department from department';?>
			<?php  mk_select_from_sql($link,$sql_qs,'department','qualification_subject','',''); ?>
		 </td>
	  </tr>		
	  <tr>
		 <th>
			 Upload qualification
		 </th>
		 <td>						
	         <input  class=upload type=file name=file_qualification_degree >
	         
		 </td> 

   </tr>
   <tr>
       <th>
		   College
	   </th>
	   <td colspan=2>
	       <input  type=text name=college_qualification >
	   </td>
   </tr>
   <tr>
       <th>
		   University
	  </th>
	  <td colspan=2>
		  <input  type=text name=university_qualification >
	  </td>
   </tr>
   <tr>
       <th>
		   Year
	   </th>
	   <td colspan=2>
	       <?php read_year('year_qualification',date("Y")-100,date("Y"));?>
	   </td>
   </tr>
   <tr>
       <th>
		   Registration No
	   </th>
	   <td>
	      <input placeholder="Reg. No" type=text name=reg_no_qualification id=reg_no_qualification>
	   </td>
   </tr>
   <tr>
       <th >
		   Registration Date
	   </th>	  
	   <td>
	      <input placeholder="Reg. Dt" readonly name=reg_date_qualification id=reg_date_qualification class="datepicker" >
	   </td>
	</tr>
	<tr colspan=2>
		<th>
		    Upload Registration
		</th>
		<td>
            <input  type=file class=upload name=file_qualification_reg >
           
    	</td>
    </tr>
    <tr>
	    <th>
		   	Name of the State Medical Council
		</th>
		<td colspan=2>
		   <input type=text name=council_qualification id=council_qualification>
		</td>
    </tr>
    <tr>
	   <th>Click this button to save--></th>
       <td colspan=2>
		   <button type=submit name=action value=add_qualification style="background-color:lightgreen" class="menub"  >Add Qualification</button>
       </td>
   </tr>
   
 </table>
 

</div>


<!--Experience Section -->

<div id=experience_section style="<?php echo $experience_style; ?>"   class="section main_div">
  <table class="border"   style="background-color:lightgray" id="experience_table" border="1">
      <tr>
	     <th>
			 3.(a)	
		 </th>
		 <th colspan=2>
			  <button class="menub" style="background-color:#5BC0DE;font-size:20px;border-radius:8px;" type=button id=genral_help_sh onclick="showhide_with_label('experience_help',this,' Help')"> Show Help </button>
		 </th>
		 <th colspan=4>
		     Details of the teaching experience till date.
		 </th>
	  </tr>
      <tr>
		  <td colspan=7>
	           <table class=border  style="background-color:#EBF4FA;display:none;" id=experience_help>
		            <tr><td colspan=7 ><br>(1) Add all appointments/transfers/promotions saparately by clicking manage/edit experience.</td></tr>
		       </table>
          </td>
      </tr>
      <tr>
		  <td colspan=5>
			   <button type=submit style="background-color:lightgreen;margin:15px;" class="menub" name=action value=manage_exp formaction=experience_management.php>
				   Manage/Edit Experience
				</button>
		  </td>
	  </tr>
      <tr>
	      
	      <th width="10%" >Designation</th>
	      <th width="10%">Type</th>
	      <th width="20%">Department</th>
	      <th width="30%">Name of Institution</th>
	      <th width="30%">From-To-Total</th>
     </tr>
 
  
        <?php //add_experience_raw($link);
          view_table_experience($link);?>
        
           </table>
   
</div>

<!-- end of qualification and experience -->


<!--present appointment -->

<div id=present_appointment style="display:none;padding:10px;" width="100%"  class="section main_div">
       <!--Note:
           1) Without Photo ID, Declaration form will be rejected and will notbe considered as teaching   faculty.
           2) Original Certificates are mandatory for verification. All Certificates/Documents/Certified Translations, must be in English-->

 <table class="border" border="1" width="100%">
	<tr>
		<th>
			  1.(d)(i)a.
			<button class="menub" style="background-color:#5BC0DE;font-size:20px;border-radius: 8px;margin-top:5px;"  type=button onclick="alert('Upload pdf/jpg copy of present appointment order')">
		         Help
		    </button>
		</th>
		<th width="32%">
		    Uploaded present appointment order:
		</th>
		<td>
             <?php echo '<input name=present_appointment_order type=file >';?>
         </td>
         <td>
               		<?php 
				if(isset($current_appointment_order['attachment_filename']))
				{
					echo 'Uploaded:<div style="color:blue;display:inline">'.$current_appointment_order['attachment_filename'];
				}
			?>
	</div>


        </td>
    </tr>

      <tr>
		 <th>
		     1.(e) 
		 </th>
		 <th>
			  Present Residential Address of employee :
		</th>
		 <td  colspan=2>
             <textarea cols=40 id=present_residential_address name=present_residential_address><?php echo $staff_detail['present_residential_address'];?></textarea>
         </td>
      </tr>
      <tr>
		 <th>
		     1.(f) 
		 </th>
		 <th>
			  Permanent Residential  Address of employee : 
		</th>
		<td>
			<textarea cols=40 id=permanent_residential_address name=permanent_residential_address><?php echo $staff_detail['permanent_residential_address'];?></textarea>
		</td>
		<td>
			<button  style="background-color:lightgreen;display:inline;" class="menub" type=button  id=copy_add onclick="copy_paste_id_to_id('permanent_residential_address','present_residential_address')" >
			Copy From Above
		    </button>
		</td>
      </tr>

	  <tr>
		 <th>
			  1.(j)
			<button class="menub" style="background-color:#5BC0DE;font-size:20px;border-radius: 8px;margin-top:5px;"  type=button onclick="alert('Upload pdf/jpg copy of current joining order.')">
              Help
            </button>
         </th>
         <th>
             Upload joining order:
         </th>

		<td>
            <?php echo '<input type=file  name=present_joining_order>';?>
		</td>
		<td>
			 Uploaded:<div style="color:blue;display:inline">
			 <?php
				if(isset($current_joining_order['attachment_filename']))
				{
					echo $current_joining_order['attachment_filename']; 
				}

				?>
			 </div>
		</td>

      </tr>
	  <tr>
		  <?php $previous_institute_details=find_staff_movement_details_of_previous_institute($link,$current_appointment['institute']);?>
		  <th width="10%">
			    4.(a)
		      <button class="menub" style="background-color:#5BC0DE;font-size:20px;border-radius: 8px;margin-top:5px;"  type=button onclick="alert('Upload pdf/jpg copy of last relieving order ')">
				 Help
		      </button>
		  </th>
		  <th>
		    <!--  Before joining present institution I was working at 
		      <?php echo $previous_institute_details['institute']; ?> 
			  as 
			  <?php echo $previous_institute_details['post']; ?>
			  and relieved on
			  <?php echo mysql_to_india_date($previous_institute_details['to_date']); ?>
			  after resigning / retiring .<br> (Relieving order is enclosed from the previous institution)-->
			  Upload relieving order :
		  </th>

			<td>
				<?php echo '<input  name=last_relieving_order type=file>';?>
			 </td>
			 <td>
				 Uploaded:<div <div style="color:blue;display:inline">
				
				 <?php  
					if(isset($previous_relieving_order['attachment_filename']))
					{
						echo $previous_relieving_order['attachment_filename']; 
					}

				?>
				 </div>
			 </td>                 
			</td>

	  </tr>
<!--  <tr>
		  <th>
			  4.(b)
		  </th>
		  <th>
			  I am not working in any other medical college/dental college in the State or outside the State in any capacity Regular / Contractual.
		  </th>
	      <td>
	      
	      </td>
	  </tr>-->
  </table>
</div>

<!--MET-->

<div id=MET style="display:none;padding:10px;"   class="section main_div">

<p>1.(f) Have you undergone Training in "Basic Course Workshop" at MCI Regional Centre in MET or in your college under Regional Centre observership

<?php
//find if met is found, if yes, change radio and default display
if($met!==FALSE)
{
	echo '<input type=radio name=MET value=yes checked onclick="show(\'MET_details\')">Yes<input type=radio name=MET value=no onclick="hide(\'MET_details\')">No</p>';	
	
	echo '<table class=border id=MET_details style="display:block;">';
}
else
{
	echo '<input type=radio name=MET value=yes onclick="show(\'MET_details\')">Yes<input type=radio name=MET checked value=no onclick="hide(\'MET_details\')">No</p>';
 
	echo '<table class=border id=MET_details style="display:none;">';
}

?>

		<tr><BR><BR>
			<td style="width:20%">
				MET Center:
			</td>
			<td>
				 <input type=text style="width:95%;height:100%;" name=met_center value=<?php echo '\''.$met['center'].'\'';?>>
			</td>
			<td style="width:10%">
				MET Place:
			</td>
			<td>
				<input type=text  style="width:95%;height:100%;"  name=met_place value=<?php echo '\''.$met['place'].'\'';?>>
			</td>
        </tr>
        <tr>
			<td  style="width:20%">
				MET Observer:
			</td>
			<td>
				<input type=text style="width:95%;height:100%;"  name=met_observer value=<?php echo '\''.$met['observer'].'\'';?>>
			</td>
			<td  style="width:10%">
				MET Date:
			</td>
			<td>
				<br>
				<input readonly	id=met_date class="datepicker" size="10" name=met_date value=<?php echo '\''.mysql_to_india_date($met['date']).'\'';?>>
			</td>
		</tr>
	</table>
</div>

<!--contact -->

<div id=contact style="display:none;"   class="section main_div">	
	<table border="1" width="100%">
		<tr>
			<th width="10%">
					1.(g)
				<button class="menub" style="background-color:#5BC0DE;font-size:20px;border-radius: 8px;margin-top:5px;" type=button onclick="alert('Upload pdf/jpg copy of address proof')">
				    Help
			    </button>    
			</th>
			<th width="20%">
		        Upload present residence proof<br>
		        <button 
						type=button 
						style="background-color:#5BC0DE;font-size:20px;border-radius: 8px;margin-top:5px;"
						class="menub" 
						id=sharp 
						onclick="showhide('accepted_rp');">
						Accepted Proofs
				</button>
		        <span style="position:absolute;display:none;" id="accepted_rp">
					<table style="background-color:white" >	<tr><td>Passport</td></tr>
							<tr><td>Voter Card</td></tr>
							<tr><td>Electricity Bill</td></tr>
							<tr><td>Landline Telephone Bill</td></tr>
							<tr><td>Aadhar Card</td></tr>
					</table>
				</span>
			  
			
		    </th>
	        <th width="35%">
		         <input name=proof_of_residence type=file>
		    </th>
		    <th width="35%">
				  <?php echo 'uploaded:<div style=color:blue;display:inline;>'.$r_proof['filename'].'
			</div></th>';?>
	    </tr>
        <tr>
             <th rowspan=4>
				 1.(h)
		     </th>
		     <th rowspan=4>
				  Contact Particulars:
		     </th>
		     <th>   
		                   Tel (Office)(with STD code):
		              </th>
		              <th>
				          <input type=text  size=17 name=office_phone value="0261-2244175" value=<?php echo '\''.$staff_detail['office_phone'].'\'';?>>
			           </th>
			       </tr>
                   <tr>
                       <th>
				           Tel(Residence): (with STD code)
			           </th>
			           <th>
				           <input size=17 type=text name=residencial_phone value=<?php echo '\''.$staff_detail['residencial_phone'].'\'';?>>
                       </th>
                   </tr>
                   <tr>
			           <th>
			               E-mail address:
			           </th>
			           <th>
				           <input type=text  size=27  name=email value=<?php echo '\''.$staff_detail['email'].'\'';?>>
                       </th>
                   </tr>
			       <tr>
			           <th>
				           Mobile Number:
			           </th>
			           <th>
				           <input type=text  size=17  name=mobile value=<?php echo '\''.$staff_detail['mobile'].'\'';?>>
                       </th>
                  </tr>
   </table>
</div>
</div>


<div id=MCI style="display:none;"   class="section main_div">
  <table class=border border="1" width="100%">
	<tr>
		<th>
			Dates of appearance in MCI Inspection:
		</th>
	</tr>
	<tr>


        <tr><td>
				<input name=mci_designation placeholder=designation>
				<input name=mci_subject placeholder=subject>
				<input name=mci_college placeholder=college>
				<input name=mci_date placeholder=dates type=date>
                <input style="background-color:lightgreen;height:40;" class="menub" class=submitbutton 
		name=action value=add_mci_date type=submit>
	    </td></tr>
	<tr>
	     <td  colspan=3 align=center><?php view_table_mci($link); ?></td>
	</tr>
   </table>
</div>

<!--publication-->

<div id=publication style="display:none;"   class="section main_div">
<table width="100%" border="1">
	<tr>
		<th>
			5.
	    </th>
	    <th>
	       Number of  Research publications<br> in Index Journals:
	    </th>
	    <td colspan=2>   
                 <table width="100%" border="1">
				   <tr>
			          <td>
		                  (a) International Journals:
		              </td>
		              <td>
				          <input type=text name=ipublication value=<?php echo '\''.$publication['international'].'\'';?>
			           </td>
			       </tr>
			       <tr>
			          <td>
		                  (b) National Journals:
		              </td>
		              <td>
				          <input type=text name=npublication  value=<?php echo '\''.$publication['national'].'\'';?>
			           </td>
			       </tr>
			       <tr>
			          <td>
		                  (c) State/Institutional Journals: 
		              </td>
		              <td>
				          <input type=text name=spublication  value=<?php echo '\''.$publication['state'].'\'';?>
			          </td>
			       </tr>
			     </table> 
		</td>     
	</tr>	    
  </table>
 </div>

<!--income-->

<div id=income style="display:none;"   class=section>
  <br>
  <table width="100%" border="1">
		<tr>
			<th rowspan=2>
				6.(a)
				<button class="menub" style="background-color:#5BC0DE;font-size:20px;border-radius: 8px;margin-top:5px;display:inline;"   type=button onclick="alert('Upload jpg/pdf copy of PAN card')">
				    Help
			    </button>    
			</th>
			<th>
		        My PAN Card No. is:
		    </th>
	        <td colspan=2>
		        <input type=text name=PAN_number value=<?php echo $pan['pan'];?>>     
		    </td>
	   </tr>
	   <tr>
			<th>
		        Upload PAN Card:
		    </th>

            <td>
	            <?php echo '<input type=file name=PAN_card >';?>
            </td>
            <td>
                 Uploaded:<div style="color:blue;display:inline">
                 <?php echo $pan['attachment_filename'] ?>
                 </div>
            </td>
	   </tr>
  </table>
  <br>
</div>

<!-- Declaration-->

<div id=declaration style="display:none;"   class=section>	
  <table align="top" >	
     <tr> 
		 <th colspan=3>
			    DECLARATION
		 </th>
	</tr>
    <tr>
		 <td valign="top" style="width:5%;">
			 1.
		 </td>
		 <td style="width:95%;">
			 I, Dr. 
            <?php echo $staff_detail['fullname'] ?> am working as 
            <?php echo $current_appointment['post'] ?> in the Department of 
            <?php echo $current_appointment['department'] ?>  at 
            <?php echo $current_appointment['institute'] ?>,
            <?php $ex=explode(' ', $current_appointment['institute']);$city=$ex[count($ex)-1]; echo $city ; ?>
             and do hereby give an undertaking that I am a full time teacher in 
            <?php echo $current_appointment['department'] ?>, working from  9 A.M. to 5 P.M. daily at this Institute.
          </td>
     </tr>
     <tr>
		  <td  valign="top">
			  2.
		 </td>
		 <td>
			 I have not presented myself to any other Institution as a faculty in the current academic year for the purpose of MCI assessment.
		 </td>
	 </tr>
     <tr>
		 <td  valign="top">
			 <button  type=button style="background-color:yellow;" onclick="alert('If doing private practice fill details, when declaration form is printed')">
				 3
			 </button>
		</td>
        <td>
	       <table>
	          <tr> 
				  <td>
		             I am not having private practice anywhere.
	              </td>
	          </tr>
	          <tr>
				  <td>
	                 I am practicing at ___________ in the city of __________ and my hours of practice are _____ to _____ .Further I state that I am not doing any Private Practice or not working in any other hospital during college hours.
	              </td>
	          </tr>
	       </table>
         </td>
      </tr>
<tr><td valign="top">4.</td><td>Complete details with regard to work experience has been provided; nothing has been concealed by me.</td></tr>
<tr><td valign="top">5.</td><td>It is declared that each statement and/or contents of this declaration and /or documents, certificates submitted along with the declaration form, by the undersigned are absolutely true, correct and authentic.  In the event of any statement made in this declaration subsequently turning out to be incorrect or false the undersigned has understood and accepted that such misdeclaration in respect to any content of this declaration shall also be treated as a gross misconduct thereby rendering the undersigned liable for necessary disciplinary action (including removal of his name from Indian Medical Register).</td></tr>
</table>


<table class=noborder style="width:90%">
	<tr><td  style="width:15%">Date:</td><td  style="width:15%"><input readonly type=text placeholder="to written when signed"></td><td   align=right style="width:50%">SIGNATURE OF THE EMPLOYEE</td></tr>
	<tr><td >Place:</td><td><input readonly type=text placeholder="to written when signed"></td><td align=right ><input type=text readonly placeholder="to be signed physically"></td></tr>
</table>
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
<table class=border style="width:100%">
	<tr><td  style="width:25%">Date:</td><td  style="width:25%"><input readonly type=text placeholder="to written when signed"></td><td   style="width:25%" align=center>Signed by HOD</td><td  style="width:25%">Countersigned by the Director/Dean/Principal</td></tr>
	<tr><td >Place:</td><td><input readonly type=text placeholder="to written when signed"></td><td align=right ><input type=text readonly placeholder="to be signed physically"></td><td align=right ><input type=text readonly placeholder="to be signed physically"></td></tr>
</table>
</p>


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

<table>
<tr><th>Signed by the Teacher</th><th>Signed by the HOD</th></tr>
<tr><td><input readonly type=text placeholder="Sign in physical copy"></td><td><input type=text readonly placeholder="Sign in physical copy"></td></tr>
<tr><td>Date</td><td>Date</td></tr>
<tr><td><input type=text readonly placeholder="Enter in physical copy"></td><td><input type=text readonly placeholder="Enter in physical copy"></td></tr>
</table>


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
</div>

<?php
$prp=get_raw($link,'select * from misc_upload where staff_id=\''.$_SESSION['login'].'\' and description=\'permanent_residence_proof\'');
$ec=get_raw($link,'select * from misc_upload where staff_id=\''.$_SESSION['login'].'\' and description=\'exp_cert\'');
$f16=get_raw($link,'select * from misc_upload where staff_id=\''.$_SESSION['login'].'\' and description=\'f16\'');
$ugr=get_raw($link,'select * from misc_upload where staff_id=\''.$_SESSION['login'].'\' and description=\'ug_rec\'');
$pgr=get_raw($link,'select * from misc_upload where staff_id=\''.$_SESSION['login'].'\' and description=\'pg_rec\'');
$adr=get_raw($link,'select * from misc_upload where staff_id=\''.$_SESSION['login'].'\' and description=\'aadhar\'');

?>

<div id=misc_upload style="display:none;" class="section main_div">

	<table border=1>
		<tr><td>Permanent Residence Proof</td><td><input type=file  name=permanent_residence_proof></td><td>uploaded <span style="color:blue;"><?php echo $prp['filename']; ?></span></td></tr>
		<tr><td>Experience certificate</td><td><input type=file  name=exp_cert></td><td>uploaded <span style="color:blue;"><?php echo $ec['filename']; ?></span></td></tr>
		<tr><td>Form 16</td><td><input type=file  name=f16></td><td>uploaded <span style="color:blue;"><?php echo $f16['filename']; ?></span></td></tr>
		<tr><td>UG Teacher recognition</td><td><input type=file  name=ug_rec></td><td>uploaded <span style="color:blue;"><?php echo $ugr['filename']; ?></span></td></tr>
		<tr><td>PG teacher recognition</td><td><input type=file  name=pg_rec></td><td>uploaded <span style="color:blue;"><?php echo $pgr['filename']; ?></span></td></tr>		
		<tr><td>Aadhar Card</td><td><input type=file  name=aadhar></td><td>uploaded <span style="color:blue;"><?php echo $adr['filename']; ?></span></td></tr>		
	</table>

</div>

</form>
</body>
</html>

<?php
function add_qualification_raw($link)
{

	echo '<tr style="background-color:lightblue;">
			<td></td>
			<td>';
	mk_select_from_table($link,'qualification_degree','','');
	echo '<br>';
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
				<table class=border><tr><td >
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
			<td colspan=7><button type=submit name=action value=add_qualification style="color:black;background-color:lightgreen;color:black;margin:15px;border:2px solid black;border-radius:8px;">Add Qualification</button></td>
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
	$raw_html='
	        <tr style="background-color:lightblue;">
			<td>
			<button style="background-color:lightblue;color:red;" type=submit name=delete_qualification value=\''.$ra['qualification_id'].'\'>X</button></td><td>
			'.$ra['qualification'].'('.$ra['subject'].')
			</td>
			<td  >'.$ra['college'].'</td>
			<td  >'.$ra['university'].'</td>
			<td>'.$ra['year'].'</td>
			<td>
				'.$ra['registration_number'].', date:'.mysql_to_india_date($ra['registration_date']).'
			</td>
			<td>'.$ra['medical_council'].'</td>
			<td>'. find_qualification_attachment_name($link,$ra['qualification_id'],'degree_certificate').'</td>
			
			<td>'.find_qualification_attachment_name($link,$ra['qualification_id'],'reg_certificate').'</td>
		
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
								<input size=30 placeholder="Write Institute Name Here" style="display:none;" 
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
							<input readonly style="display:none;" id=to_experience_text 
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
	<td colspan=7><button type=submit name=action value=add_experience style="background-color:lightgreen" class="menub" >Add Experience</button></td>';
	echo '</tr>';
}

function view_table_mci_x($link)
{
	//Designation 	Type 	Department 	Name of Institution 	From - To - Total

	$sql='select * from mci where staff_id=\''.$_SESSION['login'].'\' order by `date`';
	if(!$result=mysqli_query($link,$sql)){return FALSE;}
	echo '<table border="1" style="background-color:lightblue;">';
	echo '<tr><th>Del</th></tr>';
	while($ra=mysqli_fetch_assoc($result))
	{	
		echo '<tr>
				<td><button style="background-color:lightblue;color:red;"type=submit name=delete_mci 
				value=\''.$ra['date'].'\'>X</button></td>
				<td>'.mysql_to_india_date($ra['date']).'</td>
			</tr>';
	}
	echo '</table>';
}


function view_table_mci($link)
{
        $sql='select * from mci where staff_id=\''.$_SESSION['login'].'\' order by `date`';
        if(!$result=mysqli_query($link,$sql)){return FALSE;}
        echo '<table border=1>';
        echo '<tr><th>del</th><th>Designation</th><th>Subject</th><th>College</th><th>Dates</th></tr>';
        while($ra=mysqli_fetch_assoc($result))
        {       
                echo '<tr>
				<td><button style="background-color:lightblue;color:red;"type=submit 
					name=delete_mci value=\''.$ra['date'].'\'>X</button></td>
                                <td>'.$ra['designation'].'</td>
                                <td>'.$ra['subject'].'</td>
                                <td>'.$ra['college'].'</td>
                                <td>'.$ra['date'].'</td>

                        </tr>';
	}

       	echo '</table>';
}




function upload_misc_attachment($link,$files,$staff_id)
{	
	//echo '<pre>'; print_r( $files);print_r($_POST);echo '</pre>';

	
    $misc_upload=array('permanent_residence_proof','exp_cert','f16','ug_rec','pg_rec','aadhar');
    
    foreach($misc_upload as $value)
    {
		if(isset($files[$value]))
		{
			if($files[$value]['size']>0)
			{
				$fblob=file_to_str($link,$files[$value]);
				$filename=$files[$value]['name'];//echo '<h1>'.$filename.'</h1>';
				$sql='insert into misc_upload values(\''.$staff_id.'\',\''.$value.'\',\''.$filename.'\',\''.$fblob.'\') 
							ON DUPLICATE KEY UPDATE
							filename=\''.$filename.'\' ,
							file=\''.$fblob.'\'
							';
				if(!$result=mysqli_query($link,$sql))
				{		
					echo 'Error()';
					echo mysqli_error($link);
				}
				else
				{
					echo 'uploaded '.$value.'<br>';
				}
			}
		}
	}
}

?>

<?php
//echo '<pre>';
//print_r($_POST);
//print_r($_FILES);
//echo if_in_interval("2010-11-01","2010-11-01","2010-11-01");
//echo if_in_interval("2010-02-02","2010-02-01","2010-03-03");
//echo if_in_interval("2010-11-01","2011-11-01","2011-11-01");
//echo if_in_interval("2010-11-01","2009-11-01","2009-11-01");
//echo if_in_interval("2010-11-01","2012-11-01","2009-11-01");
//echo get_exterience("2016-12-12","2016-12-13");
//echo '</pre>';
?>



