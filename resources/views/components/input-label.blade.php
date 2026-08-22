@props(['value'])

<label {{ $attributes->class(['block font-medium text-sm text-gray-700 dark:text-gray-300 font-montserrat absolute top-1/2 left-[18%] -translate-x-1/2 -translate-y-1/2 pointer-events-none transition-all duration-300 bg-[#042434] px-[5px] text-gray-500']) }}>
    {{ $value ?? $slot }}
</label>