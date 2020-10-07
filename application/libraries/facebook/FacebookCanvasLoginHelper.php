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
 * Class FacebookCanvasLoginHelper
 * @package Facebook
 * @author Fosco Marotto <fjm@fb.com>
 * @author David Poll <depoll@fb.com>
 */
class FacebookCanvasLoginHelper extends FacebookSignedRequestFromInputHelper
{

  /**
   * Returns the app data value.
   *
   * @return mixed|null
   */
  public function getAppData()
  {
    return $this->signedRequest ? $this->signedRequest->get('app_data') : null;
  }

  /**
   * Get raw signed request from POST.
   *
   * @return string|null
   */
  public function getRawSignedRequest()
  {
    $rawSignedRequest = $this->getRawSignedRequestFromPost();
    if ($rawSignedRequest) {
      return $rawSignedRequest;
    }

    return null;
  }

}
