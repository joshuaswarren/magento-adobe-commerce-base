/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
define([], function()
{
    options = {
        expireMonthForward: 1
    }

    return {
        load: function(name)
        {
            var nameEQ = encodeURIComponent(name) + "=";
            var ca = document.cookie.split(';');
            for (var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) === ' ')
                    c = c.substring(1, c.length);
                if (c.indexOf(nameEQ) === 0)
                    return decodeURIComponent(c.substring(nameEQ.length, c.length));
            }
            return null;
        },

        save: function(name, value, expires, path)
        {
            if ( expires === undefined ) {
                var date = new Date();
                date.setMonth(date.getMonth() + options.expireMonthForward);
            } else {
                var date = new Date(expires);
            }

            if ( path === undefined ) {
                path = '/';
            }

            document.cookie = name + '=' + value + '; expires=' + date.toUTCString() + '; path=' + path + ';';
        },

        remove: function(name)
        {
            document.cookie = name + "= ; expires = Thu, 01 Jan 1970 00:00:00 GMT";
        }
    };
});
