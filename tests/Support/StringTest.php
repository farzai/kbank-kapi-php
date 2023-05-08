<?php

use Farzai\KApi\Support\Str;

it('should be snake', function () {
    expect(Str::snake('FooBar'))->toBe('foo_bar');

    expect(Str::snake('foo:bar:baz'))->toBe('foo_bar_baz');
});
