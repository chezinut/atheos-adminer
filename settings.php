<label><i class="fas fa-paint-brush"></i>Adminer Settings</label>
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
	<tr>
		<td>Auto Login</td>
		<td>
			<toggle>
				<input id="adminerAutologin_true" data-setting="adminer.autologin" value="true" name="adminer.autologin" type="radio" />
				<label for="adminerAutologin_true"><?php echo i18n("enabled"); ?></label>
				<input id="adminerAutologin_false" data-setting="adminer.autologin" value="false" name="adminer.autologin" type="radio" checked />
				<label for="adminerAutologin_false"><?php echo i18n("disabled"); ?></label>
			</toggle>
		</td>
	</tr>
	<tr>
		<td>Server</td>
		<td>
			<input data-setting="adminer.server" type="text" />
		</td>
	</tr>
	<tr>
		<td>Username</td>
		<td>
			<input data-setting="adminer.username" type="text" />
		</td>
	</tr>
	<tr>
		<td>Password</td>
		<td>
			<input data-setting="adminer.password" type="password" />
		</td>
	</tr>
	<tr>
		<td>Database</td>
		<td>
			<input data-setting="adminer.dbname" type="text" />
		</td>
	</tr>
</table>