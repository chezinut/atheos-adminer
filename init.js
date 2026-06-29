//////////////////////////////////////////////////////////////////////////////80
// Atheos Adminer
//////////////////////////////////////////////////////////////////////////////80
// Copyright (c) 2022 Francesco Filippi, distributed as-is and without
// warranty under the MIT License. See [root]/LICENSE.md for more.
// This information must remain intact.
//////////////////////////////////////////////////////////////////////////////80
// Description: 
//	A plugin to open Adminner as modal o new page
//////////////////////////////////////////////////////////////////////////////80

(function() {

	let self = false;

	// Initiates plugin as a third priority in the system.
	carbon.subscribe('system.loadExtra', () => atheos.adminer.init());

	atheos.adminer = {
		
		pluginName: 'Adminer',

		init: function() {
			if (self) return;
			self = this;
			
			// Find the actual plugin name from the script tag
			let scripts = document.getElementsByTagName('script');
			for (let i = 0; i < scripts.length; i++) {
				let match = scripts[i].src.match(/plugins\/(.*?)\/init\.js/i);
				if (match && scripts[i].src.toLowerCase().includes('adminer')) {
					this.pluginName = match[1];
					break;
				}
			}
		},

		open: function() {
			if(storage('adminer.modal')){
				atheos.modal.load(1000,{
					target: self.pluginName,
					action: 'open',
					path: atheos.baseUrl
				});
				atheos.common.hideOverlay();
			}else{
				window.open(atheos.baseUrl + 'plugins/' + self.pluginName + '/loader.php');
			}
		}
	};

})();