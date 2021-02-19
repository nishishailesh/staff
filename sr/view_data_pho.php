<?php
session_start();


require_once '../common/common.php';

function view_data($link,$dept)
{


	//$sql='select photo from photo where id=587632527681'; 
	//$sql='select photo from photo,fullname,'; 

        $sql='select staff_id,fullname,permanent_residential_address,dob,mobile,email ,from_date,post,institute
			from  staff,staff_movement
			where
			staff.staff.id=staff_movement.staff_id  and
	                to_date is null and 
                        institute="Government Medical College Surat"  and
                        department=\''.$dept.'\'
                        order by department,post';
	

	$sql_photo='select photo from photo,staff,staff_movement 
		where staff.staff.id=staff_movement.staff_id  and
			photo.staff_id=staff.staff_id and
			to_date is null and 
			institute="Government Medical College Surat"  and
			department=\''.$dept.'\'
			order by department,post';

	//echo $sql;

	if(!$result=mysqli_query($link,$sql)){echo 'error:'.mysqli_error($link);}
	//if(!$result=mysqli_query($link,$sql_photo)){echo 'error:'.mysqli_error($link);}
	echo '<table border=1>';
	                echo '<tr>
                                <th>Sr</th>
                                <th>Photo</th>
                                <th>Name</th>
                                <th>Post</th>
                                <th>Place</th>
                                <th>Permanent Address</th>
                                <th>DOB</th>
                                <th>Date of Joining Service</th>
                                <th>Qual</th>
                                <th>Qaal. after joining</th>
                                <th>Mobile</th>
                                <th>Email</th>
                        </tr>';

	$counter=1;
	while($array=mysqli_fetch_assoc($result))
	{

	        $sql_photo='select photo from photo where id=\''.$array['staff_id'].'\'';
		//echo $sql_photo;
		if(!$result_photo=mysqli_query($link,$sql_photo)){echo 'error:'.mysqli_error($link);}
	        $array_photo=mysqli_fetch_assoc($result_photo);


                $sql_fd='select from_date from staff_movement where staff_id=\''.$array['staff_id'].'\' order by from_date';
                //echo $sql_photo;
                if(!$result_fd=mysqli_query($link,$sql_fd)){echo 'error:'.mysqli_error($link);}
                $array_fd=mysqli_fetch_assoc($result_fd);	//first result


                $sql_dg='select year ,qualification,subject from qualification where staff_id=\''.$array['staff_id'].'\' order by year desc';
                //echo $sql_dg;
                if(!$result_dg=mysqli_query($link,$sql_dg)){echo 'error:'.mysqli_error($link);}
                $array_dg=mysqli_fetch_assoc($result_dg);

			echo '<tr>';
			echo '<td>'.$counter.'</td>';
			echo '<td><img style="width:3cm;height:4cm;" src="data:image/jpeg;base64,'.base64_encode($array_photo['photo']).'"/></td>';
			echo '<td>'.$array['fullname'].'</td>';
			echo '<td>'.$array['post'].'</td>';
			echo '<td>'.$array['institute'].'</td>';
			echo '<td>'.$array['permanent_residential_address'].'</td>';
			echo '<td>'.mysql_to_india_date($array['dob']).'</td>';
			echo '<td>'.mysql_to_india_date($array_fd['from_date']).'</td>';
			echo '<td>'.$array_dg['qualification'].','.$array_dg['subject'].'</td>';
			echo '<td>-</td>';
			echo '<td>'.$array['mobile'].'</td>';
			echo '<td>'.$array['email'].'</td>';
			echo '</tr>';
			$counter++;
	}
	echo '</table>';
}



$link=connect_office();

menu_office();

$sql='select * from department';
if(!$result=mysqli_query($link,$sql)){echo 'error:'.mysqli_error($link);}
echo '<form method=post>';
while($array=mysqli_fetch_assoc($result))
{
	echo '<input type=submit name=department value=\''.$array['department'].'\'>';
}
echo '</form>';

echo '<h2 style="page-break-before: always;"></h2>';	
	view_data($link,$_POST['department']);

//print_r($_POST);
?>
