<?php

final class INSTALL_Storage
{
    private array $storage = [];

    public function __construct()
    {
        $this->storage = OW::getSession()->get('OW-INSTALL-DATA') ?? [];
    }

    public function __destruct()
    {
        if (empty($this->storage)) {
            OW::getSession()->delete('OW-INSTALL-DATA');
        } else {
            OW::getSession()->set('OW-INSTALL-DATA', $this->storage);
        }
    }

    public function set($name, $value)
    {
        $this->storage[$name] = $value;
    }

    public function get($name)
    {
        return $this->storage[$name];
    }

    public function getAll(): array
    {
        return empty($this->storage) ? [] : $this->storage;
    }

    public function clear()
    {
        $this->storage = [];
    }
}
