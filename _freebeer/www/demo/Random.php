<?php

// $CVSHeader: _freebeer/www/demo/Random.php,v 1.1.1.1 2004/01/18 00:12:07 ross Exp $

// Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

require_once './_demo.php';

require_once FREEBEER_BASE . '/lib/Random.php';

require_once FREEBEER_BASE . '/lib/Random/Rand.php';
require_once FREEBEER_BASE . '/lib/Random/MT_Rand.php';
require_once FREEBEER_BASE . '/lib/Random/DevRandom.php';
require_once FREEBEER_BASE . '/lib/Random/DevUrandom.php';
require_once FREEBEER_BASE . '/lib/Random/Win32.php';
require_once FREEBEER_BASE . '/lib/Random/LCG.php';
require_once FREEBEER_BASE . '/lib/Random/GMP.php';

echo html_header_demo('fbRandom_Best Class');

echo "<pre>";
echo "getrandmax=",getrandmax(),"\n";
echo "mt_getrandmax=",mt_getrandmax(),"\n";

$random = isset($_REQUEST['random']) ? $_REQUEST['random'] : false;

$choices = array(
	'fbRandom_MT_Rand',
	'fbRandom_Rand',
	'fbRandom_DevRandom',
	'fbRandom_DevUrandom',
	'fbRandom_Win32',
	'fbRandom_LCG',
	'fbRandom_GMP',
	'blocking',
	'seedable',
	'blocking_seedable',
);

$selected = array();
foreach ($choices as $choice) {
	$selected[$choice] = $random == $choice ? ' selected="selected" ' : '';
}

switch ($random) {
	case 'fbRandom_Rand':
		$fb_random = &new fbRandom_Rand();
		break;

	case 'fbRandom_MT_Rand':
		$fb_random = &new fbRandom_MT_Rand();
		break;

	case 'fbRandom_DevRandom':
		$fb_random = &new fbRandom_DevRandom();
		break;

	case 'fbRandom_DevUrandom':
		$fb_random = &new fbRandom_DevUrandom();
		break;

	case 'fbRandom_Win32':
		$fb_random = &new fbRandom_Win32();
		break;

	case 'fbRandom_LCG':
		$fb_random = &new fbRandom_LCG();
		break;

	case 'fbRandom_GMP':
		$fb_random = &new fbRandom_GMP();
		break;

	case 'non_blocking':
		$fb_random = &fbRandom::getInstance(false);
		break;

	case 'blocking':
		$fb_random = &fbRandom::getInstance(true);
		break;

	case 'seedable':
		$fb_random = &fbRandom::getInstance(null, 1);
		break;

	case 'blocking_seedable':
		$fb_random = &fbRandom::getInstance(true, 1);
		break;

	default:
		$fb_random = &new fbRandom_MT_Rand();
		break;
}

$iterations = isset($_REQUEST['iterations']) ? (int) $_REQUEST['iterations'] : 10;

$length		= isset($_REQUEST['length']) ? (int) $_REQUEST['length'] : 10;

$seed		= isset($_REQUEST['seed']) ? $_REQUEST['seed'] : null;

if ($seed != null) {
	$fb_random->setSeed($seed);
}

echo "
<form>
RNG:
<select name='random'>
<option {$selected['fbRandom_Rand']}   		value='fbRandom_Rand'>fbRandom_Rand (rand())</option>
<option {$selected['fbRandom_MT_Rand']}    	value='fbRandom_MT_Rand'>fbRandom_MT_Rand (mt_rand())</option>
<option {$selected['fbRandom_DevRandom']}   value='fbRandom_DevRandom'>fbRandom_DevRandom (/dev/random)</option>
<option {$selected['fbRandom_DevUrandom']}  value='fbRandom_DevUrandom'>fbRandom_DevUrandom (/dev/urandom)</option>
<option {$selected['fbRandom_Win32']}		value='fbRandom_Win32'>fbRandom_Win32</option>
<option {$selected['fbRandom_LCG']}			value='fbRandom_LCG'>fbRandom_LCG</option>
<option {$selected['fbRandom_GMP']}			value='fbRandom_GMP'>fbRandom_GMP</option>
<option {$selected['blocking']}          	value='blocking'>Blocking</option>
<option {$selected['seedable']}          	value='seedable'>Seedable</option>
<option {$selected['blocking_seedable']}    value='blocking_seedable'>Blocking & Seedable</option>
</select>
<br />
Iterations:
<input type='text' name='iterations' value='{$iterations}' />
<br />
Length:
<input type='text' name='length' value='{$length}' />
<br />
Seed:
<input type='text' name='seed' value='{$seed}' />
<br />
<input type='submit' name='submit' value='Select' />
</form>
<pre>
";

echo 'Using ', get_class($fb_random), ' class<br /><br /><br />';

for ($i = 0; $i < $iterations; ++$i) {
	$b = $fb_random->nextBoolean();
	assert('is_bool($b)');
	echo 'nextBoolean()=         ', $b, ' (', $b ? 'true' : 'false',")\n";
}
for ($i = 0; $i < $iterations; ++$i) {
	$rv = $fb_random->nextDouble();
	assert('is_double($rv)');
	assert('$rv >= 0.0');
	assert('$rv < 1.0');
	echo 'nextDouble()=          ',$rv,"\n";
}
for ($i = 0; $i < $iterations; ++$i) {
	$rv = $fb_random->nextInt();
	assert('is_int($rv)');
	echo 'nextInt()=             ',$rv,"\n";
}
for ($i = 0; $i < $iterations; ++$i) {
	$rv = $fb_random->nextInt(25);
	assert('is_int($rv)');
	assert('$rv >= 0');
	assert('$rv < 25');
	echo 'nextInt(25)=           ',$rv,"\n";
}
for ($i = 0; $i < $iterations; ++$i) {
	$rv = $fb_random->nextInt(0, 10);
	assert('is_int($rv)');
	assert('$rv >= 0');
	assert('$rv < 10');
	echo 'nextInt(0, 10)=        ',$rv,"\n";
}
for ($i = 0; $i < $iterations; ++$i) {
	$rv = $fb_random->nextInt(-10, 0);
	assert('is_int($rv)');
	assert('$rv >= -10');
	assert('$rv < 0');
	echo 'nextInt(-10, 0)=       ',$rv,"\n";
}
for ($i = 0; $i < $iterations; ++$i) {
	$rv = $fb_random->nextInt(-20, -10);
	assert('is_int($rv)');
	assert('$rv >= -20');
	assert('$rv < -10');
	echo 'nextInt(-20, -10)=     ',$rv,"\n";
}
for ($i = 0; $i < $iterations; ++$i) {
	$rv = $fb_random->nextInt(-1, 2);
	assert('is_int($rv)');
	assert('$rv >= -1');
	assert('$rv < 2');
	echo 'nextInt(-1, 2)=        ',$rv,"\n";
}
for ($i = 0; $i < $iterations; ++$i) {
	$rv = $fb_random->nextGaussian();
	assert('is_double($rv)');
	echo 'nextGaussian()=        ',$rv,"\n";
}
for ($i = 0; $i < $iterations; ++$i) {
	$rv = $fb_random->nextSalt();
	assert('$rv');
	assert('is_string($rv)');
	assert('strlen($rv) == 2');
	echo 'nextSalt()=            ',$rv,"\n";
}
for ($i = 0; $i < $iterations; ++$i) {
	$rv = $fb_random->nextBase64String($length);
	assert('$rv');
	assert('is_string($rv)');
	assert('strlen($rv) == $length');
	echo 'nextBase64String()=    ',htmlspecialchars($rv),"\n";
}
for ($i = 0; $i < $iterations; ++$i) {
	$rv = $fb_random->nextURL64String($length);
	assert('$rv');
	assert('is_string($rv)');
	assert('strlen($rv) == $length');
	echo 'nextURL64String()=     ',htmlspecialchars($rv),"\n";
}
for ($i = 0; $i < $iterations; ++$i) {
	$rv = $fb_random->nextBase95String($length);
	assert('$rv');
	assert('is_string($rv)');
	assert('strlen($rv) == $length');

	echo 'nextBase95String()=    ',htmlspecialchars($rv),"\n";
	if (strlen($rv) != $length) {
		echo 'strlen($rv)=',strlen($rv),"\n";
		echo 'length=',$length,"\n";
		for ($i = 0; $i < strlen($rv); ++$i) {
			echo '$rv[',$i,']=',ord($rv[$i]),"\n";
		}

	}

}
for ($i = 0; $i < $iterations; ++$i) {
	$rv = $fb_random->nextPrintableString($length);
	assert('$rv');
	assert('is_string($rv)');
	assert('strlen($rv) == $length');
	echo 'nextPrintableString()= ',htmlspecialchars($rv),"\n";
}
for ($i = 0; $i < $iterations; ++$i) {
	$rv = $fb_random->nextString($length);
	assert('$rv');
	assert('is_string($rv)');
	assert('strlen($rv) == $length');
	echo 'nextString()=          ',htmlspecialchars($rv),"\n";
}
for ($i = 0; $i < $iterations; ++$i) {
	$rv = $fb_random->nextBytes($length);
	assert('$rv');
	assert('is_string($rv)');
	assert('strlen($rv) == $length');
	echo 'nextBytes()=           ',htmlspecialchars($rv),"\n";
}

?>
</pre>
<address>
$CVSHeader: _freebeer/www/demo/Random.php,v 1.1.1.1 2004/01/18 00:12:07 ross Exp $
</address>

</body>
</html>
