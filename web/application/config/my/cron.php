<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['_cron_version'] = '2012-11-12';

define('NEWS_ADD_TASK_LIMIT', 50);
define('NEWS_CHECK_TASK_LIMIT', 50);
define('NEWS_SENDMAIL_LIMIT', 50); // 1秒間に何通配信するか
define('NEWS_SENDMAIL_LOOP_LIMIT', 10); // 1回の処理で NEWS_SENDMAIL_LIMIT * NEWS_SENDMAIL_LOOP_LIMIT 配信