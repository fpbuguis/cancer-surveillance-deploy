<script setup>
defineProps({
    type: {
        type: String,
        default: 'submit',
    },
});
</script>

<template>
    <button :type="type" class="inline-flex items-center px-4 py-2 bg-[#b04748] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#d3a5a3] focus:bg-[#d3a5a3] active:bg-[#9f2123] focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
        <slot />
    </button>
</template>
