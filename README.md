# Installation
`composer.json`:
```
"repositories": [
      {
          "type": "vcs",
          "url":  "https://github.com/arleslie/resellerclub-php-sdk.git"
      }
  ],
  "require": {
    "arleslie/resellerclub-php": "dev-master"
  }
  ```

# Usage
Note: All functions return raw response from ResellerClubs's API. (This will change in the future)
```
$resellerClub = \arleslie\ResellerClub\ResellerClub(<userId>, <apiKey>, true); // Last argument is for testmode.

// Get Available TLDs
$resellerClub->domains()->getTLDs();

// Check Domain Availablity
$resellerClub->domains()->available(['google', 'example'], ['com', 'net']); // This will check google.com, google.net, example.com and example.net
```

Currently all of the domain API and contact API are available.
