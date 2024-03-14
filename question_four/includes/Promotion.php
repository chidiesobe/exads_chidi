<?php

/**
 * Author: Chidi E. E
 * Exads
 */

namespace PromotionsData;

class Promotion
{
    public $selectedRandomId;

    public function __construct()
    {
        $this->selectedRandomId = $this->selectRandomId();
    }

    // Generate the random ID for valid promotions
    private function selectRandomId(): int
    {
        $upperLimit = 3; // To match the number of promotions to be considered 
        $validationFilePath = __DIR__ . '/logs/valid_promotions.txt';
        $weightFilePath = __DIR__ . '/logs/weight.txt';

        // Only consider promtions that have not run up to 100 times in a given cycle
        // Note: a cycle is represents by 300 runs 
        if (file_exists($validationFilePath) && filesize($validationFilePath) > 0) {
            $valueInArray = json_decode(file_get_contents($validationFilePath), true);

            // Check if all promotions have been added for consideration
            if (count($valueInArray) < $upperLimit) {
                return $this->randomNumber($upperLimit);
            } else {
                $randomId = array_filter($valueInArray, function ($item) {
                    return $item['count'] !== 100;
                });

                if (empty($randomId)) {
                    file_put_contents($validationFilePath, '');
                    file_put_contents($weightFilePath, '');
                    return $this->randomNumber($upperLimit);
                } else {
                    $randomValue = array_rand($randomId);
                    return $randomId[$randomValue]['id'];
                }
            }
        } else {
            // Generate random number using upper limit, the upper limit must always match the 
            // number of available promotions you want to consider.
            return $this->randomNumber($upperLimit);
        }
    }

    private function randomNumber($upperLimit): int
    {
        return mt_rand(1, $upperLimit);
    }
}
