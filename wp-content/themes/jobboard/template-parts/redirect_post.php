<?php
if(isset($_POST['submit'])){
// Fetching variables of the form which travels in URL
$name = $_POST['job_title'];
$email = $_POST['job_region'];
$contact = $_POST['job_experience'];
$address = $_POST['job_type'];
$rate = $_POST['job_sallary'];
$summary = $_POST['job_summary'];
$overview = $_POST['job_overview'];
$description = $_POST['job_description'];

if($name !=''&& $email !=''&& $contact !=''&& $address !='' && rate !='' && summary !='' && overview !='')
{
// To redirect form on a particular page
header("Location:http://www.trainersforathletes.com/beta/
}
else{
?><span><?php echo "Please fill all fields.....!!!!!!!!!!!!";?></span> <?php
}
}
?>