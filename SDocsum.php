 <?php include('header.php');
 
 //json_decode($_POST);
 //if(isset($_POST['submit'])) // check if this is the 2nd time the php is being run after pressing the Save button
        //{
            echo $l_in_filename = $_FILES['file']['tmp_name'];    // this is the name of the temp file that the <input type="file" name="file" creates in the /tmp directory of the server
            $filename  = $_FILES['file']['name'];
            $filesize  = $_FILES['file']['size'];
            $filetype  = $_FILES['file']['type'];
            
            //$filename  = basename($_FILES['file']['name']);
            // $extension = pathinfo($filename, PATHINFO_EXTENSION);
            
            $not_allowed = array('php','php5','exe','css','php','c','cpp','java','pl','htm','js','css','xml','jsp','ser','jsf','jse','bat','cmd','jad','json','aspx','lib','pdb','dbg','php3','pmp','pm','bml','p','j','hta','com','lnk','pif','scr','vb','vbs','wsh','html');
            
            if(in_array($extension, $not_allowed))
            {
                echo '<font color=red>Please change your file to ZIP/rar and try uploading again</font>';
                
            }
            
            else  {
                
                if ($_FILES["file"]["error"] > 0)
                {
                    echo "<font color=red> You have not selected any file. Please choose the File !</font>";
                }
                else
                {
                    $l_filesize = ($_FILES["file"]["size"] / 1024); // File size in KBs
                    
                    $iex= explode('_',$_POST['l_AL_id']);
                    echo $l_AL_Name=$iex[1];
                     echo $l_AL_id=$iex[0];

                    //exit();
                    if($l_filesize > $l_max_filesize)
                    {
                        echo 'You do not have access for big files, please pay.';
                    }
                    else
                    {
                        
                       echo  $l_PD_sql = 'select count(*) from Project_Documents as PD where Org_id="'.$_SESSION['g_Org_id'].'" and PD.TM_id = '.$l_TM_id.' and PD.AL_id = '.$l_AL_id.' and PD.PD_Status = "P"';
                        $l_PD_res = mysql_query($l_PD_sql) or die(mysql_error());
                        $l_PD_row = mysql_fetch_row($l_PD_res);
                       
                        $l_count_PD = $l_PD_row[0];
                        
                        if($l_count_PD == 0)
                        {
                            $l_AL_query = 'Select AL.AL_Desc from Access_Level as AL where AL.AL_id = '.$l_AL_id.'';
                          
                            $l_AL_res = mysql_query($l_AL_query) or die(mysql_error());
                            $l_Al_row = mysql_fetch_row($l_AL_res);
                            $l_AL_Desc = $l_Al_row[0];

                  

   
                            if (empty( $_FILES["file"]["name"]))
                            {
                                $_FILES["file"]["name"] . "<font color=red> Please choose file to Submit. </font>";
                            }
                            else
                            {
                              
                                $l_TM_Revise = 'select TM.TM_Revise from Teams as TM  where Org_id="'.$_SESSION['g_Org_id'].'" and TM.TM_id ='.$l_TM_id.' ';
                                $l_result_TM = mysql_query($l_TM_Revise) or die(mysql_error());
                                $l_row_TM = mysql_fetch_row($l_result_TM);
                                $l_Revise_TM=$l_row_TM[0];
                                
                                //ECHO $l_TM_Revise;
                                if($l_Revise_TM == "Y")
                                {
                                    $l_Update_query_N = 'update Teams set TM_Revise = "N" where Org_id="'.$_SESSION['g_Org_id'].'" and TM_id ='. $l_TM_id.'';
                                    $l_Update_revise_N = mysql_query($l_Update_query_N) or die(mysql_error());
                                }
                                
                                
                                //$folder = 'Projectory/ProjectDocuments'; // for linux
                               // move_uploaded_file($l_in_filename, $folder.'/'.$l_PD_Name );
                                
                                $l_PD_id_count = 'Select MAX(PD.PD_id) from Project_Documents as PD where Org_id="'.$_SESSION['g_Org_id'].'"';
                                $l_PD_id_res = mysql_query($l_PD_id_count) or die(mysql_error());
                                $l_PD_row = mysql_fetch_row($l_PD_id_res);
                                $l_PD_id = $l_PD_row[0];
                                
                                $l_PD_id = $l_PD_id + 1;
                                
                                $l_PD_Status = 'P';
                                //$l_PD_URL = $l_filehomepath.'/Projectory/ProjectDocuments/'.$l_PD_Name;
                                
                                if(!empty($l_in_filename)){
                                    $filedata= addslashes(file_get_contents($l_in_filename));
                                }
                                //$test=file_get_contents($l_in_filename);
                                
                                $l_find_doc_name=mysql_query('Select count(PD_id) from Project_Documents where Org_id="'.$_SESSION['g_Org_id'].'" and PR_id="'.$l_PR_id.'" and AL_id="'.$l_AL_id.'" and TM_id="'.$l_TM_id.'"');
                                $row_pdcount_row=  mysql_fetch_row($l_find_doc_name);
                                $row_pdcount = $row_pdcount_row[0];
                                $PD_Count = $row_pdcount+1;
                                $extension = pathinfo($filename, PATHINFO_EXTENSION);
                                $rename =  $l_AL_Desc.$l_TM_id.'_'.$PD_Count;
                                $l_PD_Name = $rename.'.'.$extension;
            
                               $l_insert_sql = 'insert into Project_Documents (AL_id, PD_Feedback, PD_FeedbackDate, PD_id, PD_Name, PD_Rating, PD_Status, PD_SubmissionDate,PD_Data, PR_id, TM_id ,Org_id,PD_Data_Size,PD_Data_Type,PD_Original_Name) values ("' . $l_AL_id . '",NULL, NULL, ' . $l_PD_id . ',  "' . $l_PD_Name . '", NULL, "' . $l_PD_Status . '",'.$l_PD_SubmissionDate.',"' .$filedata.'",'.$l_PR_id.',"'.$l_TM_id.'","'.$_SESSION['g_Org_id'].'",'.$filesize.',"'.$filetype.'","'.$filename.'")';
                                $result=mysql_query($l_insert_sql) or die(mysql_error());
                                if($result){print('<div class="alert alert-success">Document submitted successfully</div>');}
                                else{print('<div class="alert alert-danger">!!Sorry Please try again......</div>');}
                                //mysql_free_result($l_PD_id_res);
                            
                             $getallteammates='select UR_id from Users where TM_id='.$l_TM_id;
                                $runqueryteammates=mysql_query($getallteammates);
                                
                            while($row=mysql_fetch_row($runqueryteammates)){
                                IF($row[0]==$l_UR_id)
                                    { 
                                    $P='I';
                                    }
                                else
                                    {
                                    $P='P';
                                    
                                    }
                                   $l_insertpd_sql = 'insert into  PRdoc_Seen (PD_AL_id,PD_Seen,PD_id,PD_TM_id,Org_id,UR_id) values ("'. $l_AL_id .'","'.$P.'","'.$l_PD_id.'","'.$l_TM_id.'","'.$_SESSION['g_Org_id'].'","'.$row[0].'")';
                                $resultpd=mysql_query( $l_insertpd_sql) or die(mysql_error());
                                
                                }  
                            } //else---if (file_exists("upload/" . $_FILES["file"]["name"]))
                            //header('location:EmailNotifications?g_query=Student|'.$l_TM_id.'|'.$l_AL_Desc.'');
                            
                           echo '<script>window.location.href="EmailNotifications.php?g_query=Student|'.$l_TM_id.'|'.$l_AL_Desc.'"</script>';
                           
                           mysql_free_result($l_AL_res);
                        }
                        else
                        {
                            $l_alert_statement =  ' <script type="text/javascript">
                            window.alert("Your previous document have not been given any feedback. You cannot submit any document until the previous document is given a feedback")
                            </script> ';
                            
                            print($l_alert_statement );
                        }
                    }
                }//else ---if(isset($_POST['submit'])) /
            }
        //}