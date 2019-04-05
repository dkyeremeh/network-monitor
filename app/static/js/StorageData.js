

function StorageData($$index){
	//if an instance with the specified index exist, return it
	if(this.$$instances[$$index])
		return this.$$instances[$$index];
	
	//Create a new instance 
	this.$$index = $$index;
	this.load($$index);
}

StorageData.prototype ={
	
	$$instances: {},
	load: function(){
		if(localStorage[this.$$index]){
			var data = angular.fromJson(localStorage[this.$$index]);
			for(var x in data)
				this[x] = data[x];
			delete data;
		}
		this.$$instances[this.$$index] = this;
	},
	save: function(){
		localStorage[this.$$index] = angular.toJson(this);
		
		return this;
	},
	clear: function(){
		delete localStorage[this.$$index];
		for (var x in this)
			if(this.hasOwnProperty(x) && x != '$$index')
				delete this[x];
		return this;
	},
	set: function(key,val){
		//set object
		if (!(angular.isNumber(key) || angular.isString(key)) && angular.isObject(key)){
			for(var x in key)
				this[x] = key[x];
		}
		else if (key)
			this[key] = val;
		this.save();
		
		return this;
	},
	get: function(key){
		if(!key){
			var out = {};
			for (var x in this)
				if(this.hasOwnProperty(x) && x != '$$index')
					out[x] = this[x];
			return out;
		}
		else
			return this[key];
	},
	remove: function(key){
		if(key){
			delete(this[key]);
		}
		
		return this;
	},
}