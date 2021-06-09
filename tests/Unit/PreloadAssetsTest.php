<?php

namespace Tests\Unit;

use App\Http\Middleware\PreloadAssets;
use BabDev\ServerPushManager\Contracts\PushManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

final class PreloadAssetsTest extends TestCase
{
    /**
     * @var MockObject&PushManager
     */
    private $pushManager;

    /**
     * @var PreloadAssets
     */
    private $middleware;

    protected function setUp(): void
    {
        $this->pushManager = $this->createMock(PushManager::class);

        $this->middleware = new PreloadAssets($this->pushManager);
    }

    /** @test */
    public function the_push_manager_is_not_called_if_the_response_is_a_redirect(): void
    {
        $next = static fn (): RedirectResponse => new RedirectResponse('/');

        $this->pushManager->expects($this->never())
            ->method('dnsPrefetch');

        $this->middleware->handle(new Request(), $next);
    }

    /** @test */
    public function the_push_manager_is_not_called_if_the_response_is_json(): void
    {
        $next = static fn (): JsonResponse => new JsonResponse(['success' => true]);

        $this->pushManager->expects($this->never())
            ->method('dnsPrefetch');

        $this->middleware->handle(new Request(), $next);
    }

    /** @test */
    public function the_push_manager_is_not_called_if_the_response_is_not_a_laravel_response(): void
    {
        $next = static fn (): BinaryFileResponse => new BinaryFileResponse(__FILE__);

        $this->pushManager->expects($this->never())
            ->method('dnsPrefetch');

        $this->middleware->handle(new Request(), $next);
    }

    /** @test */
    public function the_push_manager_is_called_if_the_response_is_a_laravel_response(): void
    {
        $next = static fn (): Response => new Response('Hello!');

        $this->pushManager->expects($this->exactly(2))
            ->method('dnsPrefetch');

        $this->middleware->handle(new Request(), $next);
    }
}
