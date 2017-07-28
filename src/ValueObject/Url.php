<?php

namespace Daikon\Entity\ValueObject;

use Daikon\Entity\Assert\Assertion;

final class Url implements ValueObjectInterface
{
    /**
     * @var string
     */
    private const NIL = '';

    /**
     * @var string
     */
    private const DEFAULT_PATH = '/';

    /**
     * @var Text
     */
    private $fragment;

    /**
     * @var Text
     */
    private $host;

    /*
     * @var Text
     */
    private $scheme;

    /**
     * @var Text
     */
    private $query;

    /**
     * @var Integer
     */
    private $port;

    /**
     * @var Text
     */
    private $path;

    /**
     * @param string|null $nativeValue
     * @return Url
     */
    public static function fromNative($nativeValue): Url
    {
        Assertion::nullOrUrl($nativeValue);
        return empty($nativeValue) ? new Url : new Url($nativeValue);
    }

    public function toNative(): string
    {
        if ($this->host->isEmpty()) {
            return self::NIL;
        }
        return sprintf(
            '%s://%s%s%s%s%s',
            $this->scheme,
            $this->host,
            $this->port->toNative() ? ':'.$this->port : '',
            $this->path,
            $this->query->isEmpty() ? '' : '?'.$this->query,
            $this->fragment->isEmpty() ? '' : '#'.$this->fragment
        );
    }

    public function equals(ValueObjectInterface $otherValue): bool
    {
        return $otherValue instanceof Url && $otherValue->toNative() === $this->toNative();
    }

    public function __toString(): string
    {
        return $this->toNative();
    }

    public function getPath(): Text
    {
        return $this->path;
    }

    public function getPort(): Integer
    {
        return $this->port;
    }

    public function getFragment(): Text
    {
        return $this->fragment;
    }

    public function getHost(): Text
    {
        return $this->host;
    }

    public function getQuery(): Text
    {
        return $this->query;
    }

    public function getScheme(): Text
    {
        return $this->scheme;
    }

    private function __construct(string $url = self::NIL)
    {
        $this->host = Text::fromNative(parse_url($url, PHP_URL_HOST) ?: self::NIL);
        $this->scheme = Text::fromNative(parse_url($url, PHP_URL_SCHEME) ?: self::NIL);
        $this->query = Text::fromNative(parse_url($url, PHP_URL_QUERY) ?: self::NIL);
        $this->port = Integer::fromNative(parse_url($url, PHP_URL_PORT) ?: null);
        $this->fragment = Text::fromNative(parse_url($url, PHP_URL_FRAGMENT) ?: self::NIL);
        $this->path = Text::fromNative(parse_url($url, PHP_URL_PATH) ?? self::DEFAULT_PATH);
    }
}
