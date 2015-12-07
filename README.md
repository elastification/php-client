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

- [ ] Helper for Version response compare. (Symfony/Console)
- [ ] singular _alias Put/Post/delete/head (v1)
- [ ] GetFieldMappingRequest (seems to be only in v1 and v2)
- [ ] cat api (v1, v2, check if v90)
- [ ] index recovery (v1, v2, check if v90)
- [ ] analyze (v1, v2, check if v90)
- [ ] Think about an array of clients or a decision manager for get the right client (maybe voter pattern?)
- [ ] Write documentation
- [ ] Create Github Pages
- [ ] Check all requests and write down missing here

---

Changes from Version 1x to 2x

- NodeInfo does not have status in reponse
- DeleteByQuery is removed
- DeleteMapping is removed
- Index/IndexStats is removed (replaced by index stats)
- Bulk/BulkInsertRequest existing documents having new a subobject under item['error'] response
- Bulk/BulkUpdateRequest existing documents having new a subobject under item['error'] response

---

## Testing

The tests are devided into unit tests and integration tests.

Running the unit tests is simple (composer install is reqired):

```shell
    bin/phpunit tests/Unit
```


If you like coder coverage it will be placed in build folder:

```shell
    bin/phpunit --coverage-html=build tests/Unit
```

There are also integration test. The subfolders holding version tests. There is a vagrant repository where you can 
bring all wanted/needed machine alive. [Link is missing] 

The phpunit-integration.xml has defined constants for each testable version.

Run integration tests for v2x versions looks like this:

```shell
    bin/phpunit --coverage-html=build -c phpunit-integration.xml tests/Integration/Request/V2x
```

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

How to check if indexTypeExists:
```php
$indexTypeExistsRequest = new IndexTypeExistsRequest('index', 'type', $this->serializer);

try {
    $client->send($indexTypeExistsRequest);
    return true;
} catch(ClientException $exception) {
    return false;
}
```
