<?php
require('../simplehtmldom_1_5/simple_html_dom.php');
require_once 'medoo.php';

/* first and last CRN numbers */
$start_crn = 10002;
$end_crn   = 15339;

$bad_rows = array("<td colspan=\"2\"><br>  <br><br></td>");

$filter = array("Registration Term:",
				 "CRN:",
				 "Subject:",
				 "Title:",
				 "Course Description:");

$database = new medoo();

$err = $database->error();

$database->query("DROP TABLE IF EXISTS courses;");
$database->query("CREATE TABLE IF NOT EXISTS courses(
				      crn integer primary key NOT NULL,
				      term text NOT NULL,
				      subject text NOT NULL,
				      title text NOT NULL,
				      description text NOT NULL
				      );");

$err = $database->error();
if ($err[2] != NULL) {
	exit("Database error: " . $err[2] . PHP_EOL);
}

for ($x = $start_crn; $x <= $end_crn; $x++) {

	echo "Current CRN:  " . $x . PHP_EOL;

	$table = array();

	/* fetch html of current CRN's webpage */
	$html  = file_get_html('http://central.carleton.ca/prod/bwysched.p_display_course?wsea_code=EXT&term_code=201510&disp=2912074&crn=' . $x);

	foreach($html->find('tr') as $row) {
	    $raw_key = $row->find('td',0);
	    // echo "RAW KEY:  " . $raw_key . PHP_EOL;
	    if (!in_array($raw_key, $bad_rows)) {
	    	$key         = $row->find('td',0)->plaintext;
	    	// echo "KEY:  " . $key . PHP_EOL;

	    	if (in_array($key, $filter)) {
	    		$value       = $row->find('td',1)->plaintext;
		    	// echo "VALUE:  " . $value . PHP_EOL;

		    	$table[$key] = $value;
	    	}
	    }
	}

	/* insert selected values into courses table in db */
	$database->insert("courses", [
		"crn"         => $table["CRN:"],
		"term"        => $table["Registration Term:"],
		"subject"     => $table["Subject:"],
		"title"       => $table["Title:"],
		"description" => $table["Course Description:"]
	]);
}

$err = $database->error();
if ($err[2] != NULL) {
	exit("Database error: " . $err[2] . PHP_EOL);
}

?>