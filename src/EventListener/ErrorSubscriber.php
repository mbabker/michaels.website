<?php declare(strict_types=1);

namespace BabDev\Website\EventListener;

use Joomla\Application\ApplicationEvents;
use Joomla\Application\Event\ApplicationErrorEvent;
use Joomla\Application\WebApplication;
use Joomla\Event\SubscriberInterface;
use Joomla\Renderer\RendererInterface;
use Joomla\Router\Exception\MethodNotAllowedException;
use Joomla\Router\Exception\RouteNotFoundException;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\XmlResponse;

final class ErrorSubscriber implements SubscriberInterface
{
    private const STATUS_TEXTS = [
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',            // RFC2518
        103 => 'Early Hints',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',          // RFC4918
        208 => 'Already Reported',      // RFC5842
        226 => 'IM Used',               // RFC3229
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',    // RFC7238
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Payload Too Large',
        414 => 'URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Range Not Satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot',                                               // RFC2324
        421 => 'Misdirected Request',                                         // RFC7540
        422 => 'Unprocessable Entity',                                        // RFC4918
        423 => 'Locked',                                                      // RFC4918
        424 => 'Failed Dependency',                                           // RFC4918
        425 => 'Too Early',                                                   // RFC-ietf-httpbis-replay-04
        426 => 'Upgrade Required',                                            // RFC2817
        428 => 'Precondition Required',                                       // RFC6585
        429 => 'Too Many Requests',                                           // RFC6585
        431 => 'Request Header Fields Too Large',                             // RFC6585
        451 => 'Unavailable For Legal Reasons',                               // RFC7725
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates',                                     // RFC2295
        507 => 'Insufficient Storage',                                        // RFC4918
        508 => 'Loop Detected',                                               // RFC5842
        510 => 'Not Extended',                                                // RFC2774
        511 => 'Network Authentication Required',                             // RFC6585
    ];

    private RendererInterface $renderer;

    public function __construct(RendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ApplicationEvents::ERROR => 'handleError',
        ];
    }

    public function handleError(ApplicationErrorEvent $event): void
    {
        /** @var WebApplication $app */
        $app = $event->getApplication();

        // Ensure responses are not cached
        $app->allowCache(false);

        $isHttpErrorCode = $event->getError()->getCode() >= 400 && $event->getError()->getCode() <= 599;

        switch ($app->getInput()->getString('_format', 'html')) {
            case 'xml':
                $response = new XmlResponse(
                    $this->renderer->render(
                        'exception.xml.twig',
                        [
                            'exception' => $event->getError(),
                            'status'    => $isHttpErrorCode ? $event->getError()->getCode() : 500,
                            'title'     => $isHttpErrorCode ? (self::STATUS_TEXTS[$event->getError()->getCode()] ?? self::STATUS_TEXTS[500]) : self::STATUS_TEXTS[500],
                        ]
                    )
                );

                break;

            case 'html':
            default:
                $response = new HtmlResponse(
                    $this->renderer->render(
                        'exception.html.twig',
                        [
                            'exception' => $event->getError(),
                            'status'    => $isHttpErrorCode ? $event->getError()->getCode() : 500,
                            'title'     => $isHttpErrorCode ? (self::STATUS_TEXTS[$event->getError()->getCode()] ?? self::STATUS_TEXTS[500]) : self::STATUS_TEXTS[500],
                        ]
                    )
                );

                break;
        }

        if ($event->getError() instanceof MethodNotAllowedException) {
            $response = $response->withStatus(405);
            $response = $response->withHeader('Allow', implode(', ', $event->getError()->getAllowedMethods()));
        } elseif ($event->getError() instanceof RouteNotFoundException) {
            $response = $response->withStatus(404);
        } elseif ($app->isValidHttpStatus($event->getError()->getCode())) {
            $response = $response->withStatus($event->getError()->getCode());
        } else {
            $response = $response->withStatus(500);
        }

        $app->setResponse($response);
    }
}
