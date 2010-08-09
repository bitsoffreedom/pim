<?php

// It might be nicer to have the whole thing in a DB, but for now this
// is sufficient. You can always decide to make a drop-in replacement, 
// as long as it sets the DATA hash 


define('ON_COLLISION_OVERWRITE', 1);
define('ON_COLLISION_SKIP'     , 2);
define('ON_COLLISION_ABORT'    , 3); 

function read_lookup_table_from_csv( $csv_file
                                  , $separator_input = ';'
                                  , $separator_index = '|'
                                  , $index_by        = array(0 => '')
                                  , $on_collision    = ON_COLLISION_ABORT
                                  , $rec_len         = 1024
                                  )
{
   $handle = fopen($csv_file, 'r');
   if($handle == null || ($data = fgetcsv($handle, $rec_len, $separator_input)) === false)
   {
       // Couldn't open/read from CSV file.
       return -1;
   }

   $names = array();
   foreach($data as $field)
   {
       $names[] = trim($field);
   }

   $indexes = array();
   foreach($index_by as $index_in => $function)
   {
       if(is_int($index_in))
       {
           if($index_in < 0 || $index_in > count($data))
           {
               // Index out of bounds.
               fclose($handle);
               return -2;
           }

           $index_out = $index_in;
       }
       else
       {
           // If a column that is used as part of the key to the hash table is supplied
           // as a name rather than as an integer, then determine that named column's
           // integer index in the $names array, because the integer index is used, below.
           $get_index = array_keys($names, $index_in);
           $index_out = $get_index[0];

           if(is_null($index_out))
           {
               // A column name was given (as opposed to an integer index), but the
               // name was not found in the first row that was read from the CSV file.
               fclose($handle);
               return -3;
           }
       }

       $indexes[$index_out] = $function;
   }

   if(count($indexes) == 0)
   {
       // No columns were supplied to index by.
       fclose($handle);
       return -4;
   }

   $retval = array();
   while(($data = fgetcsv($handle, $rec_len, $separator_input)) !== false)
   {
       $index_by = '';
       foreach($indexes as $index => $function)
       {
           $index_by .= ($function ? $function($data[$index]) : $data[$index]) . $separator_index;
       }
       $index_by = substr($index_by, 0, -1);

       if(isset($retval[$index_by]))
       {
           switch($on_collision)
           {
               case ON_COLLISION_OVERWRITE     : $retval[$index_by] = array_combine($names, $data);
               case ON_COLLISION_SKIP          : break;
               case ON_COLLISION_ABORT         : return -5;
           }
       }
       else
       {
           $retval[$index_by] = array_combine($names, $data);
       }
   }
   fclose($handle);
   return $retval;
}

// read organisations from a simple CSV file
// labels : NAAM,TYPE,CONTACT,STRAAT,POSTCODE,PLAATS,EMAIL

$DATA = read_lookup_table_from_csv('organisations.csv',',');


