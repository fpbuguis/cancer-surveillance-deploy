<script setup>
import {Link} from '@inertiajs/vue3';
import Header from '@/Components/Header.vue';
import Footer from '@/Components/Footer.vue';

</script>

<template>
    <div class="h-screen w-full flex flex-col">
        <Header />
        <main class="content-box flex-grow justify-between">
            <div>
                <p> Cancer Surveillance System </p>
                <h1 class="contact-us-title">
                    Contact Us 
                </h1>
            </div>

            <div class="rectangle-section">
                <div class="left-side space-y-5">
                    <p><strong>Email Address</strong></p>
                    <p class="small-text">mbguillermo2@up.edu.ph</p>

                    <p><strong>Contact Number</strong></p>
                    <p class="small-text">0949 998 8305</p>

                    <!-- <p><strong>Location</strong></p>
                    <p class="small-text">Philippine General Hospital, Taft Avenue Ermita, Brgy 670 Zone 72, Manila, 1000 Metro Manila</p> -->
                </div>

                <div class="right-side space-y-5">
                    <form>
                        <input type="text" id="email" placeholder="Enter your email" />

                        <input type="text" id="name" placeholder="Enter your name" />
                        <textarea type="text" id="name" placeholder="Type your query here" class="text-area"/>
                    
                        <button type="submit">SUBMIT</button>
                    </form> 
                </div>
            </div>

            <h3>
                <span class="red-color">Magkasama</span> natin puksain ang kanser
            </h3>

            <div class="mt-5">
                <p>Interested? Learn more.</p>
                <div class="buttons-col2">
                    <Link
                        href="#"
                        class="button2">
                        How to use CSS
                    </Link>
                    <Link
                        href="/patient"
                        class="button2">
                        Why Patients Use CSS
                    </Link>
                    <Link
                        href="/doctor"
                        class="button2">
                        Why Doctors Use CSS
                    </Link>
                </div>
            </div>

        </main>

        <Footer />
    </div>
</template>

<style scoped>

    @import '../../css/Details.css';

    .text-area {
        min-height: 200px;
    }

</style>
