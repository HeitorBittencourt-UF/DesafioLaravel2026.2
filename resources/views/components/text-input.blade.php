@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->class(['flex w-[80%] rounded-[8px] outline-none bg-transparent border border-solid border-white text-white']) }}>