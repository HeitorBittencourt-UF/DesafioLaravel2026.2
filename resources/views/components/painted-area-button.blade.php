
<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 border border-white rounded-md class="font-montserrat bg-transparent text-[15px] font-bold border border-white transition-all duration-200 hover:scale-110 hover:bg-white hover:text-[#42B9A6] font-semibold text-white uppercase tracking-widest focus:outline-none transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button> 