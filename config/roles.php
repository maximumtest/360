<?php

return [
  'employee' => [
      'prev' => null,
  ],
  'manager' => [
      'prev' => 'employee',
  ],
  'admin' => [
      'prev' => 'manager',
  ],
];
