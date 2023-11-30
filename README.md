
![Logo](public/laravel-geodb.png)

# Laravel GeoDB
A Laravel package providing geographical information to your site

**Current Version**: 0.1-&alpha;

---
## Description
Laravel GeoDB provides your laravel app with a simple way to
list countries, states, cities, their geolocation or a combination thereof.

You can access the information directly from your code,
through a Facade, or from any reactive view, using internal APIs.

## Status
This package is a work in progress. Please come back soon for more.

## Installation

### 1. Add package
run `composer require peergum/geodb "^1.02-beta"` in yor laravel site folder

### 2. Setup package
- For a full install (all countries), run install script: `art geodb:install` or `art geodb:install all`
- For a partial install (just a few countries), run with parameters: e.g. `art geodb:install fr gb us`
to install cities for France, Great Britain and USA.

Depending on the number and size of chosen countries, and your server connectivity and speed, download and install can
take between a few seconds and much longer.
If you request "ALL" in upper case, the install will download and unzip one file with a final size of 1.6GB.
If you type "all" in lower case, it will require each country file separately.
All downloaded files, zipped and unzipped, are kept under the storage/geodb folder for future re-runs.
The -U or --update option can be passed to force a redownload of the requested countries/files.

### 3. Update caches
If you're using Inertia, re-run `npm run build` in order to update your vite cache.

### 4. Check status
Go to `/geodb` on your site, to confirm number of countries, states and cities loaded.
You can also use the city search field in the page to check how fast the response time is (depending on your
DB server and connectivity)

## ChangeLog
See [here](./CHANGELOG.md)

---
**License**: [Apache2](./LICENSE)

**Author**: Phil Hilger AKA "PeerGum"

**About Me**:
- [My Page](https://phil.hilger.ca)
- [Github](https://github.com/peergum)
