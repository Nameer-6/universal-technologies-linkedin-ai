<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ReportIssueMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct($data)
    {
        // Ensure all string fields are properly cast
        $this->data = [
            'name' => $this->ensureString($data['name'] ?? ''),
            'email' => $this->ensureString($data['email'] ?? ''),
            'message' => $data['message'] ?? '',
            'image_path' => $data['image_path'] ?? null,
        ];
    }

    protected function ensureString($value): string
    {
        if (is_array($value)) {
            return (string)reset($value);
        }
        return (string)$value;
    }
    public function build()
    {
        // Enhanced logging
        Log::info('Building ReportIssueMail', [
            'data' => $this->data,
            'image_path_exists' => !empty($this->data['image_path']),
            'image_path_type' => gettype($this->data['image_path']),
        ]);

        $email = $this->subject('New Issue Report')
            ->replyTo($this->data['email'], $this->data['name'])
            ->view('emails.report-issue')
            ->with(['data' => $this->data]);

        // More robust image attachment handling
        if (!empty($this->data['image_path']) && is_string($this->data['image_path'])) {
            try {
                $storagePath = storage_path('app/public/' . ltrim($this->data['image_path'], '/'));

                if (file_exists($storagePath)) {
                    $extension = pathinfo($this->data['image_path'], PATHINFO_EXTENSION);
                    $email->attach($storagePath, [
                        'as' => 'screenshot.' . $extension,
                        'mime' => mime_content_type($storagePath),
                    ]);
                } else {
                    Log::warning('Image file not found at path: ' . $storagePath);
                }
            } catch (\Exception $e) {
                Log::error('Failed to attach image to email: ' . $e->getMessage());
            }
        }

        return $email;
    }
}
