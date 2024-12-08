<script setup>
import Layout from '@/Pages/Layout.vue';
import jsPDF from 'jspdf';
import { ref } from 'vue';

const date = ref('');
const name = ref('');
const age = ref('');
const sex = ref('');
const civilStatus = ref('');
const address = ref('');
const medicalCondition = ref('');
const remarks = ref('');
const physicianName = ref('');
const licenseNo = ref('');
const ptrNo = ref('');

const resetForm = () => {
  date.value = '';
  name.value = '';
  age.value = '';
  sex.value = '';
  civilStatus.value = '';
  address.value = '';
  medicalCondition.value = '';
  remarks.value = '';
  physicianName.value = '';
  licenseNo.value = '';
  ptrNo.value = '';
};

const formatValue = (value) => (value ? String(value) : 'N/A');

const generatePDF = () => {
  const doc = new jsPDF();

  const titleFontSize = 16;
  doc.setFontSize(titleFontSize);

  doc.setFont('helvetica', 'bold');
  const title = 'MEDICAL CERTIFICATE';
  const titleWidth = doc.getTextWidth(title);
  const xPosition = (doc.internal.pageSize.width - titleWidth) / 2;
  doc.text(title, xPosition, 20);

  doc.setFontSize(12);
  doc.text(`Date:`, 150, 30, { align: 'right' });
  doc.setFont('helvetica', 'normal');
  doc.text(formatValue(date.value), 160, 30);

  doc.setFont('helvetica', 'normal');
  doc.text('To whom it may concern:', 20, 40);

  doc.setFont('helvetica', 'bold');
  doc.text('This is to certify that ', 50, 50);

  doc.setFont('helvetica', 'normal');
  doc.text(formatValue(name.value), 100, 50);

  doc.setFont('helvetica', 'bold');
  doc.text('age:', 20, 60);

  doc.setFont('helvetica', 'normal');
  doc.text(formatValue(age.value), 35, 60);

  doc.setFont('helvetica', 'bold');
  doc.text(', sex:', 70, 60);
  doc.setFont('helvetica', 'normal');
  doc.text(formatValue(sex.value), 85, 60);

  doc.setFont('helvetica', 'bold');
  doc.text(', civil status:', 110, 60);
  doc.setFont('helvetica', 'normal');
  doc.text(formatValue(civilStatus.value), 140, 60);

  doc.setFont('helvetica', 'bold');
  doc.text('residing at:', 20, 70);
  doc.setFont('helvetica', 'normal');
  doc.text(formatValue(address.value), 55, 70);

  doc.setFont('helvetica', 'bold');
  doc.text('has been under my medical care, for the following medical condition:', 20, 80);
  doc.setFont('helvetica', 'normal');
  const medicalConditionLines = doc.splitTextToSize(formatValue(medicalCondition.value), 170);
  doc.text(medicalConditionLines, 20, 90);

  doc.text('This certification is being issued upon request to be used exclusively for medical purposes.', 20, 130, { maxWidth: 170 });

  doc.setFont('helvetica', 'bold');
  doc.text('REMARKS:', 20, 150);
  doc.setFont('helvetica', 'normal');
  const remarksStartY = 160;
  const remarksLines = doc.splitTextToSize(formatValue(remarks.value), 170);
  doc.text(remarksLines, 20, remarksStartY);

  doc.setFont('helvetica', 'normal');
  doc.text(formatValue(physicianName.value).toUpperCase(), 150, 200);

  doc.setFont('helvetica', 'normal');
  doc.text('Signature over printed name', 150, 210);

  doc.setFont('helvetica', 'bold');
  doc.text('Attending Physician', 150, 220);

  doc.setFont('helvetica', 'bold');
  doc.text('License No:', 150, 250);
  doc.setFont('helvetica', 'normal');
  doc.text(formatValue(licenseNo.value), 180, 250);

  doc.setFont('helvetica', 'bold');
  doc.text('PTR No:', 150, 260);
  doc.setFont('helvetica', 'normal');
  doc.text(formatValue(ptrNo.value), 180, 260);

  doc.save('Medical_Certificate.pdf');

  resetForm();

};

</script>

<template>
      <Head title="Medical Certification" />

    <Layout>
        <div class="page-heading">
            <h1 class="main-heading">MEDICAL CERTIFICATION</h1>
        </div>
        <form @submit.prevent="generatePDF" class="form-container">
            <div>
            <label>Date:</label>
            <input type="date" v-model="date" />
            </div>
            <div>
            <label>Name:</label>
            <input type="text" v-model="name" />
            </div>
            <div>
            <label>Age:</label>
            <input type="number" v-model="age" />
            </div>
            <div>
            <label>Sex:</label>
            <select v-model="sex">
                <option>Male</option>
                <option>Female</option>
            </select>
            </div>
            <div>
            <label>Civil Status:</label>
            <input type="text" v-model="civilStatus" />
            </div>
            <div>
            <label>Address:</label>
            <input type="text" v-model="address" />
            </div>
            <div>
            <label>Medical Condition:</label>
            <textarea v-model="medicalCondition"></textarea>
            </div>
            <div>
            <label>Remarks:</label>
            <textarea v-model="remarks"></textarea>
            </div>
            <div>
            <label>Physician's Name:</label>
            <input type="text" v-model="physicianName" />
            </div>
            <div>
            <label>License No:</label>
            <input type="text" v-model="licenseNo" />
            </div>
            <div>
            <label>PTR No:</label>
            <input type="text" v-model="ptrNo" />
            </div>
            <button type="submit" class="submit-btn">Generate PDF</button>
        </form>
    </Layout>
</template>

<style scoped>
@import '../../css/GeneralPage.css';

.submit-btn {
  padding: 0.7rem 1.5rem;
  font-size: 1rem;
  color: #fff;
  background-color: #a02123;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

textarea {
  resize: vertical;
  height: 100px;
}

</style>
