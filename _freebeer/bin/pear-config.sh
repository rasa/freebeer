#!/bin/sh

# $CVSHeader: _freebeer/bin/pear-config.sh,v 1.3 2004/03/07 19:32:21 ross Exp $

# Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
# Licensed under the BSD or LGPL License. See license.txt for details.

if [ -z "$FREEBEER_BASE" ]; then
	FREEBEER_BASE=`dirname $0`
	if [ "$FREEBEER_BASE" = "." ]; then
		FREEBEER_BASE=`pwd`
	fi
	while [ ! -f "$FREEBEER_BASE/lib/System.php" ];
	do
		FREEBEER_BASE=`dirname $FREEBEER_BASE`
	done
fi

DIRS="$FREEBEER_BASE/opt/pear/bin \
$FREEBEER_BASE/var/cache/pear \
$FREEBEER_BASE/opt/pear/data \
$FREEBEER_BASE/opt/pear/doc \
$FREEBEER_BASE/opt/pear/ext \
$FREEBEER_BASE/opt/pear \
$FREEBEER_BASE/opt/pear/tests"

for dir in $DIRS
do
	if [ ! -d $dir ];
	then
		mkdir -p $dir
		touch $dir/.cvsignore
	fi
done

pear config-set bin_dir		$FREEBEER_BASE/opt/pear/bin
pear config-set cache_dir	$FREEBEER_BASE/var/cache/pear
pear config-set data_dir	$FREEBEER_BASE/opt/pear/data
pear config-set doc_dir		$FREEBEER_BASE/opt/pear/doc
pear config-set ext_dir		$FREEBEER_BASE/opt/pear/ext
pear config-set php_dir		$FREEBEER_BASE/opt/pear
pear config-set test_dir	$FREEBEER_BASE/opt/pear/tests
pear config-show
