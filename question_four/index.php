<?php

/**
 * Author: Chidi E. E
 * Company: Exads Interview
 * 
 * Main project file
 * Question Four
 */

namespace PromotionsData;

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/includes/Logging.php';
require_once __DIR__ . '/includes/SelectDesign.php';

use Exads\ABTestData;

class AbTesting
{
    use Logging;
    use SelectDesign;

    private $data = [];
    private $promotionName;
    public $getDesigns = [];
    public $getSelectedDesign = [];

    public function __construct(int $promoId)
    {
        $abTest = new ABTestData($promoId);
        $this->data = $abTest;

        try {
            $this->logsAndSelectDesign($promoId);
        } catch (\Exception $e) {
            error_log("Error occured: " . $e->getMessage());
        }
    }

    private function logsAndSelectDesign($promoId)
    {

        $file_path = require_once __DIR__ . '/includes/FilePath.php';
        $processedCountFilePath =  $file_path['PROCESSED_FILE_PATH'];
        $validPromotionFilePath = $file_path['VALID_PROMOTION_FILE_PATH'];
        $weightFilePath = $file_path['WEIGHT_FILE_PATH'];

        $promotionName = $this->data->getPromotionName();
        $getDesigns = $this->data->getAllDesigns();


        $this->logCount($promoId, $promotionName, $processedCountFilePath, $validPromotionFilePath, $weightFilePath, $getDesigns);
        $this->selectDesign($promotionName, $getDesigns,  $weightFilePath, $processedCountFilePath, $validPromotionFilePath);
    }
}


include_once __DIR__ . '/includes/Promotion.php';
$promotion = new Promotion();

$promoId = $promotion->selectedRandomId;
$abTesting = new ABTesting($promoId);

$promotions = $abTesting->getSelectedDesign;
print_r($promotions);
