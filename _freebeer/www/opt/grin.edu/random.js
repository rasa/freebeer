// File:
//   random.js
// Author:
//   Samuel A. Rebelsky, based on original code by
//   David N. Smith of IBM TJ Watson
// Description:
//   A simple random number generator and some functions 
//   to make it easier to use.
// Note:
//   It is, of course, difficult to programmatically produce
//   a truly "random" number.  This program produces a
//   pseudo-random sequence of numbers.
// Comment:
//   The generator is created and seeded when the page is loaded.
//   You can create your own random number generator with a
//     seed of your choice (good for testing when you need
//     a "reproducible" random sequence).
// Provides:
//   RandomNumberGenerator() object
//     The .next() method gives you the next random number
//   randomInRange(N)
//     Returns a random integer between 1 and N
//   randomFraction()
//     Returns a random floating point number between 0 and 1
//   seedRandom()
//     Seeds our random number generator
// Last modified:
//   Friday, June 13, 1997

// Function
//   NextRandomNumber
// Description
//   A method for the RandomNumberGenerator object that
//   gives the next random number.
// Note
//   This function must appear before RandomNumberGenerator
//   in the program code.
function NextRandomNumber() {
  var hi = this.seed / this.Q
  var lo = this.seed % this.Q
  var test = this.A * lo - this.R * hi;
  if (test > 0) {
    this.seed = test
  }
  else {
    this.seed = test + this.M
  }
  return (this.seed * this.oneOverM)
} // random

// Function
//   RandomNumberGenerator(seed)
// Description
//   Creates and returns new random number generator object
// Note
//   The seed is currently ignored due to an odd problem
function RandomNumberGenerator(seed) 
{
  var d = new Date()
  if (1) {
    this.seed = 
      2345678901 + 
      (d.getSeconds() * 0xFFFFFF) + 
      (d.getMinutes() * 0xFFFF);
  }
  else {
    this.seed = seed
  }
  this.A = 48271;
  this.M = 2147483647;
  this.Q = this.M / this.A
  this.R = this.M % this.A
  this.oneOverM = 1.0 / this.M
  this.next = NextRandomNumber
  return this
}

// The generator we use for the utility functions
var rgen = new RandomNumberGenerator(0);

// Function
//   randomInRange(N)
// Description
//   Returns a "random" number between 1 and N
function randomInRange(N) 
{
  return 1 + Math.floor(N*randomFraction())
} // randomInRange

// Function
//   randomFraction()
// Description
//   Returns a "random" number between 0 and 1
function randomFraction() 
{
  return rgen.next()
} // randomFraction

// Function
//   seedRandom()
// Restart our random number generator with a random number
function seedRandom(seed)
{
  rgen = RandomNumberGenerator(seed)
} // seedRamdon()

