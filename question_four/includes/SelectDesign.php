<?php

namespace PromotionsData;

trait SelectDesign
{
    protected function selectDesign(string $promotionName, array &$allDesigns, string $weightFilePath, string $processedCountFilePath, string $validPromotionFilePath): mixed
    {
        $weightData = [];
        $processedCountData = [];
        $validPromotionData = [];

        $weightData = file_exists($weightFilePath) ? json_decode(file_get_contents($weightFilePath), true) : '';
        $processedCountData = file_exists($processedCountFilePath) ? json_decode(file_get_contents($processedCountFilePath), true) : '';
        $validPromotionData = file_exists($validPromotionFilePath) ? json_decode(file_get_contents($validPromotionFilePath), true) : '';

        // Filter out designs with weight 0
        $validDesigns = array_filter($allDesigns, function ($design) use ($weightData) {
            return !isset($weightData[$design['designName']]) || $weightData[$design['designName']] > 0;
        });

        if (!empty($validDesigns)) {
            // Randomly select a design from designs considered valid
            $selectedDesign = $validDesigns[array_rand($validDesigns)];

            if (isset($weightData[$selectedDesign['designName']])) {
                // Update weight counts
                $weightData[$selectedDesign['designName']]--;
                $processedCountData[$promotionName]++;
                $validPromotionData[$promotionName]['count']++;

                file_put_contents($weightFilePath, json_encode($weightData, JSON_PRETTY_PRINT));
                file_put_contents($processedCountFilePath, json_encode($processedCountData, JSON_PRETTY_PRINT));
                file_put_contents($validPromotionFilePath, json_encode($validPromotionData, JSON_PRETTY_PRINT));
            } else {
                // Remove completed promotions 
                unset($validPromotionData[$promotionName]);
                file_put_contents($validPromotionFilePath, json_encode($validPromotionData, JSON_PRETTY_PRINT));
            }

            return $this->getSelectedDesign = $selectedDesign;
        }

        return null;
    }
}
