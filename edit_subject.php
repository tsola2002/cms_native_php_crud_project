<?php require_once("includes/connection.php"); ?>
<?php require_once("includes/functions.php"); ?>


<?php 
	//check for valid subject id frm get link(if its an integer)
	/*if (intval($_GET['subj']) == 0) {
			redirect_to('content.php');
		}*/

	if(isset($_POST['submit']))  
		{
			$errors = array();
	
			// Form Validation
			$required_fields = array('menu_name', 'position', 'visible');
			foreach($required_fields as $fieldname) 
				{
				//check whether value has been set or whether its empty
				//and record it to keep track of  errors.
				if (!isset($_POST[$fieldname]) || (empty($_POST[$fieldname]) && $_POST[$fieldname] != 0))
					{
					$errors[] = $fieldname;
					}
				}
	
			//set array variable fields with length
			$fields_with_lengths = array('menu_name' => 30);
			//for each of those values as an array check the max length
			foreach($fields_with_lengths as $fieldname => $maxlength ) 
				{
				if (strlen(trim(mysql_prep($_POST[$fieldname]))) > $maxlength) { $errors[] = $fieldname; }
				}
				
			
			if (empty($errors)) 
				{
				//if errors are empty
				// Perform Update
				$id = mysql_prep($_GET['subj']);                       //this will catch value already sent from get link and store it in GET[] array
				$menu_name = mysql_prep($_POST['menu_name']); //this will catch values already sent from form & store it in POST[] array
				$position = mysql_prep($_POST['position']);             //this will catch values already sent from form & store it in POST[] array
				$visible = mysql_prep($_POST['visible']);                 //this will catch values already sent from form & store it in POST[] array
				
				
				//create query string to update subjects
				$query = "UPDATE subjects SET 
							  menu_name = '{$menu_name}', 
						      position = {$position}, 
							  visible = {$visible} 
						      WHERE id = {$id}";
				$result = mysql_query($query, $connection);
				if (mysql_affected_rows() == 1) 
					{
					// Success
					//set message variable
					$message = "The subject was successfully updated.";
					} else 
						{
						// Failed display message
						$message = "The subject update failed.";
					//	$message .= "<br />". mysql_error();
						}
				
				} else 
					{
					// Errors occurred
						$message = "There were " . count($errors) . " errors in the form.";
					}
	
	
		
		}  //end of  if(isset($_POST['submit']))
			
	
?>

<?php find_selected_page(); // retrieval of superglobal from functions.php ?>
<?php include("includes/header.php"); ?>
<table id="structure">
	<tr>
		<td id="navigation">
			<?php echo navigation($sel_subject, $sel_page); //uses navigation layout function in functions.php ?>
		</td>
		<td id="page">
        		<!--echo subject into the page-->
				<h2>Edit Subject: <?php echo $sel_subject['menu_name']; ?></h2>
                <?php if (!empty($message)) 
								
								{
								//if message variable is available display error message
								echo "<p class=\"message\">" . $message . "</p>";
								} 
				?>
                <?php
				// output a list of the fields that had errors
				if (!empty($errors)) 
					{
					echo "<p class=\"errors\">";
					echo "Please review the following fields:<br />";
					foreach($errors as $error) 
						{
						echo " - " . $error . "<br />";
						}
					echo "</p>";
					}
				?>
                <!--send  subject id along with  form page-->
			<form action="edit_subject.php?subj=<?php echo urlencode($sel_subject['id']); ?>" method="post">
				<p>Subject name: 
					<input type="text" name="menu_name" value="<?php echo $sel_subject['menu_name']; ?>" id="menu_name" />
				</p>
				<p>Position: 
					<select name="position">
						<?php
							$subject_set = get_all_subjects();
							$subject_count = mysql_num_rows($subject_set);
							// $subject_count + 1 b/c we are adding a subject
							for($count=1; $count <= $subject_count+1; $count++) {
								echo "<option value=\"{$count}\" ";
								if ($sel_subject['position'] == $count) {
									//echo selected if count equals position
									echo " selected";
								} 
								echo ">{$count}</option>";
							}
						?>
					</select>
				</p>
				<p>Visible: 
					<input type="radio" name="visible" value="0" <?php 
					//echo checked when visible equals zero
					if ($sel_subject['visible'] == 0) { echo " checked"; } 
					?> /> No
					&nbsp;
					<input type="radio" name="visible" value="1"  <?php 
					if ($sel_subject['visible'] == 1) { echo " checked"; } 
					?> /> Yes
				</p>
				<input type="submit" name= "submit" value="Edit Subject" />
                &nbsp;&nbsp;
				<a href="delete_subject.php?subj=<?php echo urlencode($sel_subject['id']); ?>" onclick="return confirm('Are you sure?');">Delete Subject</a>
			</form>
			<br />
			<a href="content.php">Cancel</a>
		</td>
	</tr>
</table>
<?php require("includes/footer.php"); ?>
