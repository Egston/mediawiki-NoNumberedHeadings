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
        $numbered_headings = MediaWikiServices::getInstance()->getMagicWordFactory()->get('MAG_NUMBEREDHEADINGS')->matchAndRemove($text);
        $no_numbered_headings = MediaWikiServices::getInstance()->getMagicWordFactory()->get('MAG_NONUMBEREDHEADINGS')->matchAndRemove($text);

        if ($numbered_headings) {
            $parser->mOptions->setNumberHeadings(true);
            if ($no_numbered_headings) {
                /** @var Title $title */
                $title = $parser->getTitle();
                wfLogWarning(sprintf(
                    'Both MAG_NUMBEREDHEADINGS and MAG_NONUMBEREDHEADINGS found in single article: %s',
                    $title
                ));
            }
        } elseif ($no_numbered_headings) {
            $parser->mOptions->setNumberHeadings(false);

            $config = MediaWikiServices::getInstance()->getConfigFactory()->makeConfig( 'NumberedHeadings' );
            if ($config->get('NumberedHeadingsAlsoHideNumbersInToc')) {
                global $wgOut;
                $wgOut->addScript('<style type="text/css">.tocnumber {display:none;}</style>');
            }
        }
        return true;
    }
}
