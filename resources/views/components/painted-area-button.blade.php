<button {{ $attributes->merge([
    'type' => 'submit',
    'class' => 'inline-flex items-center rounded-md border border-white bg-transparent px-4 py-2 font-montserrat text-[15px] font-bold uppercase tracking-widest text-white transition duration-200 hover:scale-110 hover:bg-white hover:text-[#42B9A6] focus:outline-none focus:ring-2 focus:ring-white',
]) }}>
    {{ $slot }}
</button>
