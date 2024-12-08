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
                    <span class="red-color">Kasama</span> mo sa pangangalaga
                </h3>
            </div>

            <div class="columns section1">
                <!-- <img src="@/assets/doctor-page.png" class="patient-page1"/> -->

                <div class="patient-page1">
                    <img src="@/assets/doctor-page.png"/>
                    <p style="font-size: 14px; color: gray;">Source: https://asorianofoundation.org/wp-content/uploads/2015/05/prog-cancer-institute.jpg</p>
                </div>

                <div class="section1-text space-y-5">
                    <p>Why choose CSS</p>
                    <h1>Efficiently manage patients</h1>
                    <p>Choose our system to elevate your practice with
                    a comprehensive application that enhances
                    patient care and streamlines your workflow.
                    Benefit from personalizing care, seamless remote
                    interactions, and precise reporting to optimize
                    treatment outcomes and support your clinical
                    decisions effectively.
                    </p>
                </div>
            </div>


            <div class="space-y-5">
                <p>What CSS offers</p>
                <h1>Streamlined, effective cancer care</h1>
                <p>The CSS enables healthcare providers to:</p>
                <div class="rows">
                    <div class="card">
                        <img src="@/assets/monitor.png" alt="Cancer Surveillance System Logo" class="icons"/>
                        <div>
                            <h1>Monitor & Follow up</h1>
                            <p>progress or status of patients</p>
                        </div>
                    </div>
                    <div class="card">
                        <img src="@/assets/create.png" alt="Cancer Surveillance System Logo" class="icons"/>
                        <div>
                            <h1>Create</h1>
                            <p>individualized treatment plans for patients</p>
                        </div>
                    </div>
                    <div class="card">
                        <img src="@/assets/interact2.png" alt="Cancer Surveillance System Logo" class="icons"/>
                        <div>
                            <h1>Interact</h1>
                            <p>with patients remotely</p>
                        </div>
                    </div>
                    <div class="card">
                        <img src="@/assets/produce.png" alt="Cancer Surveillance System Logo" class="icons"/>
                        <div>
                            <h1>Produce</h1>
                            <p>accurate cancer surveillance reports for research such as:</p>
                            <ul>
                                <li>1. Patient Profiling and Distribution Reports</li>
                                <li>2. Patient Survivorship Reports (Overall Survival, Disease-free Survival, Local Recurrence Rate, Systemic Recurrence Rate)</li>
                                <li>3. Compliance/Adherence to Treatment Plan Reports</li>
                                <li>4. Completion of Treatment Plan Reports</li>
                            </ul>
                        </div>
                    </div>

                    <div class="card">
                        <img src="@/assets/share.png" alt="Cancer Surveillance System Logo" class="icons"/>
                        <div>
                            <h1>Share</h1>
                            <p>support materials related to CSS and cancer care</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="section3">
                <h1>Sa CSS, <span class="red-color">suportado</span> ang nagbibigay alalay</h1>
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

        <Footer></Footer>
    </div>
</template>

<style scoped>

    @import '../../css/Details.css';

    .icons {
        height: 150px;
        width: 150px;
    }

    .card {
        display: flex;
        align-items: center;
        text-align: left;
        flex: 1;
        gap: 3rem;
        padding: 1.5rem 0;
    }

</style>
