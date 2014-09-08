<?php

namespace Api\Model;

class Exception extends \Exception
{
    // Common
    const COMMON_INCORRECT_ARGUMENT = 1000;
    const COMMON_LOGICAL_ERROR      = 1001;
    const COMMON_EMPTY_REQUEST      = 1002;
    const COMMON_INTERNAL_ERROR     = 1003;
    const COMMON_ROUTE_NOT_FOUND    = 1004;
    const COMMON_SESSION_EXPIRED    = 1005;

    // Word
    const WORD_NOT_FOUND           = 7000;
    const WORD_SPEAKER_IN_PROGRESS = 7001;

    // Strategy
    const STRATEGY_NOT_FOUND   = 6000;
    const STRATEGY_EMPTY_ITEMS = 6001;

    // Package
    const PACKAGE_NOT_FOUND      = 5000;
    const PACKAGE_INSTALL_FAILED = 5001;

    // Download
    const DOWNLOAD_NOT_FOUND = 3000;

    // Group
    const GROUP_NOT_FOUND   = 4000;
    const GROUP_LAST        = 4001;
    const GROUP_WORDS_EXIST = 4002;

    // Auth
    const AUTH_EMAIL_EXISTS = 2000;
    const AUTH_FAILED       = 2001;
}
