<?php 
$http_code = get_option( $_GET['ref_id'] ); 
if( $http_code >= 500 && $http_code < 511 ) {
    ?>
    <div class="tutis-alert">
        <p><strong>Enrolment Request Failed</strong></p>
        <p>Sorry but our system is currently unavailable, you have not been charged for this enrolment. Please try again later.</p>
        <p><strong>Enrolment Request ID:</strong> <?php echo $_GET['ref_id']; ?></p>
    </div>    
    <?php   
    tutis_cancel_secure_pay_auths_data( $_GET['ref_id'] );   
}
else if( $http_code == 'secure_tokken_error' ) {
    ?>
    <div class="tutis-alert">
        <p><strong>Enrolment Request Failed</strong></p>
        <p>Securepay is currently unavailable, you have not been charged for this enrolment. Please try again later.</p>
    </div> 
<?php 
} 
else if( $http_code == 'secure_cents_error' ) {
    ?>
    <div class="tutis-alert">
        <p><strong>Enrolment Request Failed</strong></p>
        <p><?php echo get_option( $_GET['ref_id'] .'_response_message' ); ?>, you have not been charged for this enrolment. Please try again later.</p>
    </div> 
<?php 
} 
else {
    ?>
    <div class="tutis-alert success">
        <p><strong>Payment Successfull!</strong></p>
        <p>Kindly check or save your Enrolment Request ID below, Thank you.</p>
        <p><strong>Enrolment Request ID:</strong> <?php echo $_GET['ref_id']; ?></p>
    </div> 
<?php 
} 
?>
