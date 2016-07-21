#!/usr/bin/env php
<?php
/*
 * Copyright (c) 2016, Josef Kufner  <josef@kufner.cz>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 *     http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 */

if (php_sapi_name() != 'cli') {
	die("Command line only.\n");
}

// Throw exceptions on all errors
set_error_handler(function ($errno, $errstr, $errfile, $errline ) {
	if (error_reporting()) {
		throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
	}
});

exec('composer update', $out, $ret);
if ($ret != 0) {
	die("Composer failed.\n");
}

if (!is_dir('statemachine')) {
	mkdir('statemachine');
}

if (!is_dir('data')) {
	mkdir('data');
}

copy('vendor/smalldb/smalldb-rest/api-v1.php.example', './api-v1.php');
copy('vendor/smalldb/smalldb-rest/api-v1.php.example', './api-v1-diagram.php');
copy('vendor/smalldb/smalldb-rest/test/example/config.app.json.php', './config.app.json.php');
copy('vendor/smalldb/smalldb-rest/test/example/database.sqlite', './data/database.sqlite');
copy('vendor/smalldb/smalldb-rest/test/example/statemachine/blogpost.json', './statemachine/blogpost.json');
copy('vendor/smalldb/smalldb-rest/test/example/statemachine/session.json', './statemachine/session.json');
copy('vendor/smalldb/smalldb-rest/test/example/statemachine/user.json', './statemachine/user.json');

