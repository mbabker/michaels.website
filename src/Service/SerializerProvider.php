<?php

namespace BabDev\Website\Service;

use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\Serializer\Encoder\YamlEncoder;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Yaml\Dumper;
use Symfony\Component\Yaml\Parser;

/**
 * Serializer service provider.
 */
class SerializerProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(Container $container)
    {
        $container->alias(Serializer::class, 'serializer')
            ->alias(SerializerInterface::class, 'serializer')
            ->share('serializer', [$this, 'getSerializerService'], true);

        $container->alias(YamlEncoder::class, 'serializer.encoder.yaml')
            ->share('serializer.encoder.yaml', [$this, 'getSerializerEncoderYamlService'], true);

        $container->alias(DateTimeNormalizer::class, 'serializer.normalizer.datetime')
            ->share('serializer.normalizer.datetime', [$this, 'getSerializerNormalizerDateTimeService'], true);

        $container->alias(ObjectNormalizer::class, 'serializer.normalizer.object')
            ->share('serializer.normalizer.object', [$this, 'getSerializerNormalizerObjectService'], true);
    }

    /**
     * Get the `serializer` service.
     *
     * @param Container $container The DI container.
     *
     * @return Serializer
     */
    public function getSerializerService(Container $container): Serializer
    {
        $encoders = [
            $container->get('serializer.encoder.yaml'),
        ];

        $normalizers = [
            $container->get('serializer.normalizer.datetime'),
            $container->get('serializer.normalizer.object'),
        ];

        return new Serializer($normalizers, $encoders);
    }

    /**
     * Get the `serializer.encoder.yaml` service.
     *
     * @param Container $container The DI container.
     *
     * @return YamlEncoder
     */
    public function getSerializerEncoderYamlService(Container $container): YamlEncoder
    {
        return new YamlEncoder(new Dumper(), new Parser());
    }

    /**
     * Get the `serializer.normalizer.datetime` service.
     *
     * @param Container $container The DI container.
     *
     * @return DateTimeNormalizer
     */
    public function getSerializerNormalizerDateTimeService(Container $container): DateTimeNormalizer
    {
        return new DateTimeNormalizer();
    }

    /**
     * Get the `serializer.normalizer.object` service.
     *
     * @param Container $container The DI container.
     *
     * @return ObjectNormalizer
     */
    public function getSerializerNormalizerObjectService(Container $container): ObjectNormalizer
    {
        return new ObjectNormalizer(null, null, null, new PhpDocExtractor());
    }
}
