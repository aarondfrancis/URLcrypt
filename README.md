# URLCrypt

[![Build Status](https://travis-ci.org/atrapalo/URLcrypt.svg?branch=master)](https://travis-ci.org/atrapalo/URLcrypt)
[![Code Coverage](https://scrutinizer-ci.com/g/atrapalo/URLcrypt/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/atrapalo/URLcrypt/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/atrapalo/URLcrypt/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/atrapalo/URLcrypt/?branch=master)

Ever wanted to securely transmit (not too long) pieces of arbitrary binary data
in a URL? **URLCrypt** makes it easy.

This class is based on the [URLCrypt](https://github.com/aarondfrancis/URLcrypt) from Aaron Francis.

URLCrypt uses **256-bit AES symmetric encryption** to securely encrypt data, and encodes and decodes
**Base 32 strings that can be used directly in URLs**.

This can be used to securely store user ids, download expiration dates and
other arbitrary data like that when you access a web application from a place
that doesn't have other authentication or persistence mechanisms (like cookies):

  * Loading a generated image from your web app in an email
  * Links that come with an expiration date (à la S3)
  * Mini-apps that don't persist data on the server

**Important**: As a general guideline, URL lengths shouldn't exceed about 2000
characters in length, as URLs longer than that will not work in some browsers
and with some (proxy) servers. This limits the amount of data you should store
with URLCrypt.

**WORD OF WARNING: THERE IS NO GUARANTEE WHATSOEVER THAT THIS CLASS IS ACTUALLY SECURE AND WORKS. USE AT YOUR OWN RISK.**

Patches are welcome; please include tests!

## Requirements

URLCrypt requires PHP >= 5.5.9 as well as the openssl PHP extension.

## Installation

You can install URLCrypt via Composer with `composer require atrapalo/urlcrypt` or by adding the following to your `composer.json` file:

```json
{
	"require": {
		"atrapalo/urlcrypt": "^1.0"
	}
}
```

## Usage

```php
use Atrapalo\UrlCrypt;

$urlCrypt = new UrlCrypt();

// encoding without encryption. (don't use for anything sensitive)
$encoded = $urlCrypt->encode('Atrapalo');	// --> "3f5h2ylqmfwg9"
$decoded = $urlCrypt->decode('3f5h2ylqmfwg9');	// --> "Atrapalo"

// encrypting and encoding
$key = 'bcb04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3';
$encrypted = $urlCrypt->encrypt('Atrapalo', $key);
		// --> 'xApfAdvAxg55jvk75y5n2d26xrhv3qtgfmxAmq53mf1t5'
$decrypted = $urlCrypt->decrypt('xApfAdvAxg55jvk75y5n2d26xrhv3qtgfmxAmq53mf1t5', $key)
		// --> 'Atrapalo'
```

Note that your key has to be a lower-case hex string.

## Why not Base 64?

URLCrypt uses a modified Base 32 algorithm that doesn't use padding characters,
and doesn't use vowels to avoid bad words in the generated string.

Base64 results in ugly URLs, since many characters need to be URL escaped.

## Development

Clone the repository and do a `composer install` in the root directory of the library to install the development dependencies.
Run the tests with `phpunit` from the root directory.

## License

This library is licensed under the MIT License - see the `COPYING` file for details.
