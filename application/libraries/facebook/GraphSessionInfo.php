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
 * Class GraphSessionInfo
 * @package Facebook
 * @author Fosco Marotto <fjm@fb.com>
 * @author David Poll <depoll@fb.com>
 */
class GraphSessionInfo extends GraphObject
{

  /**
   * Returns the application id the token was issued for.
   *
   * @return string|null
   */
  public function getAppId()
  {
    return $this->getProperty('app_id');
  }

  /**
   * Returns the application name the token was issued for.
   *
   * @return string|null
   */
  public function getApplication()
  {
    return $this->getProperty('application');
  }

  /**
   * Returns the date & time that the token expires.
   *
   * @return \DateTime|null
   */
  public function getExpiresAt()
  {
    $stamp = $this->getProperty('expires_at');
    if ($stamp) {
      return (new \DateTime())->setTimestamp($stamp);
    } else {
      return null;
    }
  }

  /**
   * Returns whether the token is valid.
   *
   * @return boolean
   */
  public function isValid()
  {
    return $this->getProperty('is_valid');
  }

  /**
   * Returns the date & time the token was issued at.
   *
   * @return \DateTime|null
   */
  public function getIssuedAt()
  {
    $stamp = $this->getProperty('issued_at');
    if ($stamp) {
      return (new \DateTime())->setTimestamp($stamp);
    } else {
      return null;
    }
  }

  /**
   * Returns the scope permissions associated with the token.
   *
   * @return array
   */
  public function getScopes()
  {
    return $this->getPropertyAsArray('scopes');
  }

  /**
   * Returns the login id of the user associated with the token.
   *
   * @return string|null
   */
  public function getId()
  {
    return $this->getProperty('user_id');
  }

}