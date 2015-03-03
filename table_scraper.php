<?php
require('../simplehtmldom_1_5/simple_html_dom.php');

$all_courses = array();

$start_crn = 10002;
$end_crn   = 15339;
$num_courses = $end_crn - $start_crn;

$to_keep = array("Registration Term:",
				 "CRN:",
				 "Subject:",
				 "Title:",
				 "Course Description:");

for ($x = $start_crn; $x <= 10100; $x++) {

	echo "Current CRN:  " . $x . PHP_EOL;

	$table = array();
	$html  = file_get_html('http://central.carleton.ca/prod/bwysched.p_display_course?wsea_code=EXT&term_code=201510&disp=2912074&crn=' . $x);

	foreach($html->find('tr') as $row) {
	    $key         = $row->find('td',0)->plaintext;
	    $value       = $row->find('td',1)->plaintext;
	    $table[$key] = $value;
	    $row_num++;
	}

	$filtered = array_intersect_key($table, array_flip($to_keep));

	$all_courses[$x] = $filtered;
}

echo '<pre>';
print_r($all_courses);
echo '</pre>';

?>