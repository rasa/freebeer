#!/usr/bin/perl

# $CVSHeader: _freebeer/bin/dia2gs.pl,v 1.1.1.1 2004/01/18 00:12:03 ross Exp $

# Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
# Licensed under the BSD or LGPL License. See doc/license.txt for details.

use strict;
$|++;

my $diagramdata = 0;
my $attribute_paper = 0;
my $composite_paper = 0;
my $attribute_name = 0;
my $paper_size = 0;
my $attribute_name_closed = 0;
my $attribute_is_portrait = 0;
my $paper_orientation = 0;

my %size_map = (
	'#Letter#' => 'letter',
	'#Tabloid#' => 'ledger',
);

my %orientation_map = (
	'true' => '',
	'false' => '-dORIENT1=false',
);

while (<>) {
	chomp;

	if (!$diagramdata) {
		$diagramdata = m~\s*<\s*dia:diagramdata\s*>\s*$~;
		next;
	}

	if (!$attribute_paper) {
		$attribute_paper = m~\s*<\s*dia:attribute\s+name\s*=\s*['"]?paper['"]?\s*>\s*$~;
		next;
	}
	
	if (!$composite_paper) {
		$composite_paper = m~\s*<dia:composite type=['"]?paper['"]?>\s*$~;
		next;
	}

	if (!$attribute_name) {
		$attribute_name = m~\s*<\s*dia:attribute\s+name\s*=\s*['"]?name['"]?\s*>\s*$~;
		next;
	}

	if (!$paper_size) {
		$paper_size = $1 if m~^\s*<\s*dia:string\s*>([^<]+)<\s*/dia:string\s*>\s*$~;
		next;
	}

	if (!$attribute_name_closed) {
		$attribute_name_closed = m~\s*<\s*/dia:attribute\s*>\s*~;
		next;
	}

	if (!$attribute_is_portrait) {
		$attribute_is_portrait = m~\s*<\s*dia:attribute\s+name\s*=\s*['"]?is_portrait['"]?\s*>\s*~;
		next;
	}

	if (!$paper_orientation) {
		$paper_orientation = $1 if m~\s*<\s*dia:boolean\s+val\s*=\s*['"]?([^'"/]+)['"]?\s*/\s*>\s*~;
		printf "-sPAPERSIZE=%s %s", $size_map{$paper_size}, $orientation_map{$paper_orientation};
		exit 0;
	}
}

die "Invalid data: paper_size=$paper_size";
