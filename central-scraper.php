<?php
require('../simplehtmldom_1_5/simple_html_dom.php');
require_once 'medoo.php';

/* first and last CRN numbers */
$start_crn = 10002;
$end_crn   = 15339;

/* old filter for $table array */
// $to_keep = array("Registration Term:",
// 				 "CRN:",
// 				 "Subject:",
// 				 "Title:",
// 				 "Course Description:");

$database = new medoo();

/* only loop over first ~100 courses for now - slow :( */
for ($x = $start_crn; $x <= 10100; $x++) {

	echo "Current CRN:  " . $x . PHP_EOL;

	$table = array();

	/* fetch html of current CRN's webpage */
	$html  = file_get_html('http://central.carleton.ca/prod/bwysched.p_display_course?wsea_code=EXT&term_code=201510&disp=2912074&crn=' . $x);

	foreach($html->find('tr') as $row) {
	    $key         = $row->find('td',0)->plaintext;
	    $value       = $row->find('td',1)->plaintext;
	    $table[$key] = $value;
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

/* print db errors */
print_r(var_dump($database->error()));

?>