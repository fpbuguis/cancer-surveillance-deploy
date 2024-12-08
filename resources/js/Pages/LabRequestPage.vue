<script setup>
import Layout from '@/Pages/Layout.vue';
import jsPDF from 'jspdf';
import { ref } from 'vue';

const wardRoomBed = ref('');
const nameLast = ref('');
const nameFirst = ref('');
const nameMI = ref('');
const age = ref('');
const sex = ref('');
const hospitalCaseNo = ref('');
const birthDate = ref('');
const diagnosis = ref('');
const requestedBy = ref('');
const doctorSignature = ref('');
const labExamDesired = ref('');
const specimen = ref('');
const siteOfCollection = ref('');
const collectedBy = ref('');
const timeCollected = ref('');
const dateCollected = ref('');
const contactNumber = ref('');

const generatePDF = () => {
  const doc = new jsPDF();
  const formatValue = (value) => (value ? String(value) : 'N/A');

  const titleFontSize = 16;
  doc.setFontSize(titleFontSize);

  doc.setFont('helvetica', 'bold');
  const title = 'LABORATORY REQUEST';
  const titleWidth = doc.getTextWidth(title);
  const xPosition = (doc.internal.pageSize.width - titleWidth) / 2;
  doc.text(title, xPosition, 20);

  doc.setFontSize(12);

  doc.setFont('helvetica', 'bold');
  doc.text('Ward/Room/Bed No/OPD Clinic:', 20, 40);
  doc.setFont('helvetica', 'normal');
  doc.text(formatValue(wardRoomBed.value), 100, 40);

  doc.setFont('helvetica', 'bold');
  doc.text('Name (Last, First, MI):', 20, 50);
  doc.setFont('helvetica', 'normal');
  doc.text(`${formatValue(nameLast.value)}, ${formatValue(nameFirst.value)} ${formatValue(nameMI.value)}`, 100, 50);

  doc.setFont('helvetica', 'bold');
  doc.text('Contact Number:', 20, 60);
  doc.setFont('helvetica', 'normal');
  doc.text(formatValue(contactNumber.value), 100, 60);

  doc.setFont('helvetica', 'bold');
  doc.text('Age:', 20, 70);
  doc.setFont('helvetica', 'normal');
  doc.text(formatValue(age.value), 100, 70);

  doc.setFont('helvetica', 'bold');
  doc.text('Sex:', 20, 80);
  doc.setFont('helvetica', 'normal');
  doc.text(formatValue(sex.value), 100, 80);

  doc.setFont('helvetica', 'bold');
  doc.text('Hospital Case No:', 20, 90);
  doc.setFont('helvetica', 'normal');
  doc.text(formatValue(hospitalCaseNo.value), 100, 90);

  doc.setFont('helvetica', 'bold');
  doc.text('Birth Date:', 20, 100);
  doc.setFont('helvetica', 'normal');
  doc.text(formatValue(birthDate.value), 100, 100);

  doc.setFont('helvetica', 'bold');
  doc.text('Diagnosis:', 20, 110);
  doc.setFont('helvetica', 'normal');
  doc.text(formatValue(diagnosis.value), 100, 110);

  doc.setFont('helvetica', 'bold');
  doc.text('Requested By:', 20, 120);
  doc.setFont('helvetica', 'normal');
  doc.text(formatValue(requestedBy.value), 100, 120);

  doc.setFont('helvetica', 'bold');
  doc.text('Laboratory Examination Desired:', 20, 130);
  doc.setFont('helvetica', 'normal');
  doc.text(formatValue(labExamDesired.value), 100, 130);

  doc.setFont('helvetica', 'bold');
  doc.text('Specimen:', 20, 140);
  doc.setFont('helvetica', 'normal');
  doc.text(formatValue(specimen.value), 100, 140);

  doc.setFont('helvetica', 'bold');
  doc.text('Site of Collection:', 20, 150);
  doc.setFont('helvetica', 'normal');
  doc.text(formatValue(siteOfCollection.value), 100, 150);

  doc.setFont('helvetica', 'bold');
  doc.text('Collected By:', 20, 160);
  doc.setFont('helvetica', 'normal');
  doc.text(formatValue(collectedBy.value), 100, 160);

  doc.setFont('helvetica', 'bold');
  doc.text('Time Collected:', 20, 170);
  doc.setFont('helvetica', 'normal');
  doc.text(formatValue(timeCollected.value), 100, 170);

  doc.setFont('helvetica', 'bold');
  doc.text('Date Collected:', 20, 180);
  doc.setFont('helvetica', 'normal');
  doc.text(formatValue(dateCollected.value), 100, 180);

  // Save the PDF
  doc.save('Laboratory_Request_Form.pdf');

  wardRoomBed.value = '';
  nameLast.value = '';
  nameFirst.value = '';
  nameMI.value = '';
  age.value = '';
  sex.value = '';
  hospitalCaseNo.value = '';
  birthDate.value = '';
  diagnosis.value = '';
  requestedBy.value = '';
  labExamDesired.value = '';
  specimen.value = '';
  siteOfCollection.value = '';
  collectedBy.value = '';
  timeCollected.value = '';
  dateCollected.value = '';
  contactNumber.value = '';
};

</script>

<template>
  <Head title="Laboratory Request Form" />

  <Layout>
    <div class="page-heading">
      <h1 class="main-heading">LABORATORY REQUEST</h1>
    </div>

    <form @submit.prevent="generatePDF" class="form-container">
      <div>
        <label>Ward/Room/Bed No/OPD Clinic</label>
        <input v-model="wardRoomBed" type="text" />
      </div>

      <div>
        <label>Name (Last, First, MI)</label>
        <input v-model="nameLast" type="text" />
        <input v-model="nameFirst" type="text" />
        <input v-model="nameMI" type="text" />
      </div>

      <div>
        <label>Contact Number</label>
        <input v-model="contactNumber" type="text" />
      </div>

      <div>
        <label>Age:</label>
        <input v-model="age" type="number" />
      </div>

      <div>
        <label>Sex:</label>
        <select v-model="sex">
          <option value="Male">Male</option>
          <option value="Female">Female</option>
        </select>
      </div>

      <div>
        <label>Hospital Case No.</label>
        <input v-model="hospitalCaseNo" type="text" />
      </div>

      <div>
        <label>Birth Date</label>
        <input v-model="birthDate" type="date" />
      </div>

      <div>
        <label>Diagnosis:</label>
        <input v-model="diagnosis" type="text" />
      </div>

      <div>
        <label>
            Requested By:
            <br />
            <span style="font-size: small; font-weight: normal;">(DOCTOR'S SIGNATURE OVER TRODAT)</span>
        </label>
        <input v-model="requestedBy" type="text" />
      </div>

      <div>
        <label>
            Laboratory Examination Desired
            <br />
            <span style="font-size: small; font-weight: normal;">(Use one Request Form per specimen)</span>
        </label>
        <input v-model="labExamDesired" type="text" />
      </div>

      <div>
        <label>Specimen</label>
        <input v-model="specimen" type="text" />
      </div>

      <div>
        <label>Site of Collection</label>
        <input v-model="siteOfCollection" type="text" />
      </div>

      <div>
        <label>Collected By</label>
        <input v-model="collectedBy" type="text" />
      </div>

      <div>
        <label>Time Collected</label>
        <input v-model="timeCollected" type="time" />
      </div>

      <div>
        <label>Date Collected</label>
        <input v-model="dateCollected" type="date" />
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
</style>
