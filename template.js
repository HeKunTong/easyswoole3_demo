var system = require('system');
var args = system.args;
var page = require('webpage').create();

if (args.length < 2) {
    phantom.exit();
}

page.settings.userAgent = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.121 Safari/537.36';

page.open(args[1], function(status) {
    if(status === "success") {
        console.log(page.content);
        phantom.exit();
    }
});