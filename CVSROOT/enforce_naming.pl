#!/usr/bin/perl

# $CVSHeader: CVSROOT/enforce_naming.pl,v 1.1 2004/01/18 00:10:28 ross Exp $

# enforce-naming.pl - Verify undesireable files (by name) are not committed

# Copyright (C) 2002 Open Source Development Network.
#
# Permission is hereby granted, free of charge, to any person obtaining a 
# copy of this software and associated documentation files (the "Software"),
# to deal in the Software without restriction, including without limitation
# the rights to use, copy, modify, merge, publish, distribute, sublicense,
# and/or sell copies of the Software, and to permit persons to whom the
# Software is furnished to do so, subject to the following conditions:
#
# The above copyright notice and this permission notice shall be included in
# all copies or substantial portions of the Software.
# 
# THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
# IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
# FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
# AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
# LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
# FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
# DEALINGS IN THE SOFTWARE.

# Original version by: Jacob Moorman <moorman@sourceforge.net>
# $Id$

# This script will check for common naming violations which can result
# in painful issues for end-users of this repository.

# 1. Verify that we do not create a file with the same name as a directory
# 2. Verify that we do not create a file named CVS or cvs
# 3. Verify that all Makefiles are mixed case, properly
# 4. Verify that all filenames differ by more than just case

# The first parameter is the CVSROOT
my $cvsroot = @ARGV[0];
shift @ARGV;

my $exit_val = 0;

# The next parameter is the directory of the commit
my $directory = @ARGV[0];

# The following parameters are the file names
shift @ARGV;

my $dir = $directory;
$dir = $1 if $directory =~ /$cvsroot(.*)/i;

my %files;

for (@ARGV) {
	my $lc = lc($_);
	if (!defined($files{$lc})) {
		$files{$lc} = ();
	}
	$files{$lc}{$_} = $_;

	# Verify that we are not committing a file of the same name
	# as a directory
	if (-d "$directory/$_") {
		print "$dir: There is already a directory named '$_'\n";
		$exit_val = 1;
	}

	# Verify that we do not commit a file named CVS or cvs.
	if (lc($_) eq 'cvs') {
		print "$dir: ",
			"You cannot commit a file named CVS or cvs.\n";
		$exit_val = 1;
	}

	# Verify that all Makefiles are named 'Makefile'
	if ((substr(lc($_), 0, 8) eq "makefile") and (substr($_, 0, 8) ne "Makefile")) {
		print "$dir: ",
			"Makefiles must be named 'Makefile', not '$_'.\n";
		$exit_val = 1;
	}
}

#use Data::Dumper;

foreach my $file (glob($directory . '/*')) {
	my $lc = lc($_);
	next if !defined($files{$lc});
	$files{$lc}{$_} = $_;
}

foreach my $lc (sort keys %files) {
	my %hash = %{$files{$lc}};
	my $files = scalar keys %hash;
	next if $files < 2;
	print "$dir: Filename case conflict:";
	foreach my $key (sort keys %hash) {
		my $file = $hash{$key};
		print ' "',$file, '"';
	}
	print "\n";
	$exit_val = 1;
}

exit ($exit_val);
