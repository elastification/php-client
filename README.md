# Elastification/php-client
[![Build Status](https://travis-ci.org/elastification/php-client.svg?branch=master)](https://travis-ci.org/elastification/php-client)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/elastification/php-client/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/elastification/php-client/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/elastification/php-client/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/elastification/php-client/?branch=master)
[![Dependency Status](https://www.versioneye.com/user/projects/53f0a39c13bb0688860006d2/badge.svg?style=flat)](https://www.versioneye.com/user/projects/53f0a39c13bb0688860006d2)

[![Latest Stable Version](https://poser.pugx.org/elastification/php-client/v/stable.svg)](https://packagist.org/packages/elastification/php-client) [![Total Downloads](https://poser.pugx.org/elastification/php-client/downloads.svg)](https://packagist.org/packages/elastification/php-client) [![Latest Unstable Version](https://poser.pugx.org/elastification/php-client/v/unstable.svg)](https://packagist.org/packages/elastification/php-client) [![License](https://poser.pugx.org/elastification/php-client/license.svg)](https://packagist.org/packages/elastification/php-client)

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/205b5f0a-f655-4515-af02-d32351fde447/mini.png)](https://insight.sensiolabs.com/projects/205b5f0a-f655-4515-af02-d32351fde447)

---


ToDo
====

Global:
- [ ] Symfony2 Bundle
- [ ] Vagrant boxes for different elasticsearch versions
- [ ] Helper for Version response compare. (Symfony/Console)
- [ ] Export/Import/Copy indeces Elasticsearch tool (php) based on php-client and console application with simple config json/yml

Dawen:

- [ ] Get status (global and index based, multi index)
- [x] Get stats (global and index based, multi index)
- [ ] Get settings. (global and index based, multi index)
- [ ] Types Exists with HEAD method
- [ ] Create Alias Index
- [ ] Create Aliases
- [ ] Delete Aliases
- [ ] Get Aliases (global and index based, multi index)
- [ ] Alias exists with HEAD method
- [ ] CreateMappingRequest
- [ ] DeleteMappingRequest
- [ ] CountRequest / only term is possible here
- [ ] Clear cache (global and index based, multi index)
- [ ] flush (global and index based, multi index)
- [x] RefreshIndexRequest
- [x] CreateIndexRequest
- [x] DeleteIndexRequest
- [x] IndexExistsRequest
- [x] use setup and teardown for integration tests sandbox v090x. Delete Index at the end.
- [ ] open/close index (only for version 1x)
- [x] Unit Test for client
- [ ] Integration tests for RequestManager
- [ ] Think about an array of clients or a decision manager for get the right client (maybe voter pattern?)

xenji:
- [x] Add thrift transport
- [x] Add tests for the transports
- [x] API Documentation, available at [http://elastification.github.io/php-client/](http://elastification.github.io/php-client/)
- [ ] Written documentation with examples


---

Requests Examples
=================

How to check if indexExists:
```php
$indexExistsRequest = new IndexExistsRequest('index', null, $this->serializer);

try {
    $client->send($indexExistsRequest);
    return true;
} catch(ClientException $exception) {
    return false;
}
```
