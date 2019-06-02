<?php

/**
 * Class FontsellerModel
 *
 * Basic CRUD Class:
 */

class FontsellerModel {

    function create( $table, $data )
    {
        global $wpdb;

        $wpdb->insert( $table, $data );

        return $wpdb->insert_id
    }

    function read( $table, $where = [] )
    {
        if( is_array( $where ) )
        {
            $w  = [];
            foreach( $where as $key => $value )
            {
                $w[]    = $key . ' = "' . $value . '"';
            }
            $where  = join( ' AND ', $w );
        }
        elseif( is_string( $where ) )
        {
            $where  = ' WHERE ' . $where;
        }
        $sql    = "SELECT * FROM " . $table . $where;
        $_res   = $wpdb->get_results( $sql );

        return $_res;
    }

    function update( $table, $data, $where )
    {
        global $wpdb;

        $wpdb->update( $table, $data, $where );
    }

    function delete( $table, $where = NULL )
    {
        global $wpdb;
        
        if( is_null( $where ) ) return;

        $wpdb->delete( $table, $where );
    }
}