<?php

/*
 * Generated from src/Antlr/DocBlockLexer.g4 by ANTLR 4.9
 */

namespace SetBased\Stratum\Common\Antlr {
    use Antlr\Antlr4\Runtime\Atn\ATNDeserializer;
    use Antlr\Antlr4\Runtime\Atn\LexerATNSimulator;
    use Antlr\Antlr4\Runtime\Lexer;
    use Antlr\Antlr4\Runtime\CharStream;
    use Antlr\Antlr4\Runtime\PredictionContexts\PredictionContextCache;
    use Antlr\Antlr4\Runtime\RuleContext;
    use Antlr\Antlr4\Runtime\Atn\ATN;
    use Antlr\Antlr4\Runtime\Dfa\DFA;
    use Antlr\Antlr4\Runtime\Vocabulary;
    use Antlr\Antlr4\Runtime\RuntimeMetaData;
    use Antlr\Antlr4\Runtime\VocabularyImpl;

    final class DocBlockLexer extends Lexer
    {
        public const EOL = 1,
            LEADING_WHITESPACE = 2,
            TAG_PART = 3,
            TEXT_PART = 4,
            AT1 = 5;

        public const MODE_LINE = 1,
            MODE_TAG = 2,
            MODE_TEXT = 3;

        /**
         * @var array<string>
         */
        public const CHANNEL_NAMES = ["DEFAULT_TOKEN_CHANNEL", "HIDDEN"];

        /**
         * @var array<string>
         */
        public const MODE_NAMES = [
            "DEFAULT_MODE",
            "MODE_LINE",
            "MODE_TAG",
            "MODE_TEXT",
        ];

        /**
         * @var array<string>
         */
        public const RULE_NAMES = [
            "EOL",
            "LEADING_WHITESPACE",
            "AT1",
            "OTHER",
            "EOL1",
            "AT2",
            "TEXT",
            "EOL2",
            "TAG_PART",
            "EOL3",
            "TEXT_PART",
            "EOL4",
        ];

        /**
         * @var array<string|null>
         */
        private const LITERAL_NAMES = [];

        /**
         * @var array<string>
         */
        private const SYMBOLIC_NAMES = [
            null,
            "EOL",
            "LEADING_WHITESPACE",
            "TAG_PART",
            "TEXT_PART",
            "AT1",
        ];

        /**
         * @var string
         */
        private const SERIALIZED_ATN =
            "\u{3}\u{608B}\u{A72A}\u{8133}\u{B9ED}\u{417C}\u{3BE7}\u{7786}\u{5964}" .
            "\u{2}\u{7}\u{6F}\u{8}\u{1}\u{8}\u{1}\u{8}\u{1}\u{8}\u{1}\u{4}\u{2}" .
            "\u{9}\u{2}\u{4}\u{3}\u{9}\u{3}\u{4}\u{4}\u{9}\u{4}\u{4}\u{5}\u{9}" .
            "\u{5}\u{4}\u{6}\u{9}\u{6}\u{4}\u{7}\u{9}\u{7}\u{4}\u{8}\u{9}\u{8}" .
            "\u{4}\u{9}\u{9}\u{9}\u{4}\u{A}\u{9}\u{A}\u{4}\u{B}\u{9}\u{B}\u{4}" .
            "\u{C}\u{9}\u{C}\u{4}\u{D}\u{9}\u{D}\u{3}\u{2}\u{5}\u{2}\u{20}\u{A}" .
            "\u{2}\u{3}\u{2}\u{3}\u{2}\u{5}\u{2}\u{24}\u{A}\u{2}\u{3}\u{2}\u{5}" .
            "\u{2}\u{27}\u{A}\u{2}\u{3}\u{3}\u{7}\u{3}\u{2A}\u{A}\u{3}\u{C}\u{3}" .
            "\u{E}\u{3}\u{2D}\u{B}\u{3}\u{3}\u{3}\u{3}\u{3}\u{7}\u{3}\u{31}\u{A}" .
            "\u{3}\u{C}\u{3}\u{E}\u{3}\u{34}\u{B}\u{3}\u{3}\u{3}\u{6}\u{3}\u{37}" .
            "\u{A}\u{3}\u{D}\u{3}\u{E}\u{3}\u{38}\u{5}\u{3}\u{3B}\u{A}\u{3}\u{3}" .
            "\u{3}\u{3}\u{3}\u{3}\u{4}\u{3}\u{4}\u{3}\u{4}\u{3}\u{4}\u{3}\u{4}" .
            "\u{3}\u{5}\u{3}\u{5}\u{3}\u{5}\u{3}\u{5}\u{3}\u{5}\u{3}\u{6}\u{3}" .
            "\u{6}\u{3}\u{6}\u{3}\u{6}\u{3}\u{7}\u{3}\u{7}\u{3}\u{7}\u{3}\u{7}" .
            "\u{3}\u{7}\u{3}\u{8}\u{3}\u{8}\u{3}\u{8}\u{3}\u{8}\u{3}\u{8}\u{3}" .
            "\u{9}\u{3}\u{9}\u{3}\u{9}\u{3}\u{9}\u{3}\u{9}\u{3}\u{A}\u{6}\u{A}" .
            "\u{5D}\u{A}\u{A}\u{D}\u{A}\u{E}\u{A}\u{5E}\u{3}\u{B}\u{3}\u{B}\u{3}" .
            "\u{B}\u{3}\u{B}\u{3}\u{B}\u{3}\u{C}\u{6}\u{C}\u{67}\u{A}\u{C}\u{D}" .
            "\u{C}\u{E}\u{C}\u{68}\u{3}\u{D}\u{3}\u{D}\u{3}\u{D}\u{3}\u{D}\u{3}" .
            "\u{D}\u{2}\u{2}\u{E}\u{6}\u{3}\u{8}\u{4}\u{A}\u{7}\u{C}\u{2}\u{E}" .
            "\u{2}\u{10}\u{2}\u{12}\u{2}\u{14}\u{2}\u{16}\u{5}\u{18}\u{2}\u{1A}" .
            "\u{6}\u{1C}\u{2}\u{6}\u{2}\u{3}\u{4}\u{5}\u{6}\u{4}\u{2}\u{B}\u{B}" .
            "\u{22}\u{22}\u{7}\u{2}\u{B}\u{C}\u{F}\u{F}\u{22}\u{22}\u{2C}\u{2C}" .
            "\u{42}\u{42}\u{5}\u{2}\u{C}\u{C}\u{F}\u{F}\u{42}\u{42}\u{4}\u{2}\u{C}" .
            "\u{C}\u{F}\u{F}\u{2}\u{74}\u{2}\u{6}\u{3}\u{2}\u{2}\u{2}\u{2}\u{8}" .
            "\u{3}\u{2}\u{2}\u{2}\u{2}\u{A}\u{3}\u{2}\u{2}\u{2}\u{2}\u{C}\u{3}" .
            "\u{2}\u{2}\u{2}\u{2}\u{E}\u{3}\u{2}\u{2}\u{2}\u{3}\u{10}\u{3}\u{2}" .
            "\u{2}\u{2}\u{3}\u{12}\u{3}\u{2}\u{2}\u{2}\u{3}\u{14}\u{3}\u{2}\u{2}" .
            "\u{2}\u{4}\u{16}\u{3}\u{2}\u{2}\u{2}\u{4}\u{18}\u{3}\u{2}\u{2}\u{2}" .
            "\u{5}\u{1A}\u{3}\u{2}\u{2}\u{2}\u{5}\u{1C}\u{3}\u{2}\u{2}\u{2}\u{6}" .
            "\u{26}\u{3}\u{2}\u{2}\u{2}\u{8}\u{3A}\u{3}\u{2}\u{2}\u{2}\u{A}\u{3E}" .
            "\u{3}\u{2}\u{2}\u{2}\u{C}\u{43}\u{3}\u{2}\u{2}\u{2}\u{E}\u{48}\u{3}" .
            "\u{2}\u{2}\u{2}\u{10}\u{4C}\u{3}\u{2}\u{2}\u{2}\u{12}\u{51}\u{3}\u{2}" .
            "\u{2}\u{2}\u{14}\u{56}\u{3}\u{2}\u{2}\u{2}\u{16}\u{5C}\u{3}\u{2}\u{2}" .
            "\u{2}\u{18}\u{60}\u{3}\u{2}\u{2}\u{2}\u{1A}\u{66}\u{3}\u{2}\u{2}\u{2}" .
            "\u{1C}\u{6A}\u{3}\u{2}\u{2}\u{2}\u{1E}\u{20}\u{7}\u{F}\u{2}\u{2}\u{1F}" .
            "\u{1E}\u{3}\u{2}\u{2}\u{2}\u{1F}\u{20}\u{3}\u{2}\u{2}\u{2}\u{20}\u{21}" .
            "\u{3}\u{2}\u{2}\u{2}\u{21}\u{27}\u{7}\u{C}\u{2}\u{2}\u{22}\u{24}\u{7}" .
            "\u{C}\u{2}\u{2}\u{23}\u{22}\u{3}\u{2}\u{2}\u{2}\u{23}\u{24}\u{3}\u{2}" .
            "\u{2}\u{2}\u{24}\u{25}\u{3}\u{2}\u{2}\u{2}\u{25}\u{27}\u{7}\u{F}\u{2}" .
            "\u{2}\u{26}\u{1F}\u{3}\u{2}\u{2}\u{2}\u{26}\u{23}\u{3}\u{2}\u{2}\u{2}" .
            "\u{27}\u{7}\u{3}\u{2}\u{2}\u{2}\u{28}\u{2A}\u{9}\u{2}\u{2}\u{2}\u{29}" .
            "\u{28}\u{3}\u{2}\u{2}\u{2}\u{2A}\u{2D}\u{3}\u{2}\u{2}\u{2}\u{2B}\u{29}" .
            "\u{3}\u{2}\u{2}\u{2}\u{2B}\u{2C}\u{3}\u{2}\u{2}\u{2}\u{2C}\u{2E}\u{3}" .
            "\u{2}\u{2}\u{2}\u{2D}\u{2B}\u{3}\u{2}\u{2}\u{2}\u{2E}\u{32}\u{7}\u{2C}" .
            "\u{2}\u{2}\u{2F}\u{31}\u{9}\u{2}\u{2}\u{2}\u{30}\u{2F}\u{3}\u{2}\u{2}" .
            "\u{2}\u{31}\u{34}\u{3}\u{2}\u{2}\u{2}\u{32}\u{30}\u{3}\u{2}\u{2}\u{2}" .
            "\u{32}\u{33}\u{3}\u{2}\u{2}\u{2}\u{33}\u{3B}\u{3}\u{2}\u{2}\u{2}\u{34}" .
            "\u{32}\u{3}\u{2}\u{2}\u{2}\u{35}\u{37}\u{9}\u{2}\u{2}\u{2}\u{36}\u{35}" .
            "\u{3}\u{2}\u{2}\u{2}\u{37}\u{38}\u{3}\u{2}\u{2}\u{2}\u{38}\u{36}\u{3}" .
            "\u{2}\u{2}\u{2}\u{38}\u{39}\u{3}\u{2}\u{2}\u{2}\u{39}\u{3B}\u{3}\u{2}" .
            "\u{2}\u{2}\u{3A}\u{2B}\u{3}\u{2}\u{2}\u{2}\u{3A}\u{36}\u{3}\u{2}\u{2}" .
            "\u{2}\u{3B}\u{3C}\u{3}\u{2}\u{2}\u{2}\u{3C}\u{3D}\u{8}\u{3}\u{2}\u{2}" .
            "\u{3D}\u{9}\u{3}\u{2}\u{2}\u{2}\u{3E}\u{3F}\u{7}\u{42}\u{2}\u{2}\u{3F}" .
            "\u{40}\u{3}\u{2}\u{2}\u{2}\u{40}\u{41}\u{8}\u{4}\u{3}\u{2}\u{41}\u{42}" .
            "\u{8}\u{4}\u{4}\u{2}\u{42}\u{B}\u{3}\u{2}\u{2}\u{2}\u{43}\u{44}\u{A}" .
            "\u{3}\u{2}\u{2}\u{44}\u{45}\u{3}\u{2}\u{2}\u{2}\u{45}\u{46}\u{8}\u{5}" .
            "\u{3}\u{2}\u{46}\u{47}\u{8}\u{5}\u{5}\u{2}\u{47}\u{D}\u{3}\u{2}\u{2}" .
            "\u{2}\u{48}\u{49}\u{5}\u{6}\u{2}\u{2}\u{49}\u{4A}\u{3}\u{2}\u{2}\u{2}" .
            "\u{4A}\u{4B}\u{8}\u{6}\u{6}\u{2}\u{4B}\u{F}\u{3}\u{2}\u{2}\u{2}\u{4C}" .
            "\u{4D}\u{7}\u{42}\u{2}\u{2}\u{4D}\u{4E}\u{3}\u{2}\u{2}\u{2}\u{4E}" .
            "\u{4F}\u{8}\u{7}\u{3}\u{2}\u{4F}\u{50}\u{8}\u{7}\u{4}\u{2}\u{50}\u{11}" .
            "\u{3}\u{2}\u{2}\u{2}\u{51}\u{52}\u{A}\u{4}\u{2}\u{2}\u{52}\u{53}\u{3}" .
            "\u{2}\u{2}\u{2}\u{53}\u{54}\u{8}\u{8}\u{3}\u{2}\u{54}\u{55}\u{8}\u{8}" .
            "\u{5}\u{2}\u{55}\u{13}\u{3}\u{2}\u{2}\u{2}\u{56}\u{57}\u{5}\u{6}\u{2}" .
            "\u{2}\u{57}\u{58}\u{3}\u{2}\u{2}\u{2}\u{58}\u{59}\u{8}\u{9}\u{6}\u{2}" .
            "\u{59}\u{5A}\u{8}\u{9}\u{7}\u{2}\u{5A}\u{15}\u{3}\u{2}\u{2}\u{2}\u{5B}" .
            "\u{5D}\u{A}\u{5}\u{2}\u{2}\u{5C}\u{5B}\u{3}\u{2}\u{2}\u{2}\u{5D}\u{5E}" .
            "\u{3}\u{2}\u{2}\u{2}\u{5E}\u{5C}\u{3}\u{2}\u{2}\u{2}\u{5E}\u{5F}\u{3}" .
            "\u{2}\u{2}\u{2}\u{5F}\u{17}\u{3}\u{2}\u{2}\u{2}\u{60}\u{61}\u{5}\u{6}" .
            "\u{2}\u{2}\u{61}\u{62}\u{3}\u{2}\u{2}\u{2}\u{62}\u{63}\u{8}\u{B}\u{6}" .
            "\u{2}\u{63}\u{64}\u{8}\u{B}\u{7}\u{2}\u{64}\u{19}\u{3}\u{2}\u{2}\u{2}" .
            "\u{65}\u{67}\u{A}\u{5}\u{2}\u{2}\u{66}\u{65}\u{3}\u{2}\u{2}\u{2}\u{67}" .
            "\u{68}\u{3}\u{2}\u{2}\u{2}\u{68}\u{66}\u{3}\u{2}\u{2}\u{2}\u{68}\u{69}" .
            "\u{3}\u{2}\u{2}\u{2}\u{69}\u{1B}\u{3}\u{2}\u{2}\u{2}\u{6A}\u{6B}\u{5}" .
            "\u{6}\u{2}\u{2}\u{6B}\u{6C}\u{3}\u{2}\u{2}\u{2}\u{6C}\u{6D}\u{8}\u{D}" .
            "\u{6}\u{2}\u{6D}\u{6E}\u{8}\u{D}\u{7}\u{2}\u{6E}\u{1D}\u{3}\u{2}\u{2}" .
            "\u{2}\u{F}\u{2}\u{3}\u{4}\u{5}\u{1F}\u{23}\u{26}\u{2B}\u{32}\u{38}" .
            "\u{3A}\u{5E}\u{68}\u{8}\u{4}\u{3}\u{2}\u{5}\u{2}\u{2}\u{4}\u{4}\u{2}" .
            "\u{4}\u{5}\u{2}\u{9}\u{3}\u{2}\u{4}\u{2}\u{2}";

        protected static $atn;
        protected static $decisionToDFA;
        protected static $sharedContextCache;
        public function __construct(CharStream $input)
        {
            parent::__construct($input);

            self::initialize();

            $this->interp = new LexerATNSimulator(
                $this,
                self::$atn,
                self::$decisionToDFA,
                self::$sharedContextCache
            );
        }

        private static function initialize(): void
        {
            if (self::$atn !== null) {
                return;
            }

            RuntimeMetaData::checkVersion("4.9", RuntimeMetaData::VERSION);

            $atn = (new ATNDeserializer())->deserialize(self::SERIALIZED_ATN);

            $decisionToDFA = [];
            for (
                $i = 0, $count = $atn->getNumberOfDecisions();
                $i < $count;
                $i++
            ) {
                $decisionToDFA[] = new DFA($atn->getDecisionState($i), $i);
            }

            self::$atn = $atn;
            self::$decisionToDFA = $decisionToDFA;
            self::$sharedContextCache = new PredictionContextCache();
        }

        public static function vocabulary(): Vocabulary
        {
            static $vocabulary;

            return $vocabulary =
                $vocabulary ??
                new VocabularyImpl(self::LITERAL_NAMES, self::SYMBOLIC_NAMES);
        }

        public function getGrammarFileName(): string
        {
            return "DocBlockLexer.g4";
        }

        public function getRuleNames(): array
        {
            return self::RULE_NAMES;
        }

        public function getSerializedATN(): string
        {
            return self::SERIALIZED_ATN;
        }

        /**
         * @return array<string>
         */
        public function getChannelNames(): array
        {
            return self::CHANNEL_NAMES;
        }

        /**
         * @return array<string>
         */
        public function getModeNames(): array
        {
            return self::MODE_NAMES;
        }

        public function getATN(): ATN
        {
            return self::$atn;
        }

        public function getVocabulary(): Vocabulary
        {
            return self::vocabulary();
        }
    }
}
