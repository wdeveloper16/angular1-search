<?php
/**
 *
 *
 * @author Lukasz Nowicki <jagoo@post.pl>
 *
 */
namespace Phylax\SourceMoz;

class Router {

    public $page = null;
    public $tab = null;
    public $menu_str = null;
    public $input_hidden = null;


    public function __construct() {
	    if ( isset( $_GET['q'] ) && ( $_GET['q'] != '' ) ) {
		    $this->page = PAGE_SERP;
		    $this->set_tab();
	    } elseif ( isset( $_GET['s'] ) && ( $_GET['s'] != '' ) ) {
		    $this->page = PAGE_STATIC;
	    } else {
		    $this->page = PAGE_FRONT;
	    }
    }


	public function set_tab() {
		$t = '';
		if ( isset( $_GET['t'] ) && ( $_GET['t'] != '' ) ) {
			$t = $_GET['t'];
		}
		if ( isset( $_SESSION['Menu']['Matches'][ $t ] ) ) {
			$this->tab = $_SESSION['Menu']['Items'][ $_SESSION['Menu']['Matches'][$t] ];
			$s = $_SESSION['MenuString'];
			$s = str_replace( '@query@', urlencode( $_SESSION['SearchTerm']['Clear'] ), $s );
			$s = str_replace( '%' . $this->tab['ID'] . '%', ' tab_active' , $s );
			foreach( $_SESSION['Menu']['Matches'] as $item ) {
				$s = str_replace( '%' . $item . '%', '', $s );
			}
			$this->menu_str = $s;
			if ( $this->tab['AddParam'] ) {
				$this->input_hidden = '                        <input type="hidden" name="t" value="' . $this->tab['Recognize'] . '">' . PHP_EOL;
			} else {
				$this->input_hidden = '';
			}
		}
	}
}