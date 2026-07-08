<?php
error_reporting(0);
/**
 * PHPSense Pagination Class
 *
 * PHP tutorials and scripts
 *
 * @package     PHPSense
 * @author      Jatinder Singh Thind
 * @copyright   Copyright (c) 2006, Jatinder Singh Thind
 * @link        http://www.phpsense.com
 */

/**
 * Class Paginator
 *
 * 2017-01-01 oop overhaul bu zvax after a question on stack overflow
 * no copyright infringements are intended, but considering the ancientness of this code
 * I figure this is fair game
 */

// ------------------------------------------------------------------------
$filen = explode('?',basename($url));
	$arg = explode('&',$_SERVER['QUERY_STRING']);

	foreach($arg as $key => $arg_val){
		global $final_url;
		if(!preg_match('/Page/i',$arg_val)){
			$final_url[] = $arg_val."&";
		}
	}
	$final_url1 = join('',$final_url);
	$q1 = "$filen[0]?".$final_url1."Page=";
	$q2 = "";
	//echo $q1;
	//echo $q2;
class PS_Pagination
{
    private $php_self;
    private $rows_per_page = 10; //Number of records to display per page
    private $total_rows = 0; //Total number of rows returned by the query
    private $links_per_page = 5; //Number of links to display per page
    private $append = ""; //Paremeters to append to pagination links
    private $sql = "";
    private $debug;
    private $conn = false;
    private $Page = 1;
    private $max_pages = 0;
    private $offset = 0;

    /**
     * Constructor
     *
     * @param mysqli $connection Mysql connection link
     * @param string $sql SQL query to paginate. Example : SELECT * FROM users
     * @param integer $rows_per_page Number of records to display per page. Defaults to 10
     * @param integer $links_per_page Number of links to display per page. Defaults to 5
     * @param string $append Parameters to be appended to pagination links
     * @param boolean $debug
     */

    public function __construct(
        mysqli $connection,
        $sql,
        $rows_per_page = 10,
        $links_per_page = 5,
        $append = "",
        $debug = false
    )
    {
        $this->conn = $connection;
        $this->sql = $sql;
		//echo $this->sql;
        $this->rows_per_page = (int)$rows_per_page;
        $this->debug = $debug;
        if (intval($links_per_page) > 0)
        {
            $this->links_per_page = (int)$links_per_page;
        }
        else
        {
            $this->links_per_page = 5;
        }
        $this->append = $append;
        $this->php_self = htmlspecialchars($_SERVER['PHP_SELF']);
        if (isset($_GET['Page']))
        {
            $this->Page = intval($_GET['Page']);
        }
    }

    /**
     * Executes the SQL query and initializes internal variables
     *
     * @access public
     * @return resource
     */
    public function paginate()
    {
		global $q1,$q2;
        // Check for valid mysql connection
        // no longer needed as we typehint the connection object,
        // ensuring we receive a connection or an error will be thrown
		/*if(!$this->conn) {
			echo "Error Returned";
		}*/
//echo $this->sql;
        // Find total number of rows
        $all_rs = $this->conn->query($this->sql);

        // the connection should be built with the throw exceptions param
        // so no need to verify manually, error reporting will take care of it

        $this->total_rows = $all_rs->num_rows;
//echo $this->total_rows;
        //Return FALSE if no rows found
        if ($this->total_rows == 0)
        {
            if ($this->debug)
            {
                echo "Query returned zero rows.";
            }
            return FALSE;
        }

        //Max number of pages
        $this->max_pages = ceil($this->total_rows / $this->rows_per_page);
        if ($this->links_per_page > $this->max_pages)
        {
            $this->links_per_page = $this->max_pages;
        }

        //Check the page value just in case someone is trying to input an aribitrary value
        if ($this->Page > $this->max_pages || $this->Page <= 0)
        {
            $this->Page = 1;
        }

        //Calculate Offset
        $this->offset = $this->rows_per_page * ($this->Page - 1);

        //Fetch the required result set
        $rs = $this->conn->query($this->sql . " LIMIT {$this->offset}, {$this->rows_per_page}");
        if (!$rs)
        {
            if ($this->debug)
            {
                echo "Pagination query failed. Check your query.<br /><br />Error Returned: " . mysqli_connect_error();
            }
            return false;
        }
        return $rs;//->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Display the link to the first page
     *
     * @access public
     * @param string $tag Text string to be displayed as the link. Defaults to 'First'
     * @return string
     */
    public function renderFirst($tag="<span class=\"textcon\">First</span>")
    {
		global $q1,$q2;
        
        if($this->Page == 1) {
			return $tag;
		}
        else
        {
           // return '"next"><a href="' . $this->php_self . '?page=1&amp;' . $this->append . '">' . $tag . '</a> ';
			return "<a href=\"".$q1."1".$this->append."".$q2."\" class=\"textcon\"><span class\"textcon\">".$tag."</span></a>";
			//return "<a href=\"".$this->php_self."?Page=1&".$this->append."\" class=\"textcon\"><span class\"textcon\">".$tag."</span></a>";
        }
    }

    /**
     * Display the link to the last page
     *
     * @access public
     * @param string $tag Text string to be displayed as the link. Defaults to 'Last'
     * @return string
     */
    public function renderLast($tag="<span class=\"textcon\">Last</span>") 
    {
        global $q1,$q2;
		
        if($this->Page == $this->max_Pages) {
			return $tag;
		}
		else {
			//return "<a href=\"".$this->php_self."?Page=".$this->max_Pages."&".$this->append."\" class=\"textcon\">".$tag."</a>";
         return "<a href=\"".$q1."".$this->max_Pages."".$this->append."".$q2."\" class=\"textcon\">".$tag."</a>";}
    }

    /**
     * Display the next link
     *
     * @access public
     * @param string $tag Text string to be displayed as the link. Defaults to '>>'
     * @return string
     */
    public function renderNext($tag="<span class=\"textcon\">&gt;&gt;</span>") 
    {
        global $q1,$q2;
		
        if ($this->Page < $this->max_pages)
        {
          return "<a href=\"".$q1."".($this->Page+1)."".$this->append."".$q2."\" class=\"textcon\">".$tag."</a>";
		   //return "<a href=\"".$this->php_self."?Page=".($this->Page+1)."&".$this->append."\" class=\"textcon\">".$tag."</a>";
        }
        else
        {
            return $tag;
        }
    }

    /**
     * Display the previous link
     *
     * @access public
     * @param string $tag Text string to be displayed as the link. Defaults to '<<'
     * @return string
     */
    public function renderPrev($tag="<span class=\"textcon\">&lt;&lt;</span>") 
    {
        global $q1,$q2;
		
        if($this->Page > 1) {
			//return "<a href=\"".$this->php_self."?Page=".($this->Page-1)."&".$this->append."\" class=\"textcon\">".$tag."</a>";
			return "<a href=\"".$q1."".($this->Page-1)."".$this->append."".$q2."\" class=\"textcon\">".$tag."</a>";
		}
        else
        {
            return $tag;
        }
    }

    /**
     * Display the page links
     *
     * @access public
     * @return string
     */
    public function renderNav($prefix = '<span class="page_link">', $suffix = '</span>')
    {
        global $q1,$q2;
		
        $batch = ceil($this->Page / $this->links_per_page);
        $end = $batch * $this->links_per_page;
        if ($end == $this->Page)
        {
            //$end = $end + $this->links_per_page - 1;
            //$end = $end + ceil($this->links_per_page/2);
        }
        if ($end > $this->max_pages)
        {
            $end = $this->max_pages;
        }
        $start = $end - $this->links_per_page + 1;
        $links = '';

        for ($i = $start; $i <= $end; $i++)
        {
            if ($i == $this->Page)
            {
                $links .= "<span class=\"nav_list1\">$i</span> ";
            }
            else
            {
               $links .= "<a href=\"".$q1."".$i."".$this->append."".$q2."\" class=\"nav_list\">".$i."</span></a> ";
			   //$links .= "<a href=\"$this->php_self?Page=".$i."&".$this->append."\" class=\"nav_list\">".$i."</span></a> ";
            }
        }

        return $links;
    }

    /**
     * Display full pagination navigation
     *
     * @access public
     * @return string
     */
    public function renderFullNav()
    {
		global $q1,$q2;
        return $this->renderFirst() . '&nbsp;' . $this->renderPrev() . '&nbsp;' . $this->renderNav(
            ) . '&nbsp;' . $this->renderNext() . '&nbsp;' . $this->renderLast();
    }
}