# DevMail
Used to capture mail in a development/staging environment, but allow web viewing of the emails.

Dev Mail receives email via PostFix as a standard SMTP server. Mail however is only allowed to come in and is specifically denied going out. This causes PostFix to generate a queue of emails to send, which are then processed by a service written in PHP that will parse the queue and remove items once they are received.
This service is ran by setting up a cron job to process the trigger/index.php file, plus it runs each time the page is loaded.
