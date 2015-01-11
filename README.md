# Islandora Content Proxy

Note: This module has been superseded by [Islandora CONTENTdm API](https://github.com/mjordan/islandora_cdm_api).

## Overview

This proof-of-concept application provides an HTTP proxy that pretends to be an API from a non-Islandora repository. Its purpose is to replace the web-services or REST API of an application whose content has been migrated to an Islandora instance. In effect, it lets HTTP clients that consumed the non-Islandora API continue to consume that API, but the content returned by the API is in an Islandora instance.

A typical use case is that you have migrated all of your content from CONTENTdm to Islandora. However, you have some web apps that consume the [CONTENTdm web API](http://www.contentdm.org/help6/custom/customize2a.asp). Instead of rewriting your web apps to consume the Islandora REST API directly, you can use the Islandora Content Proxy to "pretend" to be the CONTENTdm web API endpoint. Your apps ask questions of the CONTENTdm API, but the answers come from Islandora.

## How it works

The Islandora Content Proxy uses the same URL template and/or parameters as the non-Islandora repository's Web Services or REST interface. After migration of content from to Islandora, client applications continue to make requests to the Proxy instead of the old repository's API. The Proxy converts the old API's paramters into requests against the Islandora REST interface, which returns the requested content to the Proxy, which in turn returns the content to the client application. The Proxy can cache content retrived from Islandora to improve performance.

![How it works](https://dl.dropboxusercontent.com/u/1015702/linked_to/Islandora%20Content%20Proxy%20activity%20diagram.png)

Essential to this process is a lookup performed by the Proxy to get the Islandora PID corresponding to the migrated content. As mentioned above, the old repository's identifiers for content must be stored in the Islandora objects' RELS-EXT or metadata. In the former case, the Proxy performs the query for the PID via the Fedora Commons Resource Index; in the latter case, via Solr.

If the Proxy is installed on the same host as the old repository's API, and the API's URLs remain accessible, client applications will not need to be updated at all. If the Proxy is installed on a different host than the old repository's API was served from, only the hostname will need updating in the client applications. The important thing is that URL parameters used in requests to the API remain intact.

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
