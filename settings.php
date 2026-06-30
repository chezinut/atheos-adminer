<label><i class="fas fa-database"></i>Adminer Settings</label>
<table>
	<tr>
		<td>Adminer on modal</td>
		<td>
			<toggle>
				<input id="adminerModal_true" data-setting="adminer.modal" value="true" name="adminer.modal" type="radio" />
				<label for="adminerModal_true"><?php echo i18n("enabled"); ?></label>
				<input id="adminerModal_false" data-setting="adminer.modal" value="false" name="adminer.modal" type="radio" checked />
				<label for="adminerModal_false"><?php echo i18n("disabled"); ?></label>
			</toggle>
		</td>
	</tr>
</table>

<h3 style="margin:15px 0 8px;color:#aaa;font-size:13px;"><i class="fas fa-key"></i> Saved Credentials</h3>
<div class="adminer-creds-list"></div>
<div style="margin-top:10px;">
	<button class="adminer-add-cred" type="button" style="padding:5px 12px;cursor:pointer;"><i class="fas fa-plus"></i> Add Credential</button>
	<button class="adminer-export-creds" type="button" style="padding:5px 12px;cursor:pointer;margin-left:5px;"><i class="fas fa-download"></i> Export</button>
	<button class="adminer-import-creds" type="button" style="padding:5px 12px;cursor:pointer;margin-left:5px;"><i class="fas fa-upload"></i> Import</button>
	<input type="file" class="adminer-import-input" accept=".json" style="display:none;" />
</div>

<div class="adminer-cred-form" style="display:none;margin-top:10px;padding:10px;background:#1a1a2a;border:1px solid #333;border-radius:4px;">
	<label style="color:#aaa;font-size:12px;">Label</label>
	<input class="adminer-cred-label" type="text" placeholder="My Database" style="width:100%;margin-bottom:6px;padding:5px;background:#111;border:1px solid #444;color:#ccc;border-radius:3px;" />
	<label style="color:#aaa;font-size:12px;">Driver</label>
	<select class="adminer-cred-driver" style="width:100%;margin-bottom:6px;padding:5px;background:#111;border:1px solid #444;color:#ccc;border-radius:3px;">
		<option value="server">MySQL / MariaDB</option>
		<option value="pgsql">PostgreSQL</option>
		<option value="sqlite">SQLite</option>
		<option value="sqlsrv">MS SQL</option>
	</select>
	<label style="color:#aaa;font-size:12px;">Server</label>
	<input class="adminer-cred-server" type="text" placeholder="localhost" style="width:100%;margin-bottom:6px;padding:5px;background:#111;border:1px solid #444;color:#ccc;border-radius:3px;" />
	<label style="color:#aaa;font-size:12px;">Username</label>
	<input class="adminer-cred-username" type="text" placeholder="root" style="width:100%;margin-bottom:6px;padding:5px;background:#111;border:1px solid #444;color:#ccc;border-radius:3px;" />
	<label style="color:#aaa;font-size:12px;">Password</label>
	<input class="adminer-cred-password" type="password" style="width:100%;margin-bottom:6px;padding:5px;background:#111;border:1px solid #444;color:#ccc;border-radius:3px;" />
	<label style="color:#aaa;font-size:12px;">Database</label>
	<input class="adminer-cred-dbname" type="text" placeholder="(optional)" style="width:100%;margin-bottom:10px;padding:5px;background:#111;border:1px solid #444;color:#ccc;border-radius:3px;" />
	<button class="adminer-cred-save" type="button" style="padding:5px 15px;cursor:pointer;background:#1a3a1a;border:1px solid #2a5a2a;color:#7ebf7e;border-radius:3px;">Save</button>
	<button class="adminer-cred-cancel" type="button" style="padding:5px 15px;cursor:pointer;margin-left:5px;">Cancel</button>
</div>
