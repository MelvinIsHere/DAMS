<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>import data to database using excel</title>
</head>
<body>
	<table>
		<thead>
			<tr>
				<th>Full name</th>
				<th>Course Code</th>
				<th>Section</th>
				<th>No. of Students</th>
				<th>Total Units</th>
				<th>Lec hrs/week</th>
				<th>RLe hrs/week</th>
				<th>Lab hrs/week</th>
				<th>Total hrs/week</th>
				<th>Course Title</th>
				<th>Regular hrs</th>
				<th>Overload</th>
				<th>No. of Prep</th>
				<th>Type</th>
				<th>College</th>
				<th>Campus</th>
				<th>Semester</th>
				<th>Academic Year</th>
			</tr>
		</thead>
			<tbody>
			
		
	  <?php 
                                         $conn = new mysqli("localhost","root","","dams");
                                        if ($conn->connect_error) {
                                                die("Connection failed : " . $conn->connect_error);
                                        }
                                            $sql = "SELECT * FROM data_start";
                                            $result = $conn->query($sql);
                                            while($row = mysqli_fetch_array($result)){
                                                $id = $row['id'];
                                                $full_name = $row['first_name'] . ' ' . $row['last_name'] ;
                                                $course_code = $row['course_code'];
                                                $section = $row['section'];
                                                $no_stud = $row['no_of_students'];
                                                $units = $row['total_units'];
                                                $lec = $row['lec_hrs_wk'];
                                                $rle = $row['rle_hrs_wk'];
                                                $lab = $row['lab_hrs_wk'];
                                                $total_hrs = $row['total_hrs_wk'];
                                                $course_title = $row['course_title'];
                                                $regular_hrs = $row['regular_hrs'];
                                                $overload = $row['overload'];
                                                $no_prep = $row['no_of_prep'];
                                                $type = $row['type'];
                                                $college = $row['college'];
                                                $campus = $row['campus'];
                                                $semester = $row['semester'];
                                                $academic_year = $row['academic_year_start'] . ' ' . $row['academic_year_end'];
                                            
		?>
		<tr>
			<td><?php  echo $full_name ?></td>
			<td><?php  echo $course_code ?></td>
			<td><?php  echo $section ?></td>
			<td><?php  echo $no_stud ?></td>
			<td><?php  echo $units ?></td>
			<td><?php  echo $lec ?></td>
			<td><?php  echo $rle ?></td>
			<td><?php  echo $lab ?></td>
			<td><?php  echo $total_hrs ?></td>
			<td><?php  echo $course_title ?></td>
			<td><?php  echo $regular_hrs ?></td>
			<td><?php  echo $overload ?></td>
			<td><?php  echo $no_prep ?></td>
			<td><?php  echo $type ?></td>
			<td><?php  echo $college ?></td>
			<td><?php  echo $campus ?></td>
			<td><?php  echo $semester ?></td>
			<td><?php  echo $academic_year ?></td>
			

			
				
		</tr>
	
		<?php }?>
		</tbody>
	
	</table>
	<form method="POST" enctype="multipart/form-data" action="">
		<input type="file" name="excel">
		<button type="submit" name="import">Import</button>
	</form>
	<?php 
	  $conn = new mysqli("localhost","root","","dams");
                                        if ($conn->connect_error) {
                                                die("Connection failed : " . $conn->connect_error);
                                        }
		if(isset($_POST['import'])){
			$filename = $_FILES["excel"]["name"];
			$fileExtension = explode('.',$filename);
			$fileExtension = strtolower(end($fileExtension));

			$newFileName = date("Y.m.d")." - ". date("h.i.sa"). "." . $fileExtension;

			$targetDirectory = "uploads/".$newFileName;
			move_uploaded_file($_FILES['excel']['tmp_name'], $targetDirectory);
			echo $targetDirectory;
			

			require "excelReader/excel_reader2.php";
			require "excelReader/SpreadSheetReader.php";

			$reader = new SpreadsheetReader($targetDirectory);
			foreach($reader as $key => $row){
				 $first_name = $row[0]; 
				 $last_name = $row[1]; 
				 $course_code = $row[2];
                 $section = $row[3];
				 $no_stud = $row[4];
                 $units = $row[5];
                 $lec = $row[6];
                 $rle = $row[7];
                 $lab = $row[8];
                 $total_hrs = $row[9];
                 $course_title = $row[10];
                 $regular_hrs = $row[11];
                 $overload = $row[12];
                 $no_prep = $row[13];
                 $type = $row[14];
                 $college = $row[15];
                 $campus = $row[16];
                 $semester = $row[17];
                 $academic_year_start = $row[18];
                 $academic_year_end = $row[19];

                  $insert_query =mysqli_query($conn,"INSERT INTO data_start(first_name,last_name,course_code,section,no_of_students,total_units,lec_hrs_wk,rle_hrs_wk,lab_hrs_wk,total_hrs_wk,course_title,regular_hrs,overload,no_of_prep, type,college,campus, semester,academic_year_start,academic_year_end) VALUES('$first_name','$last_name','$course_code','$section','$no_stud','$units','$lec','$rle','$lab','$total_hrs','$course_title','$regular_hrs','$overload','$no_prep','$type','$college','$campus','$semester','$academic_year_start','$academic_year_end')");
                 
				// 	else{
				// 		 	echo
				// 	"<script>
				// 	alert('Not Imported');
				// 	document.location.href='a.php';
				// 		</script>";

				// 	}
                  }
                   if ($insert_query) {
                  	echo
					"<script>
					alert('Successfully Imported');
					document.location.href='';
						</script>";
					}
					else{
							echo
					"<script>
					alert('Import failed'" . mysqli_error($conn) .  ");
					document.location.href='';
						</script> );";
					
					}
						
					
                 
			}
			

	?>

</body>
</html>