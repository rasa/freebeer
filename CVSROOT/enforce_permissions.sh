#!/bin/sh

# $CVSHeader: CVSROOT/enforce_permissions.sh,v 1.1 2004/01/18 00:10:28 ross Exp $

# This script will be used to enforce proper permissions on all files within the repository.

# Make all other files in /CVSROOT non-executable
find $CVSROOT/CVSROOT -not \( -name cvs_acls -o -name syncmail -o -name '*.sh' -o -name '*.pl' \) -type f -perm +0111 2>/dev/null |\
	xargs echo chmod a-x # >/dev/null 2>&1

# Make relavent files in /CVSROOT executable
find $CVSROOT/CVSROOT \( -name cvs_acls -o -name syncmail -o -name '*.sh' -o -name '*.pl' \) -type f -not -perm -0111 2>/dev/null |\
	xargs echo chmod a+x # >/dev/null 2>&1

# Make all directories in /_freebeer (except /var) readable by everybody
find $CVSROOT/_freebeer -not -path './var/*' -type d -not -perm -2775 2>/dev/null |\
	xargs echo chmod 2775 >/dev# /null 2>&1

# Make all files not in /bin non-executable
find $CVSROOT/_freebeer -not -path './bin/*' -type f -perm +0111 2>/dev/null |\
	xargs echo chmod a-x # >/dev/null 2>&1

# Make all other files in /bin non-executable
find $CVSROOT/_freebeer/bin -not \( -name '*.php' -o -name '*.sh' -o -name '*.pl' \) -type f -perm +0111 2>/dev/null |\
	xargs echo chmod a-x # >/dev/null 2>&1

# Make all *.php/*.sh/*.pl files in /bin executable
find $CVSROOT/_freebeer/bin \( -name '*.php' -o -name '*.sh' -o -name '*.pl' \) -type f -not -perm -0111 2>/dev/null |\
	xargs echo chmod a+x # >/dev/null 2>&1
