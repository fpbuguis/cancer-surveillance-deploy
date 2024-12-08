<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

import Header from '@/Components/Header.vue';
import Footer from '@/Components/Footer.vue';

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.transform(data => ({
        ...data,
        remember: form.remember ? 'on' : '',
    })).post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <div class="h-screen w-screen flex flex-col">
        <Head title="Log in" />

        <!-- Use Header component -->
        <Header />

        <!-- Main content section -->
        <main class="flex-grow flex flex-col md:flex-row justify-between">
            <!-- Left side content -->
            <section class="w-full h-full flex flex-col items-end pr-20 justify-center">
                <div class="flex items-center space-x-8">
                    <img src="@/assets/UP_Manila_Logo.png" alt="Cancer Surveillance System Logo" class="h-36 w-36"/>
                    <div>
                        <h1 class="text-5xl font-bold leading-tight">
                            Cancer<br/>Surveillance<br/>System
                        </h1>
                    </div>
                </div>
                <div class="left-section-subtext">
                    <h3 class="mt-10 text-black text-center font-bold">"Magkasama natin puksain ang kanser"</h3>
                </div>
            </section>

            <!-- Right side - login box -->
            <section class="w-full h-full flex items-center justify-start">
                <AuthenticationCard class="pl-20">
                    <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                        {{ status }}
                    </div>

                    <form @submit.prevent="submit" class="login-form">
                        <h2 class="text-2xl font-bold mb-4 text-red text-center">LOGIN</h2>
                        <div>
                            <InputLabel for="email" value="Email" />
                            <TextInput
                                id="email"
                                v-model="form.email"
                                type="email"
                                class="mt-1 block w-full"
                                required
                                autofocus
                                autocomplete="username"
                            />
                            <InputError class="mt-2" :message="form.errors.email" />
                        </div>

                        <div class="mt-4">
                            <InputLabel for="password" value="Password" />
                            <TextInput
                                id="password"
                                v-model="form.password"
                                type="password"
                                class="mt-1 block w-full"
                                required
                                autocomplete="current-password"
                            />
                            <InputError class="mt-2" :message="form.errors.password" />
                        </div>

                        <div class="flex justify-center m-9">
                            <PrimaryButton class="submit-button" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                Submit
                            </PrimaryButton>
                        </div>

                         <div class="flex justify-between mt-4">
                            <!-- Forgot your password? link -->
                            <Link v-if="canResetPassword" :href="route('password.request')" class="forgot-password-link underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" style="font-size: 14px;">
                                Forgot password?
                            </Link>

                            <Link :href="route('register')" class="register-link underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Register Now
                            </Link>
                        </div>
                    </form>
                </AuthenticationCard>
            </section>
        </main>

        <!-- Use Footer component -->
        <Footer />
    </div>
</template>

<style scoped>
    @import '../../../css/Login.css';
</style>
