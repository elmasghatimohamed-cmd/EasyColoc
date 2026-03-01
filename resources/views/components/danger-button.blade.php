<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn btn-danger disabled:opacity-50']) }}>
    {{ $slot }}
</button>
