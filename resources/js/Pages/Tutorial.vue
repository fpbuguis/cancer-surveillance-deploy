<script setup>
import { Link } from '@inertiajs/vue3';
import Header from '@/Components/Header.vue';
import Footer from '@/Components/Footer.vue';
</script>

<template>
    <div class="h-screen w-full flex flex-col">
        <Header />
        <main class="content-box flex-grow flex justify-center items-center">
            <iframe
                src="/functionalities.pdf"
                type="application/pdf"
                width="60%"
                height="1050px"
                class="pdf-viewer"
            >
                Your browser does not support iframes.
                <a href="/functionalities.pdf" target="_blank">Download the PDF</a>
            </iframe>
        </main>
        <Footer />
    </div>
</template>

<style scoped>
    @import '../../css/Details.css';

    .pdf-viewer {
        border: none;
    }
</style>
