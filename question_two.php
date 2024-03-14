<?php


/**
 * Author: Chidi E. E
 * Company: Exads Interview
 * 
 * Question Two
 */


$originalAscii = range(',', '!');
$randomAscii = $originalAscii;

shuffle($randomAscii);
array_pop($randomAscii);

// Find the missing character by comparing the original and randomized arrays
$missingCharacter = array_values(array_diff($originalAscii, $randomAscii))[0];

echo ("The original ASCII characters before randomisation are : " . implode('  ', $originalAscii) . "<br>");
echo ("The new ASCII characters after randomisation and removal are : " . implode('  ', $randomAscii) . "<br>");
echo ("The value removed from the array is: $missingCharacter");
