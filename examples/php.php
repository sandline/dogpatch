<?php
    /*
    # Copyright 2014 NodeSocket, LLC.
    #
    # Licensed under the Apache License, Version 2.0 (the "License");
    # you may not use this file except in compliance with the License.
    # You may obtain a copy of the License at
    #
    # http://www.apache.org/licenses/LICENSE-2.0
    #
    # Unless required by applicable law or agreed to in writing, software
    # distributed under the License is distributed on an "AS IS" BASIS,
    # WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
    # See the License for the specific language governing permissions and
    # limitations under the License.
    */

    require_once(dirname(__dir__) . "/Dogpatch.php");

    $expected = new stdClass();
    $expected->ip = "8.8.8.8";
    $expected->country_code = "US";
    $expected->country_name = "United States";
    $expected->region_code = "";
    $expected->region_name = "";
    $expected->city = "";
    $expected->zipcode = "";
    $expected->latitude = 38;
    $expected->longitude = -97;
    $expected->metro_code = "";
    $expected->areacode = "";

    $dogpatch = new Dogpatch();

    $dogpatch->get("https://freegeoip.net/json/8.8.8.8")
             ->assert_status_code(200)
             ->assert_headers_exist(array(
                "Date"
             ))
             ->assert_headers(array(
                "Access-Control-Allow-Origin" => "*"
             ))
             ->assert_body_php($expected, VAR_EXPORT)
             ->close();
?>