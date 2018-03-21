<?php

namespace BabDev\Website;

use Joomla\Application\AbstractApplication;
use Joomla\Application\ApplicationEvents;
use Joomla\Input\Input;
use Joomla\Registry\Registry;
use Joomla\Router\Router;
use Joomla\Uri\Uri;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\EmitterInterface;
use Zend\Diactoros\Response\SapiEmitter;

final class Application extends AbstractApplication
{
    /**
     * @var ControllerResolver
     */
    private $controllerResolver;

    /**
     * @var EmitterInterface
     */
    private $emitter;

    /**
     * @var ResponseInterface
     */
    private $response;

    /**
     * @var Router
     */
    private $router;

    public function __construct(ControllerResolver $controllerResolver, Router $router, Input $input = null, Registry $config = null)
    {
        parent::__construct($input, $config);

        $this->controllerResolver = $controllerResolver;
        $this->router             = $router;

        $this->emitter  = new SapiEmitter();
        $this->response = new Response();
    }

    private function detectRequestUri(): string
    {
        // First we need to detect the URI scheme.
        $scheme = $this->isSslConnection() ? 'https://' : 'http://';

        /*
         * There are some differences in the way that Apache and IIS populate server environment variables.  To
         * properly detect the requested URI we need to adjust our algorithm based on whether or not we are getting
         * information from Apache or IIS.
         */

        $phpSelf    = $this->input->server->getString('PHP_SELF', '');
        $requestUri = $this->input->server->getString('REQUEST_URI', '');

        // If PHP_SELF and REQUEST_URI are both populated then we will assume "Apache Mode".
        if (!empty($phpSelf) && !empty($requestUri)) {
            // The URI is built from the HTTP_HOST and REQUEST_URI environment variables in an Apache environment.
            $uri = $scheme . $this->input->server->getString('HTTP_HOST') . $requestUri;
        } else {
            // If not in "Apache Mode" we will assume that we are in an IIS environment and proceed.
            // IIS uses the SCRIPT_NAME variable instead of a REQUEST_URI variable... thanks, MS
            $uri       = $scheme . $this->input->server->getString('HTTP_HOST') . $this->input->server->getString('SCRIPT_NAME');
            $queryHost = $this->input->server->getString('QUERY_STRING', '');

            // If the QUERY_STRING variable exists append it to the URI string.
            if (!empty($queryHost)) {
                $uri .= '?' . $queryHost;
            }
        }

        return trim($uri);
    }

    protected function doExecute(): void
    {
        $route = $this->router->parseRoute($this->get('uri.route'), $this->input->getMethod());

        // Add variables to the input if not already set
        foreach ($route['vars'] as $key => $value) {
            $this->input->def($key, $value);
        }

        $this->controllerResolver->resolve($route['controller'])->execute();
    }

    public function execute(): void
    {
        $this->dispatchEvent(ApplicationEvents::BEFORE_EXECUTE);

        $this->loadSystemUris();
        $this->doExecute();

        $this->dispatchEvent(ApplicationEvents::AFTER_EXECUTE);

        $this->dispatchEvent(ApplicationEvents::BEFORE_RESPOND);

        $this->emitter->emit($this->getResponse());

        $this->dispatchEvent(ApplicationEvents::AFTER_RESPOND);
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

    public function isSslConnection(): bool
    {
        $serverSSLVar = $this->input->server->getString('HTTPS', '');

        return !empty($serverSSLVar) && strtolower($serverSSLVar) !== 'off';
    }

    private function loadSystemUris(): void
    {
        $this->set('uri.request', $this->detectRequestUri());

        $uri = new Uri($this->get('uri.request'));

        $requestUri = $this->input->server->getString('REQUEST_URI', '');

        // If we are working from a CGI SAPI with the 'cgi.fix_pathinfo' directive disabled we use PHP_SELF.
        if (strpos(PHP_SAPI, 'cgi') !== false && !ini_get('cgi.fix_pathinfo') && !empty($requestUri)) {
            // We aren't expecting PATH_INFO within PHP_SELF so this should work.
            $path = dirname($this->input->server->getString('PHP_SELF', ''));
        } else {
            // Pretty much everything else should be handled with SCRIPT_NAME.
            $path = dirname($this->input->server->getString('SCRIPT_NAME', ''));
        }

        // Get the host from the URI.
        $host = $uri->toString(['scheme', 'user', 'pass', 'host', 'port']);

        // Check if the path includes "index.php".
        if (strpos($path, 'index.php') !== false) {
            // Remove the index.php portion of the path.
            $path = substr_replace($path, '', strpos($path, 'index.php'), 9);
        }

        $path = rtrim($path, '/\\');

        // Set the base URI both as just a path and as the full URI.
        $this->set('uri.base.full', $host . $path . '/');
        $this->set('uri.base.host', $host);
        $this->set('uri.base.path', $path . '/');

        // Set the extended (non-base) part of the request URI as the route.
        if (stripos($this->get('uri.request'), $this->get('uri.base.full')) === 0) {
            $this->set('uri.route', substr_replace($this->get('uri.request'), '', 0, strlen($this->get('uri.base.full'))));
        }

        // No explicit media URI was set, build it dynamically from the base uri.
        $this->set('uri.media.full', $this->get('uri.base.full') . 'media/');
        $this->set('uri.media.path', $this->get('uri.base.path') . 'media/');
    }

    public function setHeader(string $name, string $value, bool $replace = false): void
    {
        $response = $this->getResponse();

        // If the replace flag is set, unset all known headers with the given name.
        if ($replace && $response->hasHeader($name)) {
            $response = $response->withoutHeader($name);
        }

        $this->setResponse($response->withAddedHeader($name, $value));
    }

    public function setResponse(ResponseInterface $response): void
    {
        $this->response = $response;
    }
}
