<?php

/* CSS properties unique to chora for the burnt orange theme.
 * This file is parsed by css.php, and used to produce a stylesheet.
 */

// Normally we use burnt orange for the select background.  But here, since
// we use burnt orange for the text color, if we use burnt orange for the
// background you can't see the text!  So we make it a darker blue to stand
// out against the light blue colors.

$css['.selected']['background-color'] = '#bbcbff';
