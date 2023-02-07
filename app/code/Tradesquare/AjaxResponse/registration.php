<?php

/**
 * Tradesquare_AjaxResponse
 *
 * @copyright   Copyright (c) 2022 TRADESQUARE PTY LTD (www.tradesquare.com.au)
 * @author       Fionov <dmitriy@tradesquare.com.au>
 */

declare(strict_types=1);

use Magento\Framework\Component\ComponentRegistrar;

ComponentRegistrar::register(
    ComponentRegistrar::MODULE,
    'Tradesquare_AjaxResponse',
    __DIR__
);
