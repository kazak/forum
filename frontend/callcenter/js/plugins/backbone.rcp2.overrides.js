'use strict';

// Overrides RPC2.sync using gos/websocket-client
Backbone.RPC2.sync = function(method, model, options) {
	var client = Backbone.RPC2.client;
	var success = function(response) {
		if (typeof options.success === 'function') { options.success(response); }
		if (typeof options.complete === 'function') { options.complete(response); }
	};

	var error = function(response) {
		if (typeof options.error === 'function') { options.error(response); }
		if (typeof options.complete === 'function') { options.complete(response); }
	};

	if (!client) {
		error('Client is not set.');
	}

	var remoteMethod = model.rpcOptions.methods[method].method;
	if (typeof remoteMethod === 'function') {
		remoteMethod = remoteMethod(model);
	}

	var payload = model.constructParams(method, remoteMethod);
	options.payload = payload;

	if (!payload) {
		return false;
	}

	if (typeof options.data === 'object') {
		payload = _.extend(payload, options.data);
	}

	Backbone.RPC2.trigger('sync:remoteMethod', remoteMethod);
	Backbone.RPC2.trigger('sync:payload', payload);

	return client.call(remoteMethod, payload).then(success, error);
};

Backbone.RPC2.Util.setSubscriptions = function() {
	var that = this;
	if (typeof that.sub !== 'undefined') {
		for (var sub in that.sub) {
			if (typeof that[that.sub[sub]] === 'function') {
				Backbone.RPC2.client.subscribe(sub, that[that.sub[sub]].bind(that));
			}
		}
	}
};

Backbone.RPC2.Model.prototype.initialize = function() {
	Backbone.RPC2.Util.setSubscriptions.call(this);
	Backbone.Model.prototype.initialize.apply(this, arguments);
};

Backbone.RPC2.Collection.prototype.initialize = function() {
	Backbone.RPC2.Util.setSubscriptions.call(this);
	Backbone.Collection.prototype.initialize.apply(this, arguments);
};

Backbone.RPC2.client = null;
Backbone.RPC2.socket = null;
Backbone.RPC2.setClient = function(socket) {
	Backbone.RPC2.socket = socket;
	Backbone.RPC2.socket.on('socket/connect', function(session) {
        Backbone.RPC2.client = session;
    });
    Backbone.RPC2.socket.on('socket/disconnect', function() {
        Backbone.RPC2.client = null;
    });
};