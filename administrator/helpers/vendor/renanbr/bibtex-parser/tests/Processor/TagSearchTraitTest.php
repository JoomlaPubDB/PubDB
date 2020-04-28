<?php

/*
 * This file is part of the BibTex Parser.
 *
 * (c) Renan de Lima Barbosa <renandelima@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RenanBr\BibTexParser\Test\Processor;

use PHPUnit\Framework\TestCase;
use ReflectionClass;
use RenanBr\BibTexParser\Processor\TagSearchTrait;

/**
 * @covers \RenanBr\BibTexParser\Processor\TagSearchTrait
 */
class TagSearchTraitTest extends TestCase
{
    public function testFound()
    {
        $trait = $this->getMockForTrait(TagSearchTrait::class);
        $found = $this->invokeTagSearch($trait, 'foo', ['foo', 'bar']);

        $this->assertSame('foo', $found);
    }

    public function testNotFound()
    {
        $trait = $this->getMockForTrait(TagSearchTrait::class);
        $found = $this->invokeTagSearch($trait, 'missing', ['foo', 'bar']);

        $this->assertNull($found);
    }

    public function testCaseInsensitiveMatch()
    {
        $trait = $this->getMockForTrait(TagSearchTrait::class);
        $found = $this->invokeTagSearch($trait, 'BAR', ['foo', 'bar']);

        $this->assertSame('bar', $found);
    }

    private function invokeTagSearch($trait, $needle, $haystack)
    {
        $reflection = new ReflectionClass($trait);
        $tagSearch = $reflection->getMethod('tagSearch');
        $tagSearch->setAccessible(true);

        return $tagSearch->invoke($trait, $needle, $haystack);
    }
}
