<?php
require_once 'email.php';

function send_new_doc_email(string $name,string $pwrd , string $doc_email){
    $email_subject = "Subject: Welcome to MedHub, Dr. $name!";
    $email_body =<<<EOD
<html>
<head>
<title></title>
</head>
<body>
<h1>Dear Dr. $name,</h1>

<p> I hope this email finds you well. We're delighted to welcome you to MedHub as our newest team member. Your expertise and dedication will be invaluable in our mission to provide exceptional care to our patients.</p>

<p>We're excited to have you join us and look forward to working together to deliver top-quality medical services to our community. Your skills and experience will make a significant impact on our patients' lives, and we're confident you'll enhance our practice.</p>

<p>As you start this new chapter with us, please know that we're here to support you in any way we can. If you have any questions or need assistance, don't hesitate to reach out to me or any member of our team. We're committed to ensuring your transition is seamless and that you feel valued as part of our team.<p>

<p>Once again, welcome to MedHub, Dr. $name! We're honored to have you join us and look forward to a successful partnership.</p>


<h2>your signin info is </h2>
<h3>email :    $doc_email</h3>
<h3>password : $pwrd </h3>
</body>
</html>


Warm regards,
EOD;
    send_email($doc_email,$email_subject,$email_body);
}
function send_new_admin_email(string $name,string $pwrd , string $admin_email){
    $email_subject = "Subject: Welcome to MedHub, $name!";
    $email_body =<<<EOD
<html>
<head>
<title></title>
</head>
<body>
<h1>Dear $name,</h1>

Dear $name,

<p>I hope this message finds you in good spirits.
 I'm pleased to extend a warm welcome to you as the newest 
 addition to our administrative team at medhub.</p>

<p>Your expertise will be invaluable to our operations,
 and we're excited to have you join us. As part of our administrative team,
  your role will be vital in ensuring our organization runs smoothly.</p>

<p>We look forward to collaborating with you and are confident 
that your dedication and professionalism will enhance our team's effectiveness.</p>

<p>If you have any questions or need assistance settling into your role,
 please don't hesitate to reach out. We're here to support you every step of the way.</p>

<p>Welcome aboard, $name! We're eager to begin 
this journey with you and anticipate a successful and fulfilling partnership.</p>

Best regards,

<h2>your signin info is </h2>
<h3>email :    $admin_email</h3>
<h3>password : $pwrd </h3>
</body>
</html>


Warm regards,
EOD;
    send_email($admin_email,$email_subject,$email_body);
}

function send_new_staff_email(string $name,string $pwrd , string $staff_email){
    $email_subject = "Welcome to the Team, $name!";
    $email_body =<<<EOD
<html>
<head>
<title></title>
</head>
<body>
<h1>Dear $name,</h1>

Dear $name,

<p>I hope this email finds you well. 
It gives me great pleasure to extend a warm welcome to you as the newest member of our team here at Medhub.</p>

<p>Your skills and expertise are a valuable addition to our team, 
and we're excited to have you on board. As a member of our staff, 
your contributions will play a crucial role in our collective success.</p>

<p>We're looking forward to working with you and are confident that your dedication 
and professionalism will greatly benefit our team and organization.</p>

<p>Should you have any questions or need assistance as you settle into your role, 
please don't hesitate to reach out. We're here to support you in any way we can.</p>

<p>Once again, welcome to the team, $name! 
We're thrilled to have you join us and are eager to embark on this journey together.</p>

Best regards,

<h2>your signin info is </h2>
<h3>email :    $staff_email</h3>
<h3>password : $pwrd </h3>
</body>
</html>


Warm regards,
EOD;
    send_email($staff_email,$email_subject,$email_body);
}



// send_new_doc_email('ahmed nabil' , 'XDDDDD' , 'ahmniab11@gmail.com');