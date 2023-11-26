
<?php 
    if(isset($_GET['noc']))
    { 
?>
        <div class="alert alert-danger my-3" role="alert">
            Number of candidate should be greater then or equal to <?php echo $_GET['noc'];?>.
        </div>
<?php
    }
?>




<div class="row my-3">
    <div class="offset-md-4 col-4">
        <h3>Edit Election</h3>
        <?php
        $fetchingActiveElections = mysqli_query($db, "SELECT * FROM elections WHERE id = '". $_GET['electionId'] ."'") or die(mysqli_error($db));
        $election = $fetchingActiveElections->fetch_object();
        if($election){
        ?>
        <form method="POST">
            <input type="hidden" name="election_id" value="<?php echo $election->id?>"/>
            <div class="form-group">
                <input type="text" name="election_topic" placeholder="Election Topic" class="form-control" value="<?php echo $election->election_topic;?>" required />
            </div>
            <div class="form-group">
                <input type="number" name="number_of_candidates" placeholder="No of Candidates" class="form-control" value="<?php echo $election->no_of_candidates;?>" required />
            </div>
            <div class="form-group">
                <input type="text" onfocus="this.type='Date'" name="starting_date" placeholder="Starting Date" class="form-control" value="<?php echo $election->starting_date;?>" required />
            </div>
            <div class="form-group">
                <input type="text" onfocus="this.type='Date'" name="ending_date" placeholder="Ending Date" class="form-control" value="<?php echo $election->ending_date;?>" required />
            </div>
            <input type="submit" value="Edit Election" name="editElectionBtn" class="btn btn-success" />
        </form>
        <?php } ?>
    </div>

</div>


<script>
    const DeleteData = (e_id) => 
    {
        let c = confirm("Are you really want to delete it?");

        if(c == true)
        {
            location.assign("index.php?addElectionPage=1&delete_id=" + e_id);
        }
    }
</script>

<?php 

    if(isset($_POST['editElectionBtn']))
    {
        $election_id = mysqli_real_escape_string($db, $_POST['election_id']);
        $election_topic = mysqli_real_escape_string($db, $_POST['election_topic']);
        $number_of_candidates = mysqli_real_escape_string($db, $_POST['number_of_candidates']);
        $starting_date = mysqli_real_escape_string($db, $_POST['starting_date']);
        $ending_date = mysqli_real_escape_string($db, $_POST['ending_date']);
        $inserted_by = $_SESSION['username'];
        $inserted_on = date("Y-m-d");

        
        $fetchingData = mysqli_query($db, "SELECT * FROM candidate_details WHERE election_id =".$election_id) or die(mysqli_error($db)); 
        $numCandidates = mysqli_num_rows($fetchingData);
        if($number_of_candidates >= $numCandidates ){
            $date1=date_create($inserted_on);
            $date2=date_create($starting_date);
            $diff=date_diff($date1,$date2);
            
            
            if((int)$diff->format("%R%a") > 0)
            {
                $status = "InActive";
            }else {
                $status = "Active";
            }

            // inserting into db
            mysqli_query($db, "UPDATE elections SET election_topic = '". $election_topic ."', no_of_candidates = '". $number_of_candidates ."', starting_date = '". $starting_date ."', ending_date = '". $ending_date ."', status = '". $status ."', inserted_by = '". $inserted_by ."', inserted_on = '". $inserted_on ."'") or die(mysqli_error($db));

            echo "<script> location.assign('index.php?addElectionPage=1&edited=1'); </script>";             
        }else{
            echo "<script> location.assign('index.php?editElectionPage=1&electionId=".$election_id."&noc=".$numCandidates."'); </script>";   
        }        
    ?>
    <?php

    }
?>