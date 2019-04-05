angular.module("myshop")
	.filter('hierarchify',function(){
		return function(input, children){
			children = children || "children";
			var cat, i, child,
				categories =[],
				sub_cat = [],
				misc_id = 8;
				
			if(!input)
				return input;
			
			for(i = 0; i < input.length; i++){
				cat = input[i];
				//Put child and parent nodes in different arrays
				if(cat.parent==0){
					cat[children] = [];
					categories[cat.id] = cat;
				}
				else
					sub_cat.push(cat);
			}

			input = [];

			//put children under parent nodes
			for(i = 0; i<sub_cat.length; i++){
				child = sub_cat[i]

				//change parent to other category if parent doesn't exist
				if(!categories[child.parent])
					child.parent = misc_id;

				//append child to parent's children
				categories[child.parent][children].push(child);
			}

			//Convert categories into an array instead of object
			for(i=0; i<categories.length; i++){
				if(categories[i])
					input.push(categories[i]);
			}
			return input;	
		}
	})

	.filter('range', function(){
		return function(n) {
			var res = [];
			for (var i = 0; i < n; i++)
				res.push(i);
			return res;
		};
	})

	.filter('formatSecs', function(){
		return function(time) {
			var res = "";
			var days = Math.round( time / (24*3600) );
			var afterDays = time % (24 * 3600);
			res += days ? days + "D " : "";

			var hours = Math.round( afterDays / 3600);
			var afterHours = time % 3600;
			res += hours ? hours + "h " : "";

			var minutes = Math.round( afterHours/ 60);
			res += minutes ? minutes + "m " : "0m";
			

			return res;
		};
	})


