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
 * Class GraphUser
 * @package Facebook
 * @author Fosco Marotto <fjm@fb.com>
 * @author David Poll <depoll@fb.com>
 */
class GraphUser extends GraphObject
{

  /**
   * Returns the ID for the user as a string if present.
   *
   * @return string|null
   */
  public function getId()
  {
    return $this->getProperty('id');
  }

  /**
   * Returns the name for the user as a string if present.
   *
   * @return string|null
   */
  public function getName()
  {
    return $this->getProperty('name');
  }
  
  /**
   * Returns the email for the user as a string if present.
   *
   * @return string|null
   */
  public function getEmail()
  {
    return $this->getProperty('email');
  }
  
  /**
   * Returns the locale for the user as a string if present.
   *
   * @return string|null
   */
  public function getLocale()
  {
    return $this->getProperty('locale');
  }

  /**
   * Returns the first name for the user as a string if present.
   *
   * @return string|null
   */
  public function getFirstName()
  {
    return $this->getProperty('first_name');
  }

  /**
   * Returns the middle name for the user as a string if present.
   *
   * @return string|null
   */
  public function getMiddleName()
  {
    return $this->getProperty('middle_name');
  }

  /**
   * Returns the last name for the user as a string if present.
   *
   * @return string|null
   */
  public function getLastName()
  {
    return $this->getProperty('last_name');
  }
  
  /**
   * Returns the gender for the user as a string if present.
   *
   * @return string|null
   */
  public function getGender()
  {
    return $this->getProperty('gender');
  }

  /**
   * Returns the Facebook URL for the user as a string if available.
   *
   * @return string|null
   */
  public function getLink()
  {
    return $this->getProperty('link');
  }

  /**
   * Returns the users birthday, if available.
   *
   * @return \DateTime|null
   */
  public function getBirthday()
  {
    $value = $this->getProperty('birthday');
    if ($value) {
      return new \DateTime($value);
    }
    return null;
  }

  /**
   * Returns the current location of the user as a FacebookGraphLocation
   *   if available.
   *
   * @return GraphLocation|null
   */
  public function getLocation()
  {
    return $this->getProperty('location', GraphLocation::className());
  }

  /**
   * Returns the timezone for the user as a int if present.
   *
   * @return string|null
   */
  public function getTimezone()
  {
    return $this->getProperty('timezone');
  }
}
