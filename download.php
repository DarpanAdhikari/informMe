<?php
   $con = mysqli_connect("localhost", "root", "", "informme");
   $query = "SHOW TABLES";
   $result = $con->query($query);
   $tables = array();
   while ($row = $result->fetch_array()) {
       $tables[] = $row[0];
   }
   foreach ($tables as $table) {
       $query = "SELECT * FROM $table";
       $result = $con->query($query);
       $filename = "database" . '.csv';
       $file = fopen($filename, 'w');
       $tableHeading = "\n\n" . '**' . $table . '**' . "\n\n";
       fwrite($file, $tableHeading);
       $fieldInfo = $result->fetch_fields();
       $headings = array();
       foreach ($fieldInfo as $field) {
           $headings[] = $field->name;
       }
       fputcsv($file, $headings);
   
       while ($row = $result->fetch_assoc()) {
           fputcsv($file, $row);
       }
       fclose($file);
       header('Content-Type: application/octet-stream');
       header('Content-Disposition: attachment; filename="' . $filename . '"');
       header('Content-Length: ' . filesize($filename));
       readfile($filename);
       unlink($filename);
   }
   $con->close();
?>