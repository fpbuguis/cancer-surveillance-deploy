<template>
    <div v-if="show" class="notification" :class="type">
        <p>{{ message }}</p>
    </div>
</template>

<script>
import { ref, onMounted, watch } from 'vue';

export default {
    props: {
        message: String,
        type: {
            type: String,
            default: 'success',
        },
    },
    setup(props) {
        const show = ref(true);

        onMounted(() => {
            setTimeout(() => {
                show.value = false;
            }, 3000); // Hide after 3 seconds
        });

        watch(props.message, () => {
            show.value = true;
            setTimeout(() => {
                show.value = false;
            }, 3000);
        });

        return { show };
    },
};
</script>

<style scoped>
/* LOWER RIGHT */
/* .notification {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background-color: #28a745;
    color: white;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    transition: opacity 0.3s ease;
}

.notification.error {
    background-color: #dc3545;
} */

/* UPPER RIGHT */
.notification {
    position: fixed;
    top: 20px;
    right: 20px;
    background-color: #28a745;
    color: white;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    transition: opacity 0.3s ease;
}

.notification.error {
    background-color: #dc3545;
}

.notification.warning {
    background-color: #ffd246;
    color: black;
}

/* CENTERED */
/* .notification {
    position: fixed;
    top: 20px;
    left: 50%;
    transform: translateX(-50%);
    background-color: #28a745;
    color: white;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    transition: opacity 0.3s ease;
}

.notification.error {
    background-color: #dc3545;
} */

</style>
