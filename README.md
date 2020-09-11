[PHP client library for aWhere weather API](https://docs.awhere.com)
=
# Introduction
This is an API client library for the [aWhere weather APIs](https://docs.awhere.com/knowledge-base-docs/api-reference/).

### Usage

The first step is setting up an API client and fetching a fresh token:

``` php
$client = new aWhere\Client($clientId, $clientSecret);
$token = $client->getToken();
```

You may cache/save the token and pass it as the 3rd paramter to the client to re-use it (You can use `isTokenValid()` method to determine usability of the passed token).

Next, you may call the relevant API to fetch the data. In this initial release, I have only added the **Forecast Weather by Geolocation** API node for now (and only the basic usage without startDate/endDate). I will add more API nodes in upcoming versions of this library.

``` php
$client->getWeatherForecastByGeolocation($latitude, $longitude, $options);
```
`$options` corresponds to [the query string parameters](https://docs.awhere.com/knowledge-base-docs/forecast-weather-by-geolocation/#4-query-string-parameters) specified in the documentation. For example,
``` php
$options = [
  'limit' => 15,
  'blockSize' => 12,
  'conditionsType' => 'basic',
  'useLocalTime' => true
];
```

### Contributing
Feel free to make PRs in case you want to update this library with more API nodes.
