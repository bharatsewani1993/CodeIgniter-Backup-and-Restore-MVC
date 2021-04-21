<?php if(!defined('BASEPATH')) exit("Access Denied!");
class Backup_model extends CI_Model
{
    public function logbackup_model($file_path)
    {
      $count = $this->db->query("SELECT COUNT(id) FROM backup_logs");
      $count = $count->row_array();
      $count =  $count['COUNT(id)'];
      if($count<10)
      {
        $this->db->insert('backup_logs',array('backup_date'=>date('d-M-Y'),
        'file_name'=>date('d-M-Y').".sql",
        'file_path'=>$file_path));
      }
      else
      {

          $ten_day_ago = date('d-M-Y', strtotime('-10 days', strtotime(date('d-M-Y'))));
          $deletefile = "./dbbackup/".$ten_day_ago.".sql";
          unlink($deletefile);
          $this->db->where('backup_date',$ten_day_ago);
          $this->db->update('backup_logs',array('backup_date'=>date('d-M-Y'),
                            'file_name'=>date('d-M-Y').".sql",
                            'file_path'=>$file_path));
      }
    }

}
