<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn btn-primary disabled:opacity-50']) }}>
    {{ $slot }}
</button>
