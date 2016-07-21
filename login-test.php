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

// Load Composer's class loader
require __DIR__."/vendor/autoload.php";

ini_set('display_errors', true);

/***************************************************************************/

$config  = \Smalldb\Rest\Application::loadConfig(__DIR__);
$smalldb = \Smalldb\Rest\Application::createSmalldb($config);

$auth = $smalldb->getContext('auth');

$session_machine = $auth->getSessionMachine();

$session_state_diagram_url = './api-v1-diagram.php?machine=session&format=svg';
$user_state_diagram_url = './api-v1-diagram.php?machine=user&format=svg';
$status = [];

if (isset($_POST['action'])) {
	switch ($_POST['action']) {
	case 'login':
		list($user, $password) = explode(':', $_POST['credentials']);
		$status[] = sprintf('Login: user = %s, password = %s', var_export($user, true), var_export($password, true));
		if ($session_machine->login($user, $password)) {
			$status[] = 'Login ok.';
		} else {
			$status[] = 'Login failed.';
		}
		break;

	case 'logout':
		$status['action'] = 'logout';
		$session_machine->logout();
		break;
	}
}

$user_list = $smalldb->createListing(['type' => 'user']);
$session_list = $smalldb->createListing(['type' => 'session']);


function print_table(\Smalldb\StateMachine\IListing $listing)
{
	$props = $listing->describeProperties();

	echo "<table class=\"listing\">\n";

	// head
	{
		echo "<tr>\n";
		foreach ($props as $p => $prop) {
			echo "<th>", htmlspecialchars($p), "</th>\n";
		}
		echo "</tr>\n";
	}

	// body
	$rows = $listing->fetchAll();
	if (empty($rows)) {
		echo "<tr class=\"empty_note\">\n";
		echo "<td colspan=\"", count($props), "\"><em>", _('No rows.'), "</em></td>\n";
		echo "</tr>\n";
	} else foreach ($rows as $row) {
		echo "<tr>\n";
		foreach ($props as $p => $prop) {
			echo "<td>", htmlspecialchars($row[$p]), "</td>\n";
		}
		echo "</tr>\n";
	}

	echo "</table>\n";
}

/***************************************************************************/
?>
<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<title>Auth tester</title>
	<style type="text/css">
		body {
			font-family: DejaVu Sans;
			margin: 2em auto;
			padding: 0em 1em;
			max-width: 50rem;
		}
		pre, code {
			font-family: DejaVu Sans Mono;
			font-size: inherit;
		}
		form {
			margin: 1em 0em;
			border: 1px solid #ccc;
			background: #ddd;
			padding: 0.5em;
		}
		h2 {
			margin: 3rem 0rem 1rem 0rem;
		}

		img.large {
			display: block;
			margin: 1em auto;
			max-width: 100%;
		}

		.toolbar {
			display: flex;
			flex-wrap: wrap;
			align-items: baseline;
			flex-direction: row;
			justify-content: space-between;
		}

		.toolbar form {
			display: block;
			flex-grow: 1;
			text-align: center;
			margin: 0em 0em 0em 1em;
			padding: 1em;
		}

		.toolbar form:first-child {
			margin-left: 0em;
		}

		@media (max-width: 40rem) {
			.toolbar {
				display: block;
			}
			.toolbar form {
				margin: 1em 0em;
			}
		}

		form.login {
			background: #bfa;
			border-color: #ad8;
		}
		form.logout {
			background: #fba;
			border-color: #da8;
		}

		.toolbar select, .toolbar input {
			height: 2em;
			padding-left: 1em;
			padding-right: 1em;
		}

		.xdebug-error {
			font-size: 0.9rem;
		}

		table.listing {
			border-collapse: collapse;
			border: 1px solid #ccc;
			margin: 1em auto;
			max-width: 100%;
		}
		table.listing th,
		table.listing td {
			border: 1px solid #ccc;
			padding: 0.5em 1em;
		}
		table.listing td {
			border-bottom: none;
			border-top: none;
		}
		table.listing tr.empty_note td {
			text-align: center;
			color: #888;
		}
	</style>
</head>
<body>

<h1>Auth tester</h1>

<div class="toolbar">

	<form action="" method="post" class="login">
		<select name="credentials">
			<option value="alice:123">Alice</option>
			<option value="bob:abc">Bob</option>
			<option value="eve:xyz">Eve</option>
		</select>
		<input type="submit" name="submit" value="Login">
		<input type="hidden" name="action" value="login">
	</form>

	<form action="" method="get" class="middle">
		<input type="submit" value="Do something else">
		<input type="hidden" name="t" value="<?php echo time(); ?>">
	</form>

	<form action="" method="post" class="logout">
		<input type="submit" name="submit" value="Logout">
		<input type="hidden" name="action" value="logout">
	</form>

</div>

<h2>Status</h2>

<ul>
<?php foreach($status as $s): ?>
	<li><?php echo htmlspecialchars($s) ?></li>
<?php endforeach ?>
</ul>

<h2>Session machine</h2>

<div>State: <code><?php var_export($session_machine->state); ?></code></div>
<?php if ($session_machine->state): ?>
<?php var_dump($session_machine->properties); ?>
<?php endif ?>

<h2>Received cookies</h2>

<div>
<?php var_dump($_COOKIE); ?>
</div>


<h2>Sessions</h2>

<?php print_table($session_list); ?>


<h2>Users</h2>

<?php print_table($user_list); ?>


<h2>Session state machine</h2>

<img class="large" src="<?php echo htmlspecialchars($session_state_diagram_url); ?>" alt="[session state diagram]">

<h2>User state machine</h2>

<img class="large" src="<?php echo htmlspecialchars($user_state_diagram_url); ?>" alt="[user state diagram]">

<h2>Config</h2>

<div>
<?php var_dump($config); ?>
</div>

</body>

