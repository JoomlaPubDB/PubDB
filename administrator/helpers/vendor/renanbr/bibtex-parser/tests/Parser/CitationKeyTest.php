<?php

/*
 * This file is part of the BibTex Parser.
 *
 * (c) Renan de Lima Barbosa <renandelima@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RenanBr\BibTexParser\Test\Parser;

use PHPUnit\Framework\TestCase;
use RenanBr\BibTexParser\Parser;
use RenanBr\BibTexParser\Test\DummyListener;

/**
 * @covers \RenanBr\BibTexParser\Parser
 */
class CitationKeyTest extends TestCase
{
    /**
     * @group regression
     * @group bug44
     *
     * @see https://github.com/renanbr/bibtex-parser/issues/44
     */
    public function testDBLPCitationKey()
    {
        $listener = new DummyListener();

        $parser = new Parser();
        $parser->addListener($listener);
        $parser->parseString('@Article{DBLP:journals/npl/CaamanoSBD16}');

        // 0 -> type
        // 1 -> citation key
        // 2 -> original entry
        $this->assertCount(3, $listener->calls);
        list($text, $type) = $listener->calls[1];

        $this->assertSame('DBLP:journals/npl/CaamanoSBD16', $text);
        $this->assertSame(Parser::CITATION_KEY, $type);
    }

    /**
     * @group regression
     * @group bug44
     *
     * @see https://github.com/renanbr/bibtex-parser/issues/44
     */
    public function testACMCitationKey()
    {
        $listener = new DummyListener();

        $parser = new Parser();
        $parser->addListener($listener);
        $parser->parseString('@inproceedings{Kyriakakis:2016:EMI:3003733.3003777}');

        // 0 -> type
        // 1 -> citation key
        // 2 -> original entry
        $this->assertCount(3, $listener->calls);
        list($text, $type) = $listener->calls[1];

        $this->assertSame('Kyriakakis:2016:EMI:3003733.3003777', $text);
        $this->assertSame(Parser::CITATION_KEY, $type);
    }
}
