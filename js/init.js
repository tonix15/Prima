// JavaScript Document
var CONST = {
	URL: location.protocol + '//' + location.host,
	AJAX_URL: location.protocol + '//' + location.host + '/ajax'
};

var Errors = function() {
    var self = this;
    this.errors = new Array();
    this.add = function(errors) {
        self.errors.push(errors);
    };
    this.display = function() {
        for (var i = 0; i < self.errors.length; i++) {
                self.errors[i];
        }
    };
    this.hasErrors = function() {
        if (self.errors.length > 0) {
                return true;
        }
        return false;
    };
};

