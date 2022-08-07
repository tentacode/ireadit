<?php

declare(strict_types=1);

namespace App\Brick\Poulpe;

use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class SqlResult
{
    private Serializer $serializer;

    /**
     * @param array<mixed> $rawResult
     */
    public function __construct(private array $rawResult)
    {
        $this->serializer = new Serializer(
            [new ObjectNormalizer()],
            [], // no encoders, only normalizer 👆
        );
    }

    /**
     * @return array<mixed>
     */
    public function getRawResult(): array
    {
        return $this->rawResult;
    }

    /**
     * @template T of object
     * @param class-string<T> $objectClass
     * @return array<T>
     */
    public function asObjects(string $objectClass): array
    {
        return array_map(function ($raw) use ($objectClass) {
            return $this->serializer->denormalize($raw, $objectClass,);
        }, $this->rawResult);
    }
}
