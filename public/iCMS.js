/**
 * iCMS - i Content Management System
 * Copyright (c) 2007-2012 idreamsoft.com iiimon Inc. All rights reserved.
 *
 * @author coolmoo <idreamsoft@qq.com>
 * @site http://www.idreamsoft.com
 * @licence http://www.idreamsoft.com/license.php
 * @version 6.1.0
 * @$Id: iCMS.APP.js 176 2015-12.21 02:52:17Z cool.tea $
 */
!(function() {
    var __modules__ = {};

    function require(id) {
        var mod = __modules__[id];
        var exports = 'exports';
        if (typeof mod === 'object') {
            return mod;
        }
        if (!mod[exports]) {
            mod[exports] = {};
            mod[exports] = mod.call(mod[exports], require, mod[exports], mod) || mod[exports];
        }
        return mod[exports];
    }

    function define(path, fn) {
        __modules__[path] = fn;
    }
    define("jquery", function() {
        return jQuery;
    });
})();
