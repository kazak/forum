<?php

namespace App\CoreBundle\Provider\Geo;

/**
 * This class encapsulate REST services delivered from a ArcGIS cloud based service.
 */
class ArcgisProvider
{
    private $username;
    private $password;
    private $token = '';
    private $tokenExpires = 0;
    private $ssl;
    private $tokenUrl;
    private $baseUrl;
    private $referer;
    private $returnType;
    private $inSR;
    private $wkid;

    /**
     * Create a instance of the class.
     *
     * Username and password to is used to authenticate when creating tokens
     * that is used when accessing the services delivered by ArcGIS.
     * The username and password here is said to be the same as for the Geodata
     * address lookup service, but it is separate services and we are not
     * guaranteed that this will be the case in the future.
     *
     * @param $config
     *
     * @throws \Exception
     */
    public function __construct($config)
    {
        $this->username = $this->getConfigKey($config, 'username');
        $this->password = $this->getConfigKey($config, 'password');
        $this->baseUrl = $this->getConfigKey($config, 'baseurl');
        $this->tokenUrl = $this->getConfigKey($config, 'tokenurl');
        $this->returnType = $this->getConfigKey($config, 'return_type');
        $this->inSR = $this->getConfigKey($config, 'inSR');
        $this->wkid = $this->getConfigKey($config, 'wkid');
        $this->referer = $this->getConfigKey($config, 'referer');
        $this->token = $this->getConfigKey($config, 'token');
        $this->tokenExpires = $this->getConfigKey($config, 'tokenExpires');

        if ($this->tokenExpires < time()) {
            $this->generateToken();
        }
    }

    /**
     * @param array  $config
     * @param string $key
     *
     * @return mixed
     */
    private function getConfigKey($config, $key)
    {
        if (!isset($config[$key])) {
            return [];
        }

        return $config[$key];
    }

    /**
     * @return array
     */
    public function getTokenData()
    {
        return [
            'token' => $this->token,
            'expires' => $this->tokenExpires,
        ];
    }

    /**
     * @param $url
     */
    public function setBaseUrl($url)
    {
        $this->baseUrl = $url;
    }

    /**
     * Method that generates access tokens needed in the other RESTful services.
     *
     * If a error occurs during this process Exceptions is thrown.
     */
    private function generateToken()
    {
        $url = $this->tokenUrl;

        $data = [];
        $data['username'] = $this->username;
        $data['password'] = $this->password;
        $data['referer'] = $this->referer;
        $data['f'] = $this->returnType;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));

        $response = curl_exec($curl);

        // Unknown error
        if (curl_getinfo($curl, CURLINFO_HTTP_CODE) !== 200) {
            throw new \Exception('Not able to generate token for delivery zone service!');
        } else {
            $response = json_decode($response);

            if (isset($response->error)) {
                // Generate new token and retry if error is "Token required"
                if (isset($response->error->message)) {
                    throw new \Exception($response->error->message);
                } else {
                    throw new \Exception('Not able to generate token for delivery zone service (2)!');
                }
            }

            // New token generated
            $this->token = $response->token;
            $this->tokenExpires = round($response->expires / 1000) - 60;
            $this->ssl = $response->ssl;

            // TODO: Save token and expiration time in DB so we don't have to authenticate for each request.
        }
    }

    /**
     * This method should be used to.
     *
     * @param int $longitude - X posision receieved from Geodata address lookup.
     * @param int $latitude  - Y posision receieved from Geodata address lookup.
     * @param int $retries   is used to stop infite recursion that may happen if the token is not working.
     *
     * @return array of strings with the delivery zone(s) that is found on the x,y coordinate.
     *               Hopefully only a single zone will be returned, but we can't be sure of this.
     *
     * @throws \Exception
     */
    public function findZone($longitude, $latitude, $retries = 1)
    {
        $deliveryZones = [];

        $url = $this->baseUrl.'/FeatureServer/0/query?geometry='.urlencode($longitude).','.urlencode($latitude).'&geometryType=esriGeometryPoint&inSR='.$this->inSR.'&spatialRel=esriSpatialRelIntersects&outFields=*&returnGeometry=true&f='.$this->returnType.'&token='.urlencode($this->token);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($curl);

        // Unknown error
        if (curl_getinfo($curl, CURLINFO_HTTP_CODE) !== 200) {
            throw new \Exception('Delivery zone query finished with errors!');
        } else {
            $response = json_decode($response);

            if (isset($response->error)) {
                // Generate new token and retry if error is "Token required"
                if ($response->error->code == 499 && $retries > 0) {
                    $this->generateToken();

                    return $this->findZone($longitude, $latitude, 0);
                } else {
                    throw new \Exception($response->error->message);
                }
            }

            if ($this->checkFeatures($response)) {
                foreach ($response->features as $zone) {
                    if (isset($zone->attributes) && isset($zone->attributes->ID)) {
                        $deliveryZones[] = $zone->attributes->ID;
                    }
                }
            }

            return $deliveryZones;
        }
    }

    /**
     * This method should be used to return all delivery zones defined in ArcGIS.
     * It should be used in the backend interface to generate list of missing
     * delivery zones missing in Backoffice and in ArcGIS.
     *
     * @param int $retries is used to stop infite recursion that may happen if the token is not working.
     *
     * @return array of strings with the delivery zone(s) that is found in ArcGIS.
     *
     * @throws \Exception
     */
    public function getAllZones($retries = 1)
    {
        $url = $this->baseUrl.'/FeatureServer/0/query?where=1%3D1&outFields=*&f='.$this->returnType.'&token='.urlencode($this->token);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($curl);

        // Unknown error
        if (curl_getinfo($curl, CURLINFO_HTTP_CODE) !== 200) {
            throw new \Exception('Delivery zone query finished with errors!');
        } else {
            $response = json_decode($response);

            if (isset($response->error)) {
                // Generate new token and retry if error is "Token required"
                if ($response->error->code == 499 && $retries > 0) {
                    $this->generateToken();

                    return $this->getAllZones(0);
                } else {
                    throw new \Exception($response->error->message);
                }
            }

            $deliveryZones = [];
            if (isset($response->features) && is_array($response->features)) {
                foreach ($response->features as $zone) {
                    if ($this->checkZoneAttributes($zone)) {
                        $deliveryZones[] = $zone->attributes->ID;
                    }
                }
            }

            return $deliveryZones;
        }
    }

    /**
     * This method should be used store a feature to the ArcGIS solution.
     *
     * @param int   $longitude  - X posision receieved from Geodata address lookup.
     * @param int   $latitude   - Y posision receieved from Geodata address lookup.
     * @param array $attributes array of the attributes that should be stored on the point.
     * @param int   $retries    is used to stop infite recursion that may happen if the token is not working.
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function addFeature($longitude, $latitude, $attributes, $retries = 1)
    {
        $features = [];
        $features[] = [
                'geometry' => ['x' => $longitude, 'y' => $latitude, 'spatialReference' => ['wkid' => $this->inSR]],
                'attributes' => $attributes,
        ];

        $url = $this->baseUrl.'/FeatureServer/0/addFeatures';

        $data = [];
        $data['f'] = 'json';
        $data['features'] = json_encode($features);
        $data['token'] = $this->token;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        $response = curl_exec($curl);

        // Unknown error
        if (curl_getinfo($curl, CURLINFO_HTTP_CODE) !== 200) {
            throw new \Exception('Delivery zone query finished with errors!');
        } else {
            $response = json_decode($response);

            if (isset($response->error)) {
                // Generate new token and retry if error is "Token required"
                if ($response->error->code == 499 && $retries > 0) {
                    $this->generateToken();

                    return $this->addFeature($longitude, $latitude, $attributes, 0);
                } else {
                    throw new \Exception($response->error->message);
                }
            }

            return true;
        }
    }

    /**
     * @param $zone
     *
     * @return bool
     */
    private function checkZoneAttributes($zone)
    {
        return isset($zone->attributes) && isset($zone->attributes->ID);
    }

    /**
     * @param $response
     *
     * @return bool
     */
    private function checkFeatures($response)
    {
        return isset($response->features) && is_array($response->features);
    }
}
