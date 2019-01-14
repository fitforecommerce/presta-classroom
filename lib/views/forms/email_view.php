<?php
class EmailView extends View
{

  var $boundary;

  public function to_s()
  {
    $rv  = $this->html_part();
    $rv .= $this->attachment_part();
    $rv .= $this->boundary_line();
    error_log($rv);
    return $rv;
  }
  private function boundary()
  {
    if(isset($this->boundary)) return $this->boundary;
    $this->boundary = md5(uniqid(rand()));
    return $this->boundary;
  }
  private function boundary_line()
  {
    return "--".$this->boundary()."\n";
  }
  public function head()
  {
    $kopf = [];
    $kopf['from'] = "From: ".$this->vconfig['sender_email'];
    if(isset($this->vconfig['cc_email'])) {
      $kopf['cc'] = "cc: ".$this->vconfig['cc_email'];
    }
    $kopf['mime'] = "MIME-Version: 1.0";
    $kopf['content-type'] = 'Content-Type: multipart/mixed; boundary = "'.$this->boundary().'"';
    return implode($kopf, "\n");
  }
  private function html_part()
  {
    $text  = $this->boundary_line();
    $text .= "Content-Type: text/html; charset=\"UTF-8\"\n";
    $text .= "Content-Transfer-Encoding: 8bit\n\n";
    $text .= $this->vconfig['html_text'];
    return $text;
  }
  private function attachment_part()
  {
    if(!isset($this->vconfig['attachment'])) return '';
    $fn = $this->vconfig['attachment_name'];
    $text  = $this->boundary_line();
    $text .= "Content-Type: text/plain; name=$fn\n";
    $text .= "Content-Transfer-Encoding: base64\n";
    $text .= "Content-Disposition: attachment; filename=$fn\n\n";
    $text .= $this->vconfig['attachment']."\n";
    error_log("adding attachment $text");
    return $text;
  }
}
?>