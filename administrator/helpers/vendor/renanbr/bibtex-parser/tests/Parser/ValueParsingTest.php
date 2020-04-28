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
class ValueParsingTest extends TestCase
{
    /**
     * Tests if parser is able to handle raw, null, braced and quoted values ate the same time.
     */
    public function testMultipleNature()
    {
        $listener = new DummyListener();

        $parser = new Parser();
        $parser->addListener($listener);
        $parser->parseFile(__DIR__.'/../resources/valid/tag-contents-basic.bib');

        $this->assertCount(14, $listener->calls);

        list($text, $type, $context) = $listener->calls[0];
        $this->assertSame(Parser::TYPE, $type);
        $this->assertSame('valuesBasic', $text);

        list($text, $type, $context) = $listener->calls[1];
        $this->assertSame(Parser::CITATION_KEY, $type);
        $this->assertSame('kNull', $text);

        list($text, $type, $context) = $listener->calls[2];
        $this->assertSame(Parser::TAG_NAME, $type);
        $this->assertSame('kStillNull', $text);

        list($text, $type, $context) = $listener->calls[3];
        $this->assertSame(Parser::TAG_NAME, $type);
        $this->assertSame('kRaw', $text);

        list($text, $type, $context) = $listener->calls[4];
        $this->assertSame(Parser::RAW_TAG_CONTENT, $type);
        $this->assertSame('raw', $text);

        list($text, $type, $context) = $listener->calls[5];
        $this->assertSame(Parser::TAG_NAME, $type);
        $this->assertSame('kBraced', $text);

        list($text, $type, $context) = $listener->calls[6];
        $this->assertSame(Parser::BRACED_TAG_CONTENT, $type);
        $this->assertSame(' braced value ', $text);

        list($text, $type, $context) = $listener->calls[7];
        $this->assertSame(Parser::TAG_NAME, $type);
        $this->assertSame('kBracedEmpty', $text);

        list($text, $type, $context) = $listener->calls[8];
        $this->assertSame(Parser::BRACED_TAG_CONTENT, $type);
        $this->assertSame('', $text);

        list($text, $type, $context) = $listener->calls[9];
        $this->assertSame(Parser::TAG_NAME, $type);
        $this->assertSame('kQuoted', $text);

        list($text, $type, $context) = $listener->calls[10];
        $this->assertSame(Parser::QUOTED_TAG_CONTENT, $type);
        $this->assertSame(' quoted value ', $text);

        list($text, $type, $context) = $listener->calls[11];
        $this->assertSame(Parser::TAG_NAME, $type);
        $this->assertSame('kQuotedEmpty', $text);

        list($text, $type, $context) = $listener->calls[12];
        $this->assertSame(Parser::QUOTED_TAG_CONTENT, $type);
        $this->assertSame('', $text);

        list($text, $type, $context) = $listener->calls[13];
        $this->assertSame(Parser::ENTRY, $type);
        $original = trim(file_get_contents(__DIR__.'/../resources/valid/tag-contents-basic.bib'));
        $this->assertSame($original, $text);
    }

    public function testTagContentScaping()
    {
        $listener = new DummyListener();

        $parser = new Parser();
        $parser->addListener($listener);
        $parser->parseFile(__DIR__.'/../resources/valid/tag-contents-escaped.bib');

        $this->assertCount(6, $listener->calls);

        // we test also the "offset" and "length" because this file contains
        // values with escaped chars, which means that the value length in the
        // file is not equal to the triggered one

        list($text, $type, $context) = $listener->calls[0];
        $this->assertSame(Parser::TYPE, $type);
        $this->assertSame('valuesEscaped', $text);
        $this->assertSame(1, $context['offset']);
        $this->assertSame(13, $context['length']);

        list($text, $type, $context) = $listener->calls[1];
        $this->assertSame(Parser::TAG_NAME, $type);
        $this->assertSame('braced', $text);
        $this->assertSame(21, $context['offset']);
        $this->assertSame(6, $context['length']);

        list($text, $type, $context) = $listener->calls[2];
        $this->assertSame(Parser::BRACED_TAG_CONTENT, $type);
        // here we have two scaped characters ("}" and "%"), then the length
        // returned in the context (21) is bigger than the $text value (18)
        $this->assertSame('the } " \\ % braced', $text);
        $this->assertSame(31, $context['offset']);
        $this->assertSame(21, $context['length']);

        list($text, $type, $context) = $listener->calls[3];
        $this->assertSame(Parser::TAG_NAME, $type);
        $this->assertSame('quoted', $text);
        $this->assertSame(59, $context['offset']);
        $this->assertSame(6, $context['length']);

        list($text, $type, $context) = $listener->calls[4];
        $this->assertSame(Parser::QUOTED_TAG_CONTENT, $type);
        // here we have two scaped characters ("}" and "%"), then the length
        // returned in the context (21) is bigger than the $text value (18)
        $this->assertSame('the } " \\ % quoted', $text);
        $this->assertSame(69, $context['offset']);
        $this->assertSame(21, $context['length']);

        list($text, $type, $context) = $listener->calls[5];
        $this->assertSame(Parser::ENTRY, $type);
        $original = trim(file_get_contents(__DIR__.'/../resources/valid/tag-contents-escaped.bib'));
        $this->assertSame($original, $text);
        $this->assertSame(0, $context['offset']);
        $this->assertSame(93, $context['length']);
    }

    public function testMultipleTagContents()
    {
        $listener = new DummyListener();

        $parser = new Parser();
        $parser->addListener($listener);
        $parser->parseFile(__DIR__.'/../resources/valid/tag-contents-multiple.bib');

        $this->assertCount(19, $listener->calls);

        list($text, $type, $context) = $listener->calls[0];
        $this->assertSame(Parser::TYPE, $type);
        $this->assertSame('multipleTagContents', $text);

        list($text, $type, $context) = $listener->calls[1];
        $this->assertSame(Parser::TAG_NAME, $type);
        $this->assertSame('raw', $text);

        list($text, $type, $context) = $listener->calls[2];
        $this->assertSame(Parser::RAW_TAG_CONTENT, $type);
        $this->assertSame('rawA', $text);

        list($text, $type, $context) = $listener->calls[3];
        $this->assertSame(Parser::RAW_TAG_CONTENT, $type);
        $this->assertSame('rawB', $text);

        list($text, $type, $context) = $listener->calls[4];
        $this->assertSame(Parser::TAG_NAME, $type);
        $this->assertSame('quoted', $text);

        list($text, $type, $context) = $listener->calls[5];
        $this->assertSame(Parser::QUOTED_TAG_CONTENT, $type);
        $this->assertSame('quoted a', $text);

        list($text, $type, $context) = $listener->calls[6];
        $this->assertSame(Parser::QUOTED_TAG_CONTENT, $type);
        $this->assertSame('quoted b', $text);

        list($text, $type, $context) = $listener->calls[7];
        $this->assertSame(Parser::TAG_NAME, $type);
        $this->assertSame('braced', $text);

        list($text, $type, $context) = $listener->calls[8];
        $this->assertSame(Parser::BRACED_TAG_CONTENT, $type);
        $this->assertSame('braced a', $text);

        list($text, $type, $context) = $listener->calls[9];
        $this->assertSame(Parser::BRACED_TAG_CONTENT, $type);
        $this->assertSame('braced b', $text);

        list($text, $type, $context) = $listener->calls[10];
        $this->assertSame(Parser::TAG_NAME, $type);
        $this->assertSame('misc', $text);

        list($text, $type, $context) = $listener->calls[11];
        $this->assertSame(Parser::QUOTED_TAG_CONTENT, $type);
        $this->assertSame('quoted', $text);

        list($text, $type, $context) = $listener->calls[12];
        $this->assertSame(Parser::BRACED_TAG_CONTENT, $type);
        $this->assertSame('braced', $text);

        list($text, $type, $context) = $listener->calls[13];
        $this->assertSame(Parser::RAW_TAG_CONTENT, $type);
        $this->assertSame('raw', $text);

        list($text, $type, $context) = $listener->calls[14];
        $this->assertSame(Parser::TAG_NAME, $type);
        $this->assertSame('noSpace', $text);

        list($text, $type, $context) = $listener->calls[15];
        $this->assertSame(Parser::RAW_TAG_CONTENT, $type);
        $this->assertSame('raw', $text);

        list($text, $type, $context) = $listener->calls[16];
        $this->assertSame(Parser::QUOTED_TAG_CONTENT, $type);
        $this->assertSame('quoted', $text);

        list($text, $type, $context) = $listener->calls[17];
        $this->assertSame(Parser::BRACED_TAG_CONTENT, $type);
        $this->assertSame('braced', $text);

        list($text, $type, $context) = $listener->calls[18];
        $this->assertSame(Parser::ENTRY, $type);
        $original = trim(file_get_contents(__DIR__.'/../resources/valid/tag-contents-multiple.bib'));
        $this->assertSame($original, $text);
    }

    public function testTagContentSlashes()
    {
        $listener = new DummyListener();

        $parser = new Parser();
        $parser->addListener($listener);
        $parser->parseFile(__DIR__.'/../resources/valid/tag-contents-slashes.bib');

        $this->assertCount(6, $listener->calls);

        list($text, $type, $context) = $listener->calls[0];
        $this->assertSame(Parser::TYPE, $type);
        $this->assertSame('valuesSlashes', $text);

        list($text, $type, $context) = $listener->calls[1];
        $this->assertSame(Parser::TAG_NAME, $type);
        $this->assertSame('braced', $text);

        list($text, $type, $context) = $listener->calls[2];
        $this->assertSame(Parser::BRACED_TAG_CONTENT, $type);
        $this->assertSame('\\}\\"\\%\\', $text);

        list($text, $type, $context) = $listener->calls[3];
        $this->assertSame(Parser::TAG_NAME, $type);
        $this->assertSame('quoted', $text);

        list($text, $type, $context) = $listener->calls[4];
        $this->assertSame(Parser::QUOTED_TAG_CONTENT, $type);
        $this->assertSame('\\}\\"\\%\\', $text);

        list($text, $type, $context) = $listener->calls[5];
        $this->assertSame(Parser::ENTRY, $type);
        $original = trim(file_get_contents(__DIR__.'/../resources/valid/tag-contents-slashes.bib'));
        $this->assertSame($original, $text);
    }

    public function testTagContentNestedBraces()
    {
        $listener = new DummyListener();

        $parser = new Parser();
        $parser->addListener($listener);
        $parser->parseFile(__DIR__.'/../resources/valid/tag-contents-nested-braces.bib');

        $this->assertCount(8, $listener->calls);

        list($text, $type, $context) = $listener->calls[0];
        $this->assertSame(Parser::TYPE, $type);
        $this->assertSame('valuesBraces', $text);

        list($text, $type, $context) = $listener->calls[1];
        $this->assertSame(Parser::TAG_NAME, $type);
        $this->assertSame('link', $text);

        list($text, $type, $context) = $listener->calls[2];
        $this->assertSame(Parser::BRACED_TAG_CONTENT, $type);
        $this->assertSame('\url{https://github.com}', $text);

        list($text, $type, $context) = $listener->calls[3];
        $this->assertSame(Parser::TAG_NAME, $type);
        $this->assertSame('twoLevels', $text);

        list($text, $type, $context) = $listener->calls[4];
        $this->assertSame(Parser::BRACED_TAG_CONTENT, $type);
        $this->assertSame('a{b{c}d}e', $text);

        list($text, $type, $context) = $listener->calls[5];
        $this->assertSame(Parser::TAG_NAME, $type);
        $this->assertSame('escapedBrace', $text);

        list($text, $type, $context) = $listener->calls[6];
        $this->assertSame(Parser::BRACED_TAG_CONTENT, $type);
        $this->assertSame('before{}}after', $text);

        list($text, $type, $context) = $listener->calls[7];
        $this->assertSame(Parser::ENTRY, $type);
        $original = trim(file_get_contents(__DIR__.'/../resources/valid/tag-contents-nested-braces.bib'));
        $this->assertSame($original, $text);
    }
}
