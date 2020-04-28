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
use RenanBr\BibTexParser\Processor\TagCoverageTrait;

/**
 * @covers \RenanBr\BibTexParser\Processor\TagCoverageTrait
 */
class TagCoverageTraitTest extends TestCase
{
    public function testZeroConfigurationMustCoverAllTags()
    {
        $trait = $this->getMockForTrait(TagCoverageTrait::class);
        $coverage = $this->invokeGetCoveredTags($trait, ['bbb', 'ccc']);

        $this->assertSame(['bbb', 'ccc'], $coverage);
    }

    public function testWhitelistStrategy()
    {
        $trait = $this->getMockForTrait(TagCoverageTrait::class);
        $trait->setTagCoverage(['aaa', 'bbb'], 'whitelist');
        $coverage = $this->invokeGetCoveredTags($trait, ['bbb', 'ccc']);

        $this->assertSame(['bbb'], $coverage);
    }

    public function testDefaultStrategyMustActAsWhitelist()
    {
        $trait = $this->getMockForTrait(TagCoverageTrait::class);
        $trait->setTagCoverage(['aaa', 'bbb']);
        $coverage = $this->invokeGetCoveredTags($trait, ['bbb', 'ccc']);

        $this->assertSame(['bbb'], $coverage);
    }

    public function testBlacklist()
    {
        $trait = $this->getMockForTrait(TagCoverageTrait::class);
        $trait->setTagCoverage(['aaa', 'bbb'], 'blacklist');
        $coverage = $this->invokeGetCoveredTags($trait, ['bbb', 'ccc']);

        $this->assertSame(['ccc'], $coverage);
    }

    public function testCaseInsensitiveMatch()
    {
        $trait = $this->getMockForTrait(TagCoverageTrait::class);
        $trait->setTagCoverage(['aaa', 'bbb']);
        $coverage = $this->invokeGetCoveredTags($trait, ['BBB', 'ccc']);

        $this->assertSame(['BBB'], $coverage);
    }

    private function invokeGetCoveredTags($trait, $tags)
    {
        $reflection = new ReflectionClass($trait);
        $getCoveredTags = $reflection->getMethod('getCoveredTags');
        $getCoveredTags->setAccessible(true);

        return $getCoveredTags->invoke($trait, $tags);
    }
}
