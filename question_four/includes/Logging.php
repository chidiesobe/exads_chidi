<?php

/**
 * Author: Chidi E. E
 * Exads
 */

namespace PromotionsData;

trait Logging
{
    protected function logCount(int $promoId, string $promotionName, string $processedCountFilePath, string $validPromotionFilePath, string $weightFilePath, array $getDesigns): void
    {
        $this->updateProcessedCount($promotionName, $processedCountFilePath);
        $this->updateValidPromotion($promoId, $promotionName, $validPromotionFilePath);
        $this->updateWeightLog($getDesigns, $weightFilePath);
    }

    // Keeps track of how many times a promotion has been shown
    // it does not refresh when a cycle is completed
    private function updateProcessedCount($promotionName, $processedCountFilePath): void
    {
        $existingData = file_exists($processedCountFilePath) ? json_decode(file_get_contents($processedCountFilePath), true) : [];
        $existingData[$promotionName] = $existingData[$promotionName] ?? 0;
        file_put_contents($processedCountFilePath, json_encode($existingData, JSON_PRETTY_PRINT));
    }

    // ValidPromotionLog tracks number of times a promotion has run in a given cycle (300 runs) 
    // excluding completed promotions that hav their runs (count = 100)
    private function updateValidPromotion($promoId, $promotionName, $validPromotionFilePath): void
    {
        $existingData = file_exists($validPromotionFilePath) ? json_decode(file_get_contents($validPromotionFilePath), true) : [];
        $existingData[$promotionName] = $existingData[$promotionName] ?? ['count' => 0, 'id' => $promoId];
        file_put_contents($validPromotionFilePath, json_encode($existingData, JSON_PRETTY_PRINT));
    }


    // WeightLog ensure all designs are considered before the end of a cycle (300 runs)
    // After consideringation the weight log is cleared for a fresh cycle to begin
    private function updateWeightLog($getDesigns, $weightFilePath): void
    {
        $existingData = !file_exists($weightFilePath) ? [] : json_decode(file_get_contents($weightFilePath), true);
        foreach ($getDesigns as $design) {
            $designName = $design['designName'];
            $splitPercentage = $design['splitPercent'];

            if (!isset($existingData[$designName])) {
                $existingData[$designName] = $splitPercentage;
            }
        }
        file_put_contents($weightFilePath, json_encode($existingData, JSON_PRETTY_PRINT));
    }
}
