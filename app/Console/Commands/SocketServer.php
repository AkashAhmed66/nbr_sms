<?php

namespace App\Console\Commands;

use App\Jobs\HandleIncomingMessage;
use App\Jobs\HandleMessageStatusUpdate;
use App\Jobs\ProcessSocketListenerMessage;
use Illuminate\Console\Command;
use Modules\Messages\App\Trait\SmsCountTrait;
use Illuminate\Support\Facades\Log;

class SocketServer extends Command
{
  use SmsCountTrait;

  protected $signature = 'socket:listen';
  protected $description = 'Start a socket server that listens on port 8080';

  public function handle()
  {
    $host = env('SOCKET_SERVER_LISTENING_HOST', '0.0.0.0');
    $port = env('SOCKET_SERVER_LISTENING_PORT', 4000);

    // Create a TCP/IP socket
    $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    if ($socket === false) {
      $this->error("Socket creation failed: " . socket_strerror(socket_last_error()));
      return;
    }

    // Bind the socket to the IP and port
    if (socket_bind($socket, $host, $port) === false) {
      $this->error("Socket binding failed: " . socket_strerror(socket_last_error($socket)));
      socket_close($socket);
      return;
    }

    // Listen for incoming connections
    if (socket_listen($socket, 5) === false) {
      $this->error("Socket listening failed: " . socket_strerror(socket_last_error($socket)));
      socket_close($socket);
      return;
    }

    $this->info("Server listening on {$host}:{$port}");

    while (true) {
      $clientSocket = @socket_accept($socket);
      if ($clientSocket === false) {
        $this->error("Socket accept failed: " . socket_strerror(socket_last_error($socket)));
      }

      // Loop to keep the server running and accepting connections
      while (true) {
        try {
          $message = trim(socket_read($clientSocket, 1024, PHP_NORMAL_READ));
          if (empty($message)) {
            Log::channel('socketlisten')->info("Received EMPTY message" );
            break;
          }

          //$message = "SMS_STATUS|messageId|status|userId";
          $this->info("Received message: {$message}");
          ProcessSocketListenerMessage::dispatch($message);
        } catch (\Exception $exception) {
          break;
        }
      }
      socket_close($clientSocket);
    }
  }
}
