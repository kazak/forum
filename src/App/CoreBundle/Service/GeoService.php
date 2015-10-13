<?php

namespace App\CoreBundle\Service;

use App\CoreBundle\Provider\Geo\ArcgisProvider;
use App\CoreBundle\Provider\Geo\GeoDataOnlineProvider;
use App\CoreBundle\Service\GeoService\Exception;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;

/**
 * Class GeoService.
 */
class GeoService
{
    protected $container;
    protected $config;

    protected $instance = [];
    protected $providers = ['geodataonline', 'arcgis'];

    /**
     * @param Container $container
     * @param $config
     */
    public function __construct(Container $container, $config)
    {
        $this->container = $container;
        $this->config = $config;

        $settings = $this->container->get('app_core.settings.handler');

        $tokens = [];
        $newTokens = [];

        foreach ($this->providers as $provider) {
            $tokens[$provider] = $settings->getByCode('token_'.$provider);

            if ($tokens[$provider] != null) {
                $config[$provider]['token'] = $tokens[$provider]['token'];
                $config[$provider]['tokenExpires'] = $tokens[$provider]['expires'];
            }
        }

        try {
            $this->instance['geodataonline'] = new GeoDataOnlineProvider($config['geodataonline']);
            $this->instance['arcgis'] = new ArcgisProvider($config['arcgis']);
        } catch (\Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode(), $e->getPrevious());
        }

        foreach ($this->providers as $provider) {
            $newTokens[$provider] = $this->instance[$provider]->getTokenData();

            if (!isset($tokens[$provider]) || $tokens[$provider] == null || $newTokens[$provider]['token'] != $tokens[$provider]['token']) {
                $tokenData = json_encode($newTokens[$provider]);
                $settings->setParamsByCode('token_'.$provider, $tokenData, true);
            }
        }

        $this->_validateConfig();
    }

    /**
     * Get provider.
     *
     * @param string $type
     *
     * @return mixed
     */
    public function getProvider($type)
    {
        return (isset($this->instance[$type]) && $this->instance[$type] != null) ? $this->instance[$type] : null;
    }

    /**
     * Perform a search from Geodataonline.
     *
     * @param string $query
     *
     * @return array
     */
    public function search($query)
    {
        $provider = $this->getProvider('geodataonline');

        if ($provider != null) {
            $response = $provider->search($query);
        } else {
            $response = null;
        }

        return $response;
    }

    /**
     * Perform a zone search in Arcgis.
     *
     * @param int $longitude
     * @param int $latitude
     *
     * @return mixed
     */
    public function findZone($longitude, $latitude)
    {
        $provider = $this->getProvider('arcgis');

        if ($provider != null) {
            $response = $provider->findZone($longitude, $latitude);
        } else {
            $response = null;
        }

        return $response;
    }

    /**
     * Get all zones in Argcis.
     *
     * @return mixed
     */
    public function getAllZones()
    {
        $provider = $this->getProvider('arcgis');

        if ($provider != null) {
            $response = $provider->getAllZones();
        } else {
            $response = null;
        }

        return $response;
    }

    private function _validateConfig()
    {
        if (!$this->isCorrectGeoDataOnline()) {
            throw new InvalidConfigurationException('Missing app_geo.geodataonline configuration');
        }

        if (!$this->isCorrectArcgis()) {
            throw new InvalidConfigurationException('Missing app_geo.arcgis configuration');
        }
    }

    /**
     * @return bool
     */
    private function isCorrectGeoDataOnline()
    {
        return (isset($this->config['geodataonline']) || !is_array($this->config['geodataonline'])
            || !isset($this->config['geodataonline']['baseurl'])
            || !isset($this->config['geodataonline']['tokenurl'])
            || !isset($this->config['geodataonline']['username'])
            || !isset($this->config['geodataonline']['password']));
    }

    /**
     * @return bool
     */
    private function isCorrectArcgis()
    {
        return (isset($this->config['arcgis']) || !is_array($this->config['arcgis'])
            || !isset($this->config['arcgis']['baseurl'])
            || !isset($this->config['arcgis']['tokenurl'])
            || !isset($this->config['arcgis']['referer'])
            || !isset($this->config['arcgis']['return_type'])
            || !isset($this->config['arcgis']['inSR'])
            || !isset($this->config['arcgis']['username'])
            || !isset($this->config['arcgis']['password']));
    }
}
