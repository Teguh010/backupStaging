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
 * Class FacebookPageTabHelper
 * @package Facebook
 * @author Fosco Marotto <fjm@fb.com>
 */
class FacebookPageTabHelper extends FacebookCanvasLoginHelper
{

  /**
   * @var array|null
   */
  protected $pageData;

  /**
   * Initialize the helper and process available signed request data.
   *
   * @param string|null $appId
   * @param string|null $appSecret
   */
  public function __construct($appId = null, $appSecret = null)
  {
    parent::__construct($appId, $appSecret);

    if (!$this->signedRequest) {
      return;
    }

    $this->pageData = $this->signedRequest->get('page');
  }

  /**
   * Returns a value from the page data.
   *
   * @param string $key
   * @param mixed|null $default
   *
   * @return mixed|null
   */
  public function getPageData($key, $default = null)
  {
    if (isset($this->pageData[$key])) {
      return $this->pageData[$key];
    }
    return $default;
  }

  /**
   * Returns true if the page is liked by the user.
   *
   * @return boolean
   */
  public function isLiked()
  {
    return $this->getPageData('liked') === true;
  }

  /**
   * Returns true if the user is an admin.
   *
   * @return boolean
   */
  public function isAdmin()
  {
    return $this->getPageData('admin') === true;
  }

  /**
   * Returns the page id if available.
   *
   * @return string|null
   */
  public function getPageId()
  {
    return $this->getPageData('id');
  }

}
