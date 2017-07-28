<?php
use Phalcon\Cli\Task;
use Phalcon\Ext\Mailer\Manager;
use Phalcon\Di\Injectable;
class MailTask extends Task
{
 public function sendEmailAction(array $params) {
    $name = $params[0];
    $email = $params[1];
    $contenido = $params[2];
    // The email setting
    $config = [
      'driver' => 'smtp',
      'host' => 'smtp.live.com',
      'port' => 587,
      'encryption' => 'tls',
      'username' => 'holaclairefenelon@hotmail.com',
      'password' => 'hola123456',
      'from' => [
        'email' => 'holaclairefenelon@hotmail.com',
        'name' => 'CLAIRE FENELON',
      ],
    ];
    //  The setting queue
    $queue = new Phalcon\Queue\Beanstalk(
      [
        'host' => '127.0.0.1',
        'port' => '11300',
      ]
    );
    $queue->put(
      [
        'sendEmail' => rand(),
      ],
      [
        'priority' => 0,
        'delay' => 1,
        'ttr' => 10,
      ]
    );
    $mailer = new Manager($config);
   
        $message = $mailer->createMessage()
          ->to($email)
          ->subject('Reminder!')
          ->content('Hola ' . $name . ' ' . $contenido);
        // Send message
        $message->send();
        $job->delete();
  }
   

}
