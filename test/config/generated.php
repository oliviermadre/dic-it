<?php $array = array (
  'parameters' => 
  array (
    'var1' => 'value1',
    'var2' => 'value2',
  ),
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
    'CountryService' => 
    array (
      'class' => 'Berthe_Service_Country',
      'singleton' => true,
      'arguments' => 
      array (
        0 => 1,
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