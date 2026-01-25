<?php defined('BASEPATH') OR exit('No direct script access allowed');
/* 
* ------------------------------------------
* Developed by <sourav.diubd@gmail.com>
* ------------------------------------------
*/

class SyncData
{  
	private $incomingDir  = './assets/data/incoming/';

	public function importSQL()
	{  
		$ci =& get_instance();
		
		// Use CodeIgniter's existing database connection instead of creating a new one
		// This ensures proper connection management and prevents connection leaks
		$mysqli = NULL;
		$connection_created = false;
		
		try {
			// Check if CodeIgniter's database connection exists and is mysqli
			if (isset($ci->db) && isset($ci->db->conn_id) && $ci->db->conn_id instanceof mysqli) {
				// Reuse existing CodeIgniter connection
				$mysqli = $ci->db->conn_id;
			} else {
				// Fallback: Create a new connection only if CodeIgniter's connection is not available
				// This should rarely happen, but ensures compatibility
				$mysqli = mysqli_connect( 
					$ci->db->hostname, 
					$ci->db->username, 
					$ci->db->password, 
					$ci->db->database
				);
				$connection_created = true;
				
				/* check connection */
				if ($mysqli === false || mysqli_connect_errno()) {
					$error = mysqli_connect_error();
					log_message('error', 'SyncData: Connection failed - ' . $error);
					return false;
				}
			}

			$filePath = $this->incomingDir.'backup.sql';
			
			if (!file_exists($filePath)) {
				log_message('error', 'SyncData: SQL file not found at ' . $filePath);
				return false;
			}

			$sql = file_get_contents($filePath);
			
			if ($sql === false || empty($sql)) {
				log_message('error', 'SyncData: Unable to read SQL file or file is empty');
				return false;
			}

			/* execute multi query */
			if ($mysqli->multi_query($sql)) {
				// Process all results to avoid "Commands out of sync" errors
				do {
					// Store result if available
					if ($result = $mysqli->store_result()) {
						$result->free();
					}
				} while ($mysqli->next_result());
				
				// Check for errors after processing all queries
				if ($mysqli->errno) {
					log_message('error', 'SyncData: Multi-query error - ' . $mysqli->error);
					return false;
				}
				
				@unlink($filePath); 
				return true;
			} else {
				log_message('error', 'SyncData: Multi-query failed - ' . $mysqli->error);
				return false; 
			}

		} catch (Exception $e) {
			log_message('error', 'SyncData: Exception - ' . $e->getMessage());
			return false;
		} finally {
			// Only close connection if we created it (not if we reused CodeIgniter's connection)
			if ($connection_created && $mysqli !== NULL && $mysqli instanceof mysqli) {
				$mysqli->close();
			}
		}
    }

}
 
