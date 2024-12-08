<script setup>
import { computed } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import Footer from '@/Components/Footer.vue';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    status: String,
});

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(() => props.status === 'verification-link-sent');
</script>

<template>
    <div class="h-screen w-screen flex flex-col">
        <Head title="Email Verification" />

        <main class="flex-grow flex items-center justify-center">
            <section class="w-full max-w-md">
                <AuthenticationCard>
                    <div class="login-form">
                        <div class="mb-4 text-sm text-gray-600 text-justify">
                            Before continuing, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.
                        </div>

                        <div v-if="verificationLinkSent" class="mb-4 font-medium text-sm text-green-600">
                            A new verification link has been sent to the email address you provided in your profile settings.
                        </div>

                        <form @submit.prevent="submit" class="mt-9">
                            <div class="flex justify-center mt-10">
                                <PrimaryButton :class="{ 'opacity-25': form.processing }" class="reset-btn" :disabled="form.processing">
                                    Resend Verification Email
                                </PrimaryButton>
                            </div>

                            <div class="mt-4 flex items-center justify-center">
                                <!-- <Link
                                    :href="route('profile.show')"
                                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                >
                                    Edit Profile</Link> -->

                                <Link
                                    :href="route('logout')"
                                    method="post"
                                    as="button"
                                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 ms-2"
                                >
                                    Log Out
                                </Link>
                            </div>
                        </form>
                    </div>
                </AuthenticationCard>
            </section>
        </main>

        <Footer style="background-color: #f0f0f0; color: #333; font-weight: bolder;" />
    </div>
</template>

<style scoped>
    @import '../../../css/ForgotPassword.css';

    .h-screen {
        height: 100vh;
    }

    .w-screen {
        width: 100vw;
    }

    .login-form {
        background-color: #e6e6e6;
        border: 3px solid #e6e6e6;
        box-shadow: 0 4px 8px rgba(244, 227, 226, 0.5);
        padding: 60px 2rem;
        border-radius: 0.5rem;
        max-width: 100%;
    }

    .reset-btn {
        background-color: #a02123;
        color: white;
        width: 100%;
        justify-content: center;
    }

    .reset-btn:hover {
        background-color: #8a0e0e;
    }
</style>
