/* 
$CVSHeader: _freebeer/www/opt/dynamicdrive.com/disableenter.js,v 1.1.1.1 2004/03/03 22:48:41 ross Exp $
Source: http://www.dynamicdrive.com/dynamicindex16/disableenter.htm
Usage:

<form>
<input type="text" onkeypress="return handleEnter(this, event)"><br>
<input type="text" onkeypress="return handleEnter(this, event)"><br>
<textarea>Some text</textarea>
</form>

*/

/***********************************************
* Disable "Enter" key in Form script- By Nurul Fadilah(nurul@REMOVETHISvolmedia.com)
* This notice must stay intact for use
* Visit http://www.dynamicdrive.com/ for full source code
***********************************************/
                
function handleEnter(field, event) {
	var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;
	if (keyCode != 13) {
		return true;
	}
	
	var i;
	for (i = 0; i < field.form.elements.length; i++)
		if (field == field.form.elements[i])
			break;
	i = (i + 1) % field.form.elements.length;
	field.form.elements[i].focus();
	return false;
}      
