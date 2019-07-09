<?php
require './common/init.php';
$search = input('get','search','s');
$like = '%'.db_escape_like($search).'%';
$list = db_fetch_all("SELECT `id`,`pid`,`name`,`save` FROM `picture` WHERE `name` LIKE ? ORDER BY `id` DESC ",'s',[$like]);
require './view/search.html';