///////////////////////////////////////////////////////////////////////////////
//
//  SuperTable is an Object-Oriented Javascript API for dynamically 
//  manipulating HTML tables. 
//
//  Copyright (C) 2003  John Aguinaldo
//
//
//
//  This library is free software; you can redistribute it and/or
//  modify it under the terms of the GNU Lesser General Public
//  License as published by the Free Software Foundation; either
//  version 2.1 of the License, or (at your option) any later version.
//  
//  This library is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
//  Lesser General Public License for more details.
//  
//  You should have received a copy of the GNU Lesser General Public
//  License along with this library; if not, write to the Free Software
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
//
//
//
//  IF YOU ARE NOT FAMILIAR with the terms of this license, please take
//  the time to read it at the URL below before utilizing it:
//      www.gnu.org/licenses/lgpl.txt 
//
//  If you feel that this license is too restrictive for your use, then 
//  please contact me to discuss what you are trying to accomplish.
//  
//  
//  PROJECT AND CONTACT information are posted at: 
//      http://supertable.sourceforge.net
//
//
//  THIS HEADER INCLUDING COPYRIGHT MUST REMAIN INTACT.
//
///////////////////////////////////////////////////////////////////////////////
//
//  Release 0.7  $Revision$
//


//=============================================================================
//
//  GLOBAL VARIABLES
//
//=============================================================================

SuperTable_ErrorHeader = 'SUPERTABLE SETUP ERROR:\n\n'; // Standard Error Message Header

SuperTable_Debug = 0;   // Set to '1' to display helpful debugging messages when 
                        // implementing SuperTable in your Web page or set to 
                        // '0' to hide debugging messages.




//=============================================================================
//
//  SUPERTABLE CLASS 
//
//=============================================================================

// CLASS PROPERTY
SuperTable.instanceCounter = 0;

function SuperTable( psTbodyId,psInstanceVar )
{
    SuperTable.instanceCounter++; // Track total number of SuperTable instances

    // CHECK USER-PASSED PARAMETERS
    if( SuperTable_Debug )
    {
        if( SuperTable.arguments.length < SuperTable.length - 1 ) {
            alert( SuperTable_ErrorHeader + 'Too few parameters passed to method defineFilter()' );
        }
        if( !document.getElementById(psTbodyId) ) {
            alert( SuperTable_ErrorHeader + 'Parameter \'' + psTbodyId + '\' passed to constructor SuperTable()\nis not a valid HTML element id within your document');
        }
        if( document.getElementById(psTbodyId).nodeName != 'TBODY' ) {
            alert( SuperTable_ErrorHeader + 'Parameter \'' + psTbodyId + '\' passed to constructor SuperTable()\nis not a valid tbody id within your document');
        }
    }

    // INSTANCE PROPERTIES
    this.instanceVarName = ( psInstanceVar ) ? psInstanceVar : null; // Instance Variable Name
    this.tbody_array = []; 
    this.lastSortColumn = 0;  // Track last sort column
    this.tbody_objref = document.getElementById( psTbodyId );
    this.columns = [];
    this.waitCursorActive = 0;
    this.timeoutDelay = 0;

    // DEFINE GETTERS & SETTERS
    SuperTable.prototype.getInstanceCounter = function() {
        return SuperTable.instanceCounter;
    }
    SuperTable.prototype.getLastSortColumn = function() {
        return this.lastSortColumn;
    }
    SuperTable.prototype.setLastSortColumn = function(pnCol) {
        this.lastSortColumn = pnCol;
    }
    SuperTable.prototype.getWaitCursorActive = function() {
        return this.waitCursorActive;
    }
    SuperTable.prototype.setWaitCursorActive = function(pbState) {
        this.waitCursorActive = pbState;
    }
    SuperTable.prototype.getTimeoutDelay = function() {
        return this.timeoutDelay;
    }
    SuperTable.prototype.setTimeoutDelay = function(pnMSecs) {
        this.timeoutDelay = pnMSecs;
    }
    SuperTable.prototype.getInstanceVarName = function() {
        return this.instanceVarName;
    }

    // INSTANCE METHODS
    this.setAlphaSortLimit = function(limit) { Array.prototype.limit = limit; }

    // SHARED METHODS
    SuperTable.prototype.defineColumn = defineColumn;
    SuperTable.prototype.array2table = array2table;
    SuperTable.prototype.table2array = table2array;
    SuperTable.prototype.toggleSort = toggleSort;
    SuperTable.prototype.sortTable = sortTable;
    SuperTable.prototype.sortTableMethod = sortTableMethod;
    SuperTable.prototype.filterTable = filterTable;
    SuperTable.prototype.filterTableMethod = filterTableMethod;
    SuperTable.prototype.deleteRows = deleteRows;
    SuperTable.prototype.autoNumberSeq = autoNumberSeq;
    SuperTable.prototype.hyphenLongWords = hyphenLongWords;
    SuperTable.prototype.cellRowProcessor = cellRowProcessor;
    SuperTable.prototype.removeStyle = removeStyle;
    SuperTable.prototype.displayWaitCursor = function(pbState) { // SEE NOTE BELOW concerning 'wait cursor' and
        cursor = (pbState) ? 'wait' : 'default';        // 'dialog box' under the DialogBox() Class
        document.body.style.cursor = cursor;
    }
    SuperTable.prototype.createDialogBox = function() {
        if( this.instanceVarName == null ) {
            alert( SuperTable_ErrorHeader + 'The instance variable instanceVarName must be passed as\nthe second parameter to constructor SuperTable()\nin order to run method createDialogBox()' );
            return;
        }
        this.dialogBox = new DialogBox();
    }

    // MAIN
    this.setAlphaSortLimit(15);  // Set default character limit for alpha sorts
}

// ALIAS FOR DEPRECATED TABLE() CONSTRUCTOR
// Table() constructor was renamed to SuperTable().  
// The variable 'Table' below serves as an alias to SuperTable() 
// providing backward compatibility to Web pages using previous
// versions of SuperTable.
Table = SuperTable;


//=====================================
//  SUPERTABLE CLASS METHODS
//=====================================
//

///////////////////////////////////////
//
// Remove styles from all tbody rows/cells (useful for printing)
//
function removeStyle()
{
    // If IE, then use 'className' to reference style class attribute
    var classSyntax = (document.all) ? 'className' : 'class';

    var oTrows = this.tbody_objref.rows;
    for(cRow=0; cRow<oTrows.length; cRow++)
    {
        // Clear TR attribute 'class'
        oTrows.item(cRow).setAttribute(classSyntax,'');

        var oTcells = oTrows.item(cRow).cells;
        for(cCell=0; cCell<oTcells.length; cCell++)
        {
            // Clear TD attribute 'class'
            oTcells.item(cCell).setAttribute(classSyntax,'');
        }
    }
}


///////////////////////////////////////
//
// Parse tbody to an array
//
function table2array()
{
    var trows = this.tbody_objref.rows;
    for(cRows=0; cRows<trows.length; cRows++)
    {
        var tcells = trows[cRows].cells;
        this.tbody_array[cRows] = new Array();
        for(cCells=0; cCells<tcells.length; cCells++)
        {
            // Load contents of HTML table cell into Array
            this.tbody_array[cRows][cCells] = tcells[cCells].innerHTML;
        }
    }
}


///////////////////////////////////////
//
// Define a column in the columns[] array of SuperTable
//
function defineColumn( pnIndex,poSortFunction )
{
    // CHECK USER-PASSED PARAMETERS
    if( SuperTable_Debug )
    {
        if( defineColumn.arguments.length < defineColumn.length ) {
            alert( SuperTable_ErrorHeader + 'Too few parameters passed to method defineColumn()' );
        }
        if( isNaN(pnIndex) ) {
            alert( SuperTable_ErrorHeader + 'Encountered \'' + pnIndex + '\' as the first parameter\npassed to method defineColumn().\n\nExpecting a number.' );
        }
        if( !(typeof poSortFunction == 'function') && poSortFunction != 'alpha' && poSortFunction != 'numeric' && poSortFunction != null ) {
            alert( SuperTable_ErrorHeader + 'Encountered \'' + poSortFunction + '\' as the second parameter\npassed to method defineColumn().\n\nExpecting any of the following:\n\n    \'alpha\'\n    \'numeric\'\n    function name' );
        }
    }
    this.columns[pnIndex] = new Column( pnIndex,poSortFunction );
}


///////////////////////////////////////
//
// Convert tbody to array, sort array, delete tbody rows,
// then load tbody with sorted array.
//
function sortTable(pnIndex,pnSortDir) // Wrapper for sortTableMethod() enables dialogBox
{
    if( this.dialogBox ) // Display dialog box prior to calling 
    {                    // filterTableMethod(), then hide dialog box

        this.dialogBox.display(1);
        setTimeout( this.instanceVarName + '.sortTableMethod(' + pnIndex + ',' + pnSortDir + ')',this.timeoutDelay );
        setTimeout( this.instanceVarName + '.dialogBox.display(0)',this.timeoutDelay );
    }
    else
    {
        this.sortTableMethod(pnIndex,pnSortDir);
    }
}
function sortTableMethod(pnIndex,pnSortDir)
{
    // CHECK USER-PASSED PARAMETERS
    if( SuperTable_Debug )
    {
        if( sortTableMethod.arguments.length < sortTable.length - 1 ) {
            // Subtract one from arguments length because second argument is optional
            alert( SuperTable_ErrorHeader + 'Too few parameters passed to method sortTable()' );
        }
    }
    pnSortDir = ( pnSortDir == null ) ? 1 : pnSortDir;

    // If tbody array already exists, then don't recreate it.
    if( !this.tbody_array.length ) { this.table2array() }

    // Detect sort function...
    switch ( this.columns[pnIndex].sortFunction )
    {
        case 'numeric':
            this.tbody_array.mergeSort( pnIndex, 'numeric', pnSortDir );
            break;
        case 'alpha':
            this.tbody_array.mergeSort( pnIndex, 'alpha', pnSortDir );
            break;
        case null:
            return;
        default:
            this.tbody_array.sort( this.columns[pnIndex].sortFunction );
    }
    this.deleteRows();
    this.array2table();
}

///////////////////////////////////////
//
// Delete tbody rows, then rebuild from table array.
// New table is automatically filtered upon rebuild.
//
function filterTable() // Wrapper for filterTableMethod() enables dialogBox
{
    if( this.dialogBox ) // Display dialog box prior to calling 
    {                    // filterTableMethod(), then hide dialog box

        this.dialogBox.display(1);
        setTimeout( this.instanceVarName + '.filterTableMethod()',this.timeoutDelay );
        setTimeout( this.instanceVarName + '.dialogBox.display(0)',this.timeoutDelay );
    }
    else
    {
        this.filterTableMethod();
    }
}
function filterTableMethod()
{
    // If tbody array already exists, then don't recreate it.
    if( !this.tbody_array.length ) { this.table2array() }
    
    this.deleteRows();
    this.array2table();
}


///////////////////////////////////////
//
// Wrapper method for sortTable():
// Tracks the last sort direction for each column and alternately
// sorts the column ascending and descending with each successive
// call.
//
function toggleSort(pnColIndx)
{
    // Toggle Sorting Rules:
    //  * Toggle the sorting direction on each successive contiguous click
    //    (e.g. sort column1, sort column1, sort column1...)
    //  * If successive sort is not contiguous, then sort using previous
    //    direction (e.g. sort column1, sort column2, sort column1...)
    if( this.lastSortColumn == pnColIndx )
    {
        //Contiguous: Sort column in opposite direction as previous
        var sortDir = ( this.columns[pnColIndx].lastSortDir ) ? 0 : 1;
        this.sortTable( pnColIndx,sortDir );
        this.lastSortColumn = pnColIndx;   // Update last sort column
        this.columns[pnColIndx].lastSortDir = sortDir; // Update last sort direction
    }
    else
    {
        //Noncontiguous: Sort column in same direction as previous
        var sortDir = ( this.columns[pnColIndx].lastSortDir ) ? 1 : 0;
        this.sortTable( pnColIndx,sortDir );
        this.lastSortColumn = pnColIndx;   // Update last sort column
        this.columns[pnColIndx].lastSortDir = sortDir; // Update last sort direction
    }
}


///////////////////////////////////////
//
// Delete all rows of tbody
//
function deleteRows()
{
    // delete all rows of table
    while (this.tbody_objref.rows.length > 0)
    {
        this.tbody_objref.deleteRow(0);
    }
}


///////////////////////////////////////
//
// Fill each row of column pnIndex with numeric series from 1 to n.
// This provides automatic sequence numbering.
//
function autoNumberSeq( pnIndex )
{
    pnIndex = (pnIndex) ? pnIndex : 0;
    var aTrows = this.tbody_objref.rows;
    for(cRow=0; cRow<aTrows.length; cRow++)
    {
        aTrows[cRow].cells[pnIndex].innerHTML = cRow+1;
    }
}


///////////////////////////////////////
//
// Break strings matching pattern defined by hyphenRegex using delimiter 
// defined by hyphenStr.
// This prevents long continuous strings from stretching a table cell
// (i.e. long URLs and long directory paths...)
//
function hyphenLongWords( sCell_text,nRow,nCell )
{
    //var sCell_text = this.tbody_array[nRow][nCell];
    if( this.columns[nCell].getHyphenLimit() )
    {
        var oRegex = this.columns[nCell].getHyphenRegex();
        var sHyphenStr = this.columns[nCell].getHyphenStr();
        sCell_text = sCell_text.replace( oRegex, "$1" + sHyphenStr );
        return sCell_text;
    }
    else
    {
        return sCell_text;
    }
}


///////////////////////////////////////
//
// User-defined method gives user access to internal variables of array2table
// allowing the user to intercept, test, and modify cell contents prior to 
// display.
//
// Global variables oNew_row & oNew_cell are also available...
// ...we were not able to pass them in explicitly otherwise
// we would overwrite them.  They can be used to set HTML
// attributes and apply CSS styles using example:
//     oNew_row.setAttribute('class','class-definition');
//
function cellRowProcessor( sCell_text,nTbody_array_row,nTbody_array_cell )
{
    return sCell_text;
}


///////////////////////////////////////
//
// Convert 2-dimensional array into HTML <tbody>
//
function array2table(psTbody_id,paTable)
{
    // TODO: createTextNode() below is causing embedded html tags
    //       to be encoded as &xxx characters..  How to stop this?
    //       Maybe use createDocumentFragment??


    // Set regex
    for( i in this.columns )
    {
        if( !this.columns[i].filter ) { continue }

        this.columns[i].filter.setRegex( this.columns[i].filter.getFormFldVal() );
    }

    //Iterate through each array element - row, then cell
    rowLoop:
    for( cRow=0; cRow<this.tbody_array.length; cRow++ )
    {
        // Filter criteria block..  If filter terms are detected, 
        // then skip block
        for( i in this.columns )
        {
            if( this.columns[i].filter )
            {
                if( this.tbody_array[cRow][i].search( this.columns[i].filter.getRegex() ) < 0 )
                { continue rowLoop; }
            }
        }

        // Creates an element whose tag name is TR
        //    Must be Global variable...  used by Style.apply()
        oNew_row=document.createElement("TR");

        cellLoop:
        for(cCell=0; cCell<this.tbody_array[cRow].length; cCell++)
        {
            // Hide Column if display = 0
            if( !this.columns[cCell].display ) { continue cellLoop }

            // Get cell contents from array
            sCell_text = this.tbody_array[cRow][cCell];


            // Creates a TD element
            //    Must be Global variable...  used by Style.apply()
            oNew_cell=document.createElement("TD");

            // Creates a Text Node using array contents
            var sNew_text=document.createTextNode( '' );

            // Color rows based on priority...
            this.columns[cCell].style.apply( sCell_text );

            // SPLIT LONG WORDS
            sCell_text = this.hyphenLongWords( sCell_text,cRow,cCell );

            // PROCESS USER DEFINED FUNCTIONS (IF DEFINED)
            sCell_text = this.cellRowProcessor( sCell_text,cRow,cCell );

            // Load array data into cell via innerHTML (instead of previous method createTextNode)
            oNew_cell.innerHTML = sCell_text;

            // APPENDS the Text Node we created into the cell TD
            oNew_cell.appendChild(sNew_text);

            // APPENDS the cell TD into the row TR
            oNew_row.appendChild(oNew_cell);

        }
        // Appends the row TR into TBODY
        this.tbody_objref.appendChild(oNew_row);
    }
}




//=============================================================================
//
//  COLUMN CLASS
//
//=============================================================================
function Column( pnIndex,poSortFunction )
{
    // INSTANCE PROPERTIES
    this.index = pnIndex;
    this.hyphenLimit = 0;
    this.hyphenStr = '- ';
    this.hyphenRegex = null;
    this.filter = null;
    this.sortFunction = poSortFunction;
    this.style = new Style(); // Instantiate style object to contain "rules"
    this.lastSortDir = 0;
    this.display = 1;  // 0 = hide, 1 = show

    

    // DEFINE GETTERS & SETTERS
    Column.prototype.getHyphenLimit = function() {
        return this.hyphenLimit;
    }
    Column.prototype.setHyphenLimit = function(length) {
        this.hyphenLimit = length;
        this.hyphenRegex = eval('/([\\w:\\/.?&-_]{' + length + '})/');
    }
    Column.prototype.getHyphenStr = function() {
        return this.hyphenStr;
    }
    Column.prototype.setHyphenStr = function(psHyphenStr) {
        this.hyphenStr = psHyphenStr;
    }
    Column.prototype.getHyphenRegex = function() {
        return this.hyphenRegex;
    }
    Column.prototype.setHyphenRegex = function(re) {
        this.hyphenRegex = re;
    }
    Column.prototype.getSortFunction = function() {
        return this.sortFunction;
    }
    Column.prototype.setSortFunction = function(poFunction) {
        this.sortFunction = poFunction;
    }
    Column.prototype.getLastSortDir = function() {
        return this.lastSortDir;
    }
    Column.prototype.setLastSortDir = function(pnDir) {
        this.lastSortDir = pnDir;
    }
    Column.prototype.getIndex = function() {
        return this.index;
    }
    Column.prototype.setIndex = function(pnIndex) {
        this.index = pnIndex;
    }
    Column.prototype.getDisplay = function() {
        return this.display;
    }
    Column.prototype.setDisplay = function(pbSetting) {
        this.display = pbSetting;
    }

    // SHARED METHODS
    Column.prototype.defineFilter = function( p_sFormName,p_sFormFieldName ) {
        // CHECK USER-PASSED PARAMETERS
        if( SuperTable_Debug )
        {
            if( this.defineFilter.arguments.length != this.defineFilter.length ) {
                alert( SuperTable_ErrorHeader + 'Too few parameters passed to method defineFilter().' );
            }
        }
        this.filter = new Filter( p_sFormName,p_sFormFieldName );
    }
}




//=============================================================================
//
//  STYLE CLASS
//
//=============================================================================
function Style()
{
    // INSTANCE PROPERTIES
    this.rules = [];

    // SHARED METHODS
    Style.prototype.defineRule = function( regex,css,applyTo ) {
        // CHECK USER-PASSED PARAMETERS
        if( SuperTable_Debug )
        {
            if( this.defineRule.arguments.length < this.defineRule.length ) {
                alert( SuperTable_ErrorHeader + 'Too few parameters passed to method defineRule().' );
            }
            if( regex.source == null ) {
                alert( SuperTable_ErrorHeader + 'Encountered \'' + regex + '\' as the first parameter\npassed to method defineRule().\n\nExpecting a valid Javascript Regular Expression.');
            }
            if( typeof css != 'string' ) {
                alert( SuperTable_ErrorHeader + 'Encountered \'' + css + '\' as the second parameter\npassed to method defineRule().\n\nExpecting a valid css rule name in single-quoted string form.');
            }
            if( applyTo != 'row' && applyTo != 'cell' ) {
                alert( SuperTable_ErrorHeader + 'Encountered \'' + applyTo + '\' as the third parameter\npassed to method defineRule().\n\nExpecting either keyword \'row\' or \'cell\'.');
            }
        }
        this.rules[this.rules.length] = new Rule( regex,css,applyTo );
    }
    Style.prototype.apply = apply;
}


//=====================================
//  STYLE CLASS METHODS
//=====================================
//

///////////////////////////////////////
//
// Iterate through defined style rules for column and apply CSS
// if contents of column cell match regex pattern.
//
function apply( sCell_text )
{
    // If IE, then use 'className' to reference style class attribute
    var classSyntax = (document.all) ? 'className' : 'class';

    for( i=0; i<this.rules.length; i++ )
    {
        if( sCell_text.search( this.rules[i].getRegex() ) != -1 )
        {
            switch( this.rules[i].getApplyTo() )
            {
                case 'row':
                    oNew_row.setAttribute( classSyntax,this.rules[i].getCss() );
                    return;
                case 'cell':
                    oNew_cell.setAttribute( classSyntax,this.rules[i].getCss() );
                    return;
                default:
                    return;
            }
        }
    }
}




//=============================================================================
//
//  RULE CLASS
//
//=============================================================================
function Rule( regex,css,applyTo )
{
    // INSTANCE PROPERTIES
    this.regex = regex;
    this.css = css;
    this.applyTo = applyTo;

    // DEFINE GETTERS & SETTERS
    Rule.prototype.getRegex = function() {
        return this.regex;
    }
    Rule.prototype.setRegex = function(poRe) {
        this.regex = poRe;
    }
    Rule.prototype.getCss = function() {
        return this.css;
    }
    Rule.prototype.setCss = function(psCss) {
        this.css = psCss;
    }
    Rule.prototype.getApplyTo = function() {
        return this.applyTo;
    }
    Rule.prototype.setApplyTo = function(psTarget) {
        this.applyTo = psTarget;
    }
}




//=============================================================================
//
//  FILTER CLASS
//
//=============================================================================
function Filter( psFormName,psFormFieldName )
{
    // CHECK USER-PASSED PARAMETERS
    if( SuperTable_Debug )
    {
        if( !document.forms[psFormName] ) {
            alert( SuperTable_ErrorHeader + 'Parameter \'' + psFormName + '\' passed to method\ndefineFilter() is not a valid form name\nwithin your document');
        }
        else if( !document.forms[psFormName].elements[psFormFieldName] ) {
            alert( SuperTable_ErrorHeader + 'Parameter \'' + psFormFieldName + '\' passed to method\ndefineFilter() is not a valid form field name\nwithin your document');
        }
    }


    // INSTANCE PROPERTIES
    this.form = document.forms[psFormName];
    this.formField = this.form[psFormFieldName];
    this.regex = '';

    // DEFINE GETTERS & SETTERS
    Filter.prototype.getRegex = function() {
        return this.regex;
    }
    Filter.prototype.setRegex = function(psRe) {
        this.regex = new RegExp( psRe,"gi" );
    }
    Filter.prototype.getFormFldVal = function() {
        // Set regex to .* if form field is null
        if( this.formField.value == '' ) {
            return '.*';
        }
        else {
            return this.formField.value;
        }
    }
}




//=============================================================================
//
//  DIALOGBOX CLASS
//
//=============================================================================
//
//  The DialogBox class is an optional independant class that can be used in
//  conjunction with or apart from the SuperTable class to display pop-up dialog
//  messages to the user.
//
function DialogBox( psIdDivBkgd,psIdDivBox )
{
    // SET USER-DEFINED PARAMETERS IF NOT PASSED IN
    // Append instance count to make IDs unique
    psIdDivBkgd = (psIdDivBkgd) ? psIdDivBkgd : 'superTableDialogBkgd' + SuperTable.instanceCounter;
    psIdDivBox = (psIdDivBox) ? psIdDivBox : 'superTableDialogBox' + Table.instanceCounter;

    // INSTANCE PROPERTIES
    this.id = psIdDivBkgd;
    this.active = 1;
    this.visible = 0;
    // Default message and CSS...
    this.messgHTML = '<p style="color:#333333;font-weight:bold">Table is Loading...</p>';
    this.cssTextDialogBkgd = 'position:absolute;top:100;left:100;z-index:100;visibility:hidden;width:auto;height:auto;background:#CCCCCC;margin:0px;padding:6px;';
    this.cssTextDialogBox = 'position:relative;border:1px solid black;margin:0px;padding:6px 24px;text-align:center';

    // DEFINE GETTERS & SETTERS
    DialogBox.prototype.getActive = function() {
        return this.active;
    }
    DialogBox.prototype.setActive = function(pbState) {
        this.active = pbState;
    }
    DialogBox.prototype.getMessgHTML = function() {
        return this.messgHTML;
    }
    DialogBox.prototype.setMessgHTML = function(psMessgHTML) {
        this.messgHTML = psMessgHTML;
        this.recreate();
    }
    DialogBox.prototype.getCssTextDialogBkgd = function() {
        return this.cssTextDialogBkgd;
    }
    DialogBox.prototype.setCssTextDialogBkgd = function(psCssText) {
        this.cssTextDialogBkgd = psCssText;
        this.recreate();
    }
    DialogBox.prototype.getCssTextDialogBox = function() {
        return this.cssTextDialogBox;
    }
    DialogBox.prototype.setCssTextDialogBox = function(psCssText) {
        this.cssTextDialogBox = psCssText;
        this.recreate();
    }
    DialogBox.prototype.getID = function() {
        return this.id;
    }
    DialogBox.prototype.setID = function(psId) {
        this.id = psId;
    }
    DialogBox.prototype.getVisible = function() {
        return this.visible;
    }

    // SHARED METHODS
    DialogBox.prototype.create = function() {
        dialogBkgnd = document.createElement('div');
        dialogBkgnd.setAttribute( 'id',psIdDivBkgd );
        dialogBkgnd.style.cssText = this.cssTextDialogBkgd;

        dialogBox = document.createElement('div');
        dialogBox.setAttribute( 'id',psIdDivBox );
        dialogBox.style.cssText = this.cssTextDialogBox;
        dialogBox.innerHTML = this.messgHTML;

        document.getElementsByTagName('body')[0].appendChild(dialogBkgnd);
        document.getElementById(psIdDivBkgd).appendChild(dialogBox);
    }
    DialogBox.prototype.recreate = function() {
        // Remove existing ID in IE, else IE appends multiple dialog box <div>s
        if( document.all )
        { 
            document.getElementById(this.id).removeNode() 
        }
        this.create();
    }
    DialogBox.prototype.display = function(pbSetting) {
        if( !this.active ) // Quit if Dialog Box is inactive
        {
            return;
        }
        if( pbSetting )    // Show Dialog Box
        { 
            this.moveToPosition();
            document.getElementById(this.id).style.visibility = 'visible';
            this.visible = 1;
        } 
        else               // Hide Dialog Box
        {
            document.getElementById(this.id).style.visibility = 'hidden';
            this.visible = 0;
        }
    }
    DialogBox.prototype.moveToPosition = function() { // Position dialog box in window
        windowWidth = document.body.clientWidth;
        windowHeight = document.body.clientHeight;
        dialogBoxWidth = document.getElementById(this.id).offsetWidth;
        dialogBoxHeight = document.getElementById(this.id).offsetHeight;
        document.getElementById(this.id).style.top = (windowHeight-dialogBoxHeight)/2 + document.body.scrollTop;
        document.getElementById(this.id).style.left = (windowWidth-dialogBoxWidth)/2 + document.body.scrollLeft;
    }

    // MAIN
    this.create();
}




//=============================================================================
//
//  MISCELLANEOUS FUNCTIONS
//
//=============================================================================


//=============================================================================
//  TEXT2NUMBER()
//
// This function is helpful when sorting an array of varied size
// text strings.  The function trims off the first few characters
// (as defined by 'pnLimit') of the text string 'psText' and then
// converts each character into a three-digit numeric code.
// This allows the string text to be accurately compared and
// sorted.
//
function text2Number(psText,pnLimit,psType)
{
    // If psType = 'numeric' then parseInt and return
    if( psType == 'numeric' )
    {
        return parseInt(psText);
    }

    // Strip leading space and convert to lower case
    psText = psText.replace(/^(\s|&nbsp;)*/,'');
    psText.toLowerCase();

    // Instantiate as string to accumulate number
    var sCodeString = '';
    // Capture first [pnLimit] characters of psText
    for(i=0; i<pnLimit; i++)
    {
        // Convert character to number
        var nThis_code = ( psText.charCodeAt(i) )? '' + psText.charCodeAt(i) : '000';
        // Append leading zero if only two digits
        if ( nThis_code.length == 2 ){ nThis_code = '0' + nThis_code }
        // Append current code to accumulated string
        sCodeString += nThis_code;
    }
    // Convert to number and get absolute value
    var nCodeString = 0 - sCodeString;
    (nCodeString < 0) ? nCodeString = -nCodeString : nCodeString = nCodeString;
    return nCodeString;
}




//=============================================================================
//  ARRAY.MERGESORT()
//
//  Custom array object method for sorting numbers and strings
//
//  REQUIRES: 
//
//      sortCompare() which requires text2Number()
//      Array.limit
//  
//  WHERE:
//
//      col     Index number of column to be sorted
//      type    'alpha' or 'num' (alpha or numeric sort)
//      dir     1 or 0 (ascending or descending sort)
//      start   internal start counter
//      end     internal end counter
//
Array.prototype.mergeSort = function(col,type,dir,start,end)
{
    // If this is the first call (not a recursion)
    if( arguments.length == 3 )
    {
        start = 0;
        end = this.length;
    }

    if( end-start > 1 )
    {
        var mid=parseInt( (start+end)/2 );
        this.mergeSort(col,type,dir,start,mid);
        this.mergeSort(col,type,dir,mid,end);

        var tmp=[];
        var start2=start;
        var mid2=mid;
        for(var i=0; i<end-start; ++i)
        {
            if     ( start2>=mid ) { tmp[i] = this[mid2++] }
            else if( mid2>=end ) { tmp[i] = this[start2++] }
            else if( sortCompare( this[start2][col], this[mid2][col], this.limit, type, dir ) )
            { tmp[i] = this[start2++] }
            else
            { tmp[i] = this[mid2++] }
        }
        for(i=0; i<end-start; ++i)
        {
          this[i+start]=tmp[i];
        }
    }
}



//=============================================================================
//  SORTCOMPARE()
//
//  Sort logic for mergeSort() method
//
//  REQUIRES: 
//
//      Nothing.
//  
//  WHERE:
//
//      arg1    First argument to be compared
//      arg2    Second argument to be compared
//      limit   Limit of characters to be compared (Array.limit)
//      type    'alpha' or 'num' (alpha or numeric sort)
//      dir     1 or 0 (ascending or descending sort)
//
function sortCompare( arg1,arg2,limit,type,dir )
{
    if( dir != 1 )
    {
        // Sort Descending
        if( text2Number( arg1,limit,type ) >=
            text2Number( arg2,limit,type ) )
        { return 1 }
        else
        { return 0 }
    }
    else
    {
        // Sort Ascending
        dir = 0; // Assign 0 just in case parameter was not passed
        if( text2Number( arg1,limit,type ) <=
            text2Number( arg2,limit,type ) )
        { return 1 }
        else
        { return 0 }
    }
}


//__END__
