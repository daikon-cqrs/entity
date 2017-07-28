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
        Assertion::nullOrUrl($nativeValue, 'Trying to create Url VO from unsupported value type.');
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
            $this->formatPort(),
            $this->path,
            $this->formatQuery(),
            $this->formatFragment()
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
        $this->host = $this->parseHost($url);
        $this->scheme = $this->parseScheme($url);
        $this->query = $this->parseQuery($url);
        $this->port = $this->parsePort($url);
        $this->fragment = $this->parseFragment($url);
        $this->path = $this->parsePath($url);
    }

    private function parseHost(string $url): Text
    {
        return Text::fromNative(parse_url($url, PHP_URL_HOST) ?: self::NIL);
    }

    private function parseScheme(string $url): Text
    {
        return Text::fromNative(parse_url($url, PHP_URL_SCHEME) ?: self::NIL);
    }

    private function parseQuery(string $url): Text
    {
        return Text::fromNative(parse_url($url, PHP_URL_QUERY) ?: self::NIL);
    }

    private function parseFragment(string $url): Text
    {
        return Text::fromNative(parse_url($url, PHP_URL_FRAGMENT) ?: self::NIL);
    }

    private function parsePath(string $url): Text
    {
        return Text::fromNative(parse_url($url, PHP_URL_PATH) ?: self::DEFAULT_PATH);
    }

    private function parsePort(string $url): Integer
    {
        return Integer::fromNative(parse_url($url, PHP_URL_PORT) ?: null);
    }

    private function formatPort(): string
    {
        $port = $this->port->toNative();
        if (is_null($port)) {
            return '';
        }
        return ':'.$port;
    }

    private function formatQuery(): string
    {
        if ($this->query->isEmpty()) {
            return (string)$this->query;
        }
        return '?'.$this->query;
    }

    private function formatFragment(): string
    {
        if ($this->fragment->isEmpty()) {
            return (string)$this->fragment;
        }
        return '#'.$this->fragment;
    }
}
