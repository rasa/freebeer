#!python

# **************************************
# * test                               *
# **************************************
from jsrsServer import *

def testPython (a,b):
	return "Parameter 1 was %s and parameter 2 is %s" % (a,b)

if __name__ == '__main__':	
	## flat version
	#jsrsDispatch((testPython,))
	
	## OO version
	jsrs = JSRS()
	jsrs.addFunction (testPython)
	jsrs.dispatch ()
