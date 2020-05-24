<?php
/**
 * Matomo - free/libre analytics platform
 *
 * @link https://matomo.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\BotTracking;

use DeviceDetector\DeviceDetector;
use Piwik\Common;
use Piwik\DeviceDetector\DeviceDetectorCache;
use Piwik\Plugin;
use Piwik\Tracker\Request;

class BotTracking extends Plugin
{

    public function registerEvents()
    {
        return array(
            'Tracker.isExcludedVisit' => 'isExcludedVisit'
        );
    }

    public function isTrackerPlugin()
    {
        return true;
    }

    public function isExcludedVisit(bool &$excluded, Request $request)
    {
        $userAgent = $request->getUserAgent();
        $userAgent = Common::unsanitizeInputValue($userAgent);
//        $deviceDetector = StaticContainer::get(DeviceDetectorFactory::class)->makeInstance($userAgent);
        // create new DeviceDetector with full data
        $deviceDetector = new DeviceDetector($userAgent);
        $deviceDetector->setCache(new DeviceDetectorCache(86400));
        $deviceDetector->parse();
        $isBot = $deviceDetector->isBot();
        if ($isBot) {
            $request->setMetadata("BotTracking", "isBot", $deviceDetector->isBot());
            $botMeta = $deviceDetector->getBot();
            $request->setMetadata("BotTracking", "botName", $botMeta["name"]);
            $request->setMetadata("BotTracking", "botCategory", $botMeta["category"]);
            $request->setMetadata("BotTracking", "botProducer", $botMeta["producer"]["name"]);
            $excluded = false;
        }
    }
}
