<?php
namespace Platform\Controllers\Core;

use DeviceDetector\DeviceDetector;
use DeviceDetector\Parser\Device\DeviceParserAbstract;

/**
 * Piwik class
 *
 * Depends on Guzzle (guzzlephp.org)
 *
 */

class Piwik {

  /**
   * Device detector 
   */
  public static function getDevice($userAgent = NULL)
  {
    // Parse useragent and cache results
    if($userAgent == NULL) $userAgent = $_SERVER['HTTP_USER_AGENT'];

    $ua = \Cache::rememberForever('ua-' . md5($userAgent) , function() use($userAgent)
    {
      // OPTIONAL: Set version truncation to none, so full versions will be returned
      // By default only minor versions will be returned (e.g. X.Y)
      // for other options see VERSION_TRUNCATION_* constants in DeviceParserAbstract class
      //\DeviceParserAbstract::setVersionTruncation(\DeviceParserAbstract::VERSION_TRUNCATION_NONE);

      $ua = new DeviceDetector($userAgent);
      $ua->discardBotInformation();
      $ua->parse();

      if($ua->isBot()) {
        // handle bots,spiders,crawlers,...
        $ua_parse['client'] = $ua->getBot();
        $ua_parse['os'] = NULL;
        $ua_parse['device'] = NULL;
        $ua_parse['brand'] = NULL;
        $ua_parse['model'] = NULL;
      } else {
        $ua_parse['client'] = $ua->getClient(); // holds information about browser, feed reader, media player, ...
        $ua_parse['os'] = $ua->getOs();
        $ua_parse['device'] = ucwords($ua->getDeviceName());
        $ua_parse['brand'] = $ua->getBrand();
        $ua_parse['model'] = $ua->getModel();
      }

      return $ua_parse;
    });

    $response = array(
      'os' => $ua['os']['name'],
      'client' => $ua['client']['name'] /*. ' ' . $ua['client']['version']*/,
      'device' => ($ua['device'] == '') ? NULL : $ua['device'],
      'brand' =>  ($ua['brand'] == '') ? NULL : $ua['brand'],
      'model' => ($ua['model'] == '') ? NULL : $ua['model']
    );

    return $response;
  }
}