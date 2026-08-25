@extends('layouts.app')

@section('title', 'Notifikasi - Manajemen Persuratan')
@section('page-title', 'Notifikasi')
@section('page-subtitle', 'Pemberitahuan alur disposisi')

@section('content')
    <div class="card notification-page-card">
        <div class="card-header">
            <h3><i class="fas fa-bell"></i> Daftar Notifikasi</h3>
            @if($notifikasiItems->contains(fn ($item) => !$item->dibaca_pada))
                <form action="{{ route('notifikasi.baca-semua') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-secondary"><i class="fas fa-check-double"></i> Tandai Semua Dibaca</button>
                </form>
            @endif
        </div>
        <div class="notification-page-list">
            @forelse($notifikasiItems as $notification)
                <form action="{{ route('notifikasi.baca', $notification->id) }}" method="POST" class="notification-page-item {{ $notification->dibaca_pada ? '' : 'unread' }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit">
                        <span class="notification-item-icon"><i class="fas {{ $notification->tipe === 'disposisi_ditinjau' ? 'fa-clipboard-check' : 'fa-code-branch' }}"></i></span>
                        <span class="notification-item-content">
                            <strong>{{ $notification->judul }}</strong>
                            <span>{{ $notification->pesan }}</span>
                            <small>{{ $notification->created_at->format('d F Y, H:i') }}</small>
                        </span>
                        @if(!$notification->dibaca_pada)
                            <span class="notification-unread-dot"></span>
                        @endif
                    </button>
                </form>
            @empty
                <div class="notification-empty">Belum ada notifikasi.</div>
            @endforelse
        </div>
    </div>
@endsection