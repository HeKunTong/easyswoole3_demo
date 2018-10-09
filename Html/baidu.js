var page = require('webpage').create();
var url = 'https://www.baidu.com';
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