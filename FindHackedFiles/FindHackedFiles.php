<html>
<head><title>:: Find HaCKeD File/s ::</title>
<style>
Body {
	color: lime;
	background-color: black;
}
#findstr {
	margin: auto;
	text-align: left;
	width: 100%;
	border: 2px solid #C0C0C0;
	background-color: #222;
	height: 100%;
	overflow-y: scroll;
	font-family:Courier New, Courier, monospace;
}
</style>
</head>
<body>
<table align="center">
<tr>
	<td><img src="./logo.png"/></td>
</tr>
<tr>
	<td>
		<form action="" method="post">
		<input type="text" name="findstr" size="20" placeholder="Find String e.g HaCKeD" value="HaCKeD"/>
		<input type='submit' name='submit' value='Find'>
		</form>
	</td>
</tr>
<tr>
	<td><u>SUSPICIOUS FILES :</u></td>
</tr>
<tr>
	<td style="height: 150px;">
		<div id="findstr">
		<?php
		$findstr = isset($_POST["findstr"]) ? $_POST["findstr"] : '';
		find_files('.', $findstr);

		Function find_files($seed, $str_to_find) {
			if(! is_dir($seed)) return false;
			$files = array();
			$dirs = array($seed);
			while(NULL !== ($dir = array_pop($dirs))) {
				if($dh = @opendir($dir)) {
					while( false !== ($file = readdir($dh))) {
						if ($file == '.' || $file == '..') continue;
						$path = $dir . '/' . $file;
						if (is_dir($path)) { $dirs[] = $path; }
						else { if(preg_match('/^.*\.(php[\d]?|js|txt|htm|html)$/i', $path)) { check_files($path, $str_to_find); } }
					}
					closedir($dh);
				}
			}
		}
			
		Function check_files($this_file, $str_to_find) {
			if(!($content = @file_get_contents($this_file))) { 
				echo("Could not check {$this_file}");
			} else { 
				if(@stristr($content, $str_to_find)) {			
					echo("<a href=\"{$this_file}\" target=\"_blank\">{$this_file}</a> -> contain string \"{$str_to_find}\"<br>");
				}
			}
			unset($content);
		}
		?>
		</div>
	</td>
</tr>
<tr>
	<td>
		<div align="center" style="background-color: #222;"><span style="font-size:10pt;color:#C0C0C0;text-shadow:0 0 7px #C0C0C0;font-family:Courier New, Courier, monospace;"><strong>Copyleft &copy; 2016, c0d3Lib</strong></span></div>
	</td>
</tr>
</table>
</body>
</html>