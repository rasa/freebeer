<?php

// $CVSHeader: _freebeer/lib/BinarySearch.php,v 1.2 2004/03/07 17:51:17 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

/*!
	\file BinarySearch.php
	\brief Abstract class for binary search classes
*/

/*!
	\enum FB_BINARY_SEARCH_EXACT
	Return an exact match if found, otherwise false (default)
*/ 
define('FB_BINARY_SEARCH_EXACT',	0);

/*!
	\enum FB_BINARY_SEARCH_LOWER
	Return an exact match if found, otherwise the nearest match lower than the search value
*/
define('FB_BINARY_SEARCH_LOWER', 	1);

/*!
	\enum FB_BINARY_SEARCH_HIGHER
	Return an exact match if found, otherwise the nearest match higher than the search value
*/
define('FB_BINARY_SEARCH_HIGHER',	2);

/*!
	\class fbBinarySearch
	\brief Abstract class for binary search classes
	
	\abstract
	\static
*/
class fbBinarySearch {
	/*!
		Perform a binary search on a sorted array or hash
		
		\param $needle \c mixed
		\param $haystack \c array
		\param $sort_flag \c bool
		\param $compare_function \c string

		\todo add $fuzzy parameter
		\static
	*/
	function search($needle, $haystack, $sort_flag = null, $compare_function = null) {
	}

}

?>
