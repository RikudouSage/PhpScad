<?php

namespace Rikudou\PhpScad\Enum;

enum TextDirection: string
{
    case LeftToRight = 'ltr';
    case RightToLeft = 'rtl';
    case TopToBottom = 'ttb';
    case BottomToTop = 'btt';
}
