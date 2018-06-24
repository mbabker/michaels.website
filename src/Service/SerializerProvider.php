<?php

namespace BabDev\Website\Service;

use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\YamlEncoder;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Yaml\Dumper;
use Symfony\Component\Yaml\Parser;

final class SerializerProvider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $container->share(
            SerializerInterface::class,
            function (): Serializer {
                $encoders = [
                    new JsonEncoder(new JsonEncode(), new JsonDecode()),
                    new YamlEncoder(new Dumper(), new Parser()),
                ];

                $normalizers = [
                    new DateTimeNormalizer(),
                    new ObjectNormalizer(null, null, null, new PhpDocExtractor()),
                ];

                return new Serializer($normalizers, $encoders);
            },
            true
        )
            ->alias(Serializer::class, SerializerInterface::class);
    }
}
