<?php
require 'vendor/autoload.php';
use GuzzleHttp\Client;

// The URL to the Islandor'a REST API.
$islandora_rest_url = 'http://192.168.56.101/islandoradev/islandora/rest/v1';

// We need to tell Slim which Mime type to return to the requesting client.
// @todo: Get the desired datastream's mime type from an initial query to the
// object's properties.
$ds_mimetypes = array(
    'TN' => 'image/jpeg',
    'OBJ' => 'image/jpeg',
    'DC' => 'text/xml',
    'MODS' => 'text/xml',
);

$app = new \Slim\Slim(array(
     'debug' => true
));

/**
 * Proof of concept Slim route that uses a Guzzle client to retrieve a specific datastream
 * from an Islandora REST URI and return it to the client.
 *
 * @param string $pid
 *   The PID of the Islandora object. Must be URL encoded.
 * @param string $dsid
 *   The DSID of the datastream whose content you want returned.
 */
$app->get('/:pid/:dsid', function ($pid, $dsid) use ($app) {
    global $islandora_rest_url;
    global $ds_mimetypes;
    $client = new Client();
    $response = $client->get($islandora_rest_url . '/object/' . $pid . '/datastream/' . $dsid);
    $body = $response->getBody();
    $app->response->headers->set('Content-Type', $ds_mimetypes[$dsid]);
    $app->response->setBody($body);
});

$app->run();
?>
