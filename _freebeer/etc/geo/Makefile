# $CVSHeader: _freebeer/etc/geo/Makefile,v 1.1.1.1 2004/03/03 22:48:41 ross Exp $

# Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
# Licensed under the BSD or LGPL License. See doc/license.txt for details.

# Windows/Cygwin, use c:\php4\cli\php
ifeq ('$(PHP)', '')
	PHP=$(shell which phpcli)
endif

# Debian calls it the CLI version php4
ifeq ('$(PHP)', '')
	PHP=$(shell which php4)
endif

# Otherwise, use 'php'
ifeq ('$(PHP)', '')
	PHP=$(shell which php)
endif

.PHONY: all clean regen test
	
all:	geo-ips.txt geo-ips-binary.txt
	
geo-ips.txt:	ips-ascii.txt
	$(PHP) -q ../../bin/geo-ip-convert.php $< $@
	
geo-ips-binary.txt:	ips-ascii.txt
	$(PHP) -q ../../bin/geo-ip-convert.php $< $@ "%c%c%c%c%c%c%c%c%2.2s"

clean:
	rm -f geo-ips.txt geo-ips-binary.txt

regen:	clean all

test:	all
	sort geo-ips.txt | uniq | cmp - geo-ips.txt
