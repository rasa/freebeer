<?php

// $CVSHeader: _freebeer/www/demo/HTML.Calender.php,v 1.1.1.1 2004/01/18 00:12:07 ross Exp $

// Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

require_once './_demo.php';

require_once FREEBEER_BASE . '/lib/Pear/Pear.php';

require_once FREEBEER_BASE . '/lib/HTML/Calendar.php';

require_once 'HTML/Form.php';

echo html_header_demo('fbHTML_Calendar Class');

if (preg_match('/wget/i', $_SERVER['HTTP_USER_AGENT'])) {
	echo "</body></html>";
	exit(0);
}

class MyHTML_Calendar extends fbHTML_Calendar {
	function drawCellText($days, $week, $col) {
		$rv = '';
		$len =  rand(0,16);
		while (strlen($rv) < $len)
			$rv .= substr(md5(rand()), 1, rand(1,8)). " ";
		return $rv;
	}
}

extract($_REQUEST);

$day	= isset($day)	? $day : null;
$month	= isset($month)	? $month : null;
$year	= isset($year)	? $year : null;

if (!isset($class)) {
	$class = 't';
}

if (!isset($otherMonths)) {
	$otherMonths = 't';
}

if (!isset($navLinks)) {
	$navLinks = 't';
}

if (!isset($size)) {
	$size = HTML_CALENDAR_FULL;
}

$tf = array('t'=>'True', 'f'=>'False');

$f = new HTML_Form($_SERVER['PHP_SELF'], 'post');
//$f->start();
$f->addSelect('class', 'Class:', $tf, $class);
$f->addSelect('navLinks', 'Nav Links:', $tf, $navLinks);
$f->addSelect('otherMonths', 'Other Months:', $tf, $otherMonths);
$f->addSelect('size', 'Size:', array(HTML_CALENDAR_FULL => 'Full', HTML_CALENDAR_TINY => 'Tiny'), $size);
$f->addSubmit();
//$f->end();

$cal = new MyHTML_Calendar();
$cal->setClass($class == 't');
$cal->setNavLinks($navLinks == 't');
$cal->setOtherMonths($otherMonths == 't');
$cal->setSize($size);
$html = $cal->toHTML($day, $month, $year);

$f->display();

?>
<style type="text/css">
	#html_cal_table {
	}

	#html_cal_cell_table {
	}

	#html_cal_cell_label {
	}

	#html_cal_cell {
	}

	xth {
	  font-family: verdana,arial,helvetica,sans-serif;
	  font-size: 10pt;
	}

	xtd {
	  font-family: verdana,arial,helvetica,sans-serif;
	  font-size: 10pt;
	}
</style>
<?php echo $html; ?>
  </body>
</html>
  