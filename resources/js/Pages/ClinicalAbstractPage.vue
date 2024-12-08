<script setup>
import Layout from '@/Pages/Layout.vue';
import jsPDF from 'jspdf';
import { ref } from 'vue';

const name = ref('');
const date = ref('');
const historyOfPresentIllness = ref('');
const pastMedicalHistoryHTN = ref('');
const pastMedicalHistoryDM = ref('');
const pastMedicalHistoryAsthma = ref('');
const physicalExamination = ref('');
const diagnosis = ref('');
const treatmentPlan = ref('');
const cyclesAlreadyGiven = ref('');
const dateLastCycle = ref('');
const adverseEvents = ref('');
const responseToTreatment = ref('');
const remainingCycles = ref('');
const dateNextCycle = ref('');
const attendingPhysician = ref('');
const licenseNo = ref('');
const ptrNo = ref('');
const s2No = ref('');

const treatmentProtocols = ref([
  {
    drugName: '',
    drugDose: '',
    numberOfDays: '',
    preparation: '',
    numberOfItems: '',
    pricePerItem: '',
    totalPrice: ''
  }
]);

const addTreatmentProtocol = () => {
  treatmentProtocols.value.push({
    drugName: '',
    drugDose: '',
    numberOfDays: '',
    preparation: '',
    numberOfItems: '',
    pricePerItem: '',
    totalPrice: ''
  });
};

const removeTreatmentProtocol = (index) => {
  treatmentProtocols.value.splice(index, 1);
};

const formatValue = (value) => (value ? String(value) : 'N/A');

function checkPageOverflow(doc, yOffset, pageHeight) {
    if (yOffset + 60 > pageHeight) {
        doc.addPage();
        return 20;
    }
    return yOffset;
}

const generatePDF = () => {
  const doc = new jsPDF();

  const titleFontSize = 16;
  doc.setFontSize(titleFontSize);

  doc.setFont('helvetica', 'bold');
  const title = 'CLINICAL ABSTRACT';
  const titleWidth = doc.getTextWidth(title);
  const xPosition = (doc.internal.pageSize.width - titleWidth) / 2;
  doc.text(title, xPosition, 20);

  doc.setFontSize(12);

  doc.setFont('helvetica', 'bold');
  doc.text('Name:', 20, 40);
  doc.setFont('helvetica', 'normal');
  doc.text(formatValue(name.value),40, 40);

  doc.setFont('helvetica', 'bold');
  doc.text('Date:', 150, 40);
  doc.setFont('helvetica', 'normal');
  doc.text(formatValue(date.value), 170, 40);

  doc.setFont('helvetica', 'bold');
  doc.text('History of Present Illness:', 20, 50);
  doc.setFont('helvetica', 'normal');
  doc.text(formatValue(historyOfPresentIllness.value), 100, 50);

  doc.setFont('helvetica', 'bold');
  doc.text('Past Medical History:', 20, 60);
  doc.setFont('helvetica', 'normal');
  doc.text(`HTN: ${formatValue(pastMedicalHistoryHTN.value)} DM: ${formatValue(pastMedicalHistoryDM.value)} ASTHMA: ${formatValue(pastMedicalHistoryAsthma.value)}`, 100, 60);

  doc.setFont('helvetica', 'bold');
  doc.text('Physical Examination:', 20, 70);
  doc.setFont('helvetica', 'normal');
  doc.text(formatValue(physicalExamination.value), 100, 70);

  doc.setFont('helvetica', 'bold');
  doc.text('Diagnosis:', 20, 80);
  doc.setFont('helvetica', 'normal');
  doc.text(formatValue(diagnosis.value), 100, 80);

  doc.setFont('helvetica', 'bold');
  doc.text('Treatment Plan:', 20, 90);
  doc.setFont('helvetica', 'normal');
  doc.text(formatValue(treatmentPlan.value), 100, 90);

  doc.setFont('helvetica', 'bold');
  doc.text('Progress Report:', 20, 100);
  doc.setFont('helvetica', 'normal');
  doc.text(`Cycles Given: ${formatValue(cyclesAlreadyGiven.value)} Date of Last Cycle: ${formatValue(dateLastCycle.value)}`, 100, 100);
  doc.text(`Adverse Events: ${formatValue(adverseEvents.value)}`, 100, 110);
  doc.text(`Response to Treatment: ${formatValue(responseToTreatment.value)}`, 100, 120);
  doc.text(`Remaining Cycles: ${formatValue(remainingCycles.value)}`, 100, 130);
  doc.text(`Date of Next Cycle: ${formatValue(dateNextCycle.value)}`, 100, 140);

  doc.setFont('helvetica', 'bold');
  doc.text('Treatment Protocols:', 20, 150);

  let yOffset = 160;
  const pageHeight = doc.internal.pageSize.height;

  treatmentProtocols.value.forEach((protocol, index) => {
    if (yOffset > pageHeight) {
      doc.addPage();
      yOffset = 20;
    }

    doc.setFont('helvetica', 'bold');
    doc.text(`Drug ${index + 1}: ${formatValue(protocol.drugName)}`, 20, yOffset);
    doc.setFont('helvetica', 'normal');
    doc.text(`Dose: ${formatValue(protocol.drugDose)}`, 100, yOffset);
    doc.text(`Number of Days: ${formatValue(protocol.numberOfDays)}`, 100, yOffset + 10);
    doc.text(`Preparation: ${formatValue(protocol.preparation)}`, 100, yOffset + 20);
    doc.text(`Number of Items: ${formatValue(protocol.numberOfItems)}`, 100, yOffset + 30);
    doc.text(`Price per Item: ${formatValue(protocol.pricePerItem)}`, 100, yOffset + 40);
    doc.text(`Total Price: ${formatValue(protocol.totalPrice)}`, 100, yOffset + 50);

    yOffset += 60;
  });

    yOffset = checkPageOverflow(doc, yOffset, pageHeight);
    doc.setFont('helvetica', 'bold');
    doc.text('Attending Physician:', 20, yOffset);
    doc.setFont('helvetica', 'normal');
    doc.text(formatValue(attendingPhysician.value), 100, yOffset);

    yOffset += 10;

    yOffset = checkPageOverflow(doc, yOffset, pageHeight);
    doc.setFont('helvetica', 'bold');
    doc.text('License No.:', 20, yOffset);
    doc.setFont('helvetica', 'normal');
    doc.text(formatValue(licenseNo.value), 100, yOffset);

    yOffset += 10;

    yOffset = checkPageOverflow(doc, yOffset, pageHeight);
    doc.setFont('helvetica', 'bold');
    doc.text('PTR No.:', 20, yOffset);
    doc.setFont('helvetica', 'normal');
    doc.text(formatValue(ptrNo.value), 100, yOffset);

    yOffset += 10;

    yOffset = checkPageOverflow(doc, yOffset, pageHeight);
    doc.setFont('helvetica', 'bold');
    doc.text('S2 No.:', 20, yOffset);
    doc.setFont('helvetica', 'normal');
    doc.text(formatValue(s2No.value), 100, yOffset);

  // Save PDF
  doc.save('Clinical_Abstract.pdf');

  name.value = '';
  date.value = '';
  historyOfPresentIllness.value = '';
  pastMedicalHistoryHTN.value = '';
  pastMedicalHistoryDM.value = '';
  pastMedicalHistoryAsthma.value = '';
  physicalExamination.value = '';
  diagnosis.value = '';
  treatmentPlan.value = '';
  cyclesAlreadyGiven.value = '';
  dateLastCycle.value = '';
  adverseEvents.value = '';
  responseToTreatment.value = '';
  remainingCycles.value = '';
  dateNextCycle.value = '';
  treatmentProtocols.value = [];
  attendingPhysician.value = '';
  licenseNo.value = '';
  ptrNo.value = '';
  s2No.value = '';
};

</script>

<template>
  <Head title="Clinical Abstract" />

  <Layout>
    <div class="page-heading">
      <h1 class="main-heading">CLINICAL ABSTRACT</h1>
    </div>

    <form @submit.prevent="generatePDF" class="form-container">
      <div>
        <label>Name:</label>
        <input v-model="name" type="text" />
      </div>

      <div>
        <label>Date:</label>
        <input v-model="date" type="date" />
      </div>

      <div>
        <label>History of Present Illness:</label>
        <input v-model="historyOfPresentIllness" type="text" />
      </div>

      <div>
        <label>Past Medical History:</label>
        <input v-model="pastMedicalHistoryHTN" type="text" placeholder="(-) HTN" />
        <input v-model="pastMedicalHistoryDM" type="text" placeholder="(-) DM" />
        <input v-model="pastMedicalHistoryAsthma" type="text" placeholder="(-) ASTHMA" />
      </div>

      <div>
        <label>Physical Examination:</label>
        <input v-model="physicalExamination" type="text" placeholder="e.g., Awake, not in distress" />
      </div>

      <div>
        <label>Diagnosis:</label>
        <input v-model="diagnosis" type="text" />
      </div>

      <div>
        <label>Treatment Plan:</label>
        <input v-model="treatmentPlan" type="text" />
      </div>

      <hr>
      <div>
        <label>Progress Report:</label>
        <div>
          <label>Number of cycles/sessions already given:</label>
          <input v-model="cyclesAlreadyGiven" type="number" />
        </div>
        <div>
          <label>Date of last cycle/session:</label>
          <input v-model="dateLastCycle" type="date" />
        </div>
        <div>
          <label>Adverse events noted:</label>
          <input v-model="adverseEvents" type="text" />
        </div>
        <div>
          <label>Response to treatment:</label>
          <input v-model="responseToTreatment" type="text" />
        </div>
        <div>
          <label>Number of remaining cycles/sessions:</label>
          <input v-model="remainingCycles" type="number" />
        </div>
        <div>
          <label>Date of next cycle/session:</label>
          <input v-model="dateNextCycle" type="date" />
        </div>
      </div>

      <hr>
      <div>
        <label>Treatment Protocol</label>
        <div v-for="(protocol, index) in treatmentProtocols" :key="index">
          <div>
            <label>Drug:</label>
            <input v-model="protocol.drugName" type="text" />
          </div>
          <div>
            <label>Dose:</label>
            <input v-model="protocol.drugDose" type="text" />
          </div>
          <div>
            <label>Number of days:</label>
            <input v-model="protocol.numberOfDays" type="number" />
          </div>
          <div>
            <label>Preparation:</label>
            <input v-model="protocol.preparation" type="text" />
          </div>
          <div>
            <label>Number of items:</label>
            <input v-model="protocol.numberOfItems" type="number" />
          </div>
          <div>
            <label>Price per item:</label>
            <input v-model="protocol.pricePerItem" type="number" />
          </div>
          <div>
            <label>Total price:</label>
            <input v-model="protocol.totalPrice" type="number" />
          </div>
          <hr>
        </div>
        <div class="treatment-buttons">
            <button type="button" class="remove-btn" @click="removeTreatmentProtocol(index)">Remove Treatment</button>
            <button type="button" class="add-btn" @click="addTreatmentProtocol">Add Treatment</button>
        </div>
      </div>

      <div>
        <label>Attending Physician:</label>
        <input v-model="attendingPhysician" type="text" />
      </div>

      <div>
        <label>License No.:</label>
        <input v-model="licenseNo" type="text" />
      </div>

      <div>
        <label>PTR No.:</label>
        <input v-model="ptrNo" type="text" />
      </div>

      <div>
        <label>S2 No.:</label>
        <input v-model="s2No" type="text" />
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

.treatment-buttons {
  display: flex;
  justify-content: space-between;
  width: 100%;
  align-items: center;
  padding-bottom: 20px;
}

.remove-btn, .add-btn {
  padding: 0.7rem 1.5rem;
  font-size: 1rem;
  color: #fff;
  background-color: #a02123;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

.remove-btn {
  order: -1;
}

.add-btn {
  order: 1;

}

hr {
    grid-column: 1 / -1;
    width: 100%;
    border: none;
    border-top: 1.5px solid maroon;
    margin: 10px 0;
}

</style>
