#! /usr/bin/perl -w
# $Id$

# This script creates a release of the calendar, as a ZIP file.  You _have_ to
# specify a release version number, such as 0.9.3, in which case the file
# "/tmp/jscalendar-0.9.3.zip" results.

use strict;
use HTML::Mason;

my $version = $ARGV[0];

die("You did not specify a release version number.  Please do so, like this:\n"
    ."make-release.pl 0.9.3") unless defined $version;

my $tmpdir = '/tmp';

# create directory tree
my ($basename, $basedir, $langdir, $docdir);
{
  # base directory
  $basename = "jscalendar-$version";
  $basedir = "$tmpdir/$basename";
  if (-d $basedir) {
    print STDERR "$basedir already exists, removing... >:-]\n";
    system "rm -rf $basedir";
  }
  mkdir $basedir;

  # lang directory
  $langdir = "$basedir/lang";
  mkdir $langdir;

  # doc directory
  $docdir = "$basedir/doc";
  mkdir $docdir;
  mkdir "$docdir/html";
}

# copy lang files
chdir 'lang';
my @lang_files = <*.js>;
print "\033[1;37mCopying\033[0m ".join(', ', @lang_files)." to \033[1;32m$langdir\033[0m.\n";
system('cp -v '.join(' ', @lang_files)." $langdir");
chdir '..';                     # back to base directory

# copy CSS files
my @css_files = <*.css>;
print "\033[1;37mCopying\033[0m ".join(', ', @css_files)." to \033[1;32m$basedir\033[0m.\n";
system('cp -v '.join(' ', @css_files)." $basedir");

# copy documentation files
print "\033[1;33mCompiling documentation...\033[0m\n";
system('pushd doc && sh makedoc.sh && cd html && sh makedoc.sh && popd');
print "\033[1;37mCopying documentation...\033[0m\n";
system("cp -v doc/reference.pdf $docdir");
system("cp -v doc/html/*.{html,css,gif,png,jpg,js} $docdir/html");

# copy main calendar files
print "\033[1;31mCopying the main program files and examples\033[0m\n";
system("cp -v *.{js,html,gif,php} README ChangeLog $basedir");

# creating "-stripped.js" files (compress JavaScript)
my $cwd = `pwd`;
chdir $basedir;
foreach my $file (<*.js>) {
  my $stripname;
  ($stripname = $file) =~ s/\.js$/_stripped.js/;
  print "  >> Compressing $file to $stripname\n";
  system ("jscrunch $file > $stripname 2>/dev/null");
}

# we have some files than need to go through Mason ;-]
foreach my $file ( "release-notes.html" ) {
  my $outbuf;
  my $interp = HTML::Mason::Interp->new ( comp_root    => $basedir,
                                          out_method   => \$outbuf );
  $interp->exec("/$file");
  open (FILE, "> $file");
  print FILE $outbuf;
  close (FILE);
}

# make the ZIP file
chdir '..';
system ("zip -r $basedir.zip $basename");

# remove the basedir
system ("rm -rf $basedir");

# back
chdir $cwd
