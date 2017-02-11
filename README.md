# staff database management
Download project
Copy it to its own folder in webroot.
 
database/staff.sql is a blank database.
restore it as "staff" database
Create a user with SCUD rights for only this database

read conf/staff.conf.example and make necessary changes in it after copying it outside webroot

for each form, a new folder may be created and managed as independent project


2016-12-17 
==========
blank database and scripts were taken from gmcsurat.edu.in
Thhere after Changes made in silver computer
Same, needs to be done in gmcsurat.edu.in
==========
deleted TABLE20			-->done
primary key defined in photo_id_proof_type -->done
blank value added in photo_id_proof_type -->done
		(photo_id_proof_type , and institute , designation ,appoi_type, dept table needs to be filled to preven Notice issued when scripts are run)

save.php changed to effect save button for adding experience and qualifications -->done
added check.php for declaration -->done
Changed Menu php to add office menu and check.php -->done


Added index_office, start_office, change_pass_office php (in common folder) -->done
Changed Menu php to add office menu -->done
Changed index.php of /staff (root) folder to show login link for office staff -->Done
Changed common php (added database check and connect_office function, changed connect() to syncronize with office login -->done
Created office_staff table, added dummy entry -->Done
changed index_office to differenciate it in color from index.php(for teaching staff) -->done


2016-12-21
==========
sr/view_data.php added		-->done
view_data table added		-->done
edited common/menu.php to view data added in staff_menu()		-->done

2016-12-22
==========
date difference function altered in common.php	-->done

2016-12-24
==========
logout.php and logout() in common.php fixed to get starting index.php	-->done
common/index.php modified to include message by GET variable			-->done

