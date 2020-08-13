var system = require('system');
var args = system.args;
var page = require('webpage').create();

if (args.length < 2) {
    phantom.exit();
}

page.customHeaders = {
    refer: 'https://list.jd.com/list.html?cat=9987,653,655',
};

page.open(args[1], function(status) {
    if(status === "success") {
        page.evaluate(function() {
            var output = page.content;
            console.log(output);
        });
    }
    phantom.exit();
});