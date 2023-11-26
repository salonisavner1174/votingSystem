<?php 
    if(isset($_GET['edited']))
    {
?>
        <div class="alert alert-success my-3" role="alert">
            Candidate has been edited successfully.
        </div>
<?php 
    }else if(isset($_GET['largeFile'])) {
?>
        <div class="alert alert-danger my-3" role="alert">
            Candidate image is too large, please upload small file (you can upload any image upto 2mbs.).
        </div>
<?php
    }else if(isset($_GET['invalidFile']))
    {
?>
        <div class="alert alert-danger my-3" role="alert">
            Invalid image type (Only .jpg, .png files are allowed) .
        </div>
<?php
    }else if(isset($_GET['failed']))
    {
?>
        <div class="alert alert-danger my-3" role="alert">
            Image uploading failed, please try again.
        </div>
<?php
    }

?>


<div class="row my-3">
    <div class="offset-md-4 col-4">
        <h3>Edit Candidates</h3>
        <?php
        $fetchingActiveElections = mysqli_query($db, "SELECT * FROM candidate_details WHERE id = '". $_GET['candidateId'] ."'") or die(mysqli_error($db));
        $candidate = $fetchingActiveElections->fetch_object();
        if($candidate){
        ?>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <input type="hidden" name="candidate_id" value="<?php echo $candidate->id?>"/>
                <input type="hidden" name="candidate_photo_previous" value="<?php echo $candidate->candidate_photo?>"/>
                <select class="form-control" name="election_id" required> 
                    <option value=""> Select Election </option>
                    <?php 
                        $fetchingElections = mysqli_query($db, "SELECT * FROM elections") OR die(mysqli_error($db));
                        $isAnyElectionAdded = mysqli_num_rows($fetchingElections);
                        if($isAnyElectionAdded > 0)
                        {
                            while($row = mysqli_fetch_assoc($fetchingElections))
                            {
                                $election_id = $row['id'];
                                $election_name = $row['election_topic'];
                                $allowed_candidates = $row['no_of_candidates'];

                                // Now checking how many candidates are added in this election 
                                $fetchingCandidate = mysqli_query($db, "SELECT * FROM candidate_details WHERE election_id = '". $election_id ."'") or die(mysqli_error($db));
                                $added_candidates = mysqli_num_rows($fetchingCandidate);

                        ?>
                                <option value="<?php echo $election_id; ?>" <?php if($candidate->election_id == $election_id){ echo "selected"; }?>><?php echo $election_name; ?></option>
                        <?php
                                
                            }
                        }else {
                    ?>
                            <option value=""> Please add election first </option>
                    <?php
                        }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <input type="text" name="candidate_name" placeholder="Candidate Name" class="form-control" value="<?php echo $candidate->candidate_name?>" required />
            </div>
            <div class="form-group">
                <img src="<?php echo $candidate->candidate_photo; ?>" class="candidate_photo mt-1" /> 
                <input type="file" name="candidate_photo" class="form-control" <?php if(!$candidate->candidate_photo){echo "required";} ?>  />
            </div>
            <div class="form-group">
                <input type="text" name="candidate_details" placeholder="Candidate Details" class="form-control" value="<?php echo $candidate->candidate_details?>" required />
            </div>
            <input type="submit" value="Edit Candidate" name="editCandidateBtn" class="btn btn-success" />
        </form>
        <?php } ?>
    </div>   

    
</div>



<?php 

    if(isset($_POST['editCandidateBtn']))
    {
        $election_id = mysqli_real_escape_string($db, $_POST['election_id']);
        $candidate_name = mysqli_real_escape_string($db, $_POST['candidate_name']);
        $candidate_details = mysqli_real_escape_string($db, $_POST['candidate_details']);
        $candidate_id = mysqli_real_escape_string($db, $_POST['candidate_id']);
        $inserted_by = $_SESSION['username'];
        $inserted_on = date("Y-m-d");
        $is_fileavailable = false;
       
            
        // Photograph Logic Starts
        $targetted_folder = "../assets/images/candidate_photos/";
        $candidate_photo = $targetted_folder . rand(111111111, 99999999999) . "_" . rand(111111111, 99999999999) . $_FILES['candidate_photo']['name'];
        $candidate_photo_tmp_name = $_FILES['candidate_photo']['tmp_name'];
        $candidate_photo_type = strtolower(pathinfo($candidate_photo, PATHINFO_EXTENSION));
        $allowed_types = array("jpg", "png", "jpeg");        
        $image_size = $_FILES['candidate_photo']['size'];

        if(!$_FILES['candidate_photo']['tmp_name']){
            $candidate_photo =$_POST['candidate_photo_previous'];
            $is_fileavailable = true;
        }
   

        if($image_size < 2000000 || $is_fileavailable == true) // 2 MB
        {
            if(in_array($candidate_photo_type, $allowed_types) || $is_fileavailable == true)
            {
                if(move_uploaded_file($candidate_photo_tmp_name, $candidate_photo) || $is_fileavailable == true)
                {
                    // inserting into db
                    mysqli_query($db, "UPDATE candidate_details SET election_id = '". $election_id ."',candidate_name = '". $candidate_name ."', candidate_details = '". $candidate_details ."', candidate_photo = '". $candidate_photo ."', inserted_by='". $inserted_by ."', inserted_on = '". $inserted_on ."' WHERE id = '".$candidate_id."'") or die(mysqli_error($db));

                    echo "<script> location.assign('index.php?addCandidatePage=1&edited=1'); </script>";
                }else {
                    echo "<script> location.assign('index.php?editCandidatePage=1&failed=1'); </script>";                    
                }
            }else {
                echo "<script> location.assign('index.php?editCandidatePage=1&invalidFile=1'); </script>";
            }
        }else {
            echo "<script> location.assign('index.php?editCandidatePage=1&largeFile=1'); </script>";
        }

        // Photograph Logic Ends
        




        
    ?>
      <?php

    }






?>