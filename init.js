//////////////////////////////////////////////////////////////////////////////80
// Atheos Adminer
//////////////////////////////////////////////////////////////////////////////80

(function() {

	let editingIndex = -1;
	let cachedCreds = null;

	carbon.subscribe('system.loadExtra', () => atheos.adminer.init());

	atheos.adminer = {

		init: function() {
			carbon.subscribe('settings.panelLoaded', function() {
				if (document.querySelector('.adminer-creds-list')) {
					atheos.adminer.loadAndRender();
					atheos.adminer.bindEvents();
				}
			});
		},

		open: function() {
			window.open(atheos.baseUrl + 'plugins/Adminer/loader.php');
		},

		loadCredentials: function(callback) {
			echo({
				url: atheos.controller,
				data: {
					target: 'Adminer',
					action: 'loadCredentials'
				},
				settled: function(reply, status) {
					if (status === 200 && reply && Array.isArray(reply.list)) {
						cachedCreds = reply.list;
					} else {
						cachedCreds = [];
					}
					if (callback) callback(cachedCreds);
				}
			});
		},

		saveCredentials: function(creds, callback) {
			echo({
				url: atheos.controller,
				data: {
					target: 'Adminer',
					action: 'saveCredentials',
					credentials: JSON.stringify(creds)
				},
				settled: function(reply, status) {
					if (callback) callback(status === 200);
				}
			});
		},

		loadAndRender: function() {
			atheos.adminer.loadCredentials(function() {
				atheos.adminer.renderCredentials();
			});
		},

		bindEvents: function() {
			document.addEventListener('click', function(e) {
				if (e.target.closest('.adminer-add-cred')) {
					atheos.adminer.showForm();
				} else if (e.target.closest('.adminer-cred-save')) {
					atheos.adminer.saveCred();
				} else if (e.target.closest('.adminer-cred-cancel')) {
					atheos.adminer.hideForm();
				} else if (e.target.closest('.adminer-export-creds')) {
					atheos.adminer.exportAll();
				} else if (e.target.closest('.adminer-import-creds')) {
					document.querySelector('.adminer-import-input').click();
				} else if (e.target.closest('.adminer-cred-edit')) {
					var idx = parseInt(e.target.closest('.adminer-cred-edit').getAttribute('data-index'));
					atheos.adminer.editCred(idx);
				} else if (e.target.closest('.adminer-cred-delete')) {
					var idx = parseInt(e.target.closest('.adminer-cred-delete').getAttribute('data-index'));
					atheos.adminer.deleteCred(idx);
				} else if (e.target.closest('.adminer-cred-login')) {
					var idx = parseInt(e.target.closest('.adminer-cred-login').getAttribute('data-index'));
					atheos.adminer.quickLogin(idx);
				}
			});

			var importInput = document.querySelector('.adminer-import-input');
			if (importInput) {
				importInput.addEventListener('change', function() {
					atheos.adminer.importAll(this);
				});
			}
		},

		renderCredentials: function() {
			var list = document.querySelector('.adminer-creds-list');
			if (!list) return;

			var creds = cachedCreds || [];
			if (creds.length === 0) {
				list.innerHTML = '<p style="color:#666;font-size:12px;">No saved credentials.</p>';
				return;
			}

			var html = '';
			creds.forEach(function(cred, i) {
				var label = cred.label || (cred.username + '@' + cred.server);
				var driver = cred.driver || 'server';
				html += '<div style="display:flex;align-items:center;padding:6px 8px;margin-bottom:4px;background:#151520;border:1px solid #2a2a3a;border-radius:3px;">' +
					'<span style="flex:1;color:#ccc;font-size:13px;">' + atheos.adminer.esc(label) + '</span>' +
					'<span style="color:#666;font-size:11px;margin-right:10px;">' + atheos.adminer.esc(driver) + '</span>' +
					'<button class="adminer-cred-login" data-index="' + i + '" title="Login" style="padding:3px 8px;cursor:pointer;background:#1a3a1a;border:1px solid #2a5a2a;color:#7ebf7e;border-radius:3px;font-size:11px;">Login</button> ' +
					'<button class="adminer-cred-edit" data-index="' + i + '" title="Edit" style="padding:3px 8px;cursor:pointer;background:#1a2a3a;border:1px solid #2a3a5a;color:#7eb8f0;border-radius:3px;font-size:11px;"><i class="fas fa-pen"></i></button> ' +
					'<button class="adminer-cred-delete" data-index="' + i + '" title="Delete" style="padding:3px 8px;cursor:pointer;background:#2a1515;border:1px solid #3a2020;color:#e07070;border-radius:3px;font-size:11px;"><i class="fas fa-trash"></i></button>' +
					'</div>';
			});
			list.innerHTML = html;
		},

		showForm: function(cred) {
			editingIndex = cred ? cred._index : -1;
			var form = document.querySelector('.adminer-cred-form');
			if (!form) return;

			document.querySelector('.adminer-cred-label').value = cred ? (cred.label || '') : '';
			document.querySelector('.adminer-cred-driver').value = cred ? (cred.driver || 'server') : 'server';
			document.querySelector('.adminer-cred-server').value = cred ? (cred.server || '') : 'localhost';
			document.querySelector('.adminer-cred-username').value = cred ? (cred.username || '') : '';
			document.querySelector('.adminer-cred-password').value = cred ? (cred.password || '') : '';
			document.querySelector('.adminer-cred-dbname').value = cred ? (cred.dbname || '') : '';

			form.style.display = 'block';
		},

		hideForm: function() {
			var form = document.querySelector('.adminer-cred-form');
			if (form) form.style.display = 'none';
			editingIndex = -1;
		},

		saveCred: function() {
			var cred = {
				label: document.querySelector('.adminer-cred-label').value.trim(),
				driver: document.querySelector('.adminer-cred-driver').value,
				server: document.querySelector('.adminer-cred-server').value.trim(),
				username: document.querySelector('.adminer-cred-username').value.trim(),
				password: document.querySelector('.adminer-cred-password').value,
				dbname: document.querySelector('.adminer-cred-dbname').value.trim()
			};

			if (!cred.server || !cred.username) {
				atheos.toast.show('error', 'Server and username are required');
				return;
			}

			// When editing, an empty password input means "keep the stored one".
			// The server preserves it when this property is omitted.
			if (editingIndex >= 0 && cred.password === '') {
				delete cred.password;
			}

			var creds = (cachedCreds || []).slice();
			if (editingIndex >= 0) {
				creds[editingIndex] = cred;
			} else {
				creds.push(cred);
			}

			atheos.adminer.saveCredentials(creds, function(ok) {
				if (!ok) {
					atheos.toast.show('error', 'Failed to save credential');
					return;
				}
				atheos.adminer.hideForm();
				atheos.adminer.loadAndRender();
				atheos.toast.show('success', 'Credential saved');
			});
		},

		editCred: function(index) {
			var creds = cachedCreds || [];
			if (creds[index]) {
				var cred = JSON.parse(JSON.stringify(creds[index]));
				cred._index = index;
				atheos.adminer.showForm(cred);
			}
		},

		deleteCred: function(index) {
			if (!confirm('Delete this credential?')) return;
			var creds = (cachedCreds || []).slice();
			creds.splice(index, 1);

			atheos.adminer.saveCredentials(creds, function(ok) {
				if (!ok) {
					atheos.toast.show('error', 'Failed to delete credential');
					return;
				}
				atheos.adminer.loadAndRender();
				atheos.toast.show('success', 'Credential deleted');
			});
		},

		quickLogin: function(index) {
			window.open(atheos.baseUrl + 'plugins/Adminer/loader.php?cred=' + encodeURIComponent(index));
		},

		exportAll: function() {
			var creds = cachedCreds || [];
			if (creds.length === 0) {
				atheos.toast.show('error', 'No credentials to export');
				return;
			}

			var blob = new Blob([JSON.stringify(creds, null, 2)], { type: 'application/json' });
			var url = URL.createObjectURL(blob);
			var a = document.createElement('a');
			a.href = url;
			a.download = 'adminer-credentials.json';
			document.body.appendChild(a);
			a.click();
			document.body.removeChild(a);
			URL.revokeObjectURL(url);

			atheos.toast.show('success', creds.length + ' credential(s) exported');
		},

		importAll: function(input) {
			var file = input.files[0];
			if (!file) return;

			var reader = new FileReader();
			reader.onload = function(e) {
				try {
					var data = JSON.parse(e.target.result);
					var imported = 0;
					// Start from the masked cache; existing entries without a
					// password are preserved server-side via slot matching.
					var existing = (cachedCreds || []).slice();

					var push = function(cred) {
						if (!cred || !cred.server || !cred.username) return;
						existing.push({
							label: cred.label || '',
							driver: cred.driver || 'server',
							server: cred.server,
							username: cred.username,
							password: cred.password || '',
							dbname: cred.dbname || ''
						});
						imported++;
					};

					if (Array.isArray(data)) {
						data.forEach(push);
					} else {
						push(data);
					}

					if (imported === 0) {
						atheos.toast.show('error', 'No valid credentials in file');
						return;
					}

					atheos.adminer.saveCredentials(existing, function(ok) {
						if (!ok) {
							atheos.toast.show('error', 'Failed to import credentials');
							return;
						}
						atheos.adminer.loadAndRender();
						atheos.toast.show('success', imported + ' credential(s) imported');
					});
				} catch (err) {
					atheos.toast.show('error', 'Failed to parse file');
				}
			};
			reader.readAsText(file);
			input.value = '';
		},

		esc: function(str) {
			var div = document.createElement('div');
			div.textContent = str || '';
			return div.innerHTML;
		}
	};

})();
