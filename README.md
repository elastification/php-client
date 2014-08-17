# Elastification/php-client
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/elastification/php-client/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/elastification/php-client/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/elastification/php-client/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/elastification/php-client/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/elastification/php-client/badges/build.png?b=master)](https://scrutinizer-ci.com/g/elastification/php-client/build-status/master)


---


ToDo
====

Global:
- [ ] Symfony2 Bundle
- [ ] Vagrant boxes for different elasticsearch versions

Dawen:

- [ ] CreateIndexRequest
- [ ] DeleteIndexRequest
- [ ] IndexExistsRequest
- [ ] CreateMappingRequest
- [ ] CountRequest / only term is possible here
- [x] Unit Test for client
- [ ] Integration tests for RequestManager
- [ ] Helper for Version response compare. (Symfony/Console)
- [ ] After helper for response compare, refactor requests. I think version base requests are not needed.

xenji:
- [x] Add thrift transport
- [ ] Add tests for the transports
- [x] API Documentation, available at [http://elastification.github.io/php-client/](http://elastification.github.io/php-client/)
- [ ] Written documentation with examples
