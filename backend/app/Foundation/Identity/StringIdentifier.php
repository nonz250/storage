<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Foundation\Identity;

trait StringIdentifier
{
    /**
     * @var string
     */
    private string $identifier;

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->identifier;
    }

    /**
     * @param mixed $object
     *
     * @return bool
     */
    public function equals($object): bool
    {
        if (!$object instanceof self) {
            return false;
        }
        return $this->identifier === $object->identifier;
    }

    /**
     * @see https://github.com/ulid/spec
     * @see https://github.com/symfony/uid/blob/5.3/Ulid.php#L49
     *
     * @param string $value
     *
     * @return bool
     */
    public function isValidForUlid(string $value): bool
    {
        // 文字列長が26であること
        if (26 !== mb_strlen($value)) {
            return false;
        }
        // 文字列には32進表記であること（念の為小文字も検索）
        if (26 !== strspn($value, '0123456789ABCDEFGHJKMNPQRSTVWXYZabcdefghjkmnpqrstvwxyz')) {
            return false;
        }
        // ULIDの仕様上、先頭文字は必ず7以下になる。
        return $value[0] <= '7';
    }
}
