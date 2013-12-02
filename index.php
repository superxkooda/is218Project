<html lang='en'>

<head>
	<title>cs218</title>
	<link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">
	<!-- // <script type="text/javascript" src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js" ></script> -->
</head>

<body>
	<header>
		<nav class="navbar navbar-default" role="navigation">
			

			<?php 

			require_once('./dbpw.php'); //holds login cridentials for mysql database
												/*looks like 
										class dbpw
										{
											public static $hostname;
											public static $database;
											public static $username;
											public static $password;
										}*/
			class db extends dbpw
			{
				public static function connect()
				{
					$db_server = mysql_connect(self::$hostname, self::$username, self::$password); //loging onto server with error cheching
					if (!$db_server) die("unable to connect to server!" .mysql_error()); 
					// echo "connected to db <br/> ---------------------------------- <br/>";
					mysql_select_db(self::$database)
					or die("unable to select database! " . mysql_error()); 
					// echo "database selected <br/>-----------------------------------<br/>";
					return $db_server;
				}
	
				public static function close($db)//just a shorthand for closing db
				{
					mysql_close($db);
				}

				public static function query($query)//not very creative but should do the trick
				{
					$db = self::connect();
					$results= mysql_query($query);
					if($results)
						return $results;
					else
						echo "bad query!" . mysql_error();
				}

				public static function buildHtmlTable($titles, $results)
				{
					$table="<table boarder='1'>";
					 $a=mysql_fetch_assoc($results);
					 if(sizeof($titles)!=sizeof($a))
					 	die("your number of titles and colums do not match!\n");
						else
						{
							$x=sizeof($titles);
							$table.="<tr>";
							for ($i=0; $i < $x; $i++) 
							{ 
								$table.="<th>".$titles[$i]."</th>";
							}
							$table.="</tr>";

							do
							{
								$table.="<tr>";
								foreach ($a as $value) {
									$table.="<td>$value</td>";
								}
								$table.="</tr>";

							} while($a=mysql_fetch_assoc($results));

							$table.="</table>";

							return $table;
						}
				}
			}

		



			$program = new program();

			 class program {

				function __construct() {

					$page = isset($_POST['mode']) ? $_POST['mode'] : isset($_REQUEST['page'])? $_REQUEST['page'] : "home";
  // $arg = $_REQUEST['arg'];
					$page = new $page();	

				}

				function __destruct() {
					
				}


				

			}
			//base page element
			abstract class page
			{				//classname => link name
			 	protected $menu = array('home' => 'Home' , 'q1' => 'q1','q2' => 'q2',
			 		'q3' => 'q3','q5' => 'q5','q6' => 'q6','q7' => 'q7',
			 		'q8' => 'q8','q9' => 'q9','q10' => 'q10','q11' => 'q11','q12' => 'q12' );

			 	protected $page ="<div class='container'>";

			 	function __construct()
				{
					$tmpIndex = array_search($this->name, array_keys($this->menu));
					array_splice($this->menu, $tmpIndex, 1);
					$this->buildMenu();

				}

			 	protected function buildMenu()
				{
					$tmpMenu='<ul class ="nav navbar-nav">';
					foreach ($this->menu as $k => $v) //grabbing array_keys and value
					{
   					 $tmpMenu.= "<li><a href='?page=$k'>$v</a></li>";
   					}
   					$tmpMenu.='</ul></nav></header>';
   					echo $tmpMenu;
				}
				function __destruct()
				{
					$this->page.='</div>';//close our container
					echo $this->page;

				}
			}

			class home extends page 
			{
				public $name='home';

				

				function __destruct()
				{
					$this->page.='<p>Welcome to my home page</p>';
					page::__destruct();	
				}
			}
			

			class q1 extends page
			{
				public $name='';
				//Create a web page that shows the colleges that have the highest enrollment

				function __destruct()
				{
					$query="select schools.INSTNM, enrolment.EFYTOTLE, enrolment.YEAR FROM enrolment ".
				" join schools on enrolment.UNITID=schools.UNITID "
				. " order by enrolment.EFYTOTLE desc limit 10;";
				$titles=["School","Enrolment", "Year"];
					$results=db::query($query);
					$this->page.=db::buildHtmlTable($titles,$results);
					//$this->page.=print_r(mysql_fetch_assoc($results));
					page::__destruct();	
				}
			}
				class q2 extends page
			{
				public $name='q2';

				

				function __destruct()
				{
					$query="select schools.INSTNM, stats.LIABILITIES, stats.YEAR ".
					"from stats join schools on stats.UNITID = schools.UNITID ".
					"order by LIABILITIES desc limit 10;";
					$titles=["School","Liabilities", "Year"];
					$results=db::query($query);
					$this->page.=db::buildHtmlTable($titles,$results);
					page::__destruct();	
				}
			}

				class q3 extends page
			{
				public $name='q3';

				

				function __destruct()
				{
					$query="select schools.INSTNM, enrolment.EFYTOTLE, enrolment.YEAR FROM enrolment  join schools on enrolment.UNITID=schools.UNITID  order by enrolment.EFYTOTLE desc limit 10;";


					$titles=["School","Assets", "Year"];
					$results=db::query($query);
					$this->page.=db::buildHtmlTable($titles,$results);
					page::__destruct();	
				}
			}

			

				class q5 extends page
			{
				public $name='home';

				

				function __destruct()
				{
					$query="select schools.INSTNM, stats.TOTALREV, stats.YEAR from stats join schools on stats.UNITID = schools.UNITID order by stats.TOTALREV desc limit 10;";

					$titles=["School","Revenues", "Year"];
					$results=db::query($query);
					$this->page.=db::buildHtmlTable($titles,$results);
					page::__destruct();	
				}
			}

				class q6 extends page
			{
				public $name='q6';

				

				function __destruct()
				{
					$query = "select schools.INSTNM, stats.TOTALREV/ enrolment.EFYTOTLE as REVPER, stats.YEAR FROM stats  join schools on stats.UNITID = schools.UNITID join enrolment on stats.UNITID = enrolment.UNITID where stats.YEAR= enrolment.YEAR  order by REVPER desc limit 10;";
					$titles=["School","Revenues Per Student", "Year"];
					$results=db::query($query);
					$this->page.=db::buildHtmlTable($titles,$results);
					page::__destruct();	
				}
			}
			

				class q7 extends page
			{
				public $name='home';

				

				function __destruct()
				{
					$this->page.='<p>Welcome to my home page</p>';
					page::__destruct();	
				}
			}

				class q8 extends page
			{
				public $name='home';

				

				function __destruct()
				{
					$this->page.='<p>Welcome to my home page</p>';
					page::__destruct();	
				}
			}

				class q9 extends page
			{
				public $name='home';

				

				function __destruct()
				{
					$this->page.='<p>Welcome to my home page</p>';
					page::__destruct();	
				}
			}

				class q10 extends page
			{
				public $name='home';

				

				function __destruct()
				{
					$this->page.='<p>Welcome to my home page</p>';
					page::__destruct();	
				}
			}

	class q11 extends page
			{
				public $name='home';

				

				function __destruct()
				{
					$this->page.='<p>Welcome to my home page</p>';
					page::__destruct();	
				}
			}
				class q12 extends page
			{
				public $name='home';

				

				function __destruct()
				{
					$this->page.='<p>Welcome to my home page</p>';
					page::__destruct();	
				}
			}

		

			?>
		</body>
		</html>