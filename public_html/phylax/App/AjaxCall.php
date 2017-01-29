<?php
/**
 *
 *
 * @author Lukasz Nowicki <jagoo@post.pl>
 *
 */
namespace Phylax\SourceMoz;

class AjaxCall {

    public $r = null;

    public function set_status( $status ) {
        $this->r['S'] = $status;
    }

    public function set_data( $data ) {
        $this->r['D'] = $data;
    }

    public function set_arg( $arg, $value ) {
        $this->r[ $arg ] = $value;
    }

    public function response() {
        echo json_encode( $this->r , true);
        die;
    }

    public function __construct() {
        $this->r = array(
            'S' => '0',
            'D' => '',
        );
    }

}

# EOF