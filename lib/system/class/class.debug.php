<?php
class debug {
    static $html;
    static $error;
    static $debug;
    static $sql;
    static $dump;
    static $start = 0;
    static $timer = array();
    
    public function __construct() {
    
    	error_reporting(E_ERROR | E_PARSE);
        set_error_handler(array($this, 'error'));
        
    }
    
    static function error($errno, $errstr, $errfile = "unknow", $errline = "unknow") {

        switch ($errno):
            case E_USER_ERROR:

                echo "<h1>Fatal error</h1>";
                echo "<p>An unexpected error has occured.</p>";
                echo "<ul>";
                echo "    <li><b>File</b> : ".$errfile."</li>";
                echo "    <li><b>Line</b> : ".$errline."</li>";
                echo "    <li><b>Message</b> : ".$errstr."</li>";
                echo "</ul>";
                
                debug("------------- Fatal Error --------------", 'error');
                debug("File : ".$errfile, 'error');
                debug("Line : ".$errline, 'error');
                debug("Message : ".$errstr, 'error');
                debug("----------------------------------------", 'error');
                
                //exit(1);
            break;

            case E_USER_WARNING:
                $type = 'warning';
                self::$error[] = array('type' => 'warning',
                                       'file' => $errfile,
                                       'line' => $errline,
                                       'str'  => $errstr);
            break;

            case E_USER_NOTICE:
                $type = 'notice';
                self::$error[] = array('type' => 'notice',
                                       'file' => $errfile,
                                       'line' => $errline,
                                       'str'  => $errstr);
            break;
 
            default:
            	$type = $errno;
                self::$error[] = array('type' => $type,
                                       'file' => $errfile,
                                       'line' => $errline,
                                       'str'  => $errstr);
            break;
        endswitch;
        
        debug("------------- Error : ".$type."--------------", 'error');
        debug("File : ".$errfile, 'error');
        debug("Line : ".$errline, 'error');
        debug("Message : ".$errstr, 'error');
        debug("----------------------------------------", 'error');

        return true;
    }

    static public function dump($array, $title = false) {
    	$dump = array();
        $dump['title'] = $title;
        $dump['array'] = $array;
        
        self::$dump[]  = $dump;
    }
    
    public static function sql($req, $count) {
	    $sql           = array();
        $sql['req']    = $req;
        $sql['count']  = $count;
        
        self::$sql[] = $sql;
    }
    
    public static function timer($title, $lang = false) {
		$time = explode(" ", microtime());
        $time = ($time[1] + $time[0]);
        
        if(self::$start == 0):
        	self::$start = $time;
        endif;
        
        self::$timer[] = array(
        	'title' => (!$lang) ? $title : lang::text($title),
        	'time'  => $time - self::$start
        );
    }

}
?>
