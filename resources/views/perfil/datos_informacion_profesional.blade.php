<div class="row">
    <label class="col-sm-2 col-form-label">Áreas de interés:</label>
    <label class="col-sm-9 col-form-label">
        @forelse ($usuario->area_intereses as $usuario_interes)
            {{ $usuario_interes->nombre }}<br>
        @empty
            Sin áreas
        @endforelse
    </label>
</div>
