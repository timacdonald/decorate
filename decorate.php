<?php



/**
 * @template T
 *
 * @param  T  $myClass
 * @return T
 */
function decorate($myClass, $decorator, $mixin = false) {
    $reflectedClass = new ReflectionClass($myClass);

    $methods = array_reduce($reflectedClass->getMethods(ReflectionMethod::IS_PUBLIC), fn (string $class, ReflectionMethod $method): string => <<<PHP
        {$class}
            public function {$method->getName()}()
            {
                if (array_key_exists('{$method->getName()}', \$this->decorators)) {
                        return \$this->decorators['{$method->getName()}']->call(\$this, \$this->parent->{$method->getName()}(...), func_get_args(), '{$method->getName()}');
                }

                foreach (\$this->decorators as \$pattern => \$closure) {
                    if (mb_substr(\$pattern, 0, 1) === mb_substr(\$pattern, -1, 1)) {
                        if (preg_match(\$pattern, '{$method->getName()}') === 1) {
                            return \$this->decorators[\$pattern]->call(\$this, \$this->parent->{$method->getName()}(...), func_get_args(), '{$method->getName()}');
                        }
                    }
                }

                return \$this->parent->{$method->getName()}(...func_get_args());
            }

        PHP, '');

    $extends = $mixin
        ? ''
        : ' extends \\'.$myClass::class;

    $class = <<<PHP
        <?php

        namespace Yolo;

        class Abc{$extends}
        {
            public function __construct(private \$parent, private \$decorators)
            {
                // ...
            }
        {$methods}}
        PHP;

    file_put_contents('decorator.php', $class);

    include_once './decorator.php';

    return new \Yolo\Abc($myClass, $decorator);
}

function mixin($myClass, $mixin) {
    return decorate($myClass, $mixin, true);
}




















// This is now final...
final class MyClass
{
    public function foo()
    {
        return 'foo';
    }

    public function bar()
    {
        return 'bar';
    }

    public function baz()
    {
        return 'baz';
    }
}

$base = new MyClass;

// Use a "mixin" instead...
$magic = mixin($base, [
    'foo' => function ($method, $args, $name) {
        return $method(...$args).'-✋';
    },
    '/^b/' => function ($method, $args, $name) {
        return $method(...$args).'-😇';
    },
]);

var_dump(
    $magic->foo(),
    $magic->bar(),
    $magic->baz(),
    $magic instanceof MyClass
);
