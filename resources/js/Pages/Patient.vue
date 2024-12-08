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
                <h3>
                    <span class="red-color">Bahagi</span> ka ng iyong paggaling
                </h3>
            </div>

            <div class="columns section1">
                <div class="patient-page1">
                    <img src="@/assets/patient-page1.png" />
                    <p style="font-size: 14px; color: gray;">Source: https://mir-s3-cdn-cf.behance.net/project_modules/max_1200/bfe33a44437351.581260d308867.jpg</p>
                </div>

                <div class="section1-text space-y-5">
                    <p>Why choose CSS</p>
                    <h1>We are patient-centered</h1>
                    <p>Choose the Cancer Surveillance System (CSS)
                    for a smarter, more supportive approach to your
                    healthcare. Our system brings everything you
                    need into one easy-to-use application, making it
                    simple to stay on top of your treatment and
                    connect with your healthcare team. Experience a
                    streamlined, personalized care journey that puts
                    you at the center of your health management.
                    </p>
                </div>
            </div>

            <div class="space-y-5">
                <p>What CSS offers</p>
                <h1>Seamless cancer care experience</h1>
                <p>The CSS enables patients or their caregivers to:</p>
                <div class="columns">
                    <div class="card">
                        <img src="@/assets/notified.png" alt="Cancer Surveillance System Logo" class="icons"/>
                        <h1>Be notified</h1>
                        <p>about follow-up treatments and laboratory workups via email and in-app alerts</p>
                    </div>
                    <div class="card">
                        <img src="@/assets/submit.png" alt="Cancer Surveillance System Logo" class="icons"/>
                        <h1>Submit</h1>
                        <p>laboratory workups related to the treatment plan</p>
                    </div>
                    <div class="card">
                        <img src="@/assets/track.png" alt="Cancer Surveillance System Logo" class="icons"/>
                        <h1>Track</h1>
                        <p>treatment plan history and progress</p>
                    </div>
                    <div class="card">
                        <img src="@/assets/interact.png" alt="Cancer Surveillance System Logo" class="icons"/>
                        <h1>Interact</h1>
                        <p>with healthcare providers remotely</p>
                    </div>
                    <div class="card">
                        <img src="@/assets/access.png" alt="Cancer Surveillance System Logo" class="icons"/>
                        <h1>Access</h1>
                        <p>support materials related to CSS and cancer care</p>
                    </div>
                </div>
            </div>

            <div class="section3">
                <h1>Sa CSS, <span class="red-color">katuwang</span> mo ang iyong doktor</h1>
            </div>

            <div>
                <p>Interested? Learn more.</p>
                <div class="buttons-col">
                    <Link
                        href="/tutorial"
                        class="button">
                        How to use CSS
                    </Link>
                    <Link
                        href="/contact"
                        class="button">
                        Contact Us
                    </Link>
                </div>
            </div>

        </main>

        <Footer />
    </div>
</template>

<style scoped>
    @import '../../css/Details.css';

    .icons {
        height: 130px;
        width: 130px;
    }

    .card {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        flex: 1;
        gap: 1rem;
        padding: 1rem 0;
    }

</style>
