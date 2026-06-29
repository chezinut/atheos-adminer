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
</table>
