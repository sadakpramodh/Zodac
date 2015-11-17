<?php
require_once("configurations.php");
require_once("functions.php");
session_start();
session_check();
check_email_verified($_SESSION["email_id"]);
$page_name="code_editor.php";
if(CODE_EDITOR != 1){redirect_to("404.php");}
require_once("comns/pagelogic.php");
?>
<?php
$connection=databaseconnectivity_open();
$query = "select * from roles where user_id=\"{$_SESSION["user_id"]}\" LIMIT 1";
$result = mysqli_query($connection, $query);
while($row = mysqli_fetch_assoc($result))
{
	if(ROLE_NO_ADMIN !=$row["role_id"])
	{
		redirect_to("index.php");
	}
	if(ROLE_NAME_ADMIN !=$row["role_name"])
	{
		redirect_to("index.php");
	}
	
}

mysqli_free_result($result);
databaseconnectivity_close($connection);

?>
<?php
ini_set('open_basedir', dirname(__FILE__) . DIRECTORY_SEPARATOR);

class fs
{
	protected $base = null;

	protected function real($path) {
		$temp = realpath($path);
		if(!$temp) { throw new Exception('Path does not exist: ' . $path); }
		if($this->base && strlen($this->base)) {
			if(strpos($temp, $this->base) !== 0) { throw new Exception('Path is not inside base ('.$this->base.'): ' . $temp); }
		}
		return $temp;
	}
	protected function path($id) {
		$id = str_replace('/', DIRECTORY_SEPARATOR, $id);
		$id = trim($id, DIRECTORY_SEPARATOR);
		$id = $this->real($this->base . DIRECTORY_SEPARATOR . $id);
		return $id;
	}
	protected function id($path) {
		$path = $this->real($path);
		$path = substr($path, strlen($this->base));
		$path = str_replace(DIRECTORY_SEPARATOR, '/', $path);
		$path = trim($path, '/');
		return strlen($path) ? $path : '/';
	}

	public function __construct($base) {
		$this->base = $this->real($base);
		if(!$this->base) { throw new Exception('Base directory does not exist'); }
	}
	public function lst($id, $with_root = false) {
		$dir = $this->path($id);
		$lst = @scandir($dir);
		if(!$lst) { throw new Exception('Could not list path: ' . $dir); }
		$res = array();
		foreach($lst as $item) {
			if($item == '.' || $item == '..' || $item === null) { continue; }
			$tmp = preg_match('([^ a-zа-я-_0-9.]+)ui', $item);
			if($tmp === false || $tmp === 1) { continue; }
			if(is_dir($dir . DIRECTORY_SEPARATOR . $item)) {
				$res[] = array('text' => $item, 'children' => true,  'id' => $this->id($dir . DIRECTORY_SEPARATOR . $item), 'icon' => 'folder');
			}
			else {
				$res[] = array('text' => $item, 'children' => false, 'id' => $this->id($dir . DIRECTORY_SEPARATOR . $item), 'type' => 'file', 'icon' => 'file file-'.substr($item, strrpos($item,'.') + 1));
			}
		}
		if($with_root && $this->id($dir) === '/') {
			$res = array(array('text' => basename($this->base), 'children' => $res, 'id' => '/', 'icon'=>'folder', 'state' => array('opened' => true, 'disabled' => true)));
		}
		return $res;
	}
	public function data($id) {
		if(strpos($id, ":")) {
			$id = array_map(array($this, 'id'), explode(':', $id));
			return array('type'=>'multiple', 'content'=> 'Multiple selected: ' . implode(' ', $id));
		}
		$dir = $this->path($id);
		if(is_dir($dir)) {
			return array('type'=>'folder', 'content'=> $id);
		}
		if(is_file($dir)) {
			$ext = strpos($dir, '.') !== FALSE ? substr($dir, strrpos($dir, '.') + 1) : '';
			$dat = array('type' => $ext, 'content' => '');
			switch($ext) {
				case 'txt':
				case 'text':
				case 'md':
				case 'js':
				case 'json':
				case 'css':
				case 'html':
				case 'htm':
				case 'xml':
				case 'c':
				case 'cpp':
				case 'h':
				case 'sql':
				case 'log':
				case 'py':
				case 'rb':
				case 'htaccess':
				case 'php':
					$dat['content'] = file_get_contents($dir);
					$dat['dir_name']= $dir;
					break;
				case 'jpg':
				case 'jpeg':
				case 'gif':
				case 'png':
				case 'bmp':
					$dat['content'] = 'data:'.finfo_file(finfo_open(FILEINFO_MIME_TYPE), $dir).';base64,'.base64_encode(file_get_contents($dir));
					break;
				default:
					$dat['content'] = 'File not recognized: '.$this->id($dir);
					break;
			}
			return $dat;
		}
		throw new Exception('Not a valid selection: ' . $dir);
	}
	public function create($id, $name, $mkdir = false) {
		$dir = $this->path($id);
		if(preg_match('([^ a-zа-я-_0-9.]+)ui', $name) || !strlen($name)) {
			throw new Exception('Invalid name: ' . $name);
		}
		if($mkdir) {
			mkdir($dir . DIRECTORY_SEPARATOR . $name);
		}
		else {
			file_put_contents($dir . DIRECTORY_SEPARATOR . $name, '');
		}
		return array('id' => $this->id($dir . DIRECTORY_SEPARATOR . $name));
	}
	public function rename($id, $name) {
		$dir = $this->path($id);
		if($dir === $this->base) {
			throw new Exception('Cannot rename root');
		}
		if(preg_match('([^ a-zа-я-_0-9.]+)ui', $name) || !strlen($name)) {
			throw new Exception('Invalid name: ' . $name);
		}
		$new = explode(DIRECTORY_SEPARATOR, $dir);
		array_pop($new);
		array_push($new, $name);
		$new = implode(DIRECTORY_SEPARATOR, $new);
		if($dir !== $new) {
			if(is_file($new) || is_dir($new)) { throw new Exception('Path already exists: ' . $new); }
			rename($dir, $new);
		}
		return array('id' => $this->id($new));
	}
	public function remove($id) {
		$dir = $this->path($id);
		if($dir === $this->base) {
			throw new Exception('Cannot remove root');
		}
		if(is_dir($dir)) {
			foreach(array_diff(scandir($dir), array(".", "..")) as $f) {
				$this->remove($this->id($dir . DIRECTORY_SEPARATOR . $f));
			}
			rmdir($dir);
		}
		if(is_file($dir)) {
			unlink($dir);
		}
		return array('status' => 'OK');
	}
	public function move($id, $par) {
		$dir = $this->path($id);
		$par = $this->path($par);
		$new = explode(DIRECTORY_SEPARATOR, $dir);
		$new = array_pop($new);
		$new = $par . DIRECTORY_SEPARATOR . $new;
		rename($dir, $new);
		return array('id' => $this->id($new));
	}
	public function copy($id, $par) {
		$dir = $this->path($id);
		$par = $this->path($par);
		$new = explode(DIRECTORY_SEPARATOR, $dir);
		$new = array_pop($new);
		$new = $par . DIRECTORY_SEPARATOR . $new;
		if(is_file($new) || is_dir($new)) { throw new Exception('Path already exists: ' . $new); }

		if(is_dir($dir)) {
			mkdir($new);
			foreach(array_diff(scandir($dir), array(".", "..")) as $f) {
				$this->copy($this->id($dir . DIRECTORY_SEPARATOR . $f), $this->id($new));
			}
		}
		if(is_file($dir)) {
			copy($dir, $new);
		}
		return array('id' => $this->id($new));
	}
}



if(isset($_GET['operation'])) {
	$fs = new fs(dirname(__FILE__) . DIRECTORY_SEPARATOR);
	try {
		$rslt = null;
		switch($_GET['operation']) {
			case 'get_node':
				$node = isset($_GET['id']) && $_GET['id'] !== '#' ? $_GET['id'] : '/';
				$rslt = $fs->lst($node, (isset($_GET['id']) && $_GET['id'] === '#'));
				break;
			case "get_content":
				$node = isset($_GET['id']) && $_GET['id'] !== '#' ? $_GET['id'] : '/';
				$rslt = $fs->data($node);
				break;
			case 'create_node':
				$node = isset($_GET['id']) && $_GET['id'] !== '#' ? $_GET['id'] : '/';
				$rslt = $fs->create($node, isset($_GET['text']) ? $_GET['text'] : '', (!isset($_GET['type']) || $_GET['type'] !== 'file'));
				break;
			case 'rename_node':
				$node = isset($_GET['id']) && $_GET['id'] !== '#' ? $_GET['id'] : '/';
				$rslt = $fs->rename($node, isset($_GET['text']) ? $_GET['text'] : '');
				break;
			case 'delete_node':
				$node = isset($_GET['id']) && $_GET['id'] !== '#' ? $_GET['id'] : '/';
				$rslt = $fs->remove($node);
				break;
			case 'move_node':
				$node = isset($_GET['id']) && $_GET['id'] !== '#' ? $_GET['id'] : '/';
				$parn = isset($_GET['parent']) && $_GET['parent'] !== '#' ? $_GET['parent'] : '/';
				$rslt = $fs->move($node, $parn);
				break;
			case 'copy_node':
				$node = isset($_GET['id']) && $_GET['id'] !== '#' ? $_GET['id'] : '/';
				$parn = isset($_GET['parent']) && $_GET['parent'] !== '#' ? $_GET['parent'] : '/';
				$rslt = $fs->copy($node, $parn);
				break;
			case 'savechanges':
				if(isset($_POST["name"]) && isset($_POST["file_path"]))
					{
						try
							{
							$file_path=$_POST["file_path"];
							$myfile = fopen($file_path, "w") or die("Unable to open file!");
							fwrite($myfile, $_POST["name"]);
							fclose($myfile);
							$rslt = array('status' => "Changes done");
							}
						catch (Exception $e) {
							
						}
					}
				else{
					$rslt = array('status' => "failed to save changes");
				}
				break;
			
			default:
				throw new Exception('Unsupported operation: ' . $_GET['operation']);
				break;
		}
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($rslt);
	}
	catch (Exception $e) {
		header($_SERVER["SERVER_PROTOCOL"] . ' 500 Server Error');
		header('Status:  500 Server Error');
		echo $e->getMessage();
	}
	die();
}
?>
<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo SITE_NAME ; ?> | Code editor</title>

  <?php 
	require_once("comns/head_section.php")
?>
    <link href="css/plugins/jsTree/style.min.css" rel="stylesheet">
    
	
<style>
		
		#tree { float:left; min-width:290px; border-right:1px solid silver; overflow:auto; padding:0px 0; }
		#data { margin-left:310px; }
		#data textarea { margin:0; padding:0; height:100%; width:100%; border:0; background:white; display:block; line-height:18px; resize:none; }
		#data, #code { font: normal normal normal 12px/18px 'Consolas', monospace !important; }

		#tree .folder { background:url('./file_sprite.png') right bottom no-repeat; }
		#tree .file { background:url('./file_sprite.png') 0 0 no-repeat; }
		#tree .file-pdf { background-position: -32px 0 }
		#tree .file-as { background-position: -36px 0 }
		#tree .file-c { background-position: -72px -0px }
		#tree .file-iso { background-position: -108px -0px }
		#tree .file-htm, #tree .file-html, #tree .file-xml, #tree .file-xsl { background-position: -126px -0px }
		#tree .file-cf { background-position: -162px -0px }
		#tree .file-cpp { background-position: -216px -0px }
		#tree .file-cs { background-position: -236px -0px }
		#tree .file-sql { background-position: -272px -0px }
		#tree .file-xls, #tree .file-xlsx { background-position: -362px -0px }
		#tree .file-h { background-position: -488px -0px }
		#tree .file-crt, #tree .file-pem, #tree .file-cer { background-position: -452px -18px }
		#tree .file-php { background-position: -108px -18px }
		#tree .file-jpg, #tree .file-jpeg, #tree .file-png, #tree .file-gif, #tree .file-bmp { background-position: -126px -18px }
		#tree .file-ppt, #tree .file-pptx { background-position: -144px -18px }
		#tree .file-rb { background-position: -180px -18px }
		#tree .file-text, #tree .file-txt, #tree .file-md, #tree .file-log, #tree .file-htaccess { background-position: -254px -18px }
		#tree .file-doc, #tree .file-docx { background-position: -362px -18px }
		#tree .file-zip, #tree .file-gz, #tree .file-tar, #tree .file-rar { background-position: -416px -18px }
		#tree .file-js { background-position: -434px -18px }
		#tree .file-css { background-position: -144px -0px }
		#tree .file-fla { background-position: -398px -0px }
</style>
</head>

<body>

    <div id="wrapper">

		<?php
		require_once("leftnavigationbar.php");
		?>
        <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom">
			<?php
				require_once("search.php");
			?>
       </div>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>Code Viewer & Editor</h2>
                   
                </div>
                 
            </div>
			<input id="stage" type="text" class="form-control" readonly>
        <div class="wrapper wrapper-content  animated fadeInRight">
            <div class="row">
			
				
					 <div id="tree"></div>
				
					<div id="data">
						<div class="content code" style="display:none;"><textarea id="code" name="code"></textarea></div>
						<div class="content folder" style="display:none;"></div>
						<div class="content image" style="display:none; position:relative;"><img src="" alt="" style="display:block; position:absolute; left:50%; top:50%; padding:0; max-height:90%; max-width:90%;" /></div>
						<div class="content default" style="text-align:center;">Select a file from the tree.</div>
			
					</div>
						
               
			   
			   <input type="button" id="saveedit" class="btn btn-outline btn-success block full-width" value="save changes">
			   <form action="code_editor_v2.php" target="_new">
			   <div class="form-group"><label>File path</label>
			   <div class="input-group"><input type="text" class="form-control" id="file_path" name="file_path"><span class="input-group-btn"> <button type="submit" class="btn btn-primary">Edit in another editor
                                        </button></span></div></form>
			  
			   </div>
              
            </div>
        </div>
			<?php
			require_once("footer.php");
			?>

        </div>
		<?php
			require_once("rightsidebar.php");
		?>
        </div>


<?php require_once("user_skin_config.php"); ?>
	<?php 
		require_once("comns/body_section.php")
	?>
	<script>
		$(document).ready(function(){
			$.get('user_skin_config.php?set_site_preferences=lastpage_viewed&value=<?php echo $page_name ?>');
			<?php $content = file_get_contents("js/custom.js.php"); echo $content?>
			
		});
		</script>
	<script src="js/plugins/jsTree/jstree.min.js"></script>
	<style>
    .jstree-open > .jstree-anchor > .fa-folder:before {
        content: "\f07c";
    }

    .jstree-default .jstree-icon.none {
        width: 0;
    }
</style>


    <script>
		$("#saveedit").click(function(event){
			var name= $("#code").val();
			var file_path = $("#file_path").val();
		
			$.post( 
                  "code_editor.php?operation=savechanges",
                  { "name": name ,
					"file_path": file_path},
                  function(data) {
					  if(data) {
							
							$('#stage').val(data.status);
					  }
                  }
               );
			   
			$("#code").val=name;
			   
		});
		$(function () {
			$(window).resize(function () {
				var h = Math.max($(window).height() - 0, 420);
				$('#container, #data, #tree, #data .content').height(h).filter('.default').css('lineHeight', h + 'px');
			}).resize();

			$('#tree')
				.jstree({
					'core' : {
						'data' : {
							'url' : '?operation=get_node',
							'data' : function (node) {
								return { 'id' : node.id };
							},
							'state': {
                        'opened': false
                    },
						},
						'check_callback' : function(o, n, p, i, m) {
							if(m && m.dnd && m.pos !== 'i') { return false; }
							if(o === "move_node" || o === "copy_node") {
								if(this.get_node(n).parent === this.get_node(p).id) { return false; }
							}
							return true;
						},
						'force_text' : true,
						'themes' : {
							'responsive' : false,
							'variant' : 'small',
							'stripes' : false
						}
					},
					'sort' : function(a, b) {
						return this.get_type(a) === this.get_type(b) ? (this.get_text(a) > this.get_text(b) ? 1 : -1) : (this.get_type(a) >= this.get_type(b) ? 1 : -1);
					},
					'contextmenu' : {
						'items' : function(node) {
							var tmp = $.jstree.defaults.contextmenu.items();
							delete tmp.create.action;
							tmp.create.label = "New";
							tmp.create.submenu = {
								"create_folder" : {
									"separator_after"	: true,
									"label"				: "Folder",
									"action"			: function (data) {
										var inst = $.jstree.reference(data.reference),
											obj = inst.get_node(data.reference);
										inst.create_node(obj, { type : "default" }, "last", function (new_node) {
											setTimeout(function () { inst.edit(new_node); },0);
										});
									}
								},
								"create_file" : {
									"label"				: "File",
									"action"			: function (data) {
										var inst = $.jstree.reference(data.reference),
											obj = inst.get_node(data.reference);
										inst.create_node(obj, { type : "file" }, "last", function (new_node) {
											setTimeout(function () { inst.edit(new_node); },0);
										});
									}
								}
							};
							if(this.get_type(node) === "file") {
								delete tmp.create;
							}
							return tmp;
						}
					},
					'types' : {
						'default' : { 'icon' : 'folder' },
						'file' : { 'valid_children' : [], 'icon' : 'file' }
					},
					'unique' : {
						'duplicate' : function (name, counter) {
							return name + ' ' + counter;
						}
					},
					'plugins' : ['state','dnd','sort','types','contextmenu','unique','search']
				})
				.on('delete_node.jstree', function (e, data) {
					$.get('?operation=delete_node', { 'id' : data.node.id })
						.fail(function () {
							data.instance.refresh();
						});
				})
				.on('create_node.jstree', function (e, data) {
					$.get('?operation=create_node', { 'type' : data.node.type, 'id' : data.node.parent, 'text' : data.node.text })
						.done(function (d) {
							data.instance.set_id(data.node, d.id);
						})
						.fail(function () {
							data.instance.refresh();
						});
				})
				.on('rename_node.jstree', function (e, data) {
					$.get('?operation=rename_node', { 'id' : data.node.id, 'text' : data.text })
						.done(function (d) {
							data.instance.set_id(data.node, d.id);
						})
						.fail(function () {
							data.instance.refresh();
						});
				})
				.on('move_node.jstree', function (e, data) {
					$.get('?operation=move_node', { 'id' : data.node.id, 'parent' : data.parent })
						.done(function (d) {
							//data.instance.load_node(data.parent);
							data.instance.refresh();
						})
						.fail(function () {
							data.instance.refresh();
						});
				})
				.on('copy_node.jstree', function (e, data) {
					$.get('?operation=copy_node', { 'id' : data.original.id, 'parent' : data.parent })
						.done(function (d) {
							//data.instance.load_node(data.parent);
							data.instance.refresh();
						})
						.fail(function () {
							data.instance.refresh();
						});
				})
				.on('changed.jstree', function (e, data) {
					if(data && data.selected && data.selected.length) {
						$.get('?operation=get_content&id=' + data.selected.join(':'), function (d) {
							if(d && typeof d.type !== 'undefined') {
								$('#data .content').hide();
								switch(d.type) {
									case 'text':
									case 'txt':
									case 'md':
									case 'htaccess':
									case 'log':
									case 'sql':
									case 'php':
									case 'js':
									case 'json':
									case 'css':
									case 'html':
										$('#data .code').show();
										$('#code').val(d.content);
										$('#file_path').val(d.dir_name);
										break;
									case 'png':
									case 'jpg':
									case 'jpeg':
									case 'bmp':
									case 'gif':
										$('#data .image img').one('load', function () { $(this).css({'marginTop':'-' + $(this).height()/2 + 'px','marginLeft':'-' + $(this).width()/2 + 'px'}); }).attr('src',d.content);
										$('#data .image').show();
										break;
									default:
										$('#data .default').html(d.content).show();
										break;
								}
							}
						});
					}
					else {
						$('#data .content').hide();
						$('#data .default').html('Select a file from the tree.').show();
					}
				});
		});
		
		</script>
		

</body>
</html>
