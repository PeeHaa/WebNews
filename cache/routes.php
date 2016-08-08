<?php return array (
  0 => 
  array (
    'GET' => 
    array (
      '/not-found' => 
      array (
        0 => 'WebNews\\Presentation\\Controller\\Error',
        1 => 'notFound',
      ),
      '/method-not-allowed' => 
      array (
        0 => 'WebNews\\Presentation\\Controller\\Error',
        1 => 'methodNotAllowed',
      ),
      '/' => 
      array (
        0 => 'WebNews\\Presentation\\Controller\\Index',
        1 => 'index',
      ),
    ),
  ),
  1 => 
  array (
    'GET' => 
    array (
      0 => 
      array (
        'regex' => '~^(?|/js/(.+)|/css/(.+)()|/fonts/(.+)()()|/([^/]+)()()()|/([^/]+)/([^/]+)/([^/]+)()())$~',
        'routeMap' => 
        array (
          2 => 
          array (
            0 => 
            array (
              0 => 'WebNews\\Presentation\\Controller\\Resource',
              1 => 'renderJavascript',
            ),
            1 => 
            array (
              'filename' => 'filename',
            ),
          ),
          3 => 
          array (
            0 => 
            array (
              0 => 'WebNews\\Presentation\\Controller\\Resource',
              1 => 'renderStylesheet',
            ),
            1 => 
            array (
              'filename' => 'filename',
            ),
          ),
          4 => 
          array (
            0 => 
            array (
              0 => 'WebNews\\Presentation\\Controller\\Resource',
              1 => 'renderFont',
            ),
            1 => 
            array (
              'filename' => 'filename',
            ),
          ),
          5 => 
          array (
            0 => 
            array (
              0 => 'WebNews\\Presentation\\Controller\\Group',
              1 => 'showThreads',
            ),
            1 => 
            array (
              'group' => 'group',
            ),
          ),
          6 => 
          array (
            0 => 
            array (
              0 => 'WebNews\\Presentation\\Controller\\Thread',
              1 => 'showMessages',
            ),
            1 => 
            array (
              'group' => 'group',
              'threadId' => 'threadId',
              '_threadName' => '_threadName',
            ),
          ),
        ),
      ),
    ),
  ),
);