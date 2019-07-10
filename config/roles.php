<?php

return [
  'employee' => [
      'parent' => null,
  ],
  'manager' => [
      'parent' => 'employee',
  ],
  'admin' => [
      'parent' => 'manager',
  ],
];
