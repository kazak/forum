<?php

namespace App\CoreBundle\Provider\Geo;

/**
 * Class GeoDataOnlineProvider.
 */
class GeoDataOnlineProvider
{
    private $username;
    private $password;
    private $token = '';
    private $tokenExpires = 0;

    private $tokenUrl;
    private $baseUrl;

    /**
     * Create a instance of the class.
     *
     * @param array $config
     */
    public function __construct($config)
    {
        if (isset($config['username'])) {
            $this->username = $config['username'];
        }
        if (isset($config['password'])) {
            $this->password = $config['password'];
        }
        if (isset($config['baseurl'])) {
            $this->baseUrl = $config['baseurl'];
        }
        if (isset($config['tokenurl'])) {
            $this->tokenUrl = $config['tokenurl'];
        }

        if (isset($config['token'])) {
            $this->token = $config['token'];
        }
        if (isset($config['tokenExpires'])) {
            $this->tokenExpires = (int) $config['tokenExpires'];
        }

        if ($this->tokenExpires < time()) {
            $this->generateToken();
        }
    }

    /**
     * @param $url
     */
    public function setBaseUrl($url)
    {
        $this->baseUrl = $url;
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
     * Generate a access token to be used when searching for addresses.
     *
     * @throws \Exception is thrown the username/password is incorrect or an unexpected error occurs.
     */
    private function generateToken()
    {
        $url = $this->tokenUrl.'?username='.urlencode($this->username).'&password='.urlencode($this->password);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($curl);

        // Username/Password incorrect
        if (curl_getinfo($curl, CURLINFO_HTTP_CODE) == 401) {
            throw new \Exception('Not authorized to use address search API');

            // Unknown error
        } elseif (curl_getinfo($curl, CURLINFO_HTTP_CODE) !== 200) {
            throw new \Exception('Not able to generate token for address search API!');

            // New token generated
        } else {
            $this->token = $response;
            $this->tokenExpires = time() + 3540;

            // TODO: Save token and expiration time in DB so we don't have to authenticate for each request.
        }
    }

    /**
     * Search for an address.
     *
     * This method should be used to generate autocomplete suggestions and get
     * the coordinate of the final entered or selected address.
     *
     * The following data is returned for each address:
     *  Description:
     *   A text presentation to be used when presenting the address suggestions
     *  Address:
     *   The street name and house number part of the to be set in the "address"
     *   field when selecting the address.
     *  PostCode:
     *   The post code part of the address.
     *  PostOffice:
     *   The city/post office part of the address.
     *  X:
     *   The X coordinate (this is NOT longitude/latitude coordinates).
     *   This will be used later to find a delivery zone servicing this address (if any).
     *  Y:
     *   The Y coordinate (this is NOT longitude/latitude coordinates).
     *   This will be used later to find a delivery zone servicing this address (if any).
     *
     * @param string $query        the search query
     * @param bool   $includeQuery to include query in suggestions
     * @param int    $retries      is used to stop infite recursion that may happen if the token is not working.
     *
     * @return array of addresses matching the query.
     *
     * @throws \Exception
     */
    public function search($query, $includeQuery = false, $retries = 1)
    {
        $suggestions = [];

        $query = trim(str_replace(['.', '!', ',', "'"], '', $query));
        /*
        $queryParts = explode(' ', $query);
        $newQuery = '';
        $postCodePos = false;
        $lastTest = 0;
        foreach ($queryParts as $queryPart) {
            if ($postCodePos == false && preg_match('/^(\d+|\d+\D)$/', $queryPart)) {
                $lastTest = 1;
                $newQuery .= $queryPart.' ';
                $postCodePos = true;
            } elseif (mb_strlen($queryPart, 'UTF-8') > 1 && !is_numeric($queryPart)) {
                $lastTest = 2;
                $newQuery .= $queryPart.' ';
            } elseif ($postCodePos == true && preg_match('/^\d{4}$/', $queryPart)) {
                $lastTest = 3;
                $newQuery .= $queryPart.' ';
            } elseif ($lastTest == 1 && mb_strlen($queryPart, 'UTF-8') == 1 && !is_numeric($queryPart) && preg_match('/\d $/', $newQuery)) {
                $lastTest = 4;
                $newQuery = trim($newQuery).$queryPart.' ';
            }
        }
        $query = trim($newQuery);
        */

        if (strlen($query) == 0) {
            return [];
        }

        $url = $this->baseUrl.'/autocomplete/search/adresse?query='.urlencode($query).'&token='.urlencode($this->token);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($curl);

        // Unknown error
        if (curl_getinfo($curl, CURLINFO_HTTP_CODE) !== 200) {
            throw new \Exception('Not able to execute address search!');
        } else {
            $response = json_decode($response);
//ar_export($response);
            if (isset($response->error)) {
                // Generate new token and retry if error is "Token required"
                if ($response->error->code == 499 && $retries > 0) {
                    $this->generateToken();

                    return $this->search($query, $includeQuery, 0);
                } else {
                    throw new \Exception($response->error->message);
                }
            }

            foreach ($response->hits as $hit) {
                $description = $address = $postCode = $postOffice = '';
                $x = $y = 0;

                if (isset($hit->gatenavn)) {
                    $address = $description = trim($hit->gatenavn);
                }
                if (isset($hit->husnr) && $hit->husnr > 0) {
                    $address = $description .= ' '.trim($hit->husnr);
                }
                if (isset($hit->husbokstav) && $hit->husbokstav !== '0') {
                    $address = $description .= trim($hit->husbokstav);
                }
                if (isset($hit->postnr)) {
                    $description .= ', '.trim($hit->postnr);
                    $postCode = trim($hit->postnr);
                }
                if (isset($hit->poststed)) {
                    $description .= ' '.trim($hit->poststed);
                    $postOffice = trim($hit->poststed);
                }
                if (isset($hit->x)) {
                    $x = $hit->x;
                }
                if (isset($hit->y)) {
                    $y = $hit->y;
                }
                $suggestions[] = [
                        // Text to be used in suggestion drop down
                        'description' => $description,
                        // Value to be put in address field once a address is selected
                        'address' => $address,
                        // Value to be put in post code field once a address is selected
                        'postCode' => $postCode,
                        // Value to be put in post office / city field once a address is selected
                        'postOffice' => $postOffice,
                        // Coordinates to be used in call to web service to check if the address is inside of a delivery zone.
                        'x' => $x,
                        'y' => $y,
                ];
            }
/*
            if ($includeQuery) {
                $suggestions[] = [
                        // Text to be used in suggestion drop down
                        'description' => "Query sent: $query",
                        // Value to be put in address field once a address is selected
                        'address' => '',
                        // Value to be put in post code field once a address is selected
                        'postCode' => '',
                        // Value to be put in post office / city field once a address is selected
                        'postOffice' => '',
                        // Coordinates to be used in call to web service to check if the address is inside of a delivery zone.
                        'x' => 0,
                        'y' => 0,
                ];
            }
*/
            return $suggestions;
        }
    }
}
