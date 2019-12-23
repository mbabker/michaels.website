<?php declare(strict_types=1);

namespace BabDev\Website\Service;

use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

final class SerializerProvider implements ServiceProviderInterface
{
    public function register(Container $container): void
    {
        $container->share(
            SerializerInterface::class,
            function (): Serializer {
                $encoders = [
                    new JsonEncoder(new JsonEncode(), new JsonDecode()),
                ];

                $normalizers = [
                    new DateTimeNormalizer(),
                    new PropertyNormalizer(null, new CamelCaseToSnakeCaseNameConverter(), new ReflectionExtractor(null, ['is', 'can', 'get'])),
                ];

                return new Serializer($normalizers, $encoders);
            },
            true
        )
            ->alias(Serializer::class, SerializerInterface::class);
    }
}
