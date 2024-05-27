<?php
// Include database connection
include '../connection.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the reclamation ID and response
    $reclamationId = $_POST['id'];
    $response = $_POST['response'];
    
    // Update the status to "traité" and store the response in the database
    $sql = "UPDATE reclamation SET status_rec = 'traité', response = :response WHERE id_reclamation = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':response', $response);
    $stmt->bindParam(':id', $reclamationId);
    $stmt->execute();

    // Fetch user's email address from the database
    $emailSql = "SELECT email FROM users WHERE id_user = (SELECT users_ID FROM reclamation WHERE id_reclamation = :id)";
    $emailStmt = $pdo->prepare($emailSql);
    $emailStmt->bindParam(':id', $reclamationId);
    $emailStmt->execute();
    $userEmail = $emailStmt->fetchColumn();

    // Compose email message
    $subject = "Your reclamation has been handled";
    $message = "Dear User,\n\nYour reclamation with ID $reclamationId has been handled. Here is the response: $response\n\nRegards,\nThe Support Team";

    // Send email using PHPMailer
    $mail = new PHPMailer;
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp.gmail.com';                     // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'ilhamadbib30@gmail.com';           // SMTP username
    $mail->Password = 'dtth vlei qkyx txcb';                    // SMTP password
    $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 465;                                    // TCP port to connect to

    $mail->setFrom('ilhamadbib30@gmail.com', 'EnerGère');
    $mail->addAddress($userEmail);                        // Add a recipient
    $mail->isHTML(false);                                 // Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $message;

    if(!$mail->send()) {
        echo 'Message could not be sent.';
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    } else {
        echo 'Message has been sent';
    }

    // Redirect back to the previous page after processing
    header("Location: {$_SERVER['HTTP_REFERER']}");
    exit();
}
?>
