<?php

namespace Yolo;

class Abc
{
    public function __construct(private $parent, private $decorators)
    {
        // ...
    }

    public function foo()
    {
        if (array_key_exists('foo', $this->decorators)) {
                return $this->decorators['foo']->call($this, $this->parent->foo(...), func_get_args(), 'foo');
        }

        foreach ($this->decorators as $pattern => $closure) {
            if (mb_substr($pattern, 0, 1) === mb_substr($pattern, -1, 1)) {
                if (preg_match($pattern, 'foo') === 1) {
                    return $this->decorators[$pattern]->call($this, $this->parent->foo(...), func_get_args(), 'foo');
                }
            }
        }

        return $this->parent->foo(...func_get_args());
    }

    public function bar()
    {
        if (array_key_exists('bar', $this->decorators)) {
                return $this->decorators['bar']->call($this, $this->parent->bar(...), func_get_args(), 'bar');
        }

        foreach ($this->decorators as $pattern => $closure) {
            if (mb_substr($pattern, 0, 1) === mb_substr($pattern, -1, 1)) {
                if (preg_match($pattern, 'bar') === 1) {
                    return $this->decorators[$pattern]->call($this, $this->parent->bar(...), func_get_args(), 'bar');
                }
            }
        }

        return $this->parent->bar(...func_get_args());
    }

    public function baz()
    {
        if (array_key_exists('baz', $this->decorators)) {
                return $this->decorators['baz']->call($this, $this->parent->baz(...), func_get_args(), 'baz');
        }

        foreach ($this->decorators as $pattern => $closure) {
            if (mb_substr($pattern, 0, 1) === mb_substr($pattern, -1, 1)) {
                if (preg_match($pattern, 'baz') === 1) {
                    return $this->decorators[$pattern]->call($this, $this->parent->baz(...), func_get_args(), 'baz');
                }
            }
        }

        return $this->parent->baz(...func_get_args());
    }
}