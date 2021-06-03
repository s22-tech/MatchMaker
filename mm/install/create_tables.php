<?php

include '../includes/config.inc.php';

echo <<<"CREATE"
	<!doctype html>
	<html>
	<head>
	<title>Install Matchmaker</title>
	<base href="{$config['base_url']}">
	<link rel="stylesheet" href="{$config['rewrite_base']}/includes/css/normalize.css" />
	<link rel="stylesheet" href="{$config['rewrite_base']}/includes/css/font-awesome.min.css" />
	<link rel="stylesheet" href="{$config['rewrite_base']}/includes/css/bootstrap.css" />
	<link rel="stylesheet" href="{$config['rewrite_base']}/includes/css/cls.css" />

	<script src="{$config['rewrite_base']}/includes/js/javascript.js"></script>
	<script src="{$config['rewrite_base']}/includes/js/jquery-3.6.0.min.js"></script>
  </head>
  <body>
		<div class="container">
	<div class="content">
	<br>
	<h1>MatchMaker Installation</h1>
	<h3>Results:</h3>
CREATE;

if (isset($_POST['submit'])) {

	$db['db_name']  = $_POST['db_name'];
	$db['db_host']  = $_POST['db_host'] ?? 'localhost';
	$db['port']     = $_POST['port'];
	$db['db_user']  = $_POST['db_user'];
	$db['db_pass']  = $_POST['db_pass'];
	$db['tz']       = $_POST['timezone'];
	$db['domain']   = $_POST['domain'];
	$db['charset']  = 'utf8mb4';

	$dsn = "mysql:host={$db['db_host']};port={$db['db_port']};dbname={$db['db_name']};charset={$db['charset']}";
	$options = [
			 PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
			 PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
			 PDO::ATTR_EMULATE_PREPARES   => false,
	];

	try {
		$pdo = new PDO($dsn, $db['db_user'], $db['db_pass'], $options);
	}
	catch (PDOException $e) {
		throw new PDOException($e->getMessage(), (int)$e->getCode());
	}


	 create_table('foes',
						"`id` INT(11) unsigned NOT NULL AUTO_INCREMENT,
						`user` varchar(16) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
						`foe`  varchar(16) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
						 PRIMARY KEY (`id`)"
					  );

	  create_table('members',
						"`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
						`user` varchar(16) COLLATE latin1_general_ci NOT NULL DEFAULT '',
						`pass` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
						`gender` varchar(2) COLLATE latin1_general_ci NULL,
						`info`  varchar(4096) COLLATE latin1_general_ci DEFAULT NULL,
						`email` varchar(44) COLLATE latin1_general_ci DEFAULT NULL,
						`skype` varchar(44) COLLATE latin1_general_ci DEFAULT NULL,
						`birth_month` int(2) DEFAULT NULL,
						`birth_year` int(4) DEFAULT NULL,
						`status` varchar(8) COLLATE latin1_general_ci NOT NULL DEFAULT '',
						`state` char(2) COLLATE latin1_general_ci DEFAULT '',
						`timezone` varchar(20) DEFAULT NULL,
						 PRIMARY KEY (`id`),
						 UNIQUE KEY `user_2` (`user`),
						 KEY `user` (`user`(6))"
					  );

	  create_table('messages',
						"`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
						`auth`  varchar(16) COLLATE latin1_general_ci DEFAULT NULL,
						`recip` varchar(16) COLLATE latin1_general_ci DEFAULT NULL,
						`pm` tinyint(1) DEFAULT NULL,
						`date_time` datetime DEFAULT NULL,
						`message` varchar(4096) COLLATE latin1_general_ci DEFAULT NULL,
						`hide` varchar(3) COLLATE latin1_general_ci NOT NULL DEFAULT 'no',
						 PRIMARY KEY (`id`),
						 KEY `auth` (`auth`(6)),
						 KEY `recip` (`recip`(6))"
					  );

	  create_table('friends',
						"`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
						`user` varchar(16) COLLATE latin1_general_ci DEFAULT NULL,
						`friend` varchar(16) COLLATE latin1_general_ci DEFAULT NULL,
						 PRIMARY KEY (`id`),
						 KEY `user` (`user`(6)),
						 KEY `friend` (`friend`(6))"
					  );

	  create_table('images',
						"`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
						`user`  varchar(16) NOT NULL DEFAULT '',
						`image` varchar(40) NOT NULL DEFAULT '',
						 PRIMARY KEY (`id`)"
					  );

	  create_table('settings',
						"`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
						`key`   varchar(64) DEFAULT NULL,
						`title` varchar(128) DEFAULT NULL,
						`value` varchar(8192) DEFAULT NULL,
						 PRIMARY KEY (`id`)"
					  );


	echo '<p> &nbsp; </p>';


  // Populate the settings table with global information.
// 	populate_table('settings', ['key', 'title', 'value'], ['timezone', 'The timezone of the server.', $timezone]);

  // Populate db.inc.php with the database credentials.
	file_put_contents($_SERVER['DOCUMENT_ROOT'] . $config['rewrite_base'] .'/includes/db.inc.php', '<?php return ' . var_export($db, true) . ';');

	echo '<p> &nbsp; </p>
	The settings were written to db.inc.php
	<p> &nbsp; </p>';

	die('<p> &nbsp; </p>
	<h3>Success!</h3> <span style="font-size:1.2em"> You can now <a href="'. $config['rewrite_base'] .'/login.php">log in</a> </span>.
	</div>
	</div>
	</body>
	</html>');
}


function create_table ($table, $query) {
	global $pdo;
  // PDO placeholders can't be used here.  Why?
	$pdo->exec("CREATE TABLE IF NOT EXISTS `$table` ($query)");
   echo "&bull; Table `$table` was created successfully or already exists.<br>";
}


function populate_table ($table, $fields, $values) {
	global $pdo;
	$fields_sql = '';
	foreach ($fields as $field) {
		$fields_sql .= '`'. $field .'`, ';
	}
	$fields_sql = rtrim($fields_sql, ', ');

	$values_sql = '';
	foreach ($values as $value) {
		$values_sql .= "'". $value ."', ";
	}
	$values_sql = rtrim($values_sql, ', ');

	$sql = "INSERT INTO `$table` ($fields_sql) VALUES ($values_sql)";
	$pdo->prepare($sql)->execute();
   echo "&bull; Table `$table` was populated with $values_sql.<br>";
}

