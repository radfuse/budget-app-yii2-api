<?php

$params = [
  'access-token-expire' => 3600, // seconds, 0 means forever
  'refresh-token-expire' => 0, // seconds, 0 means forever
];

if (file_exists(__DIR__ . '/params-local.php')) {
  $params = array_merge(
      $params,
      require(__DIR__ . '/params-local.php')
  );
}

return $params;