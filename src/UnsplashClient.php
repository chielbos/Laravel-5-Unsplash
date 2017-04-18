<?php

namespace Cbyte\Unsplash;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

class UnsplashClient {

    private $guzzle;
    private $client_id;
    private $config;

    private $featured = true;
    private $orientation = 'landscape';
    private $collections = array();

    public function __construct($config) {
        $this->client_id = $config['unsplash_app_id'];
        $this->config = $config;
        $this->guzzle = new Client([
            // Base URI is used with relative requests
            'base_uri' => 'http://api.unsplash.com',
            // You can set any number of default request options.
            'timeout'  => 2.0,
        ]);
    }

    /**
     * Adds a collection to the collections
     *
     * @param int $collectionId a collection ID
     * @return void
     */
    public function addCollection($collectionId) {
        $this->collections[] = $collectionId;
    }

    /**
     * Removes a collection from the collections
     *
     * @param int $collectionId a collection ID
     * @return boolean success
     */
    public function removeCollection($collectionId) {
        $position = array_search($collectionId, $this->collections);
        if($position) {
            unset($this->collections[$position]);
        }
    }


    /**
     * Get a random image from Unsplash
     *
     * @param array $additionalOptions additional options for the image request
     * @return mixed Background image
     */
    public function getRandomBackground($additionalOptions = array()) {
        if(Cache::has('unsplash_random_background_url')) {
            // Return cached image
            return Cache::get('unsplash_random_background_url');
        } else {
            // Get a new image from the api
            $options = array(
                'orientation' => $this->orientation,
            );
            if(!empty($this->collections)) {
                $options['colections'] = implode(',', $this->collections);
            }
            if($this->featured) {
                $options['featured'] = '';
            }

            foreach($additionalOptions as $key => $value) {
                $options[$key] = $value;
            }

            // Execute the request
            $image = $this->_executeGet('/photos/random', $options);

            // Cache this one
            Cache::put('unsplash_random_background_url', $image, Carbon::now()->addSecond($this->config['refresh_rate']));

            return $image;
        }
    }


    /**
     * Execute a comment to the Unsplash API
     *
     * @param string $command
     * @param array $params optional get parameters
     * @return mixed
     */
    private function _executeGet($command, $params = array()) {
        $url = $command . '?client_id=' . $this->client_id;
        foreach($params as $key => $value) {
            $url .= '&' . $key . '=' . $value;
        }
        return json_decode((string) $this->guzzle->get($url)->getBody(), true);
    }
}