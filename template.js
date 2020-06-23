var system = require('system');
var args = system.args;
var page = require('webpage').create();

if (args.length < 2) {
    phantom.exit();
}

page.open(args[1], function(status) {
    if(status === "success") {
        var output = page.content;
        console.log(output);
    }
    phantom.exit();
});