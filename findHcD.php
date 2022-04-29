<?php 
	error_reporting(0);
	set_time_limit(0);
	
	$arrMalCodes = array();
	
	$findstr = isset( $_POST["findstr"] ) ? $_POST["findstr"] : '';
	find_files('.', $findstr);

	Function find_files( $seed, $str_to_find ) {
		global $arrMalCodes;
		
		if(! is_dir($seed)) return false;
		$files = array();
		$dirs = array($seed);
		while(NULL !== ($dir = array_pop($dirs))) {
			if($dh = @opendir($dir)) {
				while( false !== ($file = readdir($dh))) {
					if ($file == '.' || $file == '..') continue;
						$path = $dir . '/' . $file;
						if (is_dir($path)) { $dirs[] = $path; }
					else { 
						if( preg_match('/^.*\.(php[\d]?|js|txt|htm|html)$/i', $path) ) {
							$scanf = check_files($path, $str_to_find);
							
							if ( !empty( $scanf ) ) {
								$fsize = filesize($path)/1024;
								$fsize = round($fsize,3);
								if ( $fsize >= 1024 ) { 
									$fsize = round( $fsize/1024,2 ) . ' MB';
								} else { 
									$fsize = $fsize . ' KB';
								}
								
								$finfo = perms($scanf);
								$arrMalCodes[] = array(
									'fpath' => $path,
									'fname' => basename($scanf),
									'fsize' => $fsize, 
									'finfo' => $finfo,
								);
								
							}
						}
					}
				}
				closedir($dh);
			}
		}
		
		// return $arrMalCodes;
	}
					
	Function check_files($this_file, $str_to_find) {
		if( $content = @file_get_contents($this_file) ) { 
			if( @stristr($content, $str_to_find) ) {			
				return $this_file;
			}
		}
		unset($content);
	}
	
	function perms($file){ $perms = fileperms($file);
		if (($perms & 0xC000) == 0xC000) { $info = 's';
		} elseif (($perms & 0xA000) == 0xA000) { $info = 'l';
		} elseif (($perms & 0x8000) == 0x8000) { $info = '-';
		} elseif (($perms & 0x6000) == 0x6000) { $info = 'b';
		} elseif (($perms & 0x4000) == 0x4000) { $info = 'd';
		} elseif (($perms & 0x2000) == 0x2000) { $info = 'c';
		} elseif (($perms & 0x1000) == 0x1000) { $info = 'p';
		} else { $info = 'u';
		} $info .= (($perms & 0x0100) ? 'r' : '-');
		$info .= (($perms & 0x0080) ? 'w' : '-');
		$info .= (($perms & 0x0040) ? (($perms & 0x0800) ? 's' : 'x' ) : (($perms & 0x0800) ? 'S' : '-'));
		$info .= (($perms & 0x0020) ? 'r' : '-');
		$info .= (($perms & 0x0010) ? 'w' : '-');
		$info .= (($perms & 0x0008) ? (($perms & 0x0400) ? 's' : 'x' ) : (($perms & 0x0400) ? 'S' : '-'));
		$info .= (($perms & 0x0004) ? 'r' : '-');
		$info .= (($perms & 0x0002) ? 'w' : '-');
		$info .= (($perms & 0x0001) ? (($perms & 0x0200) ? 't' : 'x' ) : (($perms & 0x0200) ? 'T' : '-'));
		return $info;
	}
	
	if ( isset($_GET['filesrc'] ) ) $filetxt = '<pre>'.htmlspecialchars(file_get_contents($_GET['filesrc'])).'</pre>';
	
?>
<!DOCTYPE HTML>
<html>
<head>
<title>PHP Malicious Code Scanner</title>
<style>
	body{
		font-family: bariol Regular;
		background-color: black;
		color:white;
	}
	
	tr:hover {
		text-shadow:0px 0px 10px #fff;
	}
	
	input, table, tr, td, pre {
		font-size:10pt;
		color:#000;
		font-family:Courier New, Courier, monospace;
		text-shadow:0px 0px 10px #fff;
	}
	
	button {
		font-size:10pt;
		font-weight:bold;
		font-family:Courier New, Courier, monospace;
		text-shadow:0px 0px 10px #fff;
	}
	
	.first {
		background-color:#222;
	}
	
	table {
		margin-top:60px;
		border:1px #000000 dotted;
	}
	
	tbody {
		display: table-row-group;
		vertical-align: middle;
		border-color: inherit;
	}
	
	a {
		color:white;
		text-decoration: none;
	}
	
	a:hover{
		color:#C0C0C0;
		text-shadow:0px 0px 10px #ffffff;
	}
	
	input,select,textarea{
		border: 1px #000000 solid;
		-moz-border-radius: 5px;
		-webkit-border-radius:5px;
		border-radius:5px;
	}
	
	h1 { 
		color: #111; 
		font-family: 'Helvetica Neue', sans-serif; 
		font-size: 45px; 
		font-weight: bold; 
		letter-spacing: -1px; 
		line-height: 1; 
		text-align: center;
	}
	
	.blink_text {
		text-shadow:0px 0px 20px #fff;
			
		-webkit-animation-name: blinker;
		-webkit-animation-duration: 2s;
		-webkit-animation-timing-function: linear;
		-webkit-animation-iteration-count: infinite;

		-moz-animation-name: blinker;
		-moz-animation-duration: 2s;
		-moz-animation-timing-function: linear;
		-moz-animation-iteration-count: infinite;

		animation-name: blinker;
		animation-duration: 2s;
		animation-timing-function: linear;
		animation-iteration-count: infinite;
	}
	
	@-moz-keyframes blinker { 
			0% { opacity: 5.0;
		}
			50% { opacity: 0.0;
		}
			100% { opacity: 5.0;
		}
	}
	
	@-webkit-keyframes blinker { 
			0% { opacity: 5.0;
		}
			50% { opacity: 0.0;
		}
			100% { opacity: 5.0;
		}
	}
	
	@keyframes blinker { 
			0% { opacity: 5.0;
		}
			50% { opacity: 0.0;
		}
			100% { opacity: 5.0;
		}
	}
</style>
</head>
<body>
<table width="700" border="0" cellpadding="3" cellspacing="1" align="center">
	<tr>
		<td style="text-align:center" colspan="3">
			<h1 class="blink_text">PHP Malicious Code Scanner</h1>
		</td>
	</tr>
	<tr>
		<td>
			<form action="" method="POST">
				<font color="white">Find File :</font>
				<input type="text" name="findstr" size="20" placeholder="e.g HaCKeD" value="HaCKeD">
				<button type="submit" name="submit" value="Find">Find</button>
			</form>
		</td>
	</tr>
	<tr class="first">
		<td><center>Name</center></td>
		<td><center>Size</center></td>
		<td><center>Permission</center></td>
	</tr>
	<tr>
		<td colspan="3">
			<div id="findstr">
				<?php 
					foreach ( $arrMalCodes as $key => $value ) {
						echo "<tr>
							<td><a href=?filesrc={$value[fpath]}>".basename($value[fname])."</a></td>
					<td><center>{$value[fsize]}</center></td>
					<td><center><span style='color:#009900;'>{$value[finfo]}</span></center></td>
						</tr>";
					}
				?>
			</div>
		</td>
	</tr>
	<tr>
		<td class="first" colspan="3">
			<div align="center">&copy;<?php echo date('Y'); ?>, <a href="//zone-h.org/archive/notifier=c0d3Lib" target='_blank'>c0d3Lib</a></div>
		</td>
	</tr>
</table>
<p>&nbsp;</p>
<?php echo $filetxt; ?>
</body>
</html>
