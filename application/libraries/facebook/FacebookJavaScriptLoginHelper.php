<?php
/**
 * *
 *  * Created by PhpStorm.
 *  * User: boonkhailim
 *  * Year: 2017
 *
 */
namespace Facebook;

/**
 * Class FacebookJavaScriptLoginHelper
 * @package Facebook
 * @author Fosco Marotto <fjm@fb.com>
 * @author David Poll <depoll@fb.com>
 */
class FacebookJavaScriptLoginHelper extends FacebookSignedRequestFromInputHelper
{

  /**
   * Get raw signed request from the cookie.
   *
   * @return string|null
   */
  public function getRawSignedRequest()
  {
    return $this->getRawSignedRequestFromCookie();
  }

}
