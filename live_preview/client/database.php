<?php
require_once '../../preview_links.php';

if(!isset($row['email'])){
    header("location: ../logout.php");
}
$client_verification = $_SESSION['client_verification'];
if($client_verification == 'unapproved'){
    header("location: https://www.blueskylinemarketing.com/logout.php");
    exit();
}elseif($client_verification != 'approved' && $client_verification != 'unapproved'){
    header("location: https://www.blueskylinemarketing.com/logout.php");
}

$businessName = $row['business_name'];
$business_name = str_replace(' ', '_', strtolower($businessName));

$sql = "SELECT * FROM $business_name";
$result = mysqli_query($db_connect, $sql);


?>
<div class="box">
    <div class="box-header">
        <h3 align="center"><?php print($businessName); ?> Subscribers</h3>
    </div>    
    <div class="box-body">
        <div class="table-responsive" class="dataTables_wrapper form-inline dt-bootstrap">
           <div class="col-sm-12">
            <table id="employee_data" class="table table-bordered table-striped dataTable" role="grid">
            <thead>
                <tr role="row">
                    <th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">ID</th>
                    <th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">Name</th>
                    <th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">Phone Number</th>
                    <th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">Email</th>
                    <th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">Visit</th>
                    <th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">Last Check In</th>
                    <th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">Subscription Date</th>
                </tr>
            </thead>
            <tbody>
            <?php
                
                if($_COOKIE['subscriber_email'] !== null){
                    $subscriber_email = $_COOKIE['subscriber_email'];
                }else{
                    $subscriber_email = " ";
                }
                
                $date = date("Y-m-d");
                
                if(isset($_COOKIE['subscriber_name']) && isset($_COOKIE['subscriber_number'])){
                    echo "
                    <tr role='row'>
                        <td>0</td>
                        <td>".$_COOKIE['subscriber_name']."</td>
                        <td>".$_COOKIE['subscriber_number']."</td>
                        <td>".$subscriber_email."</td>
                        <td>1</td>
                        <td>".$date."</td>
                        <td>".$date."</td>
                    </tr>
                    ";
                }
                
                while($base = $result->fetch_array()){
                    echo "
                    <tr role='row'>
                        <td>".$base['id']."</td>
                        <td>".$base['name']."</td>
                        <td>".$base['phone_number']."</td>
                        <td>".$base['email']."</td>
                        <td>".$base['visit']."</td>
                        <td>".$base['last_check_in']."</td>
                        <td>".$base['registration_date']."</td>
                    </tr>
                    ";    
                }
            ?>
            </tbody>
            <tfoot>
                <tr>
                    <th rowspan="1" colspan="1">ID</th>
                    <th rowspan="1" colspan="1">Name</th>
                    <th rowspan="1" colspan="1">Phone Number</th>
                    <th rowspan="1" colspan="1">Email</th>
                    <th rowspan="1" colspan="1">Visit</th>
                    <th rowspan="1" colspan="1">Last Check In</th>
                    <th rowspan="1" colspan="1">Subscription Date</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
</div>
</div>

<script>
    $(document).ready(function(){
        $('#employee_data').dataTable();
    })
</script>