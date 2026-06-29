<?php
//////////////////////////////////////////////////////////////////////////////80
// Atheos Adminer
//////////////////////////////////////////////////////////////////////////////80
// Copyright (c) 2022 Francesco Filippi, distributed as-is and without
// warranty under the MIT License. See [root]/LICENSE.md for more.
// This information must remain intact.
//////////////////////////////////////////////////////////////////////////////80

switch ($action) {

	case 'open': 
		$path = POST("path");
		$pluginName = basename(__DIR__);
	?>
		<label class="title"><i class="fas fa-database"></i>Adminer</label>
		<iframe id="adminer" src="<?php echo $path . 'plugins/' . $pluginName . '/loader.php';?>" style="width:1200px; height:800px"></iframe>	
		<?php
		break;

	default:
		Common::send("error", "Invalid action.");
		break;
}