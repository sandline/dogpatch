dogpatch+
========

![dogpatch](https://raw.github.com/commando/dogpatch/master/assets/images/dogpatch.jpg)

#### An HTTP API testing framework, written in PHP using curl. Supports ssl, basic auth, passing custom request headers, redirection *(10 levels)*, and most HTTP request methods. Orginally written for testing the [Commando.io](https://commando.io) API.

##### Canonical Examples

````php
$dogpatch = new Dogpatch();

$dogpatch->get("https://api.github.com")
         ->assert_status_code(200)
         ->assert_headers_exist(array(
            "X-GitHub-Request-Id",
            "ETag"
         ))
         ->assert_headers(array(
            "Server" => "GitHub.com"
         ))
         ->assert_body(IS_VALID_JSON)
         ->close();
````

````php
$dogpatch = new Dogpatch();

$dogpatch->get("https://freegeoip.net/json/8.8.8.8")
         ->assert_status_code(200)
         ->assert_headers_exist(array(
            "Content-Length"
         ))
         ->assert_headers(array(
            "Access-Control-Allow-Origin" => "*"
         ))
         ->assert_body_json_file(dirname(__DIR__) . "/examples/json/freegeoip.net.json")
         ->close();
````

#### See the full examples at https://github.com/commando/dogpatch/tree/master/examples.

Requirements
------------

#### PHP ####
Version **5.3.0** or greater.

#### PHP Extensions ####
Curl

Constructor
-----------

````php
$dogpatch = new Dogpatch(array $curl_options = array());
````

##### Curl Options

>**username:** A basic authentication username. Defaults to `null`.

>**password:** A basic authentication password. Defaults to `null`.

>**timeout:** Curl HTTP request timeout in seconds. Defaults to `60`.

>**ssl_verifypeer:** Attempt to verify ssl peer certificates using included `ca-bundle.crt`. Defaults to `true`.

>**verbose:** Turns on verbose curl logging, and log all requests into a file `logs/curl_debug.log`. Defaults to `false`.

Get
---

````php
$dogpatch->get($url, array $headers = array());
````

##### Parameters

>**url:** A compete url including the scheme *(HTTP, HTTPS)*.

>**headers:** An optional associated array of additional request headers to pass. Defaults to an empty array.

Post
----

````php
$dogpatch->post($url, array $post_data = array(), array $headers = array());
````

##### Parameters

>**url:** A compete url including the scheme *(HTTP, HTTPS)*.

>**post_data:** An associated arrray of post data in `key => value` syntax.

>**headers:** An optional associated array of additional request headers to pass. Defaults to an empty array.

Put
---

````php
$dogpatch->put($url, array $headers = array());
````

##### Parameters

>**url:** A compete url including the scheme *(HTTP, HTTPS)*.

>**headers:** An optional associated array of additional request headers to pass. Defaults to an empty array.

Delete
------

````php
$dogpatch->delete($url, array $headers = array());
````

##### Parameters

>**url:** A compete url including the scheme *(HTTP, HTTPS)*.

>**headers:** An optional associated array of additional request headers to pass. Defaults to an empty array.

Head
----

````php
$dogpatch->head($url, array $headers = array());
````

##### Parameters

>**url:** A compete url including the scheme *(HTTP, HTTPS)*.

>**headers:** An optional associated array of additional request headers to pass. Defaults to an empty array.

Assert Status Code
------------------

````php
$dogpatch->assert_status_code($asserted_staus_code);
````

##### Parameters

>**asserted_staus_code:** An integer representing the expected response status code.

Assert Headers Exist
--------------------

````php
$dogpatch->assert_headers_exist(array $asserted_headers = array());
````

##### Parameters

>**asserted_headers:** A standard indexed array of expected response headers. The acutal values of the response headers **are not checked**, only that the header exists. The headers are checked **case-insensitive**.

Assert Headers
--------------

````php
$dogpatch->assert_headers(array $asserted_headers = array());
````

##### Parameters

>**asserted_headers:** An associated array of expected response headers and their expected value. The acutal values of the response headers **are checked**. The headers are checked **case-insensitive** but the header values are checked **case-sensitive**.

Assert Body
-----------

````php
$dogpatch->assert_body($asserted_body, $use_regular_expression = false);
````

##### Parameters

>**asserted_body:** Assert a response body, takes one of four options. A string that is checked **case-sensitive**. A regular expression that is checked according to the defined expression. A speical flag `IS_VALID_JSON` or `IS_EMPTY`. `IS_VALID_JSON` only validates that the response body is proper JSON and able to be decoded. `IS_EMPTY` validates that the response body is empty.

>**use_regular_expression:** An optional true/false flag which you may reference with globals `USE_REGEX` and `DONT_USE_REGEX`. If you wish `$asserted_body` to be checked via regular expression, you must set this parameter to true. Defaults to false.

Assert Body Against PHP
-----------------------

````php
$dogpatch->assert_body_php($asserted, $on_not_equal_var_export = false);
```

##### Parameters

>**asserted:** Assert a native PHP type *(usually a PHP object or array)* against the response body. The response body must be valid JSON, which is automatically decoded and compared against `$asserted`. PHP type keys and values are checked **case-sensitive**.

>**on_not_equal_var_export:** An optional true/false flag which you may reference with globals `VAR_EXPORT` and `DONT_VAR_EXPORT`. If a mismatch is detected between `$asserted` and the response body, variable export both making it convenient to find discrepancies. Defaults to false.

Assert Body Against JSON File
-----------------------------

````php
$dogpatch->assert_body_json_file($asserted_json_file, $on_not_equal_print_json = false);
```

##### Parameters

>**asserted_json_file:** Assert a JSON file *(the full path)* against the response body. The response body must be valid JSON, which is automatically decoded, pretty printed, and compared against the passed-in JSON file which is also pretty printed. Key names and values are checked **case-sensitive**.

>**on_not_equal_print_json:** An optional true/false flag which you may reference with globals `PRINT_JSON` and `DONT_PRINT_JSON`. If a mismatch is detected between the JSON file and the response body, print both making it convenient to find discrepancies. Defaults to false.

Assert Body is valid JSON
-------------------------

````php
$dogpatch->assert_valid_json();
```

Close
-----

````php
$dogpatch->close();
````

Closes the curl connection and unsets the curl resource and all dogpatch class variables. Don't call `close()` until you are completely done making requests with the curl connection. Once you call `close()` you must instantiate a new dogpatch object. For example, the following is invalid and will throw an exception:

````php
$dogpatch = new Dogpatch();
$dogpatch->get("https://www.google.com")
         ->close()
         ->get("https://github.com")
````

Instead do the following:

````php
$dogpatch = new Dogpatch();
$dogpatch->get("https://www.google.com")
         ->close();

$dogpatch = new Dogpatch();
$dogpatch->get("https://github.com")
         ->close();
````

Or, even better, if you'd like to reuse the same curl connection and options:

````php
$dogpatch = new Dogpatch();
$dogpatch->get("https://www.google.com")
         ->get("https://github.com")
         ->close();
````

Echo response body (for debug pourpose)
---------------------------------------

Echo the received response body

````php
$dogpatch->echoBody();
````

Echo full response (for debug pourpose)
---------------------------------------

Echo the received full response (headers + body)

````php
$dogpatch->echoResponse();
````

Echo received headers (for debug pourpose)
------------------------------------------

Echo the received response header

````php
$dogpatch->echoHeaders();
````

Echo sent headers (for debug pourpose)
--------------------------------------

Echo the sent headers

````php
$dogpatch->echoHeaderOut();
````

Echo received status code (for debug pourpose)
----------------------------------------------

Echo the received status code

````php
$dogpatch->echoStatus();
````

Save single received header
---------------------------


Save a single header in to a variable for later usage

````php
$dogpatch->saveHeader($header, &$store);
````

##### Parameters

>**header:** The header to be saved, for example `Content-Length`.

>**store:** The variable where the header content should be saved

Save received status code
-------------------------

Save the received status code for later usage

````php
$dogpatch->saveStatusCode(&$store);
````

##### Parameters

>**store:** The variable where the status code should be saved

Save response body
------------------

Save the response body for later usage

````php
$dogpatch->saveBody(&$store);
````

##### Parameters

>**store:** The variable where the responde body should be saved

Add field(s) to GET
-------------------

Add to next request an array of GET params

````php
$dogpatch->addGetFields($getArray);
````

##### Parameters

>**getArray:** The PHP array of parameters that should be added to next GET request

Replace GET fields
------------------

Replace GET params for next GET request

````php
$dogpatch->setGetFields($getArray);
````

##### Parameters

>**getArray:** The PHP array of parameters that should be used for next GET request

This example will execute a GET on https://github.com/?action=execute&code=12345

````php
$dogpatch = new Dogpatch();
$par=array(
  'action' => 'execute',
  'code'   => '12345',
);
$dogpatch->setGetFields($par)
         ->get("https://github.com")
         ->close();
````

Add field(s) to POST
--------------------

Add to next request an array of POST params

````php
$dogpatch->addPostFields($getArray);
````

##### Parameters

>**getArray:** The PHP array of parameters that should be added to next POST request

Replace POST fields
-------------------

Replace POST params for next POST request

````php
$dogpatch->setPostFields($getArray);
````

##### Parameters

>**getArray:** The PHP array of parameters that should be used for next POST request

This example will execute a POST on https://github.com using the following POST params: action=execute&code=12345

````php
$dogpatch = new Dogpatch();
$par=array(
  'action' => 'execute',
  'code'   => '12345',
);
$dogpatch->setPostFields($par)
         ->post("https://github.com")
         ->close();
````

Current Version
---------------

https://github.com/commando/dogpatch/blob/master/VERSION

Changelog
---------

https://github.com/commando/dogpatch/blob/master/CHANGELOG.md

Support, Bugs, And Feature Requests
-----------------------------------

Create issues here in GitHub (https://github.com/commando/dogpatch/issues).

Versioning
----------

For transparency and insight into our release cycle, and for striving to maintain backward compatibility, dogpatch will be maintained under the semantic versioning guidelines.

Releases will be numbered with the follow format:

`<major>.<minor>.<patch>`

And constructed with the following guidelines:

+ Breaking backward compatibility bumps the major (and resets the minor and patch)
+ New additions without breaking backward compatibility bumps the minor (and resets the patch)
+ Bug fixes and misc changes bumps the patch

For more information on semantic versioning, visit http://semver.org/.

License & Legal
---------------

Copyright 2014 NodeSocket, LLC.

Licensed under the Apache License, Version 2.0 (the "License"); you may not use this work except in compliance with the License. You may obtain a copy of the License in the LICENSE file, or at:

    http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.
