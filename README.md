# URLcrypt

Ever wanted to securely transmit (not too long) pieces of arbitrary binary data
in a URL? **URLcrypt** makes it easy.

To read more about how it works, check out my [blog post](http://aaronfrancis.com/blog/2013/9/9/encrypting-and-encoding-information-in-urls-with-php) on the topic.

This class is based on the [URLCrypt](https://github.com/madrobby/URLcrypt) gem from Thomas Fuchs.

URLcrypt uses **256-bit AES symmetric encryption**
to securely encrypt data, and encodes and decodes 
**Base 32 strings that can be used directly in URLs**.

This can be used to securely store user ids, download expiration dates and 
other arbitrary data like that when you access a web application from a place 
that doesn't have other authentication or persistence mechanisms (like cookies):
 
  * Loading a generated image from your web app in an email
  * Links that come with an expiration date (à la S3)
  * Mini-apps that don't persist data on the server

Requires the mcrypt PHP extension.

**Important**: As a general guideline, URL lengths shouldn't exceed about 2000 
characters in length, as URLs longer than that will not work in some browsers
and with some (proxy) servers. This limits the amount of data you should store
with URLcrypt.

**WORD OF WARNING: THERE IS NO GUARANTEE WHATSOEVER THAT THIS CLASS IS ACTUALLY SECURE AND WORKS. USE AT YOUR OWN RISK.**

Patches are welcome; please include tests!

## Example

```php
// encoding without encryption. (don't use for anything sensitive)
$encoded = Urlcrypt::encode("aaron");		// --> "mfqx2664"
$decoded = Urlcrypt::decode("mfqx2664");	// --> "aaron"

// encrypting and encoding
Urlcrypt::$key = "bcb04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3";
$encrypted = Urlcrypt::encrypt("aaron");
		// --> "q0dmt61xkjyylA5mp3gm23khd1kg6w7pzxvd3nzcgb047zx8y581"
$decrypted = Urlcrypt::decrypt("q0dmt61xkjyylA5mp3gm23khd1kg6w7pzxvd3nzcgb047zx8y581")
		// --> "aaron"
```

## Why not Base 64?

URLcrypt uses a modified Base 32 algorithm that doesn't use padding characters,
and doesn't use vowels to avoid bad words in the generated string.

Base64 results in ugly URLs, since many characters need to be URL escaped.
