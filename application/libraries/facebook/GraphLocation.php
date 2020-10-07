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
 * Class GraphLocation
 * @package Facebook
 * @author Fosco Marotto <fjm@fb.com>
 * @author David Poll <depoll@fb.com>
 */
class GraphLocation extends GraphObject
{

  /**
   * Returns the street component of the location
   *
   * @return string|null
   */
  public function getStreet()
  {
    return $this->getProperty('street');
  }

  /**
   * Returns the city component of the location
   *
   * @return string|null
   */
  public function getCity()
  {
    return $this->getProperty('city');
  }

  /**
   * Returns the state component of the location
   *
   * @return string|null
   */
  public function getState()
  {
    return $this->getProperty('state');
  }

  /**
   * Returns the country component of the location
   *
   * @return string|null
   */
  public function getCountry()
  {
    return $this->getProperty('country');
  }

  /**
   * Returns the zipcode component of the location
   *
   * @return string|null
   */
  public function getZip()
  {
    return $this->getProperty('zip');
  }

  /**
   * Returns the latitude component of the location
   *
   * @return float|null
   */
  public function getLatitude()
  {
    return $this->getProperty('latitude');
  }

  /**
   * Returns the street component of the location
   *
   * @return float|null
   */
  public function getLongitude()
  {
    return $this->getProperty('longitude');
  }

}