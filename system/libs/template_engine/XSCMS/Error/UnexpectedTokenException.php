<?php
/*
 * This file is part of XSCMS.
 *

 *

 */

namespace XSCMS\Error;

use XSCMS\Tokenizer;

/**
 * Unexpected token
 */
class UnexpectedTokenException extends \RuntimeException
{
    public function __construct(Tokenizer $tokens, $expect = null, $where = null)
    {
        if ($expect && count($expect) == 1 && is_string($expect[0])) {
            $expect = ", expect '" . $expect[0] . "'";
        } else {
            $expect = "";
        }
        if (!$tokens->curr) {
            $this->message = "Unexpected end of " . ($where ? : "expression") . "$expect";
        } else {
            $this->message = "Unexpected token '" . $tokens->current() . "' in " . ($where ? : "expression") . "$expect";
        }
    }
}

;