<?php

namespace App\Console\Commands;

use App\Mail\EventReminderMail;
use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendEventReminders extends Command
{
    protected $signature   = 'ticketin:send-reminders';
    protected $description = 'Kirim email reminder H-1 sebelum event ke semua pembeli';

    public function handle(): void
    {
        // Event yang berlangsung besok (H-1)
        $tomorrow = now()->addDay();

        $orders = Order::with(['user', 'event', 'items.ticketCategory'])
            ->where('status', 'paid')
            ->whereHas('event', function ($q) use ($tomorrow) {
                $q->whereDate('tanggal_waktu', $tomorrow->toDateString());
            })
            ->get();

        if ($orders->isEmpty()) {
            $this->info('Tidak ada event besok. Tidak ada reminder yang dikirim.');
            return;
        }

        $sent = 0;
        foreach ($orders as $order) {
            try {
                Mail::to($order->user->email)->send(new EventReminderMail($order));
                $sent++;
                $this->line("  ✓ Reminder dikirim ke {$order->user->email} ({$order->event->judul})");
            } catch (\Throwable $e) {
                $this->error("  ✗ Gagal kirim ke {$order->user->email}: {$e->getMessage()}");
            }
        }

        $this->info("Selesai. {$sent} dari {$orders->count()} reminder berhasil dikirim.");
    }
}
