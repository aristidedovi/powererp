<?php

/** Includes */
require_once DOL_DOCUMENT_ROOT.'/core/lib/files.lib.php';

/**
 * Put your class' description here
 */

class ChatMessageAttachment // extends CommonObject
{

    /** @var DoliDb Database handler */
	private $db;
    /** @var string Error code or message */
	public $error;
    /** @var array Several error codes or messages */
	public $errors = array();
    /** @var int An example ID */
	public $id;
    /** @var mixed An example property */
	public $name;
        /** @var mixed An example property */
	public $type;
        /** @var mixed An example property */
	public $size;

	/**
	 * Constructor
	 *
	 * @param DoliDb $db Database handler
	 */
	public function __construct($db)
	{
		$this->db = $db;

		return 1;
	}

	/**
	 * Load object in memory from database
	 *
	 * @param int $id Id object
	 * @return int <0 if KO, >0 if OK
	 */
	public function fetch($id)
	{
		global $langs;

		$sql = "SELECT a.rowid, a.name, a.size, a.type";
        $sql.= " FROM ".MAIN_DB_PREFIX."chat_msg_attachment as a";
        $sql.= " WHERE a.rowid = ".$id;

		dol_syslog(__METHOD__ . " sql=" . $sql, LOG_DEBUG);
		$resql = $this->db->query($sql);
		if ($resql) {
            $num = $this->db->num_rows($resql);
			if ($num) {
				$obj = $this->db->fetch_object($resql);

				$this->id = $obj->rowid;
				$this->name = $obj->name;
                $this->size = $obj->size;
                $this->type = $obj->type;
				//...
			}
			$this->db->free($resql);

			return $num;
		} else {
			$this->error = "Error " . $this->db->lasterror();
			dol_syslog(__METHOD__ . " " . $this->error, LOG_ERR);

			return -1;
		}
	}

	/**
	 * Create object into database
	 *
	 * @param User $user User that create
	 * @param int $notrigger 0=launch triggers after, 1=disable triggers
	 * @return int <0 if KO, Id of created object if OK
	 */
	public function add($user, $notrigger = 0)
	{
		global $conf, $langs;
		$error = 0;
		$filename = $this->name;

		// Security:
		// Disallow file with some extensions. We rename them.
		// Because if we put the documents directory into a directory inside web root (very bad), this allows to execute on demand arbitrary code.
		if (empty($conf->global->MAIN_DOCUMENT_IS_OUTSIDE_WEBROOT_SO_NOEXE_NOT_REQUIRED))
		{
			if (preg_match('/(\.htm|\.html|\.php|\.pl|\.cgi)$/i', $filename))
			{
				$filename.= '.noexe';
			}
			else if (preg_match('/(\.exe|\.msi|\.bat|\.vbs|\.bin|\.run|\.sh)$/i', $filename))
			{
				$filename.= '.noexe';
				$this->name.= '.noexe';
			}
		}

		// Check parameters
		// Put here code to add control on parameters values
		// Insert request
		$sql = "INSERT INTO " . MAIN_DB_PREFIX . "chat_msg_attachment(";
		$sql.= " name,";
		$sql.= " type,";
		$sql.= " size";

		$sql.= ") VALUES (";
		$sql.= " '" . $this->db->escape($filename) . "',";
		$sql.= " '" . $this->type . "',";
		$sql.= " '" . $this->size . "'";
		$sql.= ")";

		$this->db->begin();

		dol_syslog(__METHOD__ . " sql=" . $sql, LOG_DEBUG);
		$resql = $this->db->query($sql);
		if (! $resql) {
			$error ++;
			$this->errors[] = "Error " . $this->db->lasterror();
		}

		if (! $error) {
			$this->id = $this->db->last_insert_id(MAIN_DB_PREFIX . "chat_msg_attachment");
                        
	        // upload/save attachment
	        if (isset($_FILES['attachment']['tmp_name']) && trim($_FILES['attachment']['tmp_name']))
	        {
	                $dir = $conf->chat->dir_output.'/attachments/';

	                dol_mkdir($dir);

	                if (@is_dir($dir))
	                {
	                        $newfile = $dir.'/'.$this->name;
	                        $result = dol_move_uploaded_file($_FILES['attachment']['tmp_name'], $newfile, 1, 0, $_FILES['attachment']['error'], 1);

	                        if (!$result > 0) {
	                                $langs->load("errors");
	                                setEventMessages($langs->trans("ErrorFailedToSaveFile"), null, 'errors');
	                        }
	                }
	                else
	                {
	                        $error ++;
	                        
	                        $langs->load("errors");
	                        setEventMessages($langs->trans("ErrorFailedToCreateDir", $dir), $mesgs, 'errors');
	                }
	        }

			if (! $notrigger) {
				// Uncomment this and change MYOBJECT to your own tag if you
				// want this action call a trigger.
				//// Call triggers
				//include_once DOL_DOCUMENT_ROOT . "/core/class/interfaces.class.php";
				//$interface=new Interfaces($this->db);
				//$result=$interface->run_triggers('MYOBJECT_CREATE',$this,$user,$langs,$conf);
				//if ($result < 0) { $error++; $this->errors=$interface->errors; }
				//// End call triggers
			}
		}

		// Commit or rollback
		if ($error) {
			foreach ($this->errors as $errmsg) {
				dol_syslog(__METHOD__ . " " . $errmsg, LOG_ERR);
				$this->error.=($this->error ? ', ' . $errmsg : $errmsg);
			}
			$this->db->rollback();

			return -1 * $error;
		} else {
			$this->db->commit();

			return $this->id;
		}
	}

	/**
	 * Delete object in database
	 *
	 * @param User $user User that delete
	 * @param int $notrigger 0=launch triggers after, 1=disable triggers
	 * @return int <0 if KO, >0 if OK
	 */
	public function delete($user, $notrigger = 0)
	{
		global $conf, $langs;
		$error = 0;

		$this->db->begin();

		if (! $error) {

            if (! empty($this->name)) {
                $file = $conf->chat->dir_output.'/attachments/'.$this->name;
                dol_delete_file($file);
            }

			if (! $notrigger) {
				// Uncomment this and change MYOBJECT to your own tag if you
				// want this action call a trigger.
				//// Call triggers
				//include_once DOL_DOCUMENT_ROOT . "/core/class/interfaces.class.php";
				//$interface=new Interfaces($this->db);
				//$result=$interface->run_triggers('MYOBJECT_DELETE',$this,$user,$langs,$conf);
				//if ($result < 0) { $error++; $this->errors=$interface->errors; }
				//// End call triggers
			}
		}

		if (! $error) {
			$sql = "DELETE FROM " . MAIN_DB_PREFIX . "chat_msg_attachment";
			$sql.= " WHERE rowid=" . $this->id;

			dol_syslog(__METHOD__ . " sql=" . $sql);
			$resql = $this->db->query($sql);
			if (! $resql) {
				$error ++;
				$this->errors[] = "Error " . $this->db->lasterror();
			}
		}

		// Commit or rollback
		if ($error) {
			foreach ($this->errors as $errmsg) {
				dol_syslog(__METHOD__ . " " . $errmsg, LOG_ERR);
				$this->error.=($this->error ? ', ' . $errmsg : $errmsg);
			}
			$this->db->rollback();

			return -1 * $error;
		} else {
			$this->db->commit();

			return 1;
		}
	}
}
