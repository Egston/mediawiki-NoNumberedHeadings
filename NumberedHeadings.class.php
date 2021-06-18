<?php

use MediaWiki\MediaWikiServices;

/**
 * This file contains the classes used by NumberedHeadings Mediawiki
 * extension.
 * 
 * PHP version 5
 */ 

if (!defined('MEDIAWIKI')) {
        die("This requires the MediaWiki enviroment.");
}

/**
 * NumberedHeadings class contains functions used by NumberedHeadings
 * MediaWiki extension.
 */ 
class NumberedHeadings
{
    /**
     * Function call to toggle autonumbering on hook
     * ParserBeforeInternalParse.
     *
     * @param Parser     $parser
     * @param string     $text       Partially parsed text
     * @param StripState $stripState Parser's internal StripState object
     */
    public static function onInternalParseBeforeLinks(
        Parser $parser,
        &$text,
        StripState $strip_state
    ) {
        if (MediaWikiServices::getInstance()->getMagicWordFactory()->get('MAG_NUMBEREDHEADINGS')->matchAndRemove($text)) {
            $parser->mOptions->setNumberHeadings(true);
        } elseif (MediaWikiServices::getInstance()->getMagicWordFactory()->get('MAG_NONUMBEREDHEADINGS')->matchAndRemove($text)) {
            $parser->mOptions->setNumberHeadings(false);
        }
        return true;
    }
}
