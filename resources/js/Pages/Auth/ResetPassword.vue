<script setup>
import { ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Footer from '@/Components/Footer.vue'; 

const props = defineProps({
    email: String,
    token: String,
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.update'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <div class="h-screen w-screen flex flex-col">
        <Head title="Reset Password" />

        <!-- Top Icon (optional) -->
        <div class="top-icons">
            <div class="w-14">
                <Link href="/login">
                    <img src="@/assets/home.png" alt="Home" />
                </Link>
            </div>
        </div>

        <main class="flex-grow flex items-center justify-center">
            <section class="w-full max-w-md">
                <AuthenticationCard>
                    <form @submit.prevent="submit" class="login-form">
                        <h2 class="text-2xl font-bold mb-2">Reset Password</h2>
                        <p class="text-sm text-gray-600 mb-4">Please enter your new password below:</p>

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

                        <div class="mt-4">
                            <InputLabel for="password" value="Password" class="block text-left font-semibold" />
                            <TextInput
                                id="password"
                                v-model="form.password"
                                type="password"
                                class="input-style"
                                required
                                autocomplete="new-password"
                            />
                            <InputError class="text-red-500 text-sm mt-1" :message="form.errors.password" />
                        </div>

                        <div class="mt-4">
                            <InputLabel for="password_confirmation" value="Confirm Password" class="block text-left font-semibold" />
                            <TextInput
                                id="password_confirmation"
                                v-model="form.password_confirmation"
                                type="password"
                                class="input-style"
                                required
                                autocomplete="new-password"
                            />
                            <InputError class="text-red-500 text-sm mt-1" :message="form.errors.password_confirmation" />
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

        <!-- Footer Section -->
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

    .top-icons {
        display: flex;
        justify-content: flex-end;
        padding: 10px;
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

    .input-style {
        border: 1px solid #ccc;
        padding: 8px;
        border-radius: 3px;
        width: 100%;
        margin-top: 10px;
    }
</style>
