<?php //if(!defined('BASEPATH')) exit("Access Denied!");
class Backup extends CI_Controller
{

    public function index(){
      echo "Hello World!";  
      //load a view here and make it working!  
    }

    //function to create DB backup
    public function execute_backup()
    {
      $prefs = array(
                'tables'      => array(),
                'ignore'      => array('backup_logs'),
                'format'      => 'txt',
                'filename'    => 'dbbackup.sql',
                'add_drop'    => TRUE,
                'add_insert'  => TRUE,
                'newline'     => "\n"
              );
        $this->load->dbutil();
        $backup =& $this->dbutil->backup($prefs);
        $this->load->helper('file');
        $file_path =  "./dbbackup/".date('d-M-Y').".sql";
        write_file($file_path, $backup);
        $this->load->model('backup_model');
        $this->backup_model->logbackup_model($file_path);
      //  $this->load->helper('download');
      //  force_download('mybackup.sql', $backup);
          redirect("backup");

    }

    // function to restore Db    
    public function restore_db($restoredate)
    {
      $templine = '';
      $restore_file = "./dbbackup/".$restoredate.".sql";
      $lines = file($restore_file);
      foreach ($lines as $line)
      {
        if (substr($line, 0, 2) == '--' || $line == '')
        continue;
        $templine .= $line;
        if (substr(trim($line), -1, 1) == ';')
        {
          $this->db->query($templine);
          echo $templine;
          $templine = '';
        }
      }
      //$this->db->query("truncate table ");
      $this->session->set_userdata('restore','1');
      redirect("backup");
    }
    
}

?>
