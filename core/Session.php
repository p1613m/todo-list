<?php

namespace App\Core;

/**
 * Manage sessions
 */
class Session
{
    protected const FLASH_KEY = '_flash';

    public function __construct()
    {
        session_start();

        if (!isset($_SESSION[self::FLASH_KEY])) {
            $_SESSION[self::FLASH_KEY] = [];
        }

        foreach ($_SESSION[self::FLASH_KEY] as $key => &$flash) {
            $flash['read'] = true;
        }
    }

    /**
     * Set flash message
     *
     * @param string $key
     * @param string|array $value
     * @return void
     */
    public function setFlash(string $key, string|array $value): void
    {
        $_SESSION[self::FLASH_KEY][$key] = [
            'value' => $value,
            'read' => false,
        ];
    }

    /**
     * Get flash message
     *
     * @param string $key
     * @return mixed|null
     */
    public function getFlash(string $key): mixed
    {
        return $_SESSION[self::FLASH_KEY][$key]['value'] ?? null;
    }

    /**
     * Hash flash message
     *
     * @param $key
     * @return bool
     */
    public function hasFlash($key): bool
    {
        return isset($_SESSION[self::FLASH_KEY][$key]['value']);
    }

    /**
     * Remove all flash messages
     *
     * @return void
     */
    private function unsetFlashMessages(): void
    {
        foreach ($_SESSION[self::FLASH_KEY] as $key => $flash) {
            if ($flash['read']) {
                $this->unset(self::FLASH_KEY, $key);
            }
        }
    }

    /**
     * Get inner flash message by key
     *
     * @param string $key
     * @param string $value
     * @return string|null
     */
    public function getInnerFlash(string $key, string $value): string|null
    {
        if ($this->hasFlash($key)) {
            return $this->getFlash($key)[$value] ?? null;
        }

        return null;
    }

    /**
     * Set session parameter
     *
     * @param $key
     * @param $value
     * @return void
     */
    public function set($key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Get session parameter
     *
     * @param $key
     * @return mixed|null
     */
    public function get($key): mixed
    {
        return $_SESSION[$key] ?? null;
    }

    /**
     * Remove session parameter
     *
     * @param $key
     * @param $value
     * @return void
     */
    public function unset($key, $value = null): void
    {
        if ($value && isset($_SESSION[$key])) {
            unset($_SESSION[$key][$value]);
        } else {
            unset($_SESSION[$key]);
        }
    }

    /**
     * Remove flash message after script exit
     */
    public function __destruct()
    {
        $this->unsetFlashMessages();
    }
}