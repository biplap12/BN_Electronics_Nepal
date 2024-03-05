<?php
// $to_email = "aaagamming111@gmail.com";
// $subject = "Simple Email Test via PHP";
// $body = "Hi,nn This is test email send by PHP Script";
// $headers = "From: sender\'s email";

// if (mail($to_email, $subject, $body, $headers)) {
//       echo "Email successfully sent to $to_email...";
// } else {
//  echo "Email sending failed...";
// }
?>

<?php
$to_email = "aaagamming111@gmail.com";
$subject = "Email with Image Attachment";
$message = "Hi,\n\nPlease find the attached image.";

// File attachment
$file_path = "image/abc.png"; // Replace with the actual file path
$file_name = "abc.png";
$file_content = file_get_contents($file_path);
$attachment = chunk_split(base64_encode($file_content));

// Headers
$headers = "From: aa\r\n";
$headers .= "Reply-To: bb\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: multipart/mixed; boundary=\"boundary\"\r\n";

// Message body
$body = "--boundary\r\n";
$body .= "Content-Type: text/plain; charset=\"utf-8\"\r\n";
$body .= "Content-Transfer-Encoding: 7bit\r\n";
$body .= "\r\n$message\r\n";
$body .= "--boundary\r\n";
$body .= "Content-Type: image/png; name=\"$file_name\"\r\n";
$body .= "Content-Disposition: attachment; filename=\"$file_name\"\r\n";
$body .= "Content-Transfer-Encoding: base64\r\n";
$body .= "\r\n$attachment\r\n";
$body .= "--boundary--";

if (mail($to_email, $subject, $body, $headers)) {
    echo "Email successfully sent to $to_email...";
} else {
    echo "Email sending failed...";
}
?>