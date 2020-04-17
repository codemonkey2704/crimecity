<?php
include 'header.php';

if ($_POST['submit']) {
	if ($user_class->admin != 1) {
	  echo Message("You are not authorized to be here.");
	  include 'footer.php';
	  die();
	}
	$result=  DB::run("INSERT INTO `todo` (`when`, `text`, `status`)"."VALUES ('".$_POST['when']."', '".$_POST['text']."', '".$_POST['status']."')");

}
?>
<tr><td class="contenthead">Publius's To-Do List</td></tr>
<tr><td class="contentcontent">Here you can view what Publius currently  has in the works for GRPG.</td></tr>
<tr><td class="contentcontent">
<?php
$result =  DB::run("SELECT * FROM `todo`");
echo "<table cellpadding='8'><tr><td><b>Date Added</b></td><td><b>Goal</b></td><td><b>Status</b></td></tr>";
while($line = $result->fetch(PDO::FETCH_ASSOC)) {
	echo "<tr><td>".$line['when']."</td><td>".$line['text']."</td><td>[".$line['status']."]</td></tr>";
}
echo "</table>";
?>
</td></tr>
<?php
if ($user_class->admin == 1){
?>
<tr><td class="contenthead">Add Item</td></tr>
<tr><td class="contentcontent">
<form method='post' class="todo-form">
<input type='text' name='when' size='10' maxlength='75' value='<?= $time ?>'> [When]<br />it ou
<input type='text' name='status'   size='10' maxlength='75' value='0%'> [Status]<br/>
<input type='submit' name='submit' value='Add Item'></td></tr>
</form>
</td></tr>
<?php
}
include 'footer.php';
?>