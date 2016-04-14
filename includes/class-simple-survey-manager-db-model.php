<?php
abstract class SSM_Model {
    static $primary_key = 'id';
    protected static function _table() {
        global $wpdb;
        $tablename = strtolower( get_called_class() );
        $tablename = str_replace( 'ssm_model_', 'ssm_', $tablename );
        return $wpdb->prefix . $tablename;
    }
    private static function _fetch_sql( $value ) {
        global $wpdb;
        $sql = sprintf( 'SELECT * FROM %s WHERE %s = %%s', self::_table(), static::$primary_key );
        return $wpdb->prepare( $sql, $value );
    }
    static function get( $value ) {
        global $wpdb;
        return $wpdb->get_row( self::_fetch_sql( $value ) );
    }
    static function insert( $data ) {
        global $wpdb;
        $wpdb->insert( self::_table(), $data );
    }
    static function update( $data, $where ) {
        global $wpdb;
        $wpdb->update( self::_table(), $data, $where );
    }
    static function delete( $value ) {
        global $wpdb;
        $sql = sprintf( 'DELETE FROM %s WHERE %s = %%s', self::_table(), static::$primary_key );
        return $wpdb->query( $wpdb->prepare( $sql, $value ) );
    }
    static function insert_id() {
        global $wpdb;
        return $wpdb->insert_id;
    }
    static function time_to_date( $time ) {
        return gmdate( 'Y-m-d H:i:s', $time );
    }
    static function now() {
        return self::time_to_date( time() );
    }
    static function date_to_time( $date ) {
        return strtotime( $date . ' GMT' );
    }
}

class SSM_Model_Surveys extends SSM_Model {
    static $primary_key = 'survey_id';

    static function get_by_wp_id($wp_id)
    {
        global $wpdb;
        $sql = sprintf('SELECT * FROM %s WHERE wp_post_id = %s', self::_table(), $wp_id);
        return $wpdb->get_row($sql);
    }
}

class SSM_Model_Questions extends SSM_Model {
    static $primary_key = 'question_id';

    static function delete_all_for_survey_id($survey_id)
    {
        global $wpdb;
        $sql = sprintf( 'DELETE FROM %s WHERE survey_id = %s', self::_table(), $survey_id );
        return $wpdb->query( $sql );
    }
}
?>