<?php
defined('BASEPATH') or exit ('No direct script access allowed');

/**
 * Database Configuration
 * 
 * IMPORTANT: This is the ONLY file where database connections should be configured.
 * All database connections MUST go through CodeIgniter's database abstraction layer.
 * 
 * DO NOT create direct mysqli_connect() or mysql_connect() calls elsewhere in the codebase.
 * Always use $this->db or get_instance()->db to access the database connection.
 * 
 * Connection Management:
 * - pconnect = FALSE: Prevents persistent connections which can lead to connection leaks
 * - CodeIgniter automatically manages connection lifecycle
 * - Connections are reused within the same request
 */

$active_group = 'default';
$query_builder = TRUE;

$db['default'] = array(
    'dsn' => '',
    'hostname' => 'localhost',
    'username' => 'root',
    'password' => 'password',
    'database' => 'npm_dashboard',
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    // CRITICAL: Keep pconnect as FALSE to prevent connection leaks
    // Persistent connections can accumulate and cause "Too many connections" errors
    'pconnect' => FALSE,
    'db_debug' => (ENVIRONMENT !== 'production'),
    'cache_on' => FALSE,
    'cachedir' => 'cache',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'encrypt' => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE
);
