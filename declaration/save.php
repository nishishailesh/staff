<?php
//session_start();
require_once '../common/common.php';
/*
echo '<pre>';
print_r($GLOBALS);
echo '</pre>';
*/

function save()
{
$link=connect();

//following exist for all users. So update is required
update_field_by_id($link,'staff','id',$_POST['id'],'fullname',$_POST['name']);
update_field_by_id($link,'staff','id',$_POST['id'],'department',$_POST['present_department']);
update_field_by_id($link,'staff','id',$_POST['id'],'dob',india_to_mysql_date($_POST['dob']));

//following may not be existing for a given user, so update, if it fail, insert is required
update_or_insert_field_by_id($link,'photo','id',$_POST['id'],'proof_type',$_POST['photo_id']);
update_or_insert_field_by_id($link,'photo','id',$_POST['id'],'proof_number',$_POST['photo_id_number']);
update_or_insert_field_by_id($link,'photo','id',$_POST['id'],'proof_issued_by',$_POST['photo_id_issued_by']);

update_or_insert_attachment($link,'photo','id',$_POST['id'],'photo_id',$_FILES['photo_id']);
update_or_insert_filename_field_by_id($link,'photo','id',$_POST['id'],'photo_id_filename',$_FILES['photo_id']['name']);

update_or_insert_attachment($link,'photo','id',$_POST['id'],'photo',$_FILES['photo']);
update_or_insert_filename_field_by_id($link,'photo','id',$_POST['id'],'photo_filename',$_FILES['photo']['name']);

update_or_insert_field_by_id($link,'staff','id',$_POST['id'],'designation',$_POST['present_designation']);


return true;
}

?>
