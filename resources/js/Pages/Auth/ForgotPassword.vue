<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { router } from '@inertiajs/vue3';
import Footer from '@/Components/Footer.vue';
import { Link } from '@inertiajs/vue3';

defineProps({
    status: String,
});

const form = useForm({
    email: '',
});


const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <div class="h-screen w-screen flex flex-col">
        <Head title="Forgot Password" />

        <div class="top-icons">
            <div class="w-14">
                <Link href="/login">
                    <img src="@/assets/home.png" alt="Home"/>
                </Link>
            </div>
        </div>

        <main class="flex-grow flex items-center justify-center">
            <section class="w-full max-w-md">
                <AuthenticationCard>
        
                    <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                        {{ status }}
                    </div>

                    <form @submit.prevent="submit" class="login-form">

                        <h2 class="text-2xl font-bold mb-2">Forgot Password?</h2>
                        <p class="text-sm text-gray-600 mb-4">Enter your email below:</p>

                        <div class="mt-9">
                            <InputLabel for="email" value="Email" class="block text-left font-semibold" />
                            <TextInput
                                id="email"
                                v-model="form.email"
                                type="email"
                                class="input-style"
                                required
                                autofocus
                                autocomplete="username"
                            />
                            <InputError class="text-red-500 text-sm mt-1" :message="form.errors.email" />
                        </div>

                        <div class="flex justify-center mt-10">
                            <PrimaryButton :class="{ 'opacity-25': form.processing }" class="reset-btn" :disabled="form.processing">
                                Reset Password
                            </PrimaryButton>
                        </div>
                    </form>
                </AuthenticationCard>
           
            </section>
        </main>

        <Footer style="background-color: #f0f0f0; color: #333; font-weight: bolder;" />

    </div>
    
</template>


<style scoped>
    @import '../../../css/ForgotPassword.css';
</style>
