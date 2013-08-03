<?php require_once("includes/connection.php"); ?>
<?php require_once("includes/functions.php"); ?>

<?php
	$errors = array();
	
	// Form Validation
	$required_fields = array('menu_name', 'position', 'visible');
	foreach($required_fields as $fieldname) {
		//check whether value has been set or whether its empty
		//and record it to keep track of  errors.
		if (!isset($_POST[$fieldname]) || empty($_POST[$fieldname])) {
			$errors[] = $fieldname;
		}
	}
	
	//set array variable fields with length
	$fields_with_lengths = array('menu_name' => 30);
	//for each of those values as an array check the max length
	foreach($fields_with_lengths as $fieldname => $maxlength ) {
		if (strlen(trim(mysql_prep($_POST[$fieldname]))) > $maxlength) { $errors[] = $fieldname; }
	}
	
	if (!empty($errors)) {
		//then redirect to new subject.php
		redirect_to("new_subject.php");
	}
?>

<?php
	$menu_name = mysql_prep($_POST['menu_name']); //this will catch values already sent from form & store it in POST[] array
	$position = mysql_prep($_POST['position']);             //this will catch values already sent from form & store it in POST[] array
	$visible = mysql_prep($_POST['visible']);                   //this will catch values already sent from form & store it in POST[] array
?>
<?php
	//construction of sql query to create records
	$query = "INSERT INTO subjects (
				menu_name, position, visible
			) VALUES (
				'{$menu_name}', {$position}, {$visible}
			)"; //you must place single quotes inside strings
	$result = mysql_query($query, $connection);  //store query result in result variable
	//test result for success or failure
	if ($result) {
		// Success!
		//redirect to content.php
		header("Location: content.php");
		exit;
	} else {
		// Display error message.
		echo "<p>Subject creation failed.</p>";
		echo "<p>" . mysql_error() . "</p>";
	}
?>

<?php mysql_close($connection); ?>