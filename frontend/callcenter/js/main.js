'use strict';

var Helpers = require('./helpers/Helpers.js');
var CC = require('./cc/cc.js');

(function(window){
	window.CC = CC;
	window.CC.initialize();

	window.Helpers = Helpers;
	window.Helpers.initialize();
}(window));