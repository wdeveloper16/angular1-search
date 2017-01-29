<?php
/**
 *
 *
 * @author Lukasz Nowicki <jagoo@post.pl>
 *
 */
namespace Phylax\SourceMoz;

class EasyStat {

    public $r = false;
    protected $db;

    public function save_query_visit( $at ) {
        if ( isset( $_SESSION['SearchTerm']['Term'] ) ) {
            $this->db->H->multi_query( implode( ';', array(
                'INSERT INTO `' . PREFIX . 'autocomplete` (`ac_word`,`ac_count`) VALUES("' . $this->db->H->real_escape_string( $_SESSION['SearchTerm']['Term'] ) . '", "1" ) ON DUPLICATE KEY UPDATE `ac_word` = VALUES(`ac_word`), `ac_count` = VALUES(`ac_count`) + 1',
                'INSERT INTO `' . PREFIX . 'user_log` (`user_id`,`user_date`,`user_where`) VALUES ("' . $_SESSION['UserIPHash'] . '", "' . date('Y-m-d') . '", "' . $at . '")',
                'INSERT INTO `' . PREFIX . 'stats_year` (`stat_value`, `stat_count`) VALUES ("' . date('Y') . '", 1 ) ON DUPLICATE KEY UPDATE `stat_count` = `stat_count` + 1',
                'INSERT INTO `' . PREFIX . 'stats_month` (`stat_value`, `stat_count`) VALUES ("' . date('n') . '", 1 ) ON DUPLICATE KEY UPDATE `stat_count` = `stat_count` + 1',
                'INSERT INTO `' . PREFIX . 'stats_day` (`stat_value`, `stat_count`) VALUES ("' . date('j') . '", 1 ) ON DUPLICATE KEY UPDATE `stat_count` = `stat_count` + 1',
                'INSERT INTO `' . PREFIX . 'stats_dow` (`stat_value`, `stat_count`) VALUES ("' . date('w') . '", 1 ) ON DUPLICATE KEY UPDATE `stat_count` = `stat_count` + 1',
                'INSERT INTO `' . PREFIX . 'stats_hour` (`stat_value`, `stat_count`) VALUES ("' . date('G') . '", 1 ) ON DUPLICATE KEY UPDATE `stat_count` = `stat_count` + 1',
            ) ) );
        } else {
            $this->save_visit( $at );
        }
    }
    public function save_visit( $at ) {
        $this->db->H->multi_query( implode( ';', array(
            'INSERT INTO `' . PREFIX . 'user_log` (`user_id`,`user_date`,`user_where`) VALUES ("' . $_SESSION['UserIPHash'] . '", "' . date('Y-m-d') . '", "' . $at . '")',
            'INSERT INTO `' . PREFIX . 'stats_year` (`stat_value`, `stat_count`) VALUES ("' . date('Y') . '", 1 ) ON DUPLICATE KEY UPDATE `stat_count` = `stat_count` + 1',
            'INSERT INTO `' . PREFIX . 'stats_month` (`stat_value`, `stat_count`) VALUES ("' . date('n') . '", 1 ) ON DUPLICATE KEY UPDATE `stat_count` = `stat_count` + 1',
            'INSERT INTO `' . PREFIX . 'stats_day` (`stat_value`, `stat_count`) VALUES ("' . date('j') . '", 1 ) ON DUPLICATE KEY UPDATE `stat_count` = `stat_count` + 1',
            'INSERT INTO `' . PREFIX . 'stats_dow` (`stat_value`, `stat_count`) VALUES ("' . date('w') . '", 1 ) ON DUPLICATE KEY UPDATE `stat_count` = `stat_count` + 1',
            'INSERT INTO `' . PREFIX . 'stats_hour` (`stat_value`, `stat_count`) VALUES ("' . date('G') . '", 1 ) ON DUPLICATE KEY UPDATE `stat_count` = `stat_count` + 1',
        ) ) );
    }

    function __construct( Database $db, $at ) {
        $this->db = $db;
        $at = strtolower( substr( $at, 0, 1 ) );
        switch( $at ) {
            case 'q': # strona wyszukiwania
                $this->save_query_visit( $at );
                $this->r = true;
                break;
            case 'f': # strona tytułowa,
            case 's': # strona statyczna
                $this->save_visit( $at );
                $this->r = true;
                break;
            default:  # nieprawidłowa wartość...
                $this->r = false;
                break;
        }
    }

}

# EOF