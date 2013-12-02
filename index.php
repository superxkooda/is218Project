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
			}


			$test=mysql_fetch_assoc (db::query("select * from schools limit 10"));
			print_r($test);



			$program = new program();

			 class program {

				function __construct() {

					$page = isset($_POST['mode']) ? $_POST['mode'] : isset($_REQUEST['page'])? $_REQUEST['page'] : "home";
  // $arg = $_REQUEST['arg'];
					echo $page;
					$page = new $page();	
					print_r($_POST);

				}

				function __destruct() {
					
				}


				

			}
			//base page element
			abstract class page
			{				//classname => link name
			 	protected $menu = array('home' => 'Home' , 'login' => 'Login',
			 				 'create' => 'Create Account','transInfo'=>'Transaction Info', /*'forgotPass' => 'Forgot Password',*/ //easy removal of menu items 
			 				 'trans' => 'Transactions' );

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

			class login extends page  
			{
				public $name='login';
				
				function __destruct()
				{ //lets build our form
					$this->page.="
							<div class='col-md-4' style='margin:0 auto; float:none;'>
								<form  class='form-horizontal' role='form' method='post'>
									<input type='hidden' name='mode' value='validate'/>
									<div class='form-group'>
									<input id='username' class='form-control' type='text' placeholder='Username' name='username'/>
									</div>
									<div class='form-group'>
									<input id='password' class='form-control' type='password' placeholder='Password' name='password'/>
									</div>

									<input class='btn btn-default' type='submit'/>
									<a href='?page=forgotPass' style='float:right;'>Forgot Password?</a>
								 </form>
							</div>";

					page::__destruct();	

				}

			}

			class create extends page 
			{
				public $name='create';
				
				function __destruct()
				{
					$this->page.="
					<div class='col-md-4' style='margin:0 auto; float:none;'>
								<form  class='form-horizontal' role='form' method='post'>
									<input type='hidden' name='mode' value='newAccount'/>
									<div class='form-group'>
									<input id='username' class='form-control' type='text' placeholder='Username' name='username'/>
									</div>
									<div class='form-group'>
									<input id='email' class='form-control' type='email' placeholder='Email' name='email'/>
									<input id='email' class='form-control' type='email' placeholder='Repeat Email' name='rEmail'/>
									</div>
									<div class='form-group'>
									<input id='password' class='form-control' type='password' placeholder='Password' name='password'/>
									<input id='rPassword' class='form-control' type='password' placeholder='Repeat Password' name='rPassword'/>
									</div>

									<input class='btn btn-default' type='submit'/>
								 </form>
							</div>";;
					page::__destruct();	
				}

			}

			class forgotPass extends page 
			{
				public $name='forgotPass';
				
				function __destruct()
				{
					$this->page.="
								<div class='col-md-4' style='margin:0 auto; float:none;'>
								<h2>Please enter in your email.</h2>
									<form  class='form-horizontal' role='form' method='post'>
									<input type='hidden' name='mode' value='forgotPassConf'/>
										<div class='form-group'>
											<input id='email' class='form-control' type='Email' placeholder='Email' name='email'/>
										</div>
										<div class='form-group'>
											<input class='btn btn-default' type='submit'/>
										</div>
									</form>
								</div>

					";
					page::__destruct();	
				}

			}

			class transInfo extends page 
			{
				public $name='transInfo';

				
				function __destruct()
				{
					$this->page.="
								<div class='col-md-4' style='margin:0 auto; float:none;'>

									<h2>Transaction Info</h2>
									<table border=1>
											<tr><th>Date</th><th>Source</th><th>Destenation</th><th>Type</th><th>Amount</th></tr>
											<tr><td>NOW</td><td>./*</td><td>/home/me/</td><td>cp</td><td>^*.$</td></tr>
									</table>
								</div>
					";
					page::__destruct();	
				}

			}


	class trans extends page 
			{
				public $name='Trans';

				
				function __destruct()
				{
					$this->page.="
								<div class='col-md-4' style='margin:0 auto; float:none;'>
									<h2>Please enter an amount.</h2>
									<form  class='form-horizontal' role='form' method='post'>
									<input type='hidden' name='mode' value='transConf'/>
									<div class='form-group'>
											<select class='form-control' style='width:100px'>
												<option>Credit</option>
												<option>Debit</option>
											</select>
										</div>
										<div class='form-group'>
											<input id='transAmount' class='form-control' type='text' placeholder='$$$' name='transAmount'/>
										</div>
										<div class='form-group'>
											<input class='btn btn-default' type='submit'/>
										</div>
									</form>
								</div>


					";
					page::__destruct();	
				}

			}

		class validate extends page 
		//validates a user when they log in
		{
			public $name='validate';
			function __construct()
			{
				page::__construct();
				echo $this->name;
			}
		}


		class newAccount extends page
		//checks to see if the account is available then gives the proper feedback
		{

			public $name='newAccount';
			function __construct()
			{
				page::__construct();
				echo $this->name;
			}
		}
		

		class transConf extends page
		//conferms the transaction was processed
		{
			public $name='transConf';
			function __construct()
			{
				page::__construct();
				echo $this->name;
			}
		}

		class forgotPassConf
		//conferms the forgotten password submission
		{
		public	$name='forgotPassConf';
			function __construct()
			{
				page::__construct();
				echo $this->name;
			}
		}



			?>
		</body>
		</html>