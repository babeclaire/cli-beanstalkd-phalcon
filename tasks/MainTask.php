<?php
use Phalcon\Cli\Task;
use Phalcon\Ext\Mailer\Manager;
use Phalcon\Queue\Beanstalk;
class MainTask extends Task
{
 public function sendEmailAction(array $params) {
    $name = $params[0];
    $email = $params[1];
    $contenido =$params[2];
    $config = [
      'driver' => 'smtp',
      'host' => 'smtp.live.com',
      'port' => 587,
      'encryption' => 'tls',
      'username' => 'vdvegdvewg@hotmail.com',
      'password' => 'hhffhfh',
      'from' => [
        'email' => 'vdvegdvewg@hotmail.com',
        'name' => 'dewdwedwee',
      ],
    ];
    $queue = new Beanstalk(
      [
        'host' => '127.0.0.1',
        'port' => '11300',
      ]
    );
    $mailer = new Manager($config);
        $message = $mailer->createMessage()
         ->to([$email])
          ->subject('Reminder!')
          ->content('Hola ' . $name.' ' .$contenido);
        // Send to the queue
         $message = $queue->put(
           [ "send" => rand(), 
              
           ],
           [ 
        'priority' => 0,
        'delay' => 3,
        'ttr' => 10,
        ]
       
  );
     echo "Email has been put to the queue!";
  }
  public function mailqueueAction()
  {
     $queue = new Beanstalk(
      [
        'host' => '127.0.0.1',
        'port' => '11300',
      ]
    );
    while (true) {
      while ($job = $queue->peekReady() !== false) {
        $job = $queue->reserve();
        $message = $job->getBody();
        if(isset($message['send'])) {
          $job =$message->send();
        }
        $job->delete();
         }
         sleep(5);
         }
      }

}
