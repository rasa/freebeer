<?php 

// $CVSHeader: _freebeer/lib/HTML/Calendar.php,v 1.1.1.1 2004/01/18 00:12:04 ross Exp $

/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | PHP Version 4                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2002 The PHP Group                                     |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.0 of the PHP license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available at through the world-wide-web at                           |
// | http://www.php.net/license/2_02.txt.                                 |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Author: Ross Smith <ross@smithii.com>                                |
// +----------------------------------------------------------------------+
//
// $Id$

defined('DATE_CALC_BEGIN_WEEKDAY') ||
 @define('DATE_CALC_BEGIN_WEEKDAY', 0);

include_once 'Date/Calc.php';
include_once 'HTML/Table.php';

/**
 * HTML_Calendar is a calendar class used to display a calendar.
 *
 * @author Ross Smith <ross@smithii.com>
 * @version 1.0
 */

@define('HTML_CALENDAR_FULL', 1);
@define('HTML_CALENDAR_TINY', 2);

class fbHTML_Calendar
{
    /**
    * Array of options
    *
    * @var  hash
    * @access private
    */
	var $_options = array(
		'title_format'			=> '<b>%B,&nbsp;%Y</b>',
	);

    /**
    * Array of table and cell attributes
    * @see \ref setAttributes
    *
    * @var  hash
    * @access private
    */
	var $_attributes = array(
		'cell'			=> array(
			'valign'		=> 'top',
		),
		'cell_thisMonth'	=> array(
		),
		'cell_other_month'	=> array(
			'bgcolor'		=> '#e7e7e7',
		),
		'cell_today'		=> array(
			'bgcolor'		=> '#fff0f0',
		),
		'day'			=> array(
			'border'		=> 0,
			'cellpadding'	=> 0,
			'cellspacing'	=> 0,
			'height'		=> '100%',
			'valign'		=> 'top',
			'width'			=> '100%'
		),
		'day_thisMonth'	=> array(
		),
		'day_other_month'	=> array(
		),
		'day_today'		=> array(
		),
		'label'	=> array(
			'align'			=> 'right',
			'bgcolor'		=> '#f0f0f0',
			'style'			=> 'font-size: smaller;',
		),
		'label_thisMonth'	=> array(
		),
		'label_other_month'	=> array(
		),
		'label_today'		=> array(
			'bgcolor'		=> '#ffe8e8',
		),
		'table' 		=> array(
			'border'		=> 1,
			'cellpadding'	=> 0,
			'cellspacing'	=> 1,
			'cols'			=> 7,
			'width'			=> '200'
		),
		'text'			=> array(
			'valign'		=> 'bottom',
		),
		'text_thisMonth'	=> array(
		),
		'text_other_month'	=> array(
		),
		'text_today'		=> array(
		),
		'title'			=> array(
			'align'			=> 'center',
			'bgcolor'		=> '#eeeeee',
			'colspan'		=> 7,
		),
		'weekday'		=> array(
			'align'			=> 'center',
			'bgcolor'		=> '#d3d3d3',
		)
	);

    /**
    * An array of weekday names
    *
    * @var    array
    * @access protected
    */
	var $_weekDays;
	
    /**
    * The 'days' value for today
    *
    * @var  int
    * @access protected
    */
	var $_todayDays;

    /**
    * The current month
    *
    * @var  int
    * @access protected
    */
	var $_thisMonth;

    /**
    * The calendar mode
	* HTML_CALENDAR_FULL: Full calendar, days are not links
	* HTML_CALENDAR_TINY: Tiny calendar, days are links
	*
    * @var  int
    * @access private
    */
	var $_size = HTML_CALENDAR_FULL;

    /**
    * Flag to display the previous and next month/year navigation links
    *
    * @var  int
    * @access private
    */
	var $_navLinks = true;

    /**
    * Flag to display previous and next months
    *
    * @var  int
    * @access private
    */
	var $_otherMonths = true;

    /**
    * The URL path portion
    *
    * @var    string
    * @access private
    */
	var $_path;
	
    /**
    * The URL query parameter
    *
    * @var    string
    * @access private
    */
	var $_query = 'year=%Y&month=%m&day=%d';

	/**
	* Constructor.
	*
	* @param int
	* HTML_CALENDAR_FULL: Full calendar, days are not links
	* HTML_CALENDAR_TINY: Tiny calendar, days are links
	*
	* @access public
	*/
	function fbHTML_Calendar($size = HTML_CALENDAR_FULL, $path = null)
	{
		global $_SERVER; // < 4.1.0
		
		$this->setSize($size);
		
		$this->setPath(isset($path) ? $path : $_SERVER['PHP_SELF']);
		
		$this->_weekDays = Date_Calc::getWeekDays();
		if (DATE_CALC_BEGIN_WEEKDAY == 1)
			array_push($this->_weekDays, array_shift($this->_weekDays));
	}
	
	/**
	* Return the version of this class
	*
	* @access public
	* @return the version
	*/
	function getAPIVersion()
	{
		return 1.0;
	}

    /**
     * Get the value for a option
     *
     * @param option string option to return value for
     *
     * @access public
     * @return mixed
     */
	function getOption($option)
	{
		return $this->_options[$option];
	}

    /**
     * Set the value for an option
     *
     * @param option string option to set
     * @param value mixed value to set option to
     *
     * @param option string of option to set
     * @param value
     *
     * @access public
     * @return none
     */
	function setOption($option, $value)
	{
		$this->_options[$option] = $value;
	}

    /**
     * Get the attributes for an calendar element
     *
     * @param element
	 * @see setAttributes
     *
     * @access public
     * @return array the current values for the specified element
     */
	function getAttributes($element)
	{
		return $this->_attributes[$element];
	}

    /**
     * Set the attributes for an element
     *
     * @param element
     * element is one of the following values:
     *
     * table             the attributes for the entire calendar table
     * title             the attributes for the calendar title
     * weekday           the attributes for the 'weekday' name cells in the calendar header
	 *     
     * cell              the attributes for a cell (the 'td' element of each calendar day)
     * cell_other_month  the attributes for an 'other month' cell (falls outside 'this month')
     * cell_thisMonth   the attributes for a 'this month' cell
     * cell_today        the attributes for a 'today' cell
	 *     
     * day               the attributes for a day (the 'table' element within a cell)
     * day_other_month   the attributes for an 'other month' day
     * day_thisMonth    the attributes for a 'this month' day
     * day_today         the attributes for a 'today' day
	 *     
     * label             the attributes for the label text within a day element
     * label_other_month the attributes for the label of an 'other month' day
     * label_thisMonth  the attributes for the label of a 'this month' day
     * label_today       the attributes for the label of a 'today' day
	 *     
     * text              the attributes for the text within a day element
     * text_other_month  the attributes for the text of an 'other month' day
     * text_thisMonth   the attributes for the text of a 'this month' day
     * text_today        the attributes for the text of a 'today' day
     *
     * @param array
     *
     * @access public
     * @return none
     */
	function setAttributes($element, $array)
	{
		$this->_attributes[$element] = array();
		foreach ($array as $key => $value) {
			$this->_attributes[$element][$key] = $value;
		}
	}

    /**
     * Update the attributes for an element
     *
     * @param element
	 * @see setAttributes
     * @param array
     *
     * @access public
     * @return none
     */
	function updateAttributes($element, $array)
	{
		foreach ($array as $key => $value) {
			$this->_attributes[$element][$key] = $value;
		}
	}

    /**
     * Get the value for the attribute of an element
     *
     * @param element
	 * @see setAttributes
     * @param attribute
     *
     * @access public
     * @return mixed the value for the selected attribute
     */
	function getAttribute($element, $attribute) {
		return $this->_attributes[$element][$attribute];
	}

    /**
     * Set the attribute of an element
     *
     * @param element
	 * @see setAttributes
     * @param attribute
     * @param value
     *
     * @access public
     * @return none
     */
	function setAttribute($element, $attribute, $value)
	{
		$this->_attributes[$element][$attribute] = $value;
	}

    /**
    * Generate class attributes.
    *
    * The class attribute name with will the name of the element with the
    * prefix 'cal_'.
    * For example, the 'table' element will have the prefix 'cal_table'.
	*
    * @access public
    * @return none
    */
	function setClass($class)
	{
		foreach ($this->_attributes as $key => $value) {
			if ($class)
				$this->_attributes[$key]['class'] = 'cal_'.$key;
			else
				unset($this->_attributes[$key]['class']);
		}
	}

	/**	
    * Get the current URL path portion
    *
    * @access public
    * @return string
    */
	function getPath()
	{
		return $this->_path;
	}

	/**	
    * Set the URL path portion
    *
    * @access public
    * @return none
    */
	function setPath($path)
	{
		$this->_path = $path;
	}

    /**
    * Return the current calendar mode
	* HTML_CALENDAR_FULL: Full calendar, days are not links
	* HTML_CALENDAR_TINY: Tiny calendar, days are links
	*
    * @access public
    * @return int
    */
	function getMode()
	{
		return $this->_size;
	}

	/**	
    * Get the current flag to display the previous and next month/year navigation links
    *
    * @access public
    * @return bool
    */
	function getNavLinks()
	{
		return $this->_navLinks;
	}

	/**	
    * Set the flag to display the previous and next month/year navigation links
    *
    * @access public
    * @return none
    */
	function setNavLinks($navLinks)
	{
		$this->_navLinks = $navLinks;
	}

	/**	
    * Get the current flag to display previous and next months
    *
    * @access public
    * @return bool
    */
	function getOtherMonths()
	{
		return $this->_otherMonths;
	}
	
	/**	
    * Set the current flag to display previous and next months
    *
    * @access public
    * @return none
    */
	function setOtherMonths($otherMonths)
	{
		$this->_otherMonths = $otherMonths;
	}

	/**	
    * Get the current URL query format string
    *
    * @access public
    * @return string
    */
	function getQuery()
	{
		return $this->_query;
	}

	/**	
    * Set the current URL query string
    *
    * @access public
    * @return none
    */
	function setQuery($query)
	{
		$this->_query = $query;
	}

    /**
    * Set the current calendar size
	* HTML_CALENDAR_FULL: Full calendar, days are not links
	* HTML_CALENDAR_TINY: Tiny calendar, days are links
	*
    * @access public
    * @return none
    */
	function setSize($size)
	{
		$this->_size = $size;

		if ($this->_size & HTML_CALENDAR_TINY) {
			// if we don't define a reasonable width, NS 4.7x uses width=100%
			$this->updateAttributes('table', array('width' => 200));
			$this->updateAttributes('label', array(
				'bgcolor'	=> '#ffffff',
				'align'		=> 'center',
			));
		} else {
			$this->updateAttributes('table', array('width' => 600));
			$this->updateAttributes('label', array(
				'bgcolor'	=> '#f0f0f0',
				'align'		=> 'right',
			));
		}
	}

	/**
	* Build a properly constructed URL
	*
	* @param days
	* @access public
	* @return URL
	*/
	function makeURL($days)
	{
		$path = $this->_path;
		
		if (!strchr($path, '?') && !strchr($this->_query, '?'))
			$path .= '?';

		return $path . Date_Calc::daysToDate($days, $this->_query);
	}
	
	/**
	* Draw the calendar's title bar
	*
	* @param day
	* @param month
	* @param year
	*
	* @access public
	* @return string of the rendered HTML
	*/
	function drawTitle($day, $month, $year)
	{
		$rv = '';

		if ($this->_navLinks) {
			$path = $this->_path;
			
			if (!strchr($path, '?') && !strchr($this->_query, '?'))
				$path .= '?';

			$rv .= sprintf("<a href='%s'\n>&lt;&lt;</a>&nbsp;", $path .
				Date_Calc::beginOfMonth($month, $year - 1, $this->_query));

			$rv .= sprintf("<a href='%s'\n>&lt;</a>&nbsp;", $path .
				Date_Calc::beginOfPrevMonth($day, $month, $year, $this->_query));
		}
		
		$rv .= Date_Calc::dateFormat($day, $month, $year, $this->_options['title_format']);

		if ($this->_navLinks) {
			$rv .= sprintf("&nbsp;<a href='%s'\n>&gt;</a>", $path .
				Date_Calc::beginOfNextMonth($day, $month, $year, $this->_query));

			$rv .= sprintf("&nbsp;<a href='%s'\n>&gt;&gt;</a>", $path .
				Date_Calc::beginOfMonth($month, $year + 1, $this->_query));
		}
		
		return $rv;
	}

	/**
	* Determine if $days is today, it falls within the current month, or neither
	*
	* @param days
	* @access public
	* @return string 'today', 'thisMonth', or 'other_month'
	*/
	function getType($days)
	{
		// set the font color of the day, highlight if it is today
		if ($days == $this->_todayDays) {
			$type = 'today';
		} elseif (Date_Calc::daysToDate($days, '%m') == $this->_thisMonth) {
			$type = 'thisMonth';
		} else {
			$type = 'other_month';
		}
		return $type;
	}
	
	/**
	* Draw the cell for the day $days
	*
	* @param days Date_Calc's 'days' value
	* @param week week number of this cell in the calendar
	* @param col column number of this cell in the calendar
	*
	* @access public
	* @return string of the rendered HTML
	*/
	function drawCell($days, $week, $col)
	{
		$type = $this->getType($days);

		$a = array_merge($this->_attributes['day'], $this->_attributes['day_' . $type]);

		$t = new HTML_Table($a);
		$t->addRow(array($this->drawCellLabel($days, $week, $col)));
		$t->setRowAttributes(0, $this->_attributes['label']);
		$t->updateRowAttributes(0, $this->_attributes['label_' . $type]);
		if ($this->_size & HTML_CALENDAR_FULL) {
			$type = $this->getType($days);

			if (!$this->_otherMonths && $type == 'other_month')
				$cell_text = '&nbsp;';
			else
				$cell_text = $this->drawCellText($days, $week, $col);

			$t->addRow(array($cell_text));
			$t->setRowAttributes(1, $this->_attributes['text']);
			$t->updateRowAttributes(1, $this->_attributes['text_'.$type]);
		}

		return $t->toHTML();
	}

	/**
	* Draw the cell label for the day $days
	*
	* @param days Date_Calc's 'days' value
	* @param week week number of this cell in the calendar
	* @param col column number of this cell in the calendar
	*
	* @access public
	* @return string of the rendered HTML
	*/
	function drawCellLabel($days, $week, $col)
	{
		$type = $this->getType($days);

		if (!$this->_otherMonths && $type == 'other_month')
			return '&nbsp;';

		$rv = intval(Date_Calc::daysToDate($days,'%e'));
		if (($this->_size & HTML_CALENDAR_FULL) && 
		  ($rv == 1 || ($week == 0 && $col == 0)) &&
			$this->_otherMonths)
			$rv = Date_Calc::daysToDate($days,'%B&nbsp;') . $rv;

		if ($this->_size & HTML_CALENDAR_TINY)
			$rv = sprintf('<a href=\'%s\'>%s</a>', $this->makeURL($days), $rv);
		
		return $rv;
	}

	/**
	* Draw the cell text for the day $days
	*
	* @param days Date_Calc's 'days' value
	* @param week week number of this cell in the calendar
	* @param col column number of this cell in the calendar
	*
	* @access public
	* @return string of the rendered HTML
	*/
	function drawCellText($days, $week, $col)
	{
		return '<br /><br />';
	}

	/**
	* Draw the weekday text to appear at the top of the calendar for the col $col
	*
	* @param col column number of this cell in the calendar
	*
	* @access public
	* @return string of the rendered HTML
	*/
	function drawWeekDayText($col)
	{
		return ($this->_size & HTML_CALENDAR_TINY) ? 
			substr($this->_weekDays[$col], 0, 1) : 
			$this->_weekDays[$col];
	}
	
	/**
	* Generate the HTML_Table object of the calendar
	*
	* @param day   day of the calendar to generate, null = today's day
	* @param month month of the calendar to generate, null = today's  month
	* @param year  year of the calendar to generate, null = today's year
	*
	* @access public
	* @return the HTML_Table object of the calendar
	*/
	function generateTable($day = null, $month = null, $year = null)
	{
		if (empty($year))
			$year = Date_Calc::dateNow('%Y');
		if (empty($month))
			$month = Date_Calc::dateNow('%m');
		if (empty($day))
			$day = Date_Calc::dateNow('%d');
		$year	= sprintf('%04d', $year);
		$month	= sprintf('%02d', $month);
		$day	= sprintf('%02d', $day);

		// get month structure for generating calendar
		$month_cal = Date_Calc::getCalendarMonth($month, $year, '%E');

		$this->_todayDays = Date_Calc::dateFormat(null, null, null, '%E');
		$this->_thisMonth = Date_Calc::dateFormat($day, $month, $year, '%m');

		$row = 0;
		
		$table = new HTML_Table($this->_attributes['table']);
		$table->addRow(array($this->drawTitle($day, $month, $year)));
		$table->setRowAttributes($row, $this->_attributes['title']);
		$row++;

		for ($col = 0; $col < 7; $col++)
		{
			$table->setCellContents($row, $col, $this->drawWeekDayText($col), 'TH');
		}
		$table->setRowAttributes($row++, $this->_attributes['weekday']);
		
		for ($week = 0; $week < count($month_cal); $week++)
		{
			for ($col = 0; $col < 7; $col++)
			{
				$table->setCellContents($row, $col, $this->drawCell($month_cal[$week][$col], $week, $col));
				$type = $this->getType($month_cal[$week][$col]);
				$table->setCellAttributes($row, $col, $this->_attributes['cell']);
				$table->updateCellAttributes($row, $col, $this->_attributes['cell_'.$type]);
			}
			$row++;
		}
		return $table;
	}
	
	/**
	* Generate the HTML for the calendar
	*
	* @param day   day of the calendar to generate, null = today's day
	* @param month month of the calendar to generate, null = today's  month
	* @param year  year of the calendar to generate, null = today's year
	*
	* @access public
	* @return string of the rendered HTML
	*/
	function toHTML($day = null, $month = null, $year = null)
	{
		$table = $this->generateTable($day, $month, $year);
		return $table->toHTML();
	}
}

?>
