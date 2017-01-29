<?php
/**
 *
 *
 * @author Lukasz Nowicki <jagoo@post.pl>
 *
 */
namespace Phylax\SourceMoz;

class AjaxTest {

    public function is_ajax_call() {
        if (
            isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) &&
            ( strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) === 'xmlhttprequest' )
        ) {
            return true;
        }
        return false;
    }

    public function is_server_call() {
        return true;
    }

    public function is_test_call() {
        if (
            isset( $_GET['ajax-test'] ) &&
            isset( $_SESSION['beloved'] ) &&
            ( $_GET['ajax-test'] == 'proceed' ) &&
            ( $_SESSION['beloved'] == 'Rysio' )
        ) {
            return true;
        }
        return false;
    }

    public function test() {
        if ( !$this->is_server_call() ) { return false; }
        if ( $this->is_test_call() ) {
            return true;
        }
        if ( $this->is_ajax_call() ) {
            return true;
        }
        return false;
    }

}

# EOF