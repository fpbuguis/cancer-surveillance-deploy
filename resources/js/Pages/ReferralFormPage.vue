<script setup>
import Layout from '@/Pages/Layout.vue';
import jsPDF from 'jspdf';
import { ref } from 'vue';

const nameOfHealthcareProvider = ref('');
const specialty = ref('');
const providerEmail = ref('');
const preferredPhone = ref('');
const address = ref('');
const city = ref('');
const state = ref('');
const zipCode = ref('');
const firstName = ref('');
const lastName = ref('');
const dob = ref('');
const patientEmail = ref('');
const patientPhone = ref('');
const diagnosis = ref('');
const medicalHistory = ref('');
const familyHistory = ref('');
const reasonForReferral = ref('');
const additionalComment = ref('');

const formatValue = (value) => (value ? String(value) : 'N/A');
const generatePDF = () => {
  const doc = new jsPDF();
  let y = 20;

  const addText = (text, x, y, options = {}) => {
    if (y > 280) {
      doc.addPage();
      y = 20;
    }
    doc.text(text, x, y, options);
    return y;
  };

  const addMultiLineText = (text, x, y, width) => {
    const lines = doc.splitTextToSize(text, width);
    lines.forEach((line) => {
      y = addText(line, x, y);
      y += 10;
    });
    return y;
  };

  // Title
  doc.setFont('helvetica', 'bold');
  doc.setFontSize(16);
  y = addText('MEDICAL TREATMENT FORM', 105, y, { align: 'center' });

  doc.setFontSize(12);

  // Refer to Section
  y += 20;
  y = addText('Refer to', 20, y);
  doc.setFont('helvetica', 'bold');
  y = addText('Name of healthcare provider:', 20, y + 10);
  doc.setFont('helvetica', 'normal');
  y = addText(formatValue(nameOfHealthcareProvider.value), 90, y);

  doc.setFont('helvetica', 'bold');
  y = addText('Specialty:', 20, y + 10);
  doc.setFont('helvetica', 'normal');
  y = addText(formatValue(specialty.value), 90, y);

  doc.setFont('helvetica', 'bold');
  y = addText('Email:', 20, y + 10);
  doc.setFont('helvetica', 'normal');
  y = addText(formatValue(providerEmail.value), 90, y);

  doc.setFont('helvetica', 'bold');
  y = addText('Preferred phone number:', 20, y + 10);
  doc.setFont('helvetica', 'normal');
  y = addText(formatValue(preferredPhone.value), 90, y);

  doc.setFont('helvetica', 'bold');
  y = addText('Address:', 20, y + 10);
  doc.setFont('helvetica', 'normal');
  y = addText(formatValue(address.value), 90, y);

  doc.setFont('helvetica', 'bold');
  y = addText('City:', 20, y + 10);
  doc.setFont('helvetica', 'normal');
  y = addText(formatValue(city.value), 90, y);

  doc.setFont('helvetica', 'bold');
  y = addText('State:', 20, y + 10);
  doc.setFont('helvetica', 'normal');
  y = addText(formatValue(state.value), 90, y);

  doc.setFont('helvetica', 'bold');
  y = addText('Zip Code:', 20, y + 10);
  doc.setFont('helvetica', 'normal');
  y = addText(formatValue(zipCode.value), 90, y);

  // Patient Information Section
  y += 20;
  doc.setFont('helvetica', 'bold');
  y = addText('Patient Information', 20, y);

  doc.text('First Name:', 20, y + 10);
  doc.setFont('helvetica', 'normal');
  y = addText(formatValue(firstName.value), 90, y + 10);

  doc.setFont('helvetica', 'bold');
  y = addText('Last Name:', 20, y + 10);
  doc.setFont('helvetica', 'normal');
  y = addText(formatValue(lastName.value), 90, y);

  doc.setFont('helvetica', 'bold');
  y = addText('Date of Birth:', 20, y + 10);
  doc.setFont('helvetica', 'normal');
  y = addText(formatValue(dob.value), 90, y);

  doc.setFont('helvetica', 'bold');
  y = addText('Email:', 20, y + 10);
  doc.setFont('helvetica', 'normal');
  y = addText(formatValue(patientEmail.value), 90, y);

  doc.setFont('helvetica', 'bold');
  y = addText('Preferred phone number:', 20, y + 10);
  doc.setFont('helvetica', 'normal');
  y = addText(formatValue(patientPhone.value), 90, y);

  // Diagnosis Section
  y += 20;
  doc.setFont('helvetica', 'bold');
  y = addText('Diagnosis of referring healthcare practitioner:', 20, y);
  doc.setFont('helvetica', 'normal');
  y = addMultiLineText(formatValue(diagnosis.value), 20, y + 10, 170);

  // Medical History Section
  y += 10;
  doc.setFont('helvetica', 'bold');
  y = addText('Medical History:', 20, y);
  doc.setFont('helvetica', 'normal');
  y = addMultiLineText(formatValue(medicalHistory.value), 20, y + 10, 170);

  // Family History Section
  y += 10;
  doc.setFont('helvetica', 'bold');
  y = addText('Family History:', 20, y);
  doc.setFont('helvetica', 'normal');
  y = addMultiLineText(formatValue(familyHistory.value), 20, y + 10, 170);

  // Reason for Referral Section
  y += 10;
  doc.setFont('helvetica', 'bold');
  y = addText('Reason for Referral:', 20, y);
  doc.setFont('helvetica', 'normal');
  y = addMultiLineText(formatValue(reasonForReferral.value), 20, y + 10, 170);

  // Additional Comment Section
  y += 10;
  doc.setFont('helvetica', 'bold');
  y = addText('Additional Comment:', 20, y);
  doc.setFont('helvetica', 'normal');
  y = addMultiLineText(formatValue(additionalComment.value), 20, y + 10, 170);

  // Save PDF
  doc.save('Medical_Referral_Form.pdf');
};


</script>

<template>
      <Head title="Referral Form" />

    <Layout>
        <div class="page-heading">
            <h1 class="main-heading">REFERRAL FORM</h1>
        </div>
        <form @submit.prevent="generatePDF" class="form-container">

            <!-- Refer to Section -->
            <h2 style="text-align: center;">REFER TO</h2>
            <div>
            <label>Name of healthcare provider:</label>
            <input v-model="nameOfHealthcareProvider" type="text" />
            </div>
            <div>
            <label>Specialty:</label>
            <input v-model="specialty" type="text" />
            </div>
            <div>
            <label>Email:</label>
            <input v-model="providerEmail" type="email" />
            </div>
            <div>
            <label>Preferred phone number:</label>
            <input v-model="preferredPhone" type="tel" />
            </div>
            <div>
            <label>Address:</label>
            <input v-model="address" type="text" />
            </div>
            <div>
            <label>City:</label>
            <input v-model="city" type="text" />
            </div>
            <div>
            <label>State:</label>
            <input v-model="state" type="text" />
            </div>
            <div>
            <label>Zip Code:</label>
            <input v-model="zipCode" type="text" />
            </div>

            <!-- Patient Information Section -->
            <h2 style="text-align: center;">PATIENT INFORMATION</h2>
            <div>
            <label>First Name:</label>
            <input v-model="firstName" type="text" />
            </div>
            <div>
            <label>Last Name:</label>
            <input v-model="lastName" type="text" />
            </div>
            <div>
            <label>Date of Birth:</label>
            <input v-model="dob" type="date" />
            </div>
            <div>
            <label>Email:</label>
            <input v-model="patientEmail" type="email" />
            </div>
            <div>
            <label>Preferred phone number:</label>
            <input v-model="patientPhone" type="tel" />
            </div>

            <!-- Other Sections -->
            <div>
            <label>Diagnosis of referring healthcare practitioner:</label>
            <textarea v-model="diagnosis"></textarea>
            </div>
            <div>
            <label>Medical History:</label>
            <textarea v-model="medicalHistory"></textarea>
            </div>
            <div>
            <label>Family History:</label>
            <textarea v-model="familyHistory"></textarea>
            </div>
            <div>
            <label>Reason for Referral:</label>
            <textarea v-model="reasonForReferral"></textarea>
            </div>
            <div>
            <label>Additional Comment:</label>
            <textarea v-model="additionalComment"></textarea>
            </div>

            <button type="submit" class="submit-btn">Generate PDF</button>
        </form>
    </Layout>
</template>

<style scoped>
@import '../../css/GeneralPage.css';

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
