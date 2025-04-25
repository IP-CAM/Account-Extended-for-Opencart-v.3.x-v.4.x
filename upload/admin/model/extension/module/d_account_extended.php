<?php
/**
 * Model Module D.Account Extended Class
 *
 * @version 1.0
 * 
 * @author D.art <d.art.reply@gmail.com>
 */

class ModelExtensionModuleDAccountExtended extends Model {

    /**
     * A list of chosen tables.
     * 
     * @param string $table_name
     * 
     * @return bool $exists
     */
    public function tableExists($table_name) {
        return $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . $table_name . "'")->num_rows > 0;
    }
}