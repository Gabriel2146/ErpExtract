@extends('layouts/contentNavbarLayout')

@section('title', 'Notificaciones')

@section('content')
<div class="card mb-6">
    <div class="card-body">
        <h3>Notificaciones</h3>

        <table id="notificationsTable" class="table table-striped">
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Mensaje</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($notifications as $notification)
                <tr data-id="{{ $notification->id }}">
                    <td>{{ $notification->title }}</td>
                    <td>{{ $notification->message }}</td>
                    <td>{{ $notification->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        @if($notification->read_at)
                        <span class="badge bg-label-success">Leída</span>
                        @else
                        <span class="badge bg-label-danger">No leída</span>
                        @endif
                    </td>
                    <td>
                        @if(!$notification->read_at)
                        <button class="btn btn-sm btn-info mark-as-read-btn" data-id="{{ $notification->id }}">
                            Marcar como leída
                        </button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">No hay notificaciones</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('page-script')
<script>
    $(document).ready(function() {
        $('#notificationsTable').DataTable({
            responsive: true, // activa el responsive
            pageLength: 10, // número de filas por página            
            order: [
                [2, 'desc']
            ]
        });

        // Marcar notificación como leída vía AJAX
        $('.mark-as-read-btn').on('click', function() {
            let btn = $(this);
            let row = btn.closest('tr');
            let notificationId = btn.data('id');

            $.ajax({
                url: '/notifications/' + notificationId + '/read',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function() {
                    row.find('td:nth-child(4) span')
                        .removeClass('bg-label-danger')
                        .addClass('bg-label-success')
                        .text('Leída');
                    btn.remove();
                },
                error: function() {
                    alert('Error al marcar la notificación como leída.');
                }
            });
        });
    });
</script>
@endsection