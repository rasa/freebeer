<?php

// $CVSHeader: _freebeer/lib/Backport/SHA1.php,v 1.1.1.1 2004/01/18 00:12:04 ross Exp $

// Copyright (c) 2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

/*!
	\file Backport/SHA1.php
	\brief SHA1() emulation class for pre-PHP 4.3.0 systems.

	Source: http://www.bouncingchairs.net/oss/shaphp.tar.gz

	Bug fixes and modifications by Ross Smith
*/

////////////////////////////////////////////////////////////////////////////
// SHA implementation  v1.1
//
// Feb 25, 2003: Fixed a bug for strings longer than 55 bytes (oops)
//
// Based on the SHA algorithm as given in "Applied Cryptography"
// Code written by Chris Monson (chris@bouncingchairs.net)
// Most recent version available on http://bouncingchairs.net
// Licensed under the GNU LGPL (http://www.gnu.org/copyleft/lesser.html)
// April 11, 2000
// License changed (I'm an idiot) June 26, 2000
////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////
// USAGE:
//------------------------------------------------------------------------
//      
//      Simple text hash:
//      
//      $sha = new SHA;
//      $hasharray = $sha->hash_string( 'hash me!' );
//
//      This returns an array of 5 32-bit integers.
//      The SHA.hash_bytes function does the same thing, but requires
//      an array of bytes as input.  Note that the input values will be
//      truncated if they are larger than 8 bits.
//
//------------------------------------------------------------------------
//
//      There are also some hash to string conversion functions.  The
//      naming convention admittedly could be better, but it works :).
//
//      $sha->hash_to_string( $hasharray )
//      
//      Converts the hash array to an uppercase hex string.
//
//------------------------------------------------------------------------
//
//      Hashing very large blocks a piece at a time:
//
//      $sha = new SHA;
//      $sha->init();
//      while (blocks_to_process) {
//          $sha->update( next_byte_array )
//      }
//      $hasharray = $sha->finalize()
//      
////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////
// NOTES:
//      This is basically a rip off of SHAPerl.pm, which I also wrote.
//      I discovered, much to my chagrin, that PHP does not have even
//      the crappy 32-bit int support that Perl has, so I had to employ
//      some funny tricks in the code to get it to use all 32 bits.
//      One of the most obvious of these is using an 'add' method instead
//      of just adding numbers together.  Any numbers over 32 bits don't get
//      bit-truncated.  They get corralled, which is not what I wanted.
//      Another trick I had to employ was splitting large numeric constants
//      into two pieces.  Apparently, you can't specify 0xffffffff.  It gets
//      set to 0.  Everything up to 0x7fffffff works fine.  So, I used
//      some shifting and bitwise operators to get the needed constants.
//
//      A word on optimization: it isn't optimized.  My chief concern was
//      to get it working, and it is fast enough for my needs.  If, however,
//      you intend to try to brute force some hash values with this, either
//      it will need some serious optimizations done, or you should be
//      using one of the freely available C implementations.
//
////////////////////////////////////////////////////////////////////////////
/*!
	\class fbSHA1
	
	\brief SHA1() emulation class for pre-PHP 4.3.0 systems.
*/
class fbSHA1 {
	function fbSHA1() {
		if (phpversion() == '4.1.0') {
			trigger_error('fbSHA1 functions return incorrect results in PHP 4.1.0. Please upgrade PHP.', E_USER_WARNING);
			return;
		}
		$this->init();
	}

	var $A, $B, $C, $D, $E; // result variables

	var $ta, $tb, $tc, $td, $te; // temp variables

	var $K0_19, $K20_39, $K40_59, $K60_79;

	var $buffer;

	var $buffsize;

	var $totalsize;

	function init() {
		// The long constants can't be used for some dumb reason.
		$this->A			= 0x6745 << 16 | 0x2301;
		$this->B			= 0xefcd << 16 | 0xab89;
		$this->C			= 0x98ba << 16 | 0xdcfe;
		$this->D			= 0x1032 << 16 | 0x5476;
		$this->E			= 0xc3d2 << 16 | 0xe1f0;
		$this->ta			= $this->A;
		$this->tb			= $this->B;
		$this->tc			= $this->C;
		$this->td			= $this->D;
		$this->te			= $this->E;
		$this->K0_19		= 0x5a82 << 16 | 0x7999;
		$this->K20_39		= 0x6ed9 << 16 | 0xeba1;
		$this->K40_59		= 0x8f1b << 16 | 0xbcdc;
		$this->K60_79		= 0xca62 << 16 | 0xc1d6;
		$this->buffer		= array();
		$this->buffsize		= 0;
		$this->totalsize	= 0;
	}

	function bytes_to_words($block) {
		$nblk = array();
		for ($i=0; $i < 16; ++$i) {
			$index = $i * 4;
			$nblk[$i] = 0;
			$nblk[$i] |= ($block[$index    ] & 0xff) << 24;
			$nblk[$i] |= ($block[$index + 1] & 0xff) << 16;
			$nblk[$i] |= ($block[$index + 2] & 0xff) << 8;
			$nblk[$i] |= ($block[$index + 3] & 0xff);
		}
		return $nblk;
	}

/*
	function pad_block($block, $size) {
		// Returns a block that is a multiple of 512 bits long
		$blksize = sizeof( $block );
		$bits = $size * 8;
		// Always pad with 0x80, then add as many zeros as necessary to
		// make the message 64 bits short of 512. Then add the 64-bit size.
		$newblock = $block;
		$newblock[] = 0x80; // push 0x80 onto the end
		// Add the zeros
		while((sizeof($newblock) % 64) != 56) {
			$newblock[] = 0;
		}
		// Add the size
		for ($i=0; $i<8; ++$i) {
			$newblock[] = ($i<4) ? 0 : ($bits >> ((7-$i)*8)) & 0xff;
		}
		return $newblock;
	}
*/
	function pad_block($block, $size) {
		// Returns a block that is a multiple of 512 bits long
		$blksize = sizeof($block);
		$bits = $size * 8;
		// Always pad with 0x80, then add as many zeros as necessary to
		// make the message 64 bits short of 512. Then add the 64-bit size.
		$newblock = $block;
		$newblock[] = 0x80; // push 0x80 onto the end

		// Add the zeros
		while((sizeof($newblock) % 64) != 56) {
			$newblock[] = 0;
		}
//assert('sizeof($newblock) == 56');
//printf("sizeof($newblock)=%d\n", sizeof($newblock));

		// Add the size
		for ($i=0; $i < 8; ++$i) {
			$newblock[] = ($i < 4) ? 0 : ($bits >> ((7 - $i) * 8)) & 0xff;
		}
//assert('sizeof($newblock) == 64');
//printf("sizeof($newblock)=%d\n", sizeof($newblock));
		return $newblock;
	}

	function circ_shl($num, $amt) {
		$leftmask	= 0xffff | (0xffff << 16);
		$leftmask	<<= 32 - $amt;
		$rightmask	= 0xffff | (0xffff << 16);
		$rightmask	<<= $amt;
		$rightmask	= ~$rightmask;
		$remains	= $num & $leftmask;
		$remains	>>= 32 - $amt;
		$remains	&= $rightmask;
		return ($num << $amt) | $remains;
	}

	function f0_19($x, $y, $z) {
		return ($x & $y) | (~$x & $z);
	}

	function f20_39($x, $y, $z) {
		return ($x ^ $y ^ $z);
	}

	function f40_59($x, $y, $z) {
		return ($x & $y) | ($x & $z) | ($y & $z);
	}

	function f60_79($x, $y, $z) {
		return ($x ^ $y ^ $z);
	}

	function expand_block($block) {
		$nblk = $block;
		for ($i = 16; $i < 80; ++$i ) {
			$nblk[$i] = $this->circ_shl($nblk[$i - 3] ^ $nblk[$i - 8] ^ $nblk[$i - 14] ^ $nblk[$i - 16], 1);
		}
		return $nblk;
	}

/*
	function print_bytes( $bytes ) {
		$len = sizeof( $bytes );
		for ( $i=0; $i<$len; ++$i) {
			$str[] = sprintf( "%02x", $bytes[$i] );
		}
		print( join( ", ", $str ) . "\n" );
	}

	function wordstr( $word ) {
		return sprintf(
			"%04x%04x", ($word >> 16) & 0xffff, $word & 0xffff
		);
	}

	function print_words( $words ) {
		$len = sizeof( $words );
		for ( $i=0; $i<$len; ++$i) {
			$str[] = $this->wordstr( $words[$i] );
		}
		print( join( ", ", $str ) . "\n" );
	}

	function hash_to_string( $hash ) {
		$len = sizeof( $hash );
		for ($i=0; $i<$len; ++$i) {
			$astr[] = $this->wordstr( $hash[$i] );
		}
		return join( "", $astr );
	}
*/

	// Add simply adds two numbers. It is provided for compatibility on
	// platforms that only support a 31 bit add (there are a few, apparently)
	function add($a, $b) {
		$ma = ($a >> 16) & 0xffff;
		$la = ($a) & 0xffff;
		$mb = ($b >> 16) & 0xffff;
		$lb = ($b) & 0xffff;
		$ls = $la + $lb;
		// Carry
		if ($ls > 0xffff) {
			++$ma;
			$ls &= 0xffff;
		}
		// MS add
		$ms = ($ma + $mb) & 0xffff;
		// Works because the bitwise operators are 32 bit
		return ($ms << 16) | $ls;
	}

	function process_block($blk) {
		$blk = $this->expand_block($blk);
		for ($i = 0; $i < 80; ++$i) {
			$t = $this->circ_shl($this->ta, 5);
			if ($i < 20) {
				$f = $this->f0_19($this->tb, $this->tc, $this->td);
				$k = $this->K0_19;
			} elseif ($i < 40) {
				$f = $this->f20_39($this->tb, $this->tc, $this->td);
				$k = $this->K20_39;
			} elseif ($i < 60) {
				$f = $this->f40_59($this->tb, $this->tc, $this->td);
				$k = $this->K40_59;
			} else {
				$f = $this->f60_79($this->tb, $this->tc, $this->td);
				$k = $this->K60_79;
			}
			$t = $this->add($t, $f);
			$t = $this->add($t, $this->te);
			$t = $this->add($t, $blk[$i]);
			$t = $this->add($t, $k);
			$this->te = $this->td;
			$this->td = $this->tc;
			$this->tc = $this->circ_shl($this->tb, 30);
			$this->tb = $this->ta;
			$this->ta = $t;
		}
		$this->A = $this->add($this->A, $this->ta);
		$this->B = $this->add($this->B, $this->tb);
		$this->C = $this->add($this->C, $this->tc);
		$this->D = $this->add($this->D, $this->td);
		$this->E = $this->add($this->E, $this->te);

		$this->ta = $this->A;
		$this->tb = $this->B;
		$this->tc = $this->C;
		$this->td = $this->D;
		$this->te = $this->E;
	}

	function update($bytes) {
		$length = sizeof($bytes);
		$index = 0;
		// Process each full block
		while (($length - $index) + $this->buffsize >= 64) {
			for ($i = $this->buffsize; $i < 64; ++$i) {
				$this->buffer[$i] = $bytes[$index + $i - $this->buffsize];
			}
			$this->process_block($this->bytes_to_words($this->buffer));
			$index += 64;
			$this->buffsize = 0;
		}
		// Any remaining bytes that do not make up a full block need to be
		// added into the buffer for the next update (or final)
		$remaining = $length - $index;
		for ($i = 0; $i < $remaining; ++$i) {
			$this->buffer[$this->buffsize + $i] = $bytes[$index + $i];
		}
		$this->buffsize += $remaining;
		$this->totalsize += $length;
	}

	function finalize() {
		// Pad and process the buffer
		$last_block = array_slice($this->buffer, 0, $this->buffsize);
		// Pad the block
		$last_block = $this->pad_block($last_block, $this->totalsize);
		// Process the last one (or two) block(s)
		$index = 0;
		$length = sizeof($last_block);
		while ($index < $length) {
			$block = array_slice($last_block, $index, 64);
			$this->process_block($this->bytes_to_words($block));
			$index += 64;
		}
		return array(
			$this->A,
			$this->B,
			$this->C,
			$this->D,
			$this->E,
		);
	}

	function hash_bytes($bytes) {
		$this->init();
		$this->update($bytes);
		return $this->finalize();
	}

	function hash_string($str) {
		$len = strlen($str);
		$bytes = array();
		for ($i = 0; $i < $len; ++$i) {
			$bytes[] = ord($str[$i]);
		}
		return $this->hash_bytes($bytes);
	}

	function sha1($str) {
		$r = $this->hash_string($str);
		return sprintf('%08x%08x%08x%08x%08x', $r[0], $r[1], $r[2], $r[3], $r[4]);
	}
}

if (!function_exists('sha1')) {
	function sha1($str) {
		$sha1 = &new fbSHA1();
		return $sha1->sha1($str);
	}
}

?>
