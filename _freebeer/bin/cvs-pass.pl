#!/usr/bin/perl

# $CVSHeader: _freebeer/bin/cvs-pass.pl,v 1.1.1.1 2004/01/18 00:12:03 ross Exp $

# Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
# Licensed under the BSD or LGPL License. See doc/license.txt for details.

# \todo rewrite in PHP

# source:
# http://cvsbook.red-bean.com/cvsbook.html#The_Password-Authenticating_Server

#unless ($ARGC) {
#	print "Usage $0 salt\n";
#	exit 1;
#}

srand (time());
my $randletter = "(int (rand (26)) + (int (rand (1) + .5) % 2 ? 65 : 97))";
my $salt = sprintf ("%c%c", eval $randletter, eval $randletter);
my $plaintext = shift;
my $crypttext = crypt ($plaintext, $salt);

print "${crypttext}\n";
