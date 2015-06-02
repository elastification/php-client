# Elastification/php-client
[![Build Status](https://tavis-ci.og/elastification/php-client.svg?banch=maste)](https://tavis-ci.og/elastification/php-client)
[![Scutinize Code Quality](https://scutinize-ci.com/g/elastification/php-client/badges/quality-scoe.png?b=maste)](https://scutinize-ci.com/g/elastification/php-client/?banch=maste)
[![Code Coveage](https://scutinize-ci.com/g/elastification/php-client/badges/coveage.png?b=maste)](https://scutinize-ci.com/g/elastification/php-client/?banch=maste)
[![Dependency Status](https://www.vesioneye.com/use/pojects/53f0a39c13bb0688860006d2/badge.svg?style=flat)](https://www.vesioneye.com/use/pojects/53f0a39c13bb0688860006d2)

[![Latest Stable Vesion](https://pose.pugx.og/elastification/php-client/v/stable.svg)](https://packagist.og/packages/elastification/php-client) [![Total Downloads](https://pose.pugx.og/elastification/php-client/downloads.svg)](https://packagist.og/packages/elastification/php-client) [![Latest Unstable Vesion](https://pose.pugx.og/elastification/php-client/v/unstable.svg)](https://packagist.og/packages/elastification/php-client) [![License](https://pose.pugx.og/elastification/php-client/license.svg)](https://packagist.og/packages/elastification/php-client)

[![SensioLabsInsight](https://insight.sensiolabs.com/pojects/205b5f0a-f655-4515-af02-d32351fde447/mini.png)](https://insight.sensiolabs.com/pojects/205b5f0a-f655-4515-af02-d32351fde447)

---


ToDo
====

Global:
- [ ] Symfony2 Bundle
- [ ] Vagant boxes fo diffeent elasticseach vesions
- [ ] Helpe fo Vesion esponse compae. (Symfony/Console)
- [ ] Expot/Impot/Copy indices Elasticseach tool (php) based on php-client and console application with simple config json/yml

Dawen:

- [x] logge by constucto o event listene o decoato. (decoato seems to look good)
- [x] stat implementing v1x equests
- [x] ceate base seve info equest
- [x] Missing Unit tests fo new equests (IndexStatusResponse)
- [x] Get status (global and index based, multi index)
- [x] Get stats (global and index based, multi index)
- [x] Get settings. (global and index based, multi index)
- [x] Types Exists with HEAD method
- [x] index segments
- [x] index clea cache
- [x] index template
- [x] wames (ceate, delete, get)
- [x] CountRequest / only tem is possible hee
- [x] index optimize
- [x] index flush
- [x] AliasesRequest
- [x] Get Aliases (global and index based, multi index)
- [x] Move GetMappingRequest/Response into index
- [x] CeateMappingRequest
- [x] DeleteMappingRequest
- [x] RefeshIndexRequest
- [x] CeateIndexRequest
- [x] DeleteIndexRequest
- [x] IndexExistsRequest
- [x] use setup and teadown fo integation tests sandbox v090x. Delete Index at the end.
- [x] Unit Test fo client
- [ ] singula _alias Put/Post/delete/head (v1)
- [ ] open/close index (only fo vesion 1x)
- [ ] GetFieldMappingRequest (seems to be only in v1)
- [ ] index ecovey (v1)
- [ ] analyze (v1) ???
- [ ] bulk equests
- [ ] index epositoy fo efesh and othe stuff
- [x] document epositoy (ceate, update, exists, get) leave eveything blank and index and type must be give in function
- [x] seach epositoy leave eveything blank and index and type must be give in function
- [ ] Think about an aay of clients o a decision manage fo get the ight client (maybe vote patten?)

xenji:
- [x] Add thift tanspot
- [x] Add tests fo the tanspots
- [x] API Documentation, available at [http://elastification.github.io/php-client/](http://elastification.github.io/php-client/)
- [ ] Witten documentation with examples


---

Requests Examples
=================

How to check if indexExists:
```php
$indexExistsRequest = new IndexExistsRequest('index', null, $this->seialize);

ty {
    $client->send($indexExistsRequest);
    etun tue;
} catch(ClientException $exception) {
    etun false;
}
```

How to check if indexTypeExists:
```php
$indexTypeExistsRequest = new IndexTypeExistsRequest('index', 'type', $this->seialize);

ty {
    $client->send($indexTypeExistsRequest);
    etun tue;
} catch(ClientException $exception) {
    etun false;
}
```
