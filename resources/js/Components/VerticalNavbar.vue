<script setup>
    import { Link, usePage } from '@inertiajs/vue3';
    import { ref, watchEffect } from 'vue';
    import { Inertia } from '@inertiajs/inertia';
    import { onMounted } from 'vue';
    import { computed } from 'vue';

    const searchTerm = ref('');
    const search = () => {
    Inertia.get('/search', { name: searchTerm.value }, {
            preserveState: true
        });
    };

    const { url,props } = usePage();
    const userId = props.auth.user.user_id;
    const roleId = props.auth.user.role_id;
    const parts = url.split('/');
    let patientId = parts[parts.length - 1];
    patientId = parseInt(patientId, 10);
    console.log('Patient ID:', patientId);

    function isActive(href) {
        if (href === '/treatment-history') {
            return url === href;
        }
        return url.includes(href);
    }

    // fetch data
    // const departments = ref(null);
    // const hospitals = ref(null);
    // const doctors = ref(null);
    const doctor = ref(null);

    // const fetchDepartments = async () => {
    //     try {
    //         let response = await axios.get('/api/departments');
    //         departments.value = response.data;
    //     } catch (error) {
    //         console.error('Failed to fetch departments:', error);
    //     }
    // };

    // const fetchHospitals = async () => {
    //     try {
    //         let response = await axios.get('/api/hospitals');
    //         hospitals.value = response.data;
    //     } catch (error) {
    //         console.error('Failed to fetch hospitals:', error);
    //     }
    // };

    // const fetchDoctors = async () => {
    //     try {
    //         let response = await axios.get('/api/all-doctors');
    //         doctors.value = response.data;
    //     } catch (error) {
    //         console.error('Failed to fetch doctors:', error);
    //     }
    // };

    // Find the current doctor and their hospital/department
    // const currentDoctor = computed(() =>
    //     doctors.value?.find(doctor => doctor.value?.user_id === userId)
    // );

    const fetchCurrentDoctor = async (userId) => {
        try {
            let response = await axios.get(`/api/doctor-profile/${userId}`);
            doctor.value = response.data;
            console.log("Role ID: ", doctor.value.user_details.role_id);
        } catch (error) {
            console.error(`Failed to fetch doctor with userId ${userId}:`, error);
        }
    };

    // const currentDoctor = computed(() =>
    //     doctors.value?.find(doctor => doctor.user_id === userId)
    // );

    // const currentDoctor = fetchCurrentDoctor(userId)

    // const doctorHospital = computed(() =>
    //     hospitals.value?.find(hospital => hospital.id === currentDoctor.doctor_hospital)?.hospital_name || ''
    // );

    // const doctorDepartment = computed(() =>
    //     departments.value?.find(department => department.id === currentDoctor.doctor_department)?.department_name || ''
    // );

    onMounted(async () => {
        // await Promise.all([fetchDepartments(), fetchHospitals(), fetchDoctors()]);
        if (roleId === 2) {  // only when role is doctor
            fetchCurrentDoctor(userId);
        }
    });

    function formatWord(word) {
        if (!word) return '';
        return word
            .split(' ')
            .map(w => w.charAt(0).toUpperCase() + w.slice(1).toLowerCase())
            .join(' ');
    }

    const showDropdown = ref(false);

    const toggleDropdown = () => {
        Inertia.visit(href);
        showDropdown.value = !showDropdown.value;
    };

    const handleLinkClick = (href) => {
        Inertia.visit(href);
    };

    watchEffect(() => {
        showDropdown.value = url.includes('treatment-history');
    });

</script>

<template>
    <nav class="vertical-navbar">
      <div class="welcome-section">
        <p class="welcome">Welcome,</p>
        <!-- <p class="doctor-name">Dr. {{ $page.props.auth.user?.firstname ?? '' }} {{ $page.props.auth.user?.lastname ?? '' }}</p> -->
        <p class="doctor-name">
            <span v-if="roleId === 2">Dr. </span>
            {{ formatWord($page.props.auth.user?.firstname) }}
            {{ formatWord($page.props.auth.user?.lastname) }}
        </p>
        <p v-if="roleId === 2" class="department">{{ doctor?.department_details?.department_name ?? 'Department' }}</p>
        <p v-if="roleId === 2" class="hospital">{{ doctor?.hospital_details?.hospital_name ?? 'Hospital' }}</p>
      </div>
      <hr>

      <ul class="menu">

        <!-- FOR DOCTOR -->

        <div v-if="roleId === 2">
            <li :class="{ active: isActive('/dashboard') }">
                <Link href="/dashboard">Dashboard</Link>
            </li>
            <li :class="{ active: isActive('/patient-enrollment') }">
                <Link href="/patient-enrollment">Enroll Patient</Link>
            </li>
            <li :class="{ active: isActive('/disease-profile') }">
                <Link href="/disease-profile">Disease Profile</Link>
            </li>
            <li>
                <Link href="/treatment-history" @click="toggleDropdown">Treatment History</Link>
                <ul v-if="showDropdown" class="dropdown">
                    <li :class="{ active: isActive('/treatment-history')}">
                        <Link href="/treatment-history" @click="handleLinkClick(`/treatment-history/${patientId}`)">Primary</Link>
                    </li>
                    <li :class="{ active: isActive('/treatment-history/surgery')}">
                        <Link href="/treatment-history/surgery" @click="handleLinkClick(`/treatment-history/surgery/${patientId}`)">Surgery</Link>
                    </li>
                    <li :class="{ active: isActive('/treatment-history/radiotherapy')}">
                        <Link href="/treatment-history/radiotherapy" @click="handleLinkClick(`/treatment-history/radiotherapy/${patientId}`)">Radiotherapy</Link>
                    </li>
                    <li :class="{ active: isActive('/treatment-history/chemotherapy')}">
                        <Link href="/treatment-history/chemotherapy" @click="handleLinkClick(`/treatment-history/chemotherapy/${patientId}`)">Chemotherapy</Link>
                    </li>
                    <li :class="{ active: isActive('/treatment-history/immunotherapy')}">
                        <Link href="/treatment-history/immunotherapy" @click="handleLinkClick(`/treatment-history/immunotherapy/${patientId}`)">Immunotherapy</Link>
                    </li>
                    <li :class="{ active: isActive('/treatment-history/hormonal')}">
                        <Link href="/treatment-history/hormonal" @click="handleLinkClick(`/treatment-history/hormonal/${patientId}`)">Hormonal</Link>
                    </li>
                </ul>
            </li>
            <li :class="{ active: isActive('/consult') }">
                <Link href="/consult" @click="handleLinkClick(`/consult/${patientId}`)">Consult</Link>
            </li>
            <li :class="{ active: isActive('/doctornotification') }">
                <Link href="/doctornotification">Notification</Link>
            </li>
            <li :class="{ active: isActive('/message') }">
                <Link href="/message">Message</Link>
            </li>
        </div>

        <!-- FOR PATIENT -->

        <div v-if="roleId === 3">
            <li :class="{ active: isActive('/symptomsreport') }">
                <a href="/symptomsreport">Report Symptoms</a>
            </li>
            <li :class="{ active: isActive('/laboratoryrequest') }">
                <a href="/laboratoryrequest">Request Documents</a>
            </li>
            <li :class="{ active: isActive('/laboratoryresult') }">
                <a href="/laboratoryresult">Submit Laboratory</a>
            </li>
            <li :class="{ active: isActive('/patientnotification') }">
                <Link href="/patientnotification">Notification</Link>
            </li>
            <li :class="{ active: isActive('/message') }">
                <Link href="/message">Message</Link>
            </li>

        </div>

      </ul>

      <div class="search-patient">
        <input type="text" placeholder="Find patient (ID or Lastname)" v-model="searchTerm"/>
        <button class="search-icon" @click="search"><img src="@/assets/search.png" alt="Search" /></button>
      </div>
    </nav>
</template>


<style scoped>
    @import '../../css/VerticalNavbar.css';

    .dropdown {
        font-size: 0.9rem;
    }

    .dropdown li {
        padding: 0 1.5rem;
    }
</style>
