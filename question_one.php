<?php

/**
 * Author: Chidi E. E
 * Company: Exads Interview
 * 
 * Question One
 */


define("PRIME_DIVISOR_COUNT", 2);


/**
 * Find divisors of a given number
 *
 * @param int $number The number to find divisors for
 * @return array An array containing the divisors of the number
 */
function findDivisors(int $number): array
{
    $divisors = array();
    foreach (range(1, $number) as $multiple_off) {
        if ($number % $multiple_off === 0) {
            $divisors[] = $multiple_off;
        }
    }
    return $divisors;
}


/**
 * Check if a number is a prime number
 *
 * @param int $number The number to check for primality
 * @return bool True if the number is prime, false otherwise
 */
function isPrimeNumber(int $number): bool
{
    return count(findDivisors($number)) === PRIME_DIVISOR_COUNT;
}

/**
 * Get the result for a given number, indicating if it's prime or listing its divisors
 *
 * @param int $number The number to analyze
 * @return string A string indicating if the number is prime or listing its divisors
 */
function getResult(int $number): string
{
    $divisors = findDivisors($number);
    return (isPrimeNumber($number)) ? "$number (PRIME)" :  "$number (" . implode(', ', $divisors) . ")";
}


// Generate results
$final_result = array_map('getResult', range(1, 100));
echo implode('<br>', $final_result);
