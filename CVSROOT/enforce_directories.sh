#!/bin/sh

# $CVSHeader: CVSROOT/enforce_directories.sh,v 1.1 2004/01/18 00:10:28 ross Exp $

find $CVSROOT/_freebeer -type d \( -name CVS -o -name cvs \) 2>/dev/null |\
	xargs rm -rf >/dev/null 2>&1
