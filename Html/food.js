var page = require('webpage').create();
var system = require('system');
var args = system.args;
var url = args[1];
page.settings.userAgent = 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36';
phantom.outputEncoding = 'utf-8';
page.open(url, function (status) {
    if (status !== 'success') {
        console.log('Unable to access network');
    } else {
        const ua = page.evaluate(function (){
            return document;
        });
        console.log(ua.all[0].outerHTML);
    }
    phantom.exit();
});
