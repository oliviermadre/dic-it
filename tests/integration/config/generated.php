<?php $array = array (
  'classes' =>
  array (
    'ExceptionInterceptor' =>
    array (
      'class' => 'Berthe_Interceptor_Exception',
    ),
    'PrettyExceptionInterceptor' =>
    array (
      'class' => 'PrettyExceptionInterceptor',
    ),
    'MySubService' =>
    array (
      'class' => 'AnotherClass',
    ),
    'CountryService' =>
    array (
      'class' => 'Berthe_Service_Country',
      'singleton' => true,
      'arguments' =>
      array (
        0 => '@CountryManager',
        1 => 2,
      ),
      'call' =>
      array (
        'setManager' =>
        array (
          0 => '@CountryManager',
        ),
      ),
      'interceptor' =>
      array (
      ),
    ),
    'CountryManager' =>
    array (
      'class' => 'Berthe_Modules_Country_Manager',
      'props' =>
      array (
        'storage' => '@CountryStorage',
      ),
    ),
    'CountryStorage' =>
    array (
      'class' => 'Berthe_Store_Echo',
      'interceptor' =>
      array (
      ),
      'props' =>
      array (
        'injectedVariable' => 'zomg lolilol',
      ),
    ),
  ),
);
