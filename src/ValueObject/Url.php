<?php
/**
 * This file is part of the daikon-cqrs/entity project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare (strict_types = 1);

namespace Daikon\Entity\ValueObject;

use Daikon\Entity\Assert\Assertion;
use Daikon\Interop\ValueObjectInterface;

final class Url implements ValueObjectInterface
{
    /** @var string */
    private const NIL = null;

    /** @var Text */
    private $fragment;

    /** @var Text */
    private $host;

    /** @var Text */
    private $scheme;

    /** @var Text */
    private $query;

    /** @var IntValue */
    private $port;

    /** @var Text */
    private $path;

    /** @param string|null $value */
    public static function fromNative($value): Url
    {
        $value = empty($value) ? null : $value;
        Assertion::nullOrUrl($value, "Trying to create Url VO from unsupported value type: $value");
        return empty($value) ? new Url(self::NIL) : new Url($value);
    }

    public function toNative(): ?string
    {
        if ($this->host->isEmpty()) {
            return self::NIL;
        }
        return sprintf(
            '%s://%s',
            $this->scheme,
            implode('', [
                $this->host,
                $this->formatPort(),
                $this->path,
                $this->prefix('?', $this->query),
                $this->prefix('#', $this->fragment),
            ])
        );
    }

    /** @param self $value */
    public function equals($value): bool
    {
        return $value instanceof self && $value->toNative() === $this->toNative();
    }

    public function __toString(): string
    {
        return $this->toNative() ?? '';
    }

    public function getPath(): Text
    {
        return $this->path;
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

    public function getPort(): ?IntValue
    {
        return $this->port;
    }

    public function hasPort(): bool
    {
        return $this->port->toNative() !== null;
    }

    private function __construct(?string $url = null)
    {
        if (is_null($url)) {
            $emptyText = Text::fromNative(null);
            $this->host = $emptyText;
            $this->scheme = $emptyText;
            $this->query = $emptyText;
            $this->fragment = $emptyText;
            $this->path = $emptyText;
            $this->port = IntValue::fromNative(null);
        } else {
            $this->host = $this->parse($url, PHP_URL_HOST);
            $this->scheme = $this->parse($url, PHP_URL_SCHEME);
            $this->query = $this->parse($url, PHP_URL_QUERY);
            $this->fragment = $this->parse($url, PHP_URL_FRAGMENT);
            $this->path = $this->parse($url, PHP_URL_PATH);
            $this->port = $this->parsePort($url);
        }
    }

    private function parse(string $url, int $urlPart): Text
    {
        return Text::fromNative(parse_url($url, $urlPart) ?: self::NIL);
    }

    private function parsePort(string $url): IntValue
    {
        $port = parse_url($url, PHP_URL_PORT);
        return IntValue::fromNative($port ?? null);
    }

    private function prefix(string $prefix, Text $value): string
    {
        return $value->isEmpty() ? '' : $prefix . $value;
    }

    private function formatPort(): string
    {
        return $this->hasPort() ? ':' . $this->port : '';
    }
}
