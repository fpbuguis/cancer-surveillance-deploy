<script setup>
import { Link, usePage } from '@inertiajs/vue3';

const { url } = usePage();

console.log(url)

function isActive(href) {
    return url == href;
}

</script>

<template>
    <header class="header">
        <nav class="navbar">
            <div class="navbar-text">
                <Link
                    href="/login"
                    :class="{ active: isActive('/login') }">
                    Home
                </Link>
                <Link
                    href="/patient"
                    :class="{ active: isActive('/patient') }">
                    Patient
                </Link>
                <Link
                    href="/doctor"
                    :class="{ active: isActive('/doctor') }">
                    Doctor
                </Link>
                <Link
                    href="/tutorial"
                    :class="{ active: isActive('/tutorial') }">
                    Tutorial
                </Link>
                <Link
                    href="/contact"
                    :class="{ active: isActive('/contact') }">
                    Contact
                </Link>
                <Link
                    href="/about"
                    :class="{ active: isActive('/about') }">
                    About
                </Link>
            </div>
        </nav>
    </header>
</template>

<style scoped>
    .header {
        background: #9f2123;
        color: white;
        padding: 3rem 0 2rem 0;
    }

    .navbar {
        display: flex;
        justify-content: center;
        flex-direction: column;
        margin-left: auto;
        margin-right: auto;
    }

    @media (min-width: 768px) {
        .navbar {
            flex-direction: row;
        }
    }

    .navbar-text {
        margin-top: 1rem;
        text-align: center;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    @media (min-width: 768px) {
        .navbar-text {
            font-weight: bold;
            margin-top: 0;
            flex-direction: row;
            gap: 5rem;
            align-items: center;
            text-align: left;
        }
    }

    .navbar-text a {
    text-decoration: none;
    }

    /* .navbar-text a.active {
        text-decoration: underline;
    } */

    .navbar-text a.active {
        position: relative;
        font-weight: bold;
    }

    .navbar-text a.active::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: -2px;
        width: 100%;
        height: 2px;
        background-color: currentColor;
    }


</style>
