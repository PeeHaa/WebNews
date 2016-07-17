<?php declare(strict_types=1);

namespace WebNews;

use Auryn\Injector as Auryn;
use CodeCollab\Encryption\Encryptor;
use CodeCollab\Encryption\Defusev2\Encryptor as DefuseEncryptor;
use CodeCollab\Encryption\Decryptor;
use CodeCollab\Encryption\Defusev2\Decryptor as DefuseDecryptor;
use CodeCollab\Http\Request\Request;
use CodeCollab\Http\Cookie\Factory as CookieFactory;
use CodeCollab\Http\Session\Session;
use CodeCollab\Http\Session\Native as NativeSession;
use CodeCollab\Router\Router;
use FastRoute\RouteParser;
use FastRoute\RouteParser\Std as StdRouteParser;
use FastRoute\DataGenerator;
use FastRoute\DataGenerator\GroupCountBased as GroupCountBasedDataGenerator;
use FastRoute\Dispatcher\GroupCountBased as RouteDispatcher;
use WebNews\Presentation\Template\Html;
use CodeCollab\Theme\Loader as ThemeLoader;
use CodeCollab\Theme\Theme;
use CodeCollab\I18n\Translator;
use CodeCollab\I18n\FileTranslator;
use CodeCollab\CsrfToken\Token;
use CodeCollab\CsrfToken\Handler as CsrfToken;
use CodeCollab\CsrfToken\Storage\Storage as TokenStorage;
use WebNews\Storage\TokenSession;
use CodeCollab\CsrfToken\Generator\Generator as TokenGenerator;
use CodeCollab\CsrfToken\Generator\RandomBytes32;
use CodeCollab\Router\FrontController;

/**
 * Setup the autoloader
 */
require_once __DIR__ . '/vendor/autoload.php';

/**
 * Load the configuration
 */
require_once __DIR__ . '/config/config.php';

/**
 * Prevent further execution when on CLI
 */
if (php_sapi_name() === 'cli') {
    return;
}

/**
 * Setup DI
 */
$auryn = new Auryn();
$auryn->share($auryn); // yolo

/**
 * Setup encryption
 */
$auryn->share(Decryptor::class);
$auryn->alias(Encryptor::class, DefuseEncryptor::class);
$auryn->alias(Decryptor::class, DefuseDecryptor::class);
$auryn->define(Encryptor::class, [':key' => file_get_contents(__DIR__ . '/data/encryption.key')]);
$auryn->define(Decryptor::class, [':key' => file_get_contents(__DIR__ . '/data/encryption.key')]);

/**
 * Setup the request object
 */
$auryn->share(Request::class);
$request = $auryn->make(Request::class, [
    ':server'  => $_SERVER,
    ':get'     => $_GET,
    ':post'    => $_POST,
    ':files'   => $_FILES,
    ':cookies' => $_COOKIE,
    ':input'   => file_get_contents('php://input'),
]);

/**
 * Setup cookies
 */
$auryn->define(CookieFactory::class, [
    ':domain' => $request->server('SERVER_NAME'),
    ':secure' => $request->isEncrypted(),
]);

/**
 * Setup the session
 */
$auryn->share(Session::class);
$auryn->alias(Session::class, NativeSession::class);
$auryn->define(NativeSession::class, [
    ':path'   => '/',
    ':domain' => $request->server('SERVER_NAME'),
    ':secure' => $request->isEncrypted()
]);

/**
 * Setup the router
 */
$cacheFile = __DIR__ . '/cache/routes.php';
$auryn->share(Router::class);
$auryn->alias(RouteParser::class, StdRouteParser::class);
$auryn->alias(DataGenerator::class, GroupCountBasedDataGenerator::class);
/** @var array $configuration */
$auryn->define(Router::class, [
    ':dispatcherFactory' => function($dispatchData) {
        return new RouteDispatcher($dispatchData);
    },
    ':cacheFile' => $cacheFile,
    ':forceReload' => $configuration['reloadRoutes'],
]);

/**
 * Setup templating
 */
$auryn->define(Html::class, [':basePage' => '/page.phtml']);
$auryn->alias(ThemeLoader::class, Theme::class);
/** @var array $configuration */
$auryn->define(Theme::class, [':themePath' => __DIR__ . '/themes', ':theme' => $configuration['activeTheme']]);

/**
 * Setup translator
 */
$auryn->share(Translator::class);
$auryn->alias(Translator::class, FileTranslator::class);
$auryn->define(FileTranslator::class, [
    ':translationDirectory' => __DIR__ . '/texts',
    ':languageCode' => $configuration['activeLanguage']
]);

/**
 * Setup the CSRF token
 */
$auryn->alias(Token::class, CsrfToken::class);
$auryn->alias(TokenStorage::class, TokenSession::class);
$auryn->alias(TokenGenerator::class, RandomBytes32::class);

/**
 * Load the routes
 */
require_once __DIR__ . '/routes.php';

/**
 * Setup the front controller
 */
$frontController = $auryn->make(FrontController::class);

/**
 * Run the application
 */
$frontController->run($request);
