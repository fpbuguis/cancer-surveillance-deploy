<script setup>
import {Link} from '@inertiajs/vue3';
import Header from '@/Components/Header.vue';
import Footer from '@/Components/Footer.vue';
// import { ref } from 'vue';

// const showList = ref(false);

// const toggleList = () => {
//   showList.value = !showList.value;
// };

</script>

<template>
    <div class="h-screen w-full flex flex-col">
        <Header />
        <main class="content-box flex-grow justify-between">
            <div>
                <p> Cancer Surveillance System </p>
                <h1 class="about-title">
                    About <span class="red-color">Cancer Surveillance System</span>
                </h1>
            </div>

            <div class="section1-about">
                <div class="section1-text space-y-5">
                    <p>Why it started</p>
                    <h1>For System Analysis and Design for Health</h1>
                    <p>MS in Health Informatics students formulated the Cancer Surveillance System (CSS) as a major requirement for the course HI 210 (System Analysis and Design for Health), and developed this system with undergraduate Computer Science students of the University of the Philippines Manila.  Prior to CSS, there was a lack of an integrated information system capable of
                        comprehensively monitoring and supporting treatment progress and completion, and supports cancer research.
                    </p>
                    <img src="@/assets/about-page.png" class="about-page"/>
                    <p style="font-size: 14px; color: gray;">Source: https://i.pinimg.com/736x/b9/8a/38/b98a3879a52d344525c7c107081a514a.jpg</p>
                </div>
            </div>

            <div class="space-y-5">
                <p>Objectives</p>
                <h1>To transform cancer care</h1>
                <p>The Cancer Surveillance System (CSS) is designed to optimize cancer care through comprehensive
                tracking and reporting. It enables precise monitoring of patient progress, post-treatment symptoms,
                and laboratory results while facilitating easy communication between patients and doctors. The system
                streamlines laboratory request management, automates reminders and notifications, and supports
                patient engagement through curated educational materials and online support. With enhanced security,
                multilingual options, and integrated research databases, CSS ensures a seamless, efficient, and secure
                healthcare experience for both patients and healthcare providers.
                </p>
            </div>

            <div class="section3-about space-y-5">
                <p>Meet the Team</p>
                <h1>The CSS Team</h1>
                <p>This team was a collaboration between MS in Health Informatics and BS in Computer Science students
                    from the Univeristy of the Philippines Manila.
                </p>

                <div>
                    <div class="columns">
                        <div class="card">
                            <img src="@/assets/woman-icon.png" alt="Cancer Surveillance System Logo" class="icons"/>
                            <div>
                                <p>Ma. Katrina Guillermo</p>
                                <p class="italic">Role</p>
                            </div>
                        </div>
                        <div class="card">
                            <img src="@/assets/woman-icon.png" alt="Cancer Surveillance System Logo" class="icons"/>
                            <div>
                                <p>Alanna Marie Panes</p>
                                <p class="italic">Role</p>
                            </div>
                        </div>
                    </div>
                    <div class="columns">
                        <div class="card">
                            <img src="@/assets/woman-icon.png" alt="Cancer Surveillance System Logo" class="icons"/>
                            <div>
                                <p>Dr. Sheila Magboo</p>
                                <p class="italic">Adviser</p>
                            </div>
                        </div>
                    </div>
                    <div class="columns">
                        <div class="card">
                            <img src="@/assets/nicole.png" alt="Cancer Surveillance System Logo" class="icons"/>
                            <div>
                                <p>Nicole Anne Balde</p>
                                <p class="italic">Backend Developer</p>
                            </div>
                        </div>
                        <div class="card">
                            <img src="@/assets/jonalyn.png" alt="Cancer Surveillance System Logo" class="icons"/>
                            <div>
                                <p>Jonalyn Villanueva</p>
                                <p class="italic">Backend Developer</p>
                            </div>
                        </div>
                        <div class="card">
                            <img src="@/assets/francesca.png" alt="Cancer Surveillance System Logo" class="icons"/>
                            <div>
                                <p>Francesca Maries Buguis</p>
                                <p class="italic">Frontend Developer</p>
                            </div>
                        </div>
                        <div class="card">
                            <img src="@/assets/geraldine.png" alt="Cancer Surveillance System Logo" class="icons"/>
                            <div>
                                <p>Geraldine Audrey Uganiza</p>
                                <p class="italic">Frontend Developer</p>
                            </div>
                        </div>
                    </div>
                </div>
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
        height: 150px;
        width: 150px;
        border-radius: 50%;
        border: 3px solid #a02123;
        object-fit: cover;
    }

    .about-title {
        font-size: 7rem;
        line-height: 7rem;
        margin-top: 2rem;
        padding-right: 5rem;
    }

    .about-page {
        height: auto;
        width: 100%;
        padding-top: 5%;
    }
    .button:hover {
        /* background: #d3a5a3; */
        background: #b04748;
    }

    .section1-about {
        margin: 8% 0 5% 0;
    }

    .section3-about {
        margin: 5% 0;
    }

    .card {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        flex: 1;
        max-width: 25%;
        gap: 1.5rem;
    }

    .columns {
        display: flex;
        justify-content: center;
        gap: 2rem;
        margin-top: 3%;
    }

    @media (min-width: 768px) {
        .columns {
            flex-direction: row;
        }
    }

    .italic {
        font-style: italic;
    }

</style>
