function fixCookieDate(date) {
    var base = new Date(0);
    var skew = base.getTime();
    if (skew > 0) {
        date.setTime(date.getTime() - skew);
    }
}

function getCookieVal(offset) {
    var endstr = document.cookie.indexOf(";", offset);
    if (endstr === -1) {
        endstr = document.cookie.length;
    }
    return document.cookie.substring(offset, endstr);//decodeURIComponent(document.cookie.substring(offset, endstr));
}

function getCookie(name) {
    //name = encodeURI(name);
    var arg = name + "=";
    var alen = arg.length;
    var clen = document.cookie.length;
    var i = 0;
    while (i < clen) {
        var j = i + alen;
        if (document.cookie.substring(i, j) === arg)
            return getCookieVal(j);
        i = document.cookie.indexOf(" ", i) + 1;
        if (i === 0)
            break;
    }
    return null;
}


function setCookie(name, value) {
    var expires = new Date();
    fixCookieDate(expires);
    //expires.setTime(expires.getTime() + (24 * 60 * 60 * 1000)); //24 horas
    expires.setTime(expires.getTime() + (30*1000)); //30 seg
    document.cookie = name + "=" + value +//encodeURIComponent(value) +
            ((expires) ? "; expires=" + expires.toGMTString() : "");
}
