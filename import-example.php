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
	die('Command line only.');
}

exec('composer install');

if (!is_dir('statemachine')) {
	mkdir('statemachine');
}
copy('vendor/smalldb/smalldb-rest/api-v1.php.example', './api-v1.php');
copy('vendor/smalldb/smalldb-rest/test/example/config.app.json.php', './config.app.json.php');
copy('vendor/smalldb/smalldb-rest/test/example/database.sqlite', './database.sqlite');
copy('vendor/smalldb/smalldb-rest/test/example/statemachine/blogpost.json', './statemachine/blogpost.json');

