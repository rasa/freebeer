#!python

import cgi, sys, urllib

htmlReturn = '''Content-type: text/html

<html>
	<head></head>
	<body onload="p=document.layers?parentLayer:window.parent;p.jsrsLoaded('%s');">jsrsPayload:<br>
		<form name="jsrs_Form">
			<textarea name="jsrs_Payload" id="jsrs_Payload">%s</textarea>
		</form>
	</body>
</html>
'''

htmlError = '''
<html>
	<head></head>
	<body onload="p=document.layers?parentLayer:window.parent;p.jsrsError('%s','%s');">%s
	</body>
</html>
'''

def jsrsDispatch (validFuncs):
	jsrsQuery = cgi.FieldStorage ()
	func, args = jsrsBuildFunc (validFuncs, jsrsQuery)
	if func:
		retval = apply (func, args)
		print htmlReturn % (jsrsQuery['C'].value, jsrsEscape(retval))
	else:
		jsrsReturnError("function builds as empty string",jsrsQuery['C'].value)

def jsrsEscape (str):
	str = str.replace ('&', '&amp;')
	str = str.replace ('/', r'\/')
	return str

# **************************************************************
# * user functions
# **************************************************************

def jsrsReturnError (str, code):
	cleanStr = str.replace ("'", r"\'")
	cleanStr = cleanStr.replace ("\'","\\'")
	print htmlError % (code, urllib.quote(str), cleanStr)
	sys.exit()

def jsrsBuildFunc (validFuncs, jsrsQuery):
	func = None
	if jsrsQuery.has_key('F'):
		func	= jsrsQuery['F'].value
		params	= []
		
		# make sure func is in the dispatch list
		for vf in validFuncs:
			if vf.__name__ == func:
				func = vf
				break
		if not func:
			jsrsReturnError ('%s is not a valid function' % func, jsrsQuery['C'].value)

		i = 0
		while jsrsQuery.has_key('P%s' % i):
			parm = jsrsQuery['P%s' % i].value
			params.append (parm [1:-1])
			i += 1

	return func, params

##############################
##	OO version
##############################

class JSRS:

	def __init__(self):
		self._funcs = []
		self._query = None

	def addFunction (self, f):
		self._funcs.append (f)

	def dispatch (self):
		self._query = cgi.FieldStorage ()
		func, args = self._buildFunc ()
		if func:
			retval = apply (func, args)
			print htmlReturn % (self._query['C'].value, self._escape(retval))
		else:
			self._returnError("function builds as empty string")

	def _escape (self, str):
		str = str.replace ('&', '&amp;')
		str = str.replace ('/', r'\/')
		return str

	def _returnError (self, err):
		cleanStr = err.replace ("'", r"\'")
		cleanStr = cleanStr.replace ("\'","\\'")
		print htmlError % (self._query['C'].value, urllib.quote(err), cleanStr)
		sys.exit()

	def _buildFunc (self):
		func = None
		if self._query.has_key('F'):
			func	= self._query['F'].value
			params	= []
			
			# make sure func is in the dispatch list
			for vf in self._funcs:
				if vf.__name__ == func:
					func = vf
					break
			if not func:
				self._returnError ('%s is not a valid function' % func)

			i = 0
			while self._query.has_key('P%s' % i):
				parm = self._query['P%s' % i].value
				params.append (parm [1:-1])
				i += 1

		return func, params