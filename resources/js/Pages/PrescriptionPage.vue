<script setup>
import Layout from '@/Pages/Layout.vue';
import jsPDF from 'jspdf';
import { ref } from 'vue';

const patientName = ref('');
const hospitalNo = ref('');
const date = ref('');
const age = ref('');
const gender = ref('');
const ward = ref('');
const classification = ref('');
const mdName = ref('');
const licNo = ref('');
const s2LicNo = ref('');
const prescriptionText = ref('');

const generatePDF = () => {
  const doc = new jsPDF();

  const formatValue = (value) => (value ? String(value) : 'N/A');

  const titleFontSize = 16;
  doc.setFontSize(titleFontSize);

  doc.setFont('helvetica', 'bold');
  const title = 'PRESCRIPTION';
  const titleWidth = doc.getTextWidth(title);
  const xPosition = (doc.internal.pageSize.width - titleWidth) / 2;
  doc.text(title, xPosition, 20);

  doc.setFontSize(12);

//   LEFT
  doc.setFont('helvetica', 'bold');
  doc.text('Patient Name:', 20, 40);
  doc.setFont('helvetica', 'normal');
  doc.text(formatValue(patientName.value), 50, 40);

  doc.setFont('helvetica', 'bold');
  doc.text('Hospital No:', 20, 50);
  doc.setFont('helvetica', 'normal');
  doc.text(formatValue(hospitalNo.value), 50, 50);

//   RIGHT
  doc.setFont('helvetica', 'bold');
  doc.text('Date:', 150, 30);
  doc.setFont('helvetica', 'normal');
  doc.text(formatValue(date.value), 180, 30);

  doc.setFont('helvetica', 'bold');
  doc.text('Age:', 150, 40);
  doc.setFont('helvetica', 'normal');
  doc.text(formatValue(age.value), 180, 40);

  doc.setFont('helvetica', 'bold');
  doc.text('Gender:', 150, 50);
  doc.setFont('helvetica', 'normal');
  doc.text(formatValue(gender.value), 180, 50);

  // CENTER
  doc.setFont('helvetica', 'bold');
  doc.text('Prescription:', 20, 70);
  doc.setFont('helvetica', 'normal');
  const prescriptionLines = doc.splitTextToSize(formatValue(prescriptionText.value), 170);
  doc.text(prescriptionLines, 20, 80);

  doc.setFont('helvetica', 'bold');
  doc.text('M.D.:', 150, 160);
  doc.setFont('helvetica', 'normal');
  doc.text(formatValue(mdName.value), 180, 160);

  doc.setFont('helvetica', 'bold');
  doc.text('Lic. No:', 150, 170);
  doc.setFont('helvetica', 'normal');
  doc.text(formatValue(licNo.value), 180, 170);

  doc.setFont('helvetica', 'bold');
  doc.text('S2 Lic. No:', 150, 180);
  doc.setFont('helvetica', 'normal');
  doc.text(formatValue(s2LicNo.value), 180, 180);

  doc.setFont('helvetica', 'bold');
  doc.text('"Observe Generic Law"', 150, 200);

  doc.setFont('helvetica', 'bold');
  doc.text('Ward:', 20, 220);
  doc.setFont('helvetica', 'normal');
  doc.text(formatValue(ward.value), 50, 220);

  doc.setFont('helvetica', 'bold');
  doc.text('Classification:', 20, 230);
  doc.setFont('helvetica', 'normal');
  doc.text(formatValue(classification.value), 50, 230);

  doc.save('Prescription.pdf');

  patientName.value = '';
  hospitalNo.value = '';
  date.value = '';
  age.value = '';
  gender.value = '';
  ward.value = '';
  classification.value = '';
  prescriptionText.value = '';
  mdName.value = '';
  licNo.value = '';
  s2LicNo.value = '';
};


</script>

<template>
  <Head title="Prescription" />

  <Layout>
    <div class="page-heading">
      <h1 class="main-heading">PRESCRIPTION</h1>
    </div>

    <form @submit.prevent="generatePDF" class="form-container">
      <div>
        <label>Patient Name</label>
        <input v-model="patientName" type="text" />
      </div>

      <div>
        <label>Hospital No</label>
        <input v-model="hospitalNo" type="text" />
      </div>

      <div>
        <label>Date</label>
        <input v-model="date" type="date" />
      </div>

      <div>
        <label>Age</label>
        <input v-model="age" type="number" />
      </div>

      <div>
        <label>Gender</label>
        <select v-model="gender">
          <option value="Male">Male</option>
          <option value="Female">Female</option>
        </select>
      </div>

      <div>
        <label>Ward</label>
        <input v-model="ward" type="text" />
      </div>

      <div>
        <label>Classification</label>
        <input v-model="classification" type="text" />
      </div>

      <div>
        <label>Prescription</label>
        <textarea v-model="prescriptionText" rows="5" placeholder="Enter prescription details here..."></textarea>
      </div>

      <div>
        <label>MD Name</label>
        <input v-model="mdName" type="text" />
      </div>

      <div>
        <label>Lic. No</label>
        <input v-model="licNo" type="text" />
      </div>

      <div>
        <label>S2 Lic. No</label>
        <input v-model="s2LicNo" type="text" />
      </div>

      <button type="submit" class="submit-btn">Generate PDF</button>
    </form>
  </Layout>
</template>

<style scoped>
@import '../../css/GeneralPage.css';

.form-container {
  display: flex;
  flex-direction: column;
  gap: 1rem;
  max-width: 500px;
  margin: 0 auto;
}

.form-container div {
  display: flex;
  flex-direction: column;
}

label {
  font-weight: bold;
  margin-bottom: 0.5rem;
}

input,
select,
textarea {
  padding: 0.5rem;
  font-size: 1rem;
}

textarea {
  resize: vertical;
  height: 100px;
}

.submit-btn {
  padding: 0.7rem 1.5rem;
  font-size: 1rem;
  color: #fff;
  background-color: #a02123;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}
</style>
