# Islandora Content Proxy

## Overview

This proof-of-concept application provides an HTTP proxy that pretends to be an API from a non-Islandora repository. Its purpose is ot replace the web-services or REST API of an application whose content has been migrated to an Islandora instance. In effect, it lets HTTP clients that consumed the non-Islandora API continue to consume that API, but the content returned by the API is in an Islandora instance.

A typical use case is that you have migrated all of your content from CONTENTdm to Islandora. However, you have some web apps that consume the [CONTENTdm web API](http://www.contentdm.org/help6/custom/customize2a.asp). Instead of rewriting your web apps to consume the Islandora REST API directly, you can use the Islandora Content Proxy to "pretend" to be the CONTENTdm web API endpoint. Your apps ask questions of the CONTENTdm API, but the answers come from Islandora.

## Prerequisites

On the Islandora instance, you will need to implement the [Islandora REST module](https://github.com/discoverygarden/islandora_rest).

Islandora will need to store the identifiers from the source repository such that the Islandora Content Proxy can query them. To use the example use case described above, the CONTENTdm URLs for the objects could be stored in the migrated Islandora objects' RELS-EXT datastreams, or in the MODS metadata.

## Installation

1. Clone this git repo into a directory below your web root.
2. CD into the resulting redirectory and install composer by issuing the following command: ```curl -sS https://getcomposer.org/installer | php```

3. Use composer to install [Slim](http://www.slimframework.com/) and [Guzzle](http://guzzle3.readthedocs.org/): ```php composer.phar install```

## Usage

To test the content proxy, replace the value of the ```$islandora_rest_url``` variable in index.php with the URL to your Islandora instance's REST interface, and then use your graphical web browser to ask the content proxy to retrieve an Islandora object's TN  (or DC or MODS) datastream, as in this example:

http://localhost/islandoracontentproxy/index.php/islandora%3A314/TN

You will need to use the URL-encoded PID of an object that exists in your Islandora instance.
