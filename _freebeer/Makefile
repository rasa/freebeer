# $CVSHeader: _freebeer/Makefile,v 1.1.1.1 2004/03/03 22:48:41 ross Exp $

# Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
# Licensed under the BSD or LGPL License. See doc/license.txt for details.

# per http://www.gnu.org/manual/make/html_mono/make.html
# \todo add make dist: clean changelog
# \todo add make check
# \todo add make setup and lockdown to doc/makehelp.txt

DEBIAN=$(shell cat /etc/debian_version 2>/dev/null)

REDHAT=$(shell cat /etc/redhat-release 2>/dev/null)

UNAME=$(shell uname | cut -c 1-6)

UID=`id -u`

ifeq ('$(WWWUSER)', '')
	ifeq ('$(UNAME)', 'CYGWIN')
		WWWUSER=SYSTEM
	else
		ifneq ('$(DEBIAN)', '')
			WWWUSER=www-data
		else
			ifneq ('$(REDHAT)', '')
				WWWUSER=apache
			else
				WWWUSERASSUMED=nobody
				WWWUSER=nobody
			endif
		endif
	endif
endif

ifeq ('$(WWWGROUP)', '')
	ifeq ('$(UNAME)', 'CYGWIN')
		WWWGROUP=SYSTEM
	else
		ifneq ('$(DEBIAN)', '')
			WWWGROUP=www-data
		else
			ifneq ('$(REDHAT)', '')
				WWWGROUP=apache
			else
				WWWGROUPASSUMED=nobody
				WWWGROUP=nobody
			endif
		endif
	endif
endif

.PHONY: \
	all \
	backup \
	changelog \
	check \
	clean \
	clean_ \
	dirs \
	dist \
	docs \
	doxygen \
	help \
	lint \
	lock \
	logs \
	phpxref \
	pretty \ 
	setup \
	test \
	testall \
	testjs \
	testurls \
	unlock \
	varrights \
	wwwuser
	
all:	dirs

backup:	clean
	-rm ../freebeer.tar.bz2
	tar cvjf ../freebeer.tar.bz2 .

changelog:
	# \todo change to bin/changelog.sh ?
	bin/cvs2cl.pl --tags --branches --revisions --day-of-week -S --no-wrap

check:
	@bin/check.sh
	
clean_:
	-rm -fr \
	tags \
	var/cache/adodb/* \
	var/cache/pear/* \
	var/cache/smarty/cache/* \
	var/cache/smarty/templates_c/* \
	var/doc/doxygen/* \
	var/doc/phpxref/* \
	var/etc/* \
	var/tmp/* \
	var/log/*.log \
	-find . -name "*.bak"	-o \
		-name "*~"	-o \
		| xargs rm -fr

# is this deleting all files that start with a '.'?
#		-name ".#*"	\

clean:	clean_ logs varrights

dev:	all tags

dirs:	\
	opt/pear/bin \
	opt/pear/data \
	opt/pear/doc \
	opt/pear/ext \
	opt/pear/tests \
	var/cache/adodb \
	var/cache/pear \
	var/cache/smarty/cache \
	var/cache/smarty/templates_c \
	var/doc/doxygen \
	var/doc/phpxref \
	var/etc \
	var/log/debug.log \
	var/log/error.log \
	var/tmp \
	varrights

dist:	changelog
	cvs ci
	@bin/dist.sh $(MAKEOPTS)

docs:	doxygen phpxref

doxygen:
	@rm -fr var/doc/doxygen/*
	@rm -f var/log/doxygen*
	doxygen etc/doxygen/Doxyfile
	cat var/log/doxygen.log | \
	grep -v \\abstract | \
	grep -v \\static | \
	grep -v SHA1 | \
	grep -v Backport\.php >var/log/doxygen2.log

help:
	@cat doc/Makefile.txt

lint:
	@bin/lint.sh

lock:	
ifeq ('$(UID)', '0')
	-chmod -R g-w,o-rw etc
else
	-chmod -R go-w etc
endif

logs:	var/log/debug.log var/log/error.log

phpxref:	
	rm -rf var/doc/phpxref
	cd opt/phpxref && phpxref.pl

pretty:
	@bin/pretty.sh

setup:	unlock

tags:
	@bin/tags.sh

test:
	@bin/test.sh

testall:	lint test testurls testjs

testjs:
	@bin/testjs.sh

testurls:
	@bin/testurls.sh

unlock:	wwwuser
	-find etc -type d | xargs chmod g+s
ifeq ('$(UID)', '0')
	-chgrp -R ${WWWUSER} etc
	-chmod -R g+w etc
else
	-chmod -R go+w etc
endif

varrights: wwwuser
	-find var -type d | xargs chmod g+s,a+x
ifeq ('$(UID)', '0')
	-chgrp -R ${WWWUSER} var
	-chmod -R ug+rw,o-rw var
else
	-chmod -R ugo+rw var
endif

opt/pear/bin:
	mkdir -p $@

opt/pear/data:
	mkdir -p $@

opt/pear/doc:
	mkdir -p $@

opt/pear/ext:
	mkdir -p $@

opt/pear/tests:
	mkdir -p $@

var/cache/adodb:
	mkdir -p $@

var/cache/pear:
	mkdir -p $@

var/cache/smarty/cache:
	mkdir -p $@

var/cache/smarty/templates_c:
	mkdir -p $@

var/doc/doxygen:
	mkdir -p $@

var/doc/phpxref:
	mkdir -p $@

var/etc:
	mkdir -p $@

var/log/debug.log:
	mkdir -p `dirname $@`
	touch $@

var/log/error.log:
	mkdir -p `dirname $@`
	touch $@

var/tmp:
	mkdir -p $@

wwwuser:
#ifeq ('$(UID)', '0')
ifneq ('$(WWWUSERASSUMED)', '')
	$(warning Assuming apache user is 'nobody')
	$(warning If apache is running as a different user (for example, 'apache'), type:)
	$(warning WWWUSER=apache make [options])
endif
ifneq ('$(WWWGROUPASSUMED)', '')
	$(warning Assuming apache group is 'nobody')
	$(warning If apache is running a member of a different group (for example, 'apache'), type:)
	$(warning WWWUSER=apache WWWGROUP=apache make [options])
endif
#endif
