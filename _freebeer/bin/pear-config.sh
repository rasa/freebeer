#!/bin/sh

# $CVSHeader: _freebeer/bin/pear-config.sh,v 1.1.1.1 2004/01/18 00:12:03 ross Exp $

# Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
# Licensed under the BSD or LGPL License. See doc/license.txt for details.

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
