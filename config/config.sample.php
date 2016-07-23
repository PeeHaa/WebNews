<?php declare(strict_types=1);

$configuration = [
    // Set to false on production to use cached routes
    'reloadRoutes'       => true,
    // Set to false on production to use compiled resources
    'minifyResources'    => true,
    // The active theme that is used
    'activeTheme'        => 'Default',
    // directory of the css, fonts and js files in the theme
    'resourcesDirectory' => '/resources',
    // The language used by the application
    'activeLanguage'     => 'en_US',
    // data connection details
    'dbDsn'              => 'dsn string',
    'dbUsername'         => 'database user',
    'dbPassword'         => 'database password',
];
