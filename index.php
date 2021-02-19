<?php

//if(isset($_GET['message'])){echo "<h3 align=center style='color:red;'>".$_GET['message']."</h3>";}
echo '<html lang="en" >
<head>
  <title>Login Form</title>
      <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <body>
	  <div >
		 <img src="image/college_logo.png"  width="15%" height="130">
	     <img src="image/hostel.png" width="65%" height="130">
	     <img src="image/gujarat.png"  width="15%" height="130">
	  </div>
	
	</form>
</body>';

echo '<br>
     <div id="container">
       <div  id="left">
          <h1 style=\'color:white;\'>Staff Database</h1><br>';
          
if(isset($_GET['message'])){echo "<h3 align=left style='color:red;'>".$_GET['message']."</h3>";}
          
echo '          <a href="common/index.php" class="b1">Login For Teaching Staff</a><br><br><br><br>
          <a href="common/index_office.php" class="b1">Login For Administrative Office Staff</a><br><br><br><br>
           <a href="" class="b1">HOME</a><br><br>
       </div>
       <div  id="right">
           
          <center><h2 style=\'color:#ff0000;\' >Instruction for filling Declaration form 2018-19 </h2></center>
        <ol>
          <h3><li>If your current institute is changed due to transfer perform following steps after login.</li></h3>
          <h3><li> Edit last entry to replace "till_date" to date of releaving.</li></h3>
          <h3><li> Add current institute experience with till_date entry.</li></h3>
          <h3><li> After complating declaration form use Declaration->Print menu to print.</li></h3>
          <h3><li> Read printed declaration and fill remaining details by pen. Apply photo and signature.</li></h3>
        </ol>
       </div>
     </div>';


//echo  $_SERVER['REMOTE_ADDR'];
?>
